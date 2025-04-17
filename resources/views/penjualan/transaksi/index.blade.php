<x-layout-without-navbar>
    @if (session('print_id'))
        <script>
            const printRoute = "{{ route('transaksi.print', ['id' => session('print_id')]) }}";
            console.log("Print route:", printRoute);
            window.open(printRoute, '_blank');
        </script>
    @endif

    <!-- Kontainer utama -->
    <div class="max-w-7xl mx-auto px-2" x-data="posApp">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Transaksi</h1>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Kasir: {{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-500" x-data x-init="setInterval(() => $el.textContent = new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(new Date()), 1000)">
                        {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
                    </p>
                </div>
                <a href="{{ route('penjualan.index') }}"
                    class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Layout 2 kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom kiri (2/3): Daftar produk dan informasi transaksi -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Panel pencarian produk dengan dropdown list -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex space-x-2">
                        <!-- Search input -->
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-search text-gray-500 text-sm"></i>
                            </div>
                            <!-- Input pencarian -->
                            <input type="search" id="product-search" x-model="searchQuery" @keyup="searchProducts"
                                @keydown.f2.window.prevent="$el.focus(); showDropdown = true" autocomplete="off"
                                @click="showDropdown = true"
                                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cari produk (F2)..." />

                            <!-- Product dropdown list yang mengambang -->
                            <div x-show="showDropdown" @click.away="showDropdown = false"
                                class="absolute z-10 mt-1 w-full border border-gray-200 rounded-lg bg-white shadow-lg max-h-96 overflow-y-auto">
                                <!-- Daftar produk dengan format list -->
                                <template x-for="product in filteredProducts" :key="product.id" x-cloak>
                                    <div @click="addToCart(product); showDropdown = false"
                                        class="flex items-center p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-all">
                                        <!-- Informasi produk -->
                                        <div class="ml-4 flex-grow">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm text-gray-500" x-text="product.kode"></p>
                                                <h3 class="text-sm font-medium" x-text="product.nama"></h3>
                                            </div>
                                            <div class="flex items-center mt-1">
                                                <p class="text-green-600 font-bold text-sm"
                                                    x-text="formatCurrency(product.final_price || 
                                        ((product.harga_jual - (product.diskon_nominal || 0)) + 
                                        ((product.harga_jual - (product.diskon_nominal || 0)) * ((product.pajak?.persen || 0)/100)))
                                    )">
                                                </p>
                                                <p x-show="product.diskon_value > 0" class="text-xs text-gray-500 ml-2">
                                                    <span class="line-through"
                                                        x-text="formatCurrency(product.harga_jual + (product.harga_jual * ((product.pajak?.persen || 0)/100)))"></span>
                                                    <span class="ml-1 text-red-500"
                                                        x-text="'-' + product.diskon_value + '%'"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Stok produk -->
                                        <div class="flex-shrink-0 ml-4">
                                            <p class="text-xs text-gray-500" x-text="'Stok: ' + product.stok"></p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Pesan jika tidak ada produk -->
                                <div x-show="filteredProducts.length === 0" class="p-4 text-center text-gray-500"
                                    x-cloak>
                                    Tidak ada produk yang ditemukan
                                </div>
                            </div>
                        </div>

                        <!-- Kategori dropdown -->
                        <div class="relative">
                            <select x-model="selectedCategory" @change="searchProducts"
                                class="block w-36 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Kategori</option>
                                <template x-for="category in categories" :key="category.id">
                                    <option :value="category.id" x-text="category.nama"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Daftar item yang dipilih -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold">Item Pesanan</h2>
                        <button @click="clearCart" class="text-red-500 hover:text-red-600 text-sm flex items-center">
                            <i class="fas fa-trash mr-1"></i> Batal
                        </button>
                    </div>

                    <!-- Items -->
                    <div class="space-y-3 max-h-72 overflow-y-auto mb-4">
                        <template x-for="(item, index) in cart.items" :key="index">
                            <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                                <div class="flex items-center space-x-3">
                                    <div>
                                        <h3 class="font-medium" x-text="item.nama_barang"></h3>
                                        <p class="text-sm text-gray-500"
                                            x-text="formatCurrency(item.harga_satuan + item.pajak_nominal)"></p>
                                        <!-- Info diskon jika ada -->
                                        <p x-show="item.diskon_value > 0" class="text-xs text-green-600">
                                            <span x-text="'Diskon: ' + item.diskon_value + '%'"></span>
                                            <span class="ml-1"
                                                x-text="'(' + formatCurrency(item.total_diskon) + ')'"></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center border border-gray-300 rounded-md">
                                        <button @click="decreaseQuantity(index)"
                                            class="px-2 py-1 bg-gray-100 text-gray-700">−</button>
                                        <input type="text" x-model="item.kuantitas" min="1"
                                            :max="item.stok_max" @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                                            @change="updateCartItem(index)"
                                            class="w-12 px-2 py-1 text-center border-none focus:ring-0 focus:outline-none">
                                        <button @click="increaseQuantity(index)"
                                            class="px-2 py-1 bg-gray-100 text-gray-700">+</button>
                                    </div>
                                    <div class="text-right">
                                        <p x-show="item.diskon_value > 0" class="text-xs text-gray-500 line-through"
                                            x-text="formatCurrency(item.subtotal)"></p>
                                        <p class="font-medium text-gray-800" x-text="formatCurrency(item.total)"></p>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Empty state -->
                        <div x-show="cart.items.length === 0" class="text-center py-6 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                            <p>Keranjang kosong</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom kanan (1/3): Total pembayaran -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Informasi member -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-lg font-semibold mb-3">Informasi Pelanggan</h2>

                    <template x-if="!selectedCustomer">
                        <div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-blue-700">Pilih pelanggan untuk mencatat transaksi atau buat
                                    pelanggan baru.</p>
                            </div>

                            <button @click="showCustomerSearch = true"
                                @keydown.f3.window.prevent="showCustomerSearch = true"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium">
                                <i class="fas fa-user-plus mr-1"></i> Pilih Pelanggan (F3)
                            </button>
                        </div>
                    </template>

                    <template x-if="selectedCustomer">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-medium text-gray-900" x-text="selectedCustomer.nama"></h3>
                                    <p class="text-sm text-gray-600" x-text="selectedCustomer.telepon"></p>
                                    <p class="text-xs text-gray-600" x-text="selectedCustomer.alamat"></p>
                                </div>
                                <button @click="selectedCustomer = null"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    Ganti
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Total pembayaran -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-lg font-semibold mb-3">Ringkasan Pesanan</h2>

                    <!-- Detail pembayaran -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" x-text="formatCurrency(cart.subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Diskon</span>
                            <span class="font-medium text-green-600"
                                x-text="'-' + formatCurrency(cart.total_diskon)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">DPP</span>
                            <span class="font-medium " x-text="formatCurrency(cart.dpp)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pajak (11%)</span>
                            <span class="font-medium" x-text="formatCurrency(cart.total_pajak)"></span>
                        </div>

                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total</span>
                                <span x-text="formatCurrency(cart.grand_total)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Metode pembayaran -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran:</label>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="method in paymentMethods" :key="method.id">
                                <button @click="selectPaymentMethod(method.id)"
                                    :class="{
                                        'bg-blue-50 border-blue-500': selectedPaymentMethod === method.id,
                                        'bg-white border-gray-300': selectedPaymentMethod !== method.id
                                    }"
                                    class="border rounded-md p-2 text-center text-sm font-medium transition-colors flex items-center justify-center">
                                    <i :class="method.icon + ' mr-2'"></i>
                                    <span x-text="method.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Input uang tunai (hanya untuk pembayaran tunai) -->
                    <div x-show="selectedPaymentMethod === 'tunai'" class="mb-4">
                        <label for="cash-amount" class="block text-sm font-medium text-gray-700 mb-1">Uang
                            Tunai:</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">Rp</span>
                            <input type="text" id="cash-amount" x-model="cashAmountFormatted"
                                @input="formatRupiahInput" @blur="formatFinalRupiah"
                                class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 text-sm text-right"
                                placeholder="0,00">
                        </div>

                        <div class="mt-2 flex justify-between text-sm font-medium">
                            <span>Kembalian:</span>
                            <span x-text="formatCurrency(cashChange)"></span>
                        </div>
                    </div>

                    <!-- Form untuk checkout dengan hidden inputs -->
                    <form id="checkout-form" action="{{ route('transaksi.store') }}" method="POST">
                        @csrf
                        <!-- Hidden inputs akan dihasilkan secara dinamis oleh JavaScript -->
                    </form>


                    <!-- Container untuk tombol-tombol -->
                    <div class="flex space-x-2">
                        <!-- Tombol bayar saja -->
                        <button @click="simpanTransaksi(false)" :disabled="!canCheckout"
                            @keydown.f4.window.prevent="canCheckout && simpanTransaksi(false)"
                            :class="{
                                'bg-green-600 hover:bg-green-700': canCheckout,
                                'bg-green-300 cursor-not-allowed': !canCheckout
                            }"
                            class="w-1/2 text-white py-3 px-4 rounded-lg font-medium text-lg">
                            <i class="fas fa-check-circle mr-1"></i> Bayar Saja (F4)
                        </button>

                        <!-- Tombol simpan dan cetak -->
                        <button @click="simpanTransaksi(true)" :disabled="!canCheckout"
                            @keydown.f5.window.prevent="canCheckout && simpanTransaksi(true)"
                            :class="{
                                'bg-blue-600 hover:bg-blue-700': canCheckout,
                                'bg-blue-300 cursor-not-allowed': !canCheckout
                            }"
                            class="w-1/2 text-white py-3 px-4 rounded-lg font-medium text-lg">
                            <i class="fas fa-print mr-1"></i> Simpan & Cetak (F5)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pencarian Pelanggan -->
        <div x-show="showCustomerSearch" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click="showCustomerSearch = false" class="fixed inset-0 bg-black opacity-30"></div>

                <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-md z-10">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Pilih Pelanggan</h3>
                    </div>

                    <div class="p-4">
                        <!-- Pencarian pelanggan -->
                        <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 mb-4">
                            <i class="fas fa-search text-gray-400"></i>
                            <input type="text" id="customer-search" x-model="customerSearchQuery"
                                @input="searchCustomers" placeholder="Cari pelanggan..."
                                class="ml-2 flex-1 outline-none text-gray-700">
                        </div>

                        <!-- Daftar pelanggan -->
                        <div class="max-h-64 overflow-y-auto">
                            <template x-for="customer in filteredCustomers" :key="customer.id">
                                <div @click="selectCustomer(customer)"
                                    class="p-3 border-b border-gray-200 hover:bg-blue-50 cursor-pointer">
                                    <h4 class="font-medium text-gray-900" x-text="customer.nama"></h4>
                                    <p class="text-sm text-gray-600" x-text="customer.telepon"></p>
                                    <p class="text-xs text-gray-500" x-text="customer.alamat"></p>
                                </div>
                            </template>

                            <!-- Empty state -->
                            <div x-show="filteredCustomers.length === 0" class="text-center py-6 text-gray-500">
                                <p>Tidak ada pelanggan yang ditemukan</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-200 flex justify-between">
                        <button @click="showCustomerSearch = false"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                            Batal
                        </button>

                        <x-base-modal :id="'addModal-'" title="Tambah Customer" triggerText="Daftar Pelanggan Baru"
                            triggerClass="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">

                            <form action="{{ route('customer.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="nama"
                                            class="block text-sm font-medium text-gray-700">Nama</label>
                                        <input type="text" name="nama" id="nama"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <div>
                                        <label for="tanggal_lahir"
                                            class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>



                                    <div>
                                        <label for="telepon"
                                            class="block text-sm font-medium text-gray-700">Telepon</label>
                                        <input type="text" name="telepon" id="telepon"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <div class="col-span-2">
                                        <label for="alamat"
                                            class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <textarea name="alamat" id="alamat" rows="1"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                    </div>

                                    <input type="hidden" name="status" value="aktif">
                                </div>

                                <button type="submit" class="mt-4 w-full px-4 py-2 bg-green-600 text-white rounded">
                                    Simpan
                                </button>
                            </form>
                        </x-base-modal>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Struk -->
        <div x-show="showReceiptModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click="showReceiptModal = false" class="fixed inset-0 bg-black opacity-30"></div>

                <div class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-md z-10">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Transaksi Berhasil</h3>
                    </div>

                    <div class="p-4">
                        <!-- Struk -->
                        <div class="bg-gray-50 p-4 border border-gray-200 rounded-lg">
                            <div class="text-center mb-4">
                                <h3 class="font-bold">KOPI NUSANTARA</h3>
                                <p class="text-sm">Jl. Merdeka No. 123, Jakarta</p>
                                <p class="text-sm">Tel: (021) 555-1234</p>
                                <div class="border-t border-dashed border-gray-300 my-2"></div>
                                <p class="text-sm" x-text="'No: ' + formData.no_ref"></p>
                                <p class="text-xs" x-text="new Date().toLocaleString('id-ID')"></p>
                                <p x-show="selectedCustomer" class="text-xs"
                                    x-text="'Pelanggan: ' + (selectedCustomer ? selectedCustomer.nama : '-')"></p>
                                <div class="border-t border-dashed border-gray-300 my-2"></div>
                            </div>

                            <div class="space-y-1 text-sm">
                                <template x-for="item in cart.items" :key="item.barang_id">
                                    <div>
                                        <div class="flex justify-between">
                                            <span x-text="item.nama_barang"></span>
                                            <span x-text="formatCurrency(item.subtotal)"></span>
                                        </div>
                                        <div class="text-xs text-gray-500 pl-2"
                                            x-text="item.kuantitas + ' x ' + formatCurrency(item.harga_final)"></div>
                                    </div>
                                </template>

                                <div class="border-t border-dashed border-gray-300 my-2"></div>

                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span x-text="formatCurrency(cart.subtotal)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Diskon</span>
                                    <span x-text="'-' + formatCurrency(cart.total_diskon)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Pajak (11%)</span>
                                    <span x-text="formatCurrency(cart.total_pajak)"></span>
                                </div>
                                <div class="flex justify-between font-bold">
                                    <span>TOTAL</span>
                                    <span x-text="formatCurrency(cart.grand_total)"></span>
                                </div>

                                <div class="border-t border-dashed border-gray-300 my-2"></div>

                                <div class="flex justify-between">
                                    <span>Metode Pembayaran</span>
                                    <span
                                        x-text="selectedPaymentMethod === 'tunai' ? 'Tunai' : (selectedPaymentMethod === 'kartu' ? 'Kartu' : (selectedPaymentMethod === 'qris' ? 'QRIS' : 'Transfer'))"></span>
                                </div>
                                <template x-if="selectedPaymentMethod === 'tunai'">
                                    <div>
                                        <div class="flex justify-between">
                                            <span>Tunai</span>
                                            <span x-text="formatCurrency(cashAmount)"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Kembalian</span>
                                            <span x-text="formatCurrency(cashChange)"></span>
                                        </div>
                                    </div>
                                </template>

                                <div class="border-t border-dashed border-gray-300 my-2"></div>
                                <div class="text-center text-xs">
                                    <p>Terima kasih atas kunjungan Anda</p>
                                    <p>Selamat menikmati!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-200 flex justify-between">
                        <button @click="printReceipt"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-print mr-1"></i> Cetak Struk
                        </button>
                        <button @click="newTransaction"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus-circle mr-1"></i> Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        window.$barangs = @json($barangs);
        window.$kategoris = @json($kategoris);
        window.$customers = @json($customers);

        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', () => ({
                // Data utama dari server
                products: window.$barangs || [],
                categories: window.$kategoris || [],
                customers: window.$customers || [],

                // Cart dan item yang dipilih
                cart: {
                    items: [],
                    subtotal: 0, // Subtotal adalah total harga asli dengan pajak (tanpa diskon)
                    total_diskon: 0, // Total nilai diskon dihitung dari harga dengan pajak
                    total_pajak: 0, // Total pajak dari semua item
                    grand_total: 0, // Subtotal - total_diskon
                    dpp: 0 // DPP - Dasar Pengenaan Pajak (total harga_jual yang sudah didiskon * kuantitas untuk item dengan pajak > 0)
                },

                // State UI
                showDropdown: false,
                searchQuery: '',
                selectedCategory: null,
                filteredProducts: [],

                // State customer
                selectedCustomer: null,
                showCustomerSearch: false,
                customerSearchQuery: '',
                filteredCustomers: [],


                // State pembayaran
                paymentMethods: [{
                        id: 'tunai',
                        name: 'Tunai',
                        icon: 'fas fa-money-bill-wave'
                    },
                    {
                        id: 'kartu',
                        name: 'Kartu',
                        icon: 'fas fa-credit-card'
                    },
                    {
                        id: 'transfer',
                        name: 'Transfer',
                        icon: 'fas fa-university'
                    },
                    {
                        id: 'qris',
                        name: 'QRIS',
                        icon: 'fas fa-qrcode'
                    }
                ],
                selectedPaymentMethod: 'tunai',
                cashAmountFormatted: '0,00',
                cashAmount: 0,
                cashChange: 0,

                // Form data untuk hidden input
                formData: {
                    customer_id: null,
                    items: [],
                    subtotal: 0,
                    total_diskon: 0,
                    total_pajak: 0,
                    grand_total: 0,
                    total_bayar: 0,
                    kembalian: 0,
                    metode_pembayaran: 'tunai',
                    dpp: 0 // Tambahan field DPP pada formData
                },

                // Lifecycle hook
                // Update pada bagian init()
                init() {
                    this.loadData();
                    this.cashAmountFormatted = '0,00'; // Inisialisasi format awal

                    // Setup keyboard shortcuts
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'F2') {
                            e.preventDefault();
                            document.querySelector('#product-search').focus();
                        } else if (e.key === 'F3') {
                            e.preventDefault();
                            this.showCustomerSearch = true;
                            setTimeout(() => {
                                document.querySelector('#customer-search').focus();
                            }, 100);
                        } else if (e.key === 'F4' && this.canCheckout) {
                            e.preventDefault();
                            this.simpanTransaksi(false);
                        } else if (e.key === 'F5' && this.canCheckout) {
                            e.preventDefault();
                            this.simpanTransaksi(true);
                        }
                    });
                },

                // Computed properties
                get canCheckout() {
                    if (this.cart.items.length === 0) return false;
                    if (this.selectedPaymentMethod === 'tunai') {
                        // Nilai cashAmount sudah dalam format sen
                        return this.cashAmount >= parseInt(this.cart.grand_total);
                    }
                    return true;
                },

                // Methods
                // Methods
                loadData() {
                    // Inisialisasi data dari server
                    // Filter produk yang hanya memiliki status active sebelum ditampilkan
                    this.filteredProducts = this.products.filter(product => product.status ===
                        'active');
                },

                // Fungsi pencarian produk
                searchProducts() {
                    const query = this.searchQuery.toLowerCase();

                    this.filteredProducts = this.products.filter(product => {
                        // Memfilter berdasarkan query pencarian (nama atau kode produk)
                        const matchesQuery = product.nama.toLowerCase().includes(query) ||
                            product.kode.toLowerCase().includes(query);

                        // Memfilter berdasarkan kategori yang dipilih
                        const matchesCategory = this.selectedCategory === null ||
                            product.kategori_id === this.selectedCategory;

                        // Memfilter berdasarkan status produk, hanya tampilkan yang active
                        const isActive = product.status === 'active';

                        // Produk harus memenuhi semua kriteria filter: query pencarian, kategori, dan status active
                        return matchesQuery && matchesCategory && isActive;
                    });
                },

                filterByCategory(categoryId) {
                    this.selectedCategory = categoryId;
                    this.searchProducts();
                },

                // Fungsi cart
                addToCart(product) {
                    // Cek stok
                    if (product.stok <= 0) {
                        Swal.fire({
                            title: 'Stok Habis!',
                            text: 'Produk ini sedang tidak tersedia',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Cek item sudah ada di cart
                    const existingItemIndex = this.cart.items.findIndex(item => item.barang_id ===
                        product.id);

                    if (existingItemIndex >= 0) {
                        // Update quantity jika sudah ada
                        const existingItem = this.cart.items[existingItemIndex];
                        if (existingItem.kuantitas < product.stok) {
                            existingItem.kuantitas += 1;
                            this.updateCartItem(existingItemIndex);
                        } else {
                            Swal.fire({
                                title: 'Stok Tidak Cukup!',
                                text: 'Jumlah melebihi stok yang tersedia',
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        // Harga jual (harga dasar tanpa pajak dan diskon)
                        const harga_jual = product.harga_jual;

                        // Ambil informasi pajak dari produk
                        const pajak_value = product.pajak ? product.pajak.persen : 0;

                        // Nilai diskon berdasarkan Harag Jual (tnapa pajak) dalam persen
                        const diskon_value = product.diskon_value || 0;

                        // Menghitung diskon berdasarkan Harga Jual (tanpa pajak)
                        const diskon_nominal_dasar = product.diskon_nominal ||
                            Math.round((diskon_value / 100) * harga_jual);

                        // Hitung harga setelah diskon (tanpa pajak)
                        const harga_setelah_diskon = Math.max(0, harga_jual - diskon_nominal_dasar);

                        // Hitung pajak dari harga jual
                        const pajak_per_item = Math.round((pajak_value / 100) * harga_jual);

                        //HItung pajak dari harga sudah diskon
                        const pajak_per_item_diskon = Math.round((pajak_value / 100) *
                            harga_setelah_diskon);

                        // Harga dengan pajak (diambil dari harga setelah diskon)
                        const harga_dengan_pajak = harga_setelah_diskon + pajak_per_item_diskon;

                        // Diskon nominal untuk display dihitung dari harga_jual (dengan/tanpa pajak)
                        let diskon_nominal;
                        if (product.diskon_nominal) {
                            diskon_nominal = product.diskon_nominal + Math.round(product
                                .diskon_nominal * (pajak_value / 100));
                        } else {
                            diskon_nominal = Math.round((diskon_value / 100) * (harga_jual +
                                pajak_per_item));
                        }

                        // Tambah item baru
                        this.cart.items.push({
                            barang_id: product.id,
                            nama_barang: product.nama,
                            kode_barang: product.kode,
                            harga_satuan: harga_jual, // Harga asli tanpa pajak dan diskon
                            harga_dengan_pajak: harga_jual +
                                pajak_per_item, // Harga satuan asli + pajak (sebelum diskon)
                            harga_setelah_diskon: harga_setelah_diskon, // Harga setelah diskon, sebelum pajak (untuk DPP)
                            harga_diskon: harga_setelah_diskon +
                                pajak_per_item_diskon, // Harga setelah diskon + pajak per item diskon
                            harga_final: harga_dengan_pajak -
                                diskon_nominal, // Harga final setelah pajak dan diskon
                            pajak_value: pajak_per_item_diskon, // Nilai pajak per item final (dari harga yang sudah didiskon)
                            pajak_persen: pajak_value, // Persentase pajak (disimpan untuk keperluan perhitungan)
                            pajak_nominal: pajak_per_item, // Nilai pajak dalam nominal per item
                            diskon_value: diskon_value, // Persentase diskon
                            diskon_nominal: diskon_nominal, // Nilai diskon dalam nominal per item untuk display
                            diskon_nominal_dasar: diskon_nominal_dasar, // Nilai diskon pada harga dasar (untuk DPP)
                            kuantitas: 1,
                            satuan_id: product.satuan_id,
                            satuan_nama: product.satuan ? product.satuan.nama : 'pcs',
                            stok_max: product.stok,
                            total_diskon: diskon_nominal, // Total diskon untuk item ini (quantity = 1) untuk display
                            total_pajak: pajak_per_item_diskon, // Total pajak untuk item ini (quantity = 1)
                            subtotal: harga_jual +
                                pajak_per_item, // Subtotal untuk item ini (harga asli + pajak) * quantity = 1
                            total: harga_dengan_pajak, // Total setelah diskon untuk item ini (quantity = 1)
                            is_dpp_item: pajak_value >
                                0 // Tambahkan flag untuk menandai item termasuk DPP atau tidak
                        });

                        this.calculateCartTotal();
                    }
                },

                updateCartItem(index) {
                    const item = this.cart.items[index];

                    // Validasi quantity
                    if (item.kuantitas <= 0) {
                        item.kuantitas = 1;
                    } else if (item.kuantitas > item.stok_max) {
                        item.kuantitas = item.stok_max;
                        Swal.fire({
                            title: 'Penyesuaian Quantity!',
                            text: 'Quantity disesuaikan dengan stok yang tersedia',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }

                    // Hitung ulang total per item
                    // Pajak dihitung dari harga satuan setelah diskon * kuantitas
                    item.total_pajak = item.pajak_value * item.kuantitas;

                    // Subtotal adalah (harga satuan asli + pajak) * kuantitas (untuk display)
                    item.subtotal = item.harga_dengan_pajak * item.kuantitas;

                    // Diskon dihitung dari subtotal untuk display
                    item.total_diskon = item.diskon_nominal * item.kuantitas;

                    // Total adalah subtotal - total diskon
                    item.total = item.subtotal - item.total_diskon;

                    // Update total cart
                    this.calculateCartTotal();
                },

                increaseQuantity(index) {
                    const item = this.cart.items[index];
                    if (item.kuantitas < item.stok_max) {
                        item.kuantitas += 1;
                        this.updateCartItem(index);
                    } else {
                        Swal.fire({
                            title: 'Stok Tidak Cukup!',
                            text: 'Jumlah melebihi stok yang tersedia',
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },

                decreaseQuantity(index) {
                    const item = this.cart.items[index];
                    if (item.kuantitas > 1) {
                        item.kuantitas -= 1;
                        this.updateCartItem(index);
                    }
                },

                removeFromCart(index) {
                    this.cart.items.splice(index, 1);
                    this.calculateCartTotal();
                },

                clearCart() {
                    Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: "Transaksi ini akan dibatalkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, batalkan!',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.cart.items = [];
                            this.selectedCustomer = null;
                            this.formData.customer_id = null;
                            this.calculateCartTotal();
                            this.cashAmount = 0;
                            this.cashChange = 0;
                            this.cashAmountFormatted = '0,00';

                            Swal.fire(
                                'Dibatalkan!',
                                'Transaksi berhasil dibatalkan.',
                                'success'
                            )
                        }
                    })
                },

                // Fungsi kalkulasi yang sudah diupdate dengan tambahan DPP untuk harga yang sudah didiskon
                calculateCartTotal() {
                    let subtotal = 0; // Total harga dengan pajak (tanpa diskon)
                    let total_diskon = 0; // Total nilai diskon dihitung dari harga dengan pajak
                    let total_pajak = 0; // Total nilai pajak dari seluruh item
                    let grand_total = 0; // Total akhir setelah diskon
                    let dpp =
                        0; // DPP - untuk item dengan pajak > 0, total harga_satuan yang sudah didiskon * kuantitas

                    // Hitung dari setiap item
                    this.cart.items.forEach(item => {
                        // Subtotal adalah harga dengan pajak * kuantitas
                        subtotal += item.subtotal;

                        // Total diskon dihitung dari harga dengan pajak
                        total_diskon += item.total_diskon;

                        // Total pajak dari semua item
                        total_pajak += item.total_pajak;

                        // Hitung DPP - jumlah harga_setelah_diskon * kuantitas untuk item dengan pajak > 0
                        if (item.pajak_persen > 0) {
                            // DPP adalah harga yang sudah didiskon (sebelum pajak) * kuantitas
                            dpp += item.harga_setelah_diskon * item.kuantitas;
                        }
                    });

                    // Grand total adalah subtotal - total diskon
                    grand_total = subtotal - total_diskon;

                    // Update cart (nilai tetap dalam sen/integer)
                    this.cart.subtotal = subtotal;
                    this.cart.total_diskon = total_diskon;
                    this.cart.total_pajak = total_pajak;
                    this.cart.grand_total = grand_total;
                    this.cart.dpp = dpp; // Simpan nilai DPP ke cart

                    // Update formData untuk hidden inputs
                    this.formData.subtotal = this.cart.subtotal;
                    this.formData.total_diskon = this.cart.total_diskon;
                    this.formData.total_pajak = this.cart.total_pajak;
                    this.formData.grand_total = this.cart.grand_total;
                    this.formData.dpp = this.cart.dpp; // Simpan nilai DPP ke formData
                    this.formData.items = JSON.parse(JSON.stringify(this.cart.items)); // Deep copy

                    // Recalculate kembalian
                    this.calculateChange();
                },

                // Customer functions
                searchCustomers() {
                    const query = this.customerSearchQuery.toLowerCase();
                    this.filteredCustomers = this.customers.filter(customer =>
                        customer.nama.toLowerCase().includes(query) ||
                        customer.kode.toLowerCase().includes(query) ||
                        (customer.telepon && customer.telepon.includes(query))
                    );
                },

                selectCustomer(customer) {
                    this.selectedCustomer = customer;
                    this.formData.customer_id = customer.id;
                    this.showCustomerSearch = false;
                    this.customerSearchQuery = '';
                },

                // Payment functions
                selectPaymentMethod(methodId) {
                    this.selectedPaymentMethod = methodId;
                    this.formData.metode_pembayaran = methodId;

                    if (methodId === 'tunai') {
                        this.cashAmount = 0;
                        this.cashAmountFormatted = '0,00';
                        this.cashChange = 0;
                        this.formData.total_bayar = 0;
                        this.formData.kembalian = 0;
                    } else {
                        // Untuk kartu/transfer, jumlah pembayaran sama dengan total
                        this.formData.total_bayar = this.cart.grand_total;
                        this.formData.kembalian = 0;
                    }
                },

                // Update metode calculateChange
                calculateChange() {
                    if (this.selectedPaymentMethod === 'tunai' && this.cashAmount > 0) {
                        // cashAmount sudah dalam nilai sen, tidak perlu konversi lagi
                        this.cashChange = Math.max(0, this.cashAmount - parseInt(this.cart
                            .grand_total));
                        this.formData.total_bayar = this.cashAmount;
                        this.formData.kembalian = this.cashChange;
                    } else {
                        this.cashChange = 0;
                        if (this.selectedPaymentMethod !== 'tunai') {
                            this.formData.total_bayar = this.cart.grand_total;
                        }
                        this.formData.kembalian = this.cashChange;
                    }
                },

                // Metode untuk menyimpan transaksi dengan atau tanpa cetak
                simpanTransaksi(cetak) {
                    if (!this.canCheckout) return;

                    // Simpan status cetak untuk digunakan saat generate form
                    this.cetakStatus = cetak;

                    // Generate hidden input fields
                    this.generateFormFields();

                    // Submit form
                    document.getElementById('checkout-form').submit();
                },

                generateFormFields() {
                    const form = document.getElementById('checkout-form');

                    // Bersihkan form dari input sebelumnya
                    const oldInputs = form.querySelectorAll('input[type="hidden"]');
                    oldInputs.forEach(input => input.remove());

                    this.addHiddenInput(form, '_token', document.querySelector(
                        'meta[name="csrf-token"]').getAttribute('content'));

                    // Tambahkan input untuk status cetak
                    this.addHiddenInput(form, 'cetak', this.cetakStatus ? 'true' : 'false');

                    // Customer
                    if (this.formData.customer_id) {
                        this.addHiddenInput(form, 'customer_id', this.formData.customer_id);
                    }

                    // Payment
                    this.addHiddenInput(form, 'metode_pembayaran', this.formData.metode_pembayaran);
                    this.addHiddenInput(form, 'total_bayar', this.formData.total_bayar);
                    this.addHiddenInput(form, 'kembalian', this.formData.kembalian);

                    // Totals
                    this.addHiddenInput(form, 'subtotal', this.formData.subtotal);
                    this.addHiddenInput(form, 'total_diskon', this.formData.total_diskon);
                    this.addHiddenInput(form, 'total_pajak', this.formData.total_pajak);
                    this.addHiddenInput(form, 'grand_total', this.formData.grand_total);
                    this.addHiddenInput(form, 'dpp', this.formData
                        .dpp); // Tambahkan input hidden untuk DPP

                    // Items
                    this.formData.items.forEach((item, index) => {
                        this.addHiddenInput(form, `items[${index}][barang_id]`, item.barang_id);
                        this.addHiddenInput(form, `items[${index}][nama_barang]`, item
                            .nama_barang);
                        this.addHiddenInput(form, `items[${index}][harga_satuan]`, item
                            .harga_satuan);
                        this.addHiddenInput(form, `items[${index}][harga_dengan_pajak]`, item
                            .harga_dengan_pajak);
                        this.addHiddenInput(form, `items[${index}][harga_setelah_diskon]`, item
                            .harga_setelah_diskon); // Tambahkan harga setelah diskon
                        this.addHiddenInput(form, `items[${index}][harga_diskon]`, item
                            .harga_diskon
                        ); // Tambahkan harga diskon (harga_setelah_diskon + pajak_per_item_diskon)
                        this.addHiddenInput(form, `items[${index}][pajak_value]`, item
                            .pajak_value); // Nilai pajak per item final (sudah termasuk diskon)
                        this.addHiddenInput(form, `items[${index}][pajak_nominal]`, item
                            .pajak_nominal);
                        this.addHiddenInput(form, `items[${index}][diskon_value]`, item
                            .diskon_value);
                        this.addHiddenInput(form, `items[${index}][diskon_nominal]`, item
                            .diskon_nominal);
                        this.addHiddenInput(form, `items[${index}][diskon_nominal_dasar]`, item
                            .diskon_nominal_dasar); // Tambahkan diskon nominal dasar
                        this.addHiddenInput(form, `items[${index}][kuantitas]`, item.kuantitas);
                        this.addHiddenInput(form, `items[${index}][satuan_id]`, item.satuan_id);
                        this.addHiddenInput(form, `items[${index}][total_diskon]`, item
                            .total_diskon);
                        this.addHiddenInput(form, `items[${index}][total_pajak]`, item
                            .total_pajak);
                        this.addHiddenInput(form, `items[${index}][subtotal]`, item.subtotal);
                        this.addHiddenInput(form, `items[${index}][total]`, item.total);
                        this.addHiddenInput(form, `items[${index}][is_dpp_item]`, item
                            .is_dpp_item ? 1 : 0); // Tambahkan flag untuk item DPP
                    });
                },

                addHiddenInput(form, name, value) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                },

                // Format currency untuk tampilan - konversi dari nilai sen (integer) ke format rupiah
                formatCurrency(amount) {
                    // Konversi dari sen ke rupiah dengan memindahkan koma desimal 2 digit
                    const rupiah = amount / 100;

                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(rupiah);
                },

                // Format untuk input rupiah ke nilai sen (menyimpan data dalam bentuk integer)
                formatRupiahToSen(amount) {
                    // Menghilangkan semua karakter non-numerik
                    const numericValue = String(amount).replace(/[^0-9]/g, '');

                    // Jika input mungkin sudah dalam bentuk rupiah dengan koma desimal yang tidak terlihat
                    // Kita perlu menambahkan dua angka 0 di belakang sesuai kebutuhan
                    if (numericValue.length <= 2) {
                        // Jika kurang dari 3 digit, asumsikan ini adalah sen
                        return parseInt(numericValue.padEnd(2, '0'));
                    } else {
                        // Input sudah dalam format rupiah+sen, tidak perlu transformasi lebih lanjut
                        return parseInt(numericValue);
                    }
                },

                // Format dari nilai sen (integer) ke rupiah untuk tampilan dengan dua desimal
                formatSenToRupiah(amount) {
                    // Konversi sen ke rupiah dengan 2 desimal
                    const rupiah = amount / 100;
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(rupiah);
                },

                formatRupiahInput(event) {
                    // Menyimpan posisi kursor sebelum pemformatan
                    const input = event.target;
                    const cursorPos = input.selectionStart;
                    const originalLength = this.cashAmountFormatted.length;

                    // Menghapus semua karakter non-digit
                    let value = this.cashAmountFormatted.replace(/\D/g, '');

                    // Menyimpan panjang nilai sebelum diformat
                    const beforeLength = value.length;

                    // Menambahkan titik sebagai pemisah ribuan dan koma untuk desimal
                    if (value === '') {
                        this.cashAmountFormatted = '0,00';
                        this.cashAmount = 0;
                    } else {
                        // Menentukan jumlah digit
                        const length = value.length;

                        // Jika kurang dari 3 digit, tambahkan leading zero untuk desimal
                        if (length === 1) {
                            value = '00' + value;
                        } else if (length === 2) {
                            value = '0' + value;
                        }

                        // Ambil 2 digit terakhir sebagai desimal
                        const decimal = value.substring(value.length - 2);
                        // Sisanya sebagai bagian integer
                        const integer = value.substring(0, value.length - 2) || '0';

                        // Format dengan pemisah ribuan
                        const formatted = integer.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                        // Update model dengan format dan nilai sebenarnya
                        this.cashAmountFormatted = formatted + ',' + decimal;
                        this.cashAmount = parseInt(value);
                    }

                    // Hitung kembalian berdasarkan nilai baru
                    this.calculateChange();

                    // Perhitungan posisi kursor yang benar setelah pemformatan
                    setTimeout(() => {
                        // Hitung berapa banyak titik pemisah ribuan sebelum posisi kursor
                        const beforeDots = this.cashAmountFormatted.substring(0, cursorPos)
                            .split('.').length - 1;
                        const afterDots = this.cashAmountFormatted.split('.').length - 1;

                        // Selisih panjang string setelah pemformatan
                        const lengthDiff = this.cashAmountFormatted.length - originalLength;

                        // Hitung posisi kursor yang baru dengan mempertimbangkan titik pemisah ribuan
                        let newCursorPos = cursorPos + lengthDiff;

                        // Jika ada perubahan jumlah titik pemisah ribuan, sesuaikan posisi kursor
                        if (beforeDots !== afterDots) {
                            newCursorPos = cursorPos + (afterDots - beforeDots);
                        }

                        // Pastikan posisi kursor tidak melewati batas string
                        newCursorPos = Math.min(newCursorPos, this.cashAmountFormatted.length);
                        newCursorPos = Math.max(newCursorPos, 0);

                        // Setel posisi kursor ke posisi yang benar
                        input.setSelectionRange(newCursorPos, newCursorPos);
                    }, 0);
                },

                formatFinalRupiah() {
                    // Pastikan format akhir sudah benar saat input kehilangan fokus
                    if (this.cashAmountFormatted === '') {
                        this.cashAmountFormatted = '0,00';
                        this.cashAmount = 0;
                    }

                    // Pastikan desimal selalu ditampilkan dengan 2 digit
                    const parts = this.cashAmountFormatted.split(',');
                    if (parts.length > 1) {
                        if (parts[1].length < 2) {
                            this.cashAmountFormatted = parts[0] + ',' + parts[1].padEnd(2, '0');
                        }
                    } else {
                        this.cashAmountFormatted = this.cashAmountFormatted + ',00';
                    }

                    // Hitung kembalian berdasarkan nilai final
                    this.calculateChange();
                }
            }));
        });
        // Script AlpineJS untuk POS Checkout dengan AJAX
        // document.addEventListener('alpine:init', () => {
        //     Alpine.data('posApp', () => ({
        //         // Data utama dari server
        //         products: window.$barangs || [],
        //         categories: window.$kategoris || [],
        //         customers: window.$customers || [],

        //         // Cart dan item yang dipilih
        //         cart: {
        //             items: [],
        //             subtotal: 0, // Subtotal adalah total harga asli dengan pajak (tanpa diskon)
        //             total_diskon: 0, // Total nilai diskon dihitung dari harga dengan pajak
        //             total_pajak: 0, // Total pajak dari semua item
        //             grand_total: 0, // Subtotal - total_diskon
        //             dpp: 0 // DPP - Dasar Pengenaan Pajak (total harga_jual yang sudah didiskon * kuantitas untuk item dengan pajak > 0)
        //         },

        //         // State UI
        //         searchQuery: '',
        //         selectedCategory: null,
        //         filteredProducts: [],
        //         isProcessing: false, // Status untuk menandai proses checkout
        //         checkoutSuccess: false, // Status keberhasilan checkout
        //         errorMessage: '', // Pesan error jika ada

        //         // State customer
        //         selectedCustomer: null,
        //         showCustomerSearch: false,
        //         customerSearchQuery: '',
        //         filteredCustomers: [],

        //         // State pembayaran
        //         paymentMethods: [{
        //                 id: 'tunai',
        //                 name: 'Tunai',
        //                 icon: 'fas fa-money-bill-wave'
        //             },
        //             {
        //                 id: 'debit',
        //                 name: 'Kartu',
        //                 icon: 'fas fa-credit-card'
        //             },
        //             {
        //                 id: 'transfer',
        //                 name: 'Transfer',
        //                 icon: 'fas fa-university'
        //             },
        //             {
        //                 id: 'qris',
        //                 name: 'QRIS',
        //                 icon: 'fas fa-qrcode'
        //             }
        //         ],
        //         selectedPaymentMethod: 'tunai',
        //         cashAmountFormatted: '0,00',
        //         cashAmount: 0,
        //         cashChange: 0,

        //         // Form data untuk AJAX request
        //         formData: {
        //             no_ref: '',
        //             customer_id: null,
        //             items: [],
        //             subtotal: 0,
        //             total_diskon: 0,
        //             total_pajak: 0,
        //             grand_total: 0,
        //             total_bayar: 0,
        //             kembalian: 0,
        //             metode_pembayaran: 'tunai',
        //             dpp: 0 // Tambahan field DPP pada formData
        //         },

        //         // Lifecycle hook
        //         init() {
        //             this.loadData();
        //             this.formData.no_ref = this.generateRefNumber(); // Update no_ref di sini
        //             this.cashAmountFormatted = '0,00'; // Inisialisasi format awal

        //             // Setup keyboard shortcuts
        //             window.addEventListener('keydown', (e) => {
        //                 if (e.key === 'F2') {
        //                     e.preventDefault();
        //                     document.querySelector('#product-search').focus();
        //                 } else if (e.key === 'F3') {
        //                     e.preventDefault();
        //                     this.showCustomerSearch = true;
        //                     setTimeout(() => {
        //                         document.querySelector('#customer-search').focus();
        //                     }, 100);
        //                 } else if (e.key === 'F4' && this.canCheckout) {
        //                     e.preventDefault();
        //                     this.prepareCheckout();
        //                 }
        //             });
        //         },

        //         // Computed properties
        //         get canCheckout() {
        //             if (this.cart.items.length === 0) return false;
        //             if (this.selectedPaymentMethod === 'tunai') {
        //                 // Nilai cashAmount sudah dalam format sen
        //                 return this.cashAmount >= parseInt(this.cart.grand_total);
        //             }
        //             return true;
        //         },

        //         // Methods
        //         loadData() {
        //             // Inisialisasi data dari server
        //             this.filteredProducts = [...this.products];

        //             // Generate nomor referensi
        //             this.formData.no_ref = this.generateRefNumber();
        //         },

        //         // Fungsi untuk nomor referensi
        //         generateRefNumber() {
        //             const date = new Date();
        //             const year = date.getFullYear().toString().substr(-2);
        //             const month = String(date.getMonth() + 1).padStart(2, '0');
        //             const day = String(date.getDate()).padStart(2, '0');
        //             const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');

        //             return `INV${year}${month}${day}${random}`;
        //         },

        //         // Fungsi pencarian produk
        //         searchProducts() {
        //             const query = this.searchQuery.toLowerCase();

        //             this.filteredProducts = this.products.filter(product => {
        //                 const matchesQuery = product.nama.toLowerCase().includes(query) ||
        //                     product.kode.toLowerCase().includes(query);

        //                 const matchesCategory = this.selectedCategory === null ||
        //                     product.kategori_id === this.selectedCategory;

        //                 return matchesQuery && matchesCategory;
        //             });
        //         },

        //         filterByCategory(categoryId) {
        //             this.selectedCategory = categoryId;
        //             this.searchProducts();
        //         },

        //         // Fungsi cart
        //         addToCart(product) {
        //             // Cek stok
        //             if (product.stok <= 0) {
        //                 alert('Stok habis!');
        //                 return;
        //             }

        //             // Cek item sudah ada di cart
        //             const existingItemIndex = this.cart.items.findIndex(item => item.barang_id ===
        //                 product.id);

        //             if (existingItemIndex >= 0) {
        //                 // Update quantity jika sudah ada
        //                 const existingItem = this.cart.items[existingItemIndex];
        //                 if (existingItem.kuantitas < product.stok) {
        //                     existingItem.kuantitas += 1;
        //                     this.updateCartItem(existingItemIndex);
        //                 } else {
        //                     alert('Stok tidak mencukupi!');
        //                 }
        //             } else {
        //                 // Harga jual (harga dasar tanpa pajak dan diskon)
        //                 const harga_jual = product.harga_jual;

        //                 // Ambil informasi pajak dari produk
        //                 const pajak_value = product.pajak ? product.pajak.persen : 0;

        //                 // Menghitung diskon untuk harga dasar (sebelum pajak)
        //                 const diskon_value = product.diskon_value || 0;
        //                 const diskon_nominal_dasar = product.diskon_nominal ||
        //                     Math.round((diskon_value / 100) * harga_jual);

        //                 // Hitung harga_setelah_diskon (harga dasar setelah didiskon, sebelum pajak)
        //                 const harga_setelah_diskon = Math.max(0, harga_jual - diskon_nominal_dasar);

        //                 // Hitung pajak dari harga yang sudah didiskon, pembulatan ke integer
        //                 const pajak_per_item = Math.round((pajak_value / 100) * harga_jual);

        //                 const pajak_per_item_diskon = Math.round((pajak_value / 100) *
        //                     harga_setelah_diskon);

        //                 // Harga dengan pajak (harga setelah diskon + pajak)
        //                 const harga_dengan_pajak = harga_setelah_diskon + pajak_per_item_diskon;

        //                 // Diskon nominal untuk display dihitung dari harga_jual (dengan/tanpa pajak)
        //                 let diskon_nominal;
        //                 if (product.diskon_nominal) {
        //                     diskon_nominal = product.diskon_nominal + (product.diskon_nominal * (
        //                         pajak_value / 100));
        //                 } else {
        //                     diskon_nominal = Math.round((diskon_value / 100) * (harga_jual +
        //                         pajak_per_item));
        //                 }

        //                 // Tambah item baru
        //                 this.cart.items.push({
        //                     barang_id: product.id,
        //                     nama_barang: product.nama,
        //                     kode_barang: product.kode,
        //                     harga_satuan: harga_jual, // Harga asli tanpa pajak dan diskon
        //                     harga_dengan_pajak: harga_jual +
        //                         pajak_per_item, // Harga satuan asli + pajak (sebelum diskon)
        //                     harga_setelah_diskon: harga_setelah_diskon, // Harga setelah diskon, sebelum pajak (untuk DPP)
        //                     harga_final: harga_dengan_pajak -
        //                         diskon_nominal, // Harga final setelah pajak dan diskon
        //                     pajak_value: pajak_value, // Persentase pajak
        //                     pajak_nominal: pajak_per_item, // Nilai pajak dalam nominal per item
        //                     diskon_value: diskon_value, // Persentase diskon
        //                     diskon_nominal: diskon_nominal, // Nilai diskon dalam nominal per item untuk display
        //                     diskon_nominal_dasar: diskon_nominal_dasar, // Nilai diskon pada harga dasar (untuk DPP)
        //                     kuantitas: 1,
        //                     satuan_id: product.satuan_id,
        //                     satuan_nama: product.satuan ? product.satuan.nama : 'pcs',
        //                     stok_max: product.stok,
        //                     total_diskon: diskon_nominal, // Total diskon untuk item ini (quantity = 1) untuk display
        //                     total_pajak: pajak_per_item_diskon, // Total pajak untuk item ini (quantity = 1)
        //                     subtotal: harga_jual +
        //                         pajak_per_item, // Subtotal untuk item ini (harga asli + pajak) * quantity = 1
        //                     total: harga_dengan_pajak, // Total setelah diskon untuk item ini (quantity = 1)
        //                     is_dpp_item: pajak_value >
        //                         0 // Tambahkan flag untuk menandai item termasuk DPP atau tidak
        //                 });

        //                 this.calculateCartTotal();
        //             }
        //         },

        //         updateCartItem(index) {
        //             const item = this.cart.items[index];

        //             // Validasi quantity
        //             if (item.kuantitas <= 0) {
        //                 item.kuantitas = 1;
        //             } else if (item.kuantitas > item.stok_max) {
        //                 item.kuantitas = item.stok_max;
        //                 alert('Quantity disesuaikan dengan stok yang tersedia.');
        //             }

        //             // Hitung ulang total per item
        //             // Pajak dihitung dari harga satuan setelah diskon * kuantitas
        //             item.total_pajak = item.pajak_nominal * item.kuantitas;

        //             // Subtotal adalah (harga satuan asli + pajak) * kuantitas (untuk display)
        //             item.subtotal = item.harga_dengan_pajak * item.kuantitas;

        //             // Diskon dihitung dari subtotal untuk display
        //             item.total_diskon = item.diskon_nominal * item.kuantitas;

        //             // Total adalah subtotal - total diskon
        //             item.total = item.subtotal - item.total_diskon;

        //             // Update total cart
        //             this.calculateCartTotal();
        //         },

        //         increaseQuantity(index) {
        //             const item = this.cart.items[index];
        //             if (item.kuantitas < item.stok_max) {
        //                 item.kuantitas += 1;
        //                 this.updateCartItem(index);
        //             } else {
        //                 alert('Stok tidak mencukupi!');
        //             }
        //         },

        //         decreaseQuantity(index) {
        //             const item = this.cart.items[index];
        //             if (item.kuantitas > 1) {
        //                 item.kuantitas -= 1;
        //                 this.updateCartItem(index);
        //             }
        //         },

        //         removeFromCart(index) {
        //             this.cart.items.splice(index, 1);
        //             this.calculateCartTotal();
        //         },

        //         clearCart() {
        //             Swal.fire({
        //                 title: 'Apakah anda yakin?',
        //                 text: "Transaksi ini akan dibatalkan!",
        //                 icon: 'warning',
        //                 showCancelButton: true,
        //                 confirmButtonColor: '#3085d6',
        //                 cancelButtonColor: '#d33',
        //                 confirmButtonText: 'Ya, batalkan!',
        //                 cancelButtonText: 'Tidak'
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     this.cart.items = [];
        //                     this.selectedCustomer = null;
        //                     this.formData.customer_id = null;
        //                     this.calculateCartTotal();
        //                     this.cashAmount = 0;
        //                     this.cashChange = 0;
        //                     this.cashAmountFormatted = '0,00';
        //                     this.checkoutSuccess = false;
        //                     this.errorMessage = '';

        //                     Swal.fire(
        //                         'Dibatalkan!',
        //                         'Transaksi berhasil dibatalkan.',
        //                         'success'
        //                     )
        //                 }
        //             })
        //         },

        //         // Fungsi kalkulasi yang sudah diupdate dengan tambahan DPP untuk harga yang sudah didiskon
        //         calculateCartTotal() {
        //             let subtotal = 0; // Total harga dengan pajak (tanpa diskon)
        //             let total_diskon = 0; // Total nilai diskon dihitung dari harga dengan pajak
        //             let total_pajak = 0; // Total nilai pajak dari seluruh item
        //             let grand_total = 0; // Total akhir setelah diskon
        //             let dpp =
        //                 0; // DPP - untuk item dengan pajak > 0, total harga_satuan yang sudah didiskon * kuantitas

        //             // Hitung dari setiap item
        //             this.cart.items.forEach(item => {
        //                 // Subtotal adalah harga dengan pajak * kuantitas
        //                 subtotal += item.subtotal;

        //                 // Total diskon dihitung dari harga dengan pajak
        //                 total_diskon += item.total_diskon;

        //                 // Total pajak dari semua item
        //                 total_pajak += item.total_pajak;

        //                 // Hitung DPP - jumlah harga_setelah_diskon * kuantitas untuk item dengan pajak > 0
        //                 if (item.pajak_value > 0) {
        //                     // DPP adalah harga yang sudah didiskon (sebelum pajak) * kuantitas
        //                     dpp += item.harga_setelah_diskon * item.kuantitas;
        //                 }
        //             });

        //             // Grand total adalah subtotal - total diskon
        //             grand_total = subtotal - total_diskon;

        //             // Update cart (nilai tetap dalam sen/integer)
        //             this.cart.subtotal = subtotal;
        //             this.cart.total_diskon = total_diskon;
        //             this.cart.total_pajak = total_pajak;
        //             this.cart.grand_total = grand_total;
        //             this.cart.dpp = dpp; // Simpan nilai DPP ke cart

        //             // Update formData untuk AJAX request
        //             this.formData.subtotal = this.cart.subtotal;
        //             this.formData.total_diskon = this.cart.total_diskon;
        //             this.formData.total_pajak = this.cart.total_pajak;
        //             this.formData.grand_total = this.cart.grand_total;
        //             this.formData.dpp = this.cart.dpp; // Simpan nilai DPP ke formData
        //             this.formData.items = JSON.parse(JSON.stringify(this.cart.items)); // Deep copy

        //             // Recalculate kembalian
        //             this.calculateChange();
        //         },

        //         // Customer functions
        //         searchCustomers() {
        //             const query = this.customerSearchQuery.toLowerCase();
        //             this.filteredCustomers = this.customers.filter(customer =>
        //                 customer.nama.toLowerCase().includes(query) ||
        //                 customer.kode.toLowerCase().includes(query) ||
        //                 (customer.telepon && customer.telepon.includes(query))
        //             );
        //         },

        //         selectCustomer(customer) {
        //             this.selectedCustomer = customer;
        //             this.formData.customer_id = customer.id;
        //             this.showCustomerSearch = false;
        //             this.customerSearchQuery = '';
        //         },

        //         // Payment functions
        //         selectPaymentMethod(methodId) {
        //             this.selectedPaymentMethod = methodId;
        //             this.formData.metode_pembayaran = methodId;

        //             if (methodId === 'tunai') {
        //                 this.cashAmount = 0;
        //                 this.cashAmountFormatted = '0,00';
        //                 this.cashChange = 0;
        //                 this.formData.total_bayar = 0;
        //                 this.formData.kembalian = 0;
        //             } else {
        //                 // Untuk kartu/transfer, jumlah pembayaran sama dengan total
        //                 this.formData.total_bayar = this.cart.grand_total;
        //                 this.formData.kembalian = 0;
        //             }
        //         },

        //         // Update metode calculateChange
        //         calculateChange() {
        //             if (this.selectedPaymentMethod === 'tunai' && this.cashAmount > 0) {
        //                 // cashAmount sudah dalam nilai sen, tidak perlu konversi lagi
        //                 this.cashChange = Math.max(0, this.cashAmount - parseInt(this.cart
        //                     .grand_total));
        //                 this.formData.total_bayar = this.cashAmount;
        //                 this.formData.kembalian = this.cashChange;
        //             } else {
        //                 this.cashChange = 0;
        //                 if (this.selectedPaymentMethod !== 'tunai') {
        //                     this.formData.total_bayar = this.cart.grand_total;
        //                 }
        //                 this.formData.kembalian = this.cashChange;
        //             }
        //         },

        //         // Checkout dengan AJAX
        //         prepareCheckout() {
        //             if (!this.canCheckout) return;

        //             // Set status processing
        //             this.isProcessing = true;

        //             // Persiapkan data yang akan dikirim
        //             const checkoutData = {
        //                 _token: document.querySelector('meta[name="csrf-token"]').getAttribute(
        //                     'content'),
        //                 no_ref: this.formData.no_ref,
        //                 customer_id: this.formData.customer_id,
        //                 metode_pembayaran: this.formData.metode_pembayaran,
        //                 total_bayar: this.formData.total_bayar,
        //                 kembalian: this.formData.kembalian,
        //                 subtotal: this.formData.subtotal,
        //                 total_diskon: this.formData.total_diskon,
        //                 total_pajak: this.formData.total_pajak,
        //                 grand_total: this.formData.grand_total,
        //                 dpp: this.formData.dpp,
        //                 items: this.formData.items
        //             };

        //             // Kirim data dengan fetch API
        //             fetch("{{ route('transaksi.store') }}", {
        //                     method: 'POST',
        //                     headers: {
        //                         'Content-Type': 'application/json',
        //                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
        //                             .getAttribute('content')
        //                     },
        //                     body: JSON.stringify(checkoutData)
        //                 })
        //                 .then(response => {
        //                     // Cek status response
        //                     if (!response.ok) {
        //                         return response.json().then(err => {
        //                             throw new Error(err.message ||
        //                                 'Terjadi kesalahan saat checkout');
        //                         });
        //                     }
        //                     return response.json();
        //                 })
        //                 .then(data => {
        //                     // Proses sukses
        //                     this.isProcessing = false;
        //                     this.checkoutSuccess = true;

        //                     // Tampilkan pesan sukses dengan SweetAlert2
        //                     Swal.fire({
        //                         title: 'Sukses!',
        //                         text: 'Transaksi berhasil disimpan',
        //                         icon: 'success',
        //                         showCancelButton: true,
        //                         confirmButtonColor: '#3085d6',
        //                         cancelButtonColor: '#6c757d',
        //                         confirmButtonText: 'Cetak Struk',
        //                         cancelButtonText: 'Transaksi Baru'
        //                     }).then((result) => {
        //                         if (result.isConfirmed) {
        //                             // Redirect ke halaman cetak struk
        //                             window.open(`/penjualan/cetak/${data.penjualan_id}`,
        //                                 '_blank');
        //                             // Reset untuk transaksi baru
        //                             this.resetTransaksi();
        //                         } else {
        //                             // Reset untuk transaksi baru
        //                             this.resetTransaksi();
        //                         }
        //                     });
        //                 })
        //                 .catch(error => {
        //                     // Tangani error
        //                     this.isProcessing = false;
        //                     this.errorMessage = error.message || 'Terjadi kesalahan saat checkout';

        //                     // Tampilkan pesan error
        //                     Swal.fire({
        //                         title: 'Error!',
        //                         text: this.errorMessage,
        //                         icon: 'error',
        //                         confirmButtonColor: '#3085d6'
        //                     });
        //                 });
        //         },

        //         // Reset transaksi setelah checkout berhasil
        //         resetTransaksi() {
        //             this.cart.items = [];
        //             this.selectedCustomer = null;
        //             this.formData.customer_id = null;
        //             this.calculateCartTotal();
        //             this.cashAmount = 0;
        //             this.cashChange = 0;
        //             this.cashAmountFormatted = '0,00';
        //             this.checkoutSuccess = false;
        //             this.errorMessage = '';
        //             this.formData.no_ref = this.generateRefNumber();
        //         },

        //         // Format currency untuk tampilan - konversi dari nilai sen (integer) ke format rupiah
        //         formatCurrency(amount) {
        //             // Konversi dari sen ke rupiah dengan memindahkan koma desimal 2 digit
        //             const rupiah = amount / 100;

        //             return new Intl.NumberFormat('id-ID', {
        //                 style: 'currency',
        //                 currency: 'IDR',
        //                 minimumFractionDigits: 2,
        //                 maximumFractionDigits: 2
        //             }).format(rupiah);
        //         },

        //         // Format untuk input rupiah ke nilai sen (menyimpan data dalam bentuk integer)
        //         formatRupiahToSen(amount) {
        //             // Menghilangkan semua karakter non-numerik
        //             const numericValue = String(amount).replace(/[^0-9]/g, '');

        //             // Jika input mungkin sudah dalam bentuk rupiah dengan koma desimal yang tidak terlihat
        //             // Kita perlu menambahkan dua angka 0 di belakang sesuai kebutuhan
        //             if (numericValue.length <= 2) {
        //                 // Jika kurang dari 3 digit, asumsikan ini adalah sen
        //                 return parseInt(numericValue.padEnd(2, '0'));
        //             } else {
        //                 // Input sudah dalam format rupiah+sen, tidak perlu transformasi lebih lanjut
        //                 return parseInt(numericValue);
        //             }
        //         },

        //         // Format dari nilai sen (integer) ke rupiah untuk tampilan dengan dua desimal
        //         formatSenToRupiah(amount) {
        //             // Konversi sen ke rupiah dengan 2 desimal
        //             const rupiah = amount / 100;
        //             return new Intl.NumberFormat('id-ID', {
        //                 style: 'currency',
        //                 currency: 'IDR',
        //                 minimumFractionDigits: 2,
        //                 maximumFractionDigits: 2
        //             }).format(rupiah);
        //         },

        //         formatRupiahInput(event) {
        //             // Menyimpan posisi kursor sebelum pemformatan
        //             const input = event.target;
        //             const cursorPos = input.selectionStart;
        //             const originalLength = this.cashAmountFormatted.length;

        //             // Menghapus semua karakter non-digit
        //             let value = this.cashAmountFormatted.replace(/\D/g, '');

        //             // Menyimpan panjang nilai sebelum diformat
        //             const beforeLength = value.length;

        //             // Menambahkan titik sebagai pemisah ribuan dan koma untuk desimal
        //             if (value === '') {
        //                 this.cashAmountFormatted = '0,00';
        //                 this.cashAmount = 0;
        //             } else {
        //                 // Menentukan jumlah digit
        //                 const length = value.length;

        //                 // Jika kurang dari 3 digit, tambahkan leading zero untuk desimal
        //                 if (length === 1) {
        //                     value = '00' + value;
        //                 } else if (length === 2) {
        //                     value = '0' + value;
        //                 }

        //                 // Ambil 2 digit terakhir sebagai desimal
        //                 const decimal = value.substring(value.length - 2);
        //                 // Sisanya sebagai bagian integer
        //                 const integer = value.substring(0, value.length - 2) || '0';

        //                 // Format dengan pemisah ribuan
        //                 const formatted = integer.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        //                 // Update model dengan format dan nilai sebenarnya
        //                 this.cashAmountFormatted = formatted + ',' + decimal;
        //                 this.cashAmount = parseInt(value);
        //             }

        //             // Hitung kembalian berdasarkan nilai baru
        //             this.calculateChange();

        //             // Perhitungan posisi kursor yang benar setelah pemformatan
        //             setTimeout(() => {
        //                 // Hitung berapa banyak titik pemisah ribuan sebelum posisi kursor
        //                 const beforeDots = this.cashAmountFormatted.substring(0, cursorPos)
        //                     .split('.').length - 1;
        //                 const afterDots = this.cashAmountFormatted.split('.').length - 1;

        //                 // Selisih panjang string setelah pemformatan
        //                 const lengthDiff = this.cashAmountFormatted.length - originalLength;

        //                 // Hitung posisi kursor yang baru dengan mempertimbangkan titik pemisah ribuan
        //                 let newCursorPos = cursorPos + lengthDiff;

        //                 // Jika ada perubahan jumlah titik pemisah ribuan, sesuaikan posisi kursor
        //                 if (beforeDots !== afterDots) {
        //                     newCursorPos = cursorPos + (afterDots - beforeDots);
        //                 }

        //                 // Pastikan posisi kursor tidak melewati batas string
        //                 newCursorPos = Math.min(newCursorPos, this.cashAmountFormatted.length);
        //                 newCursorPos = Math.max(newCursorPos, 0);

        //                 // Setel posisi kursor ke posisi yang benar
        //                 input.setSelectionRange(newCursorPos, newCursorPos);
        //             }, 0);
        //         },

        //         formatFinalRupiah() {
        //             // Pastikan format akhir sudah benar saat input kehilangan fokus
        //             if (this.cashAmountFormatted === '') {
        //                 this.cashAmountFormatted = '0,00';
        //                 this.cashAmount = 0;
        //             }

        //             // Pastikan desimal selalu ditampilkan dengan 2 digit
        //             const parts = this.cashAmountFormatted.split(',');
        //             if (parts.length > 1) {
        //                 if (parts[1].length < 2) {
        //                     this.cashAmountFormatted = parts[0] + ',' + parts[1].padEnd(2, '0');
        //                 }
        //             } else {
        //                 this.cashAmountFormatted = this.cashAmountFormatted + ',00';
        //             }

        //             // Hitung kembalian berdasarkan nilai final
        //             this.calculateChange();
        //         }
        //     }));
        // });
    </script>
</x-layout-without-navbar>
