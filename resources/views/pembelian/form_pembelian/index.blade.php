<x-layout>
    @include('components.breadcrumbs')
    <!-- Add form tag around the purchase form div -->
    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf
        <!-- Previous breadcrumbs code -->
        <div class="p-4 md:p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pembelian</h1>

            <!-- Purchase Form using Alpine.js -->
            <div x-data="purchaseForm()" @submit.prevent="submitForm" class="bg-white rounded-lg shadow-md">

                <!-- Form Header -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border-b">
                    <!-- Supplier Dropdown -->
                    <div x-data="dropdown('supplier')">
                        <label for="supplier"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                        <div class="relative">
                            <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                                autocomplete="off" placeholder="Cari supplier..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <ul x-show="open" @click.outside="close()" x-cloak
                                class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                <template x-for="supplier in filteredSuppliers" :key="supplier.id">
                                    <li @click="select(supplier)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                        <span x-text="supplier.nama"></span>
                                    </li>
                                </template>
                            </ul>
                            <input type="hidden" name="supplier_id" x-model="selected.id">
                        </div>
                    </div>
                    <!-- Nomor Faktur -->
                    <div>
                        <label for="noFaktur" class="block text-sm font-medium text-gray-700 mb-1">No. Faktur</label>
                        <input type="text" id="noFaktur" name="no_faktur" x-model="formData.noFaktur"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Isi nomor faktur">
                    </div>
                    <!-- Checkbox for Lunas -->
                    <div class="flex items-center mt-6">
                        <input id="lunas" type="checkbox" name="lunas"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                            x-model="formData.lunas">
                        <label for="lunas" class="ml-2 text-sm font-medium text-gray-900">Lunas</label>
                    </div>
                </div>

                <!-- Date, Reference, Description -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border-b">
                    <!-- Date -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            x-model="formData.tanggal">
                    </div>
                    <!-- Reference Number -->
                    <div>
                        <label for="noReferensi" class="block text-sm font-medium text-gray-700 mb-1">No.
                            Referensi</label>
                        <input type="text" id="noReferensi" name="no_referensi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            x-model="formData.noReferensi" placeholder="P0000001">
                    </div>
                    <!-- Description -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <input type="text" id="deskripsi" name="deskripsi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            x-model="formData.deskripsi">
                    </div>
                </div>

                <!-- Item Table -->
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 w-12 text-center">#</th>
                                <th scope="col" class="px-4 py-3">Jenis Barang/Jasa</th>
                                <th scope="col" class="px-4 py-3">Jumlah</th>
                                <th scope="col" class="px-4 py-3">Satuan Ukur</th>
                                <th scope="col" class="px-4 py-3">Harga Satuan</th>
                                <th scope="col" class="px-4 py-3">Total</th>
                                <th scope="col" class="px-4 py-3 w-12 text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in formData.items" :key="index">
                                <tr class="bg-white border-b">
                                    <td class="px-4 py-2 text-center" x-text="index + 1"></td>
                                    <td class="px-4 py-2">
                                        <div x-data="dropdown('barang', index)">
                                            <div class="relative">
                                                <input type="text" x-model="search" @input="watchSearch()"
                                                    x-ref="barang" @click="openDropdown()"
                                                    @keydown.down="navigateDown(index)" @keydown.up="navigateUp(index)"
                                                    autocomplete="off" placeholder="Cari Barang..."
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                <ul x-show="open" @click.outside="close()" x-cloak
                                                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                                    <template x-for="(barang, barangIndex) in filteredBarangs"
                                                        :key="barang.id">
                                                        <li @click="select(barang, index)"
                                                            @mouseenter="highlightItem(barangIndex)"
                                                            :class="{ 'bg-gray-100': highlightedIndex === barangIndex }"
                                                            class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                                            <span x-text="barang.kode + ' - ' + barang.nama"></span>
                                                        </li>
                                                    </template>
                                                </ul>
                                                <input type="hidden" :name="'items[' + index + '][barang_id]'"
                                                    x-model="item.barang_id">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" :name="'items[' + index + '][jumlah]'"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            x-model="item.jumlah" @input="calculateItemTotal(index)"
                                            @keydown.down="navigateToNextRow(index)"
                                            @keydown.up="navigateToPreviousRow(index)">
                                    </td>
                                    <td class="px-4 py-2">
                                        <div x-data="dropdown('satuan', index)">
                                            <div class="relative">
                                                <input type="text" x-model="search" @input="watchSearch()"
                                                    @click="openDropdown()" @keydown.down="navigateDown(index)"
                                                    @keydown.up="navigateUp(index)" autocomplete="off"
                                                    placeholder="-"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                <ul x-show="open" @click.outside="close()" x-cloak
                                                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                                    <template x-for="(satuan, satuanIndex) in filteredSatuans"
                                                        :key="satuan.id">
                                                        <li @click="select(satuan, index)"
                                                            @mouseenter="highlightItem(satuanIndex)"
                                                            :class="{ 'bg-gray-100': highlightedIndex === satuanIndex }"
                                                            class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                                            <span x-text="satuan.nama"></span>
                                                        </li>
                                                    </template>
                                                </ul>
                                                <input type="hidden" :name="'items[' + index + '][satuan_id]'"
                                                    x-model="item.satuan_id">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" :name="'items[' + index + '][harga_satuan]'"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            x-model="item.hargaSatuan" @input="calculateItemTotal(index)"
                                            @keydown.down="navigateToNextRow(index)"
                                            @keydown.up="navigateToPreviousRow(index)" placeholder="Rp 0,00">
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="block w-full p-2.5 text-right"
                                            x-text="'Rp ' + formatNumber(item.total)"></span>
                                        <input type="hidden" :name="'items[' + index + '][total]'"
                                            x-model="item.total">
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Add Item Button -->
                    <div class="w-full mt-4">
                        <button type="button" @click="addItem()"
                            class="w-full py-2 px-4 border border-gray-300 rounded-lg text-blue-500 hover:bg-gray-50">
                            <span class="mr-1">+</span> Tambah Baris Baru
                        </button>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="p-4 border-t">
                    <!-- Summary Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div></div> <!-- Empty space for left column -->
                        <div class="space-y-3">
                            <!-- Total Raw -->
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Total Raw:</span>
                                <span x-text="'Rp ' + formatNumber(calculateSubtotal())"></span>
                                <input type="hidden" name="subtotal" x-model="formData.subtotal">
                            </div>

                            <!-- Biaya Lain -->
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Biaya Lain</span>
                                <div class="flex items-center">
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            Rp
                                        </span>
                                        <input type="text" name="biaya_lain"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2 pl-9 text-right"
                                            placeholder="0,00" x-model="formData.biayaLain"
                                            @input="calculateTotal()">
                                    </div>
                                </div>
                            </div>

                            <!-- Diskon -->
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600"
                                    x-text="formData.diskonType === 'persen' ? 'Diskon (%)' : 'Diskon (Rp)'"></span>
                                <div class="flex items-center">
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500"
                                            x-text="formData.diskonType === 'persen' ? '%' : 'Rp'">
                                        </span>
                                        <input type="text" name="diskon"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2 pl-9 text-right"
                                            placeholder="0,00" x-model="formData.diskon" @input="calculateTotal()">
                                    </div>
                                    <div class="ml-2">
                                        <select name="diskon_type" x-model="formData.diskonType"
                                            @change="calculateTotal()"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                                            <option value="persen">%</option>
                                            <option value="nominal">Rp</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Diskon -->
                            <div class="flex justify-between items-center text-sm"
                                x-show="formData.diskonType === 'persen' && formData.diskon > 0">
                                <span class="text-gray-600">Total Diskon:</span>
                                <span x-text="'Rp ' + formatNumber(calculateDiscountAmount())"></span>
                                <input type="hidden" name="total_diskon" x-model="formData.totalDiskon">
                            </div>

                            <!-- Total Pajak -->
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Total Pajak</span>
                                <span x-text="'Rp ' + formatNumber(calculateTax())"></span>
                                <input type="hidden" name="total_pajak" x-model="formData.totalPajak">
                            </div>

                            <!-- Subtotal -->
                            <div class="flex justify-between items-center text-sm font-medium"
                                x-show="!formData.lunas">
                                <span>Subtotal</span>
                                <span x-text="'Rp ' + formatNumber(calculateGrandTotal())"></span>
                                <input type="hidden" name="grand_total" x-model="formData.grandTotal">
                            </div>

                            <!-- Total (untuk lunas) -->
                            <div class="flex justify-between items-center text-sm font-medium"
                                x-show="formData.lunas">
                                <span>Total</span>
                                <span x-text="'Rp ' + formatNumber(calculateGrandTotal())"></span>
                                <input type="hidden" name="grand_total" x-model="formData.grandTotal">
                            </div>

                            <!-- Uang Muka -->
                            <div class="flex justify-between items-center text-sm" x-show="!formData.lunas">
                                <span class="text-gray-600">Uang Muka</span>
                                <div class="flex items-center">
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            Rp
                                        </span>
                                        <input type="text" name="uang_muka"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2 pl-9 text-right"
                                            placeholder="0,00" x-model="formData.uangMuka" @input="calculateTotal()">
                                    </div>
                                </div>
                            </div>

                            <!-- Sisa -->
                            <div class="flex justify-between items-center text-sm" x-show="!formData.lunas">
                                <span class="text-gray-600">Sisa</span>
                                <span x-text="'Rp ' + formatNumber(calculateRemaining())"></span>
                                <input type="hidden" name="sisa" x-model="formData.sisa">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="p-4 border-t text-right">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Pembelian
                    </button>
                </div>
            </div>
        </div>
    </form>


    <!-- Alpine.js Script -->
    <script>
        // Fungsi untuk dropdown pencarian
        function dropdown(type, rowIndex) {
            return {
                open: false,
                search: '',
                highlightedIndex: -1,
                watchSearch() {
                    this.open = true;
                    this.highlightedIndex = -1;
                },
                selected: {},
                suppliers: type === 'supplier' ? @json($suppliers) : [],
                barangs: type === 'barang' ? @json($barangs) : [],
                satuans: type === 'satuan' ? @json($satuans) : [],

                // Fungsi filter untuk pencarian
                get filteredSuppliers() {
                    return this.search.trim() === '' ? this.suppliers : this.suppliers.filter(supplier =>
                        supplier.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                // Fungsi filter untuk pencarian barang berdasarkan kode atau nama
                get filteredBarangs() {
                    if (this.search.trim() === '') return this.barangs;

                    const searchLower = this.search.toLowerCase();
                    return this.barangs.filter(barang =>
                        barang.nama.toLowerCase().includes(searchLower) ||
                        barang.kode.toLowerCase().includes(searchLower)
                    );
                },

                get filteredSatuans() {
                    return this.search.trim() === '' ? this.satuans : this.satuans.filter(satuan =>
                        satuan.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                // Inisialisasi dropdown
                init() {
                    this.selected = (this.suppliers || this.barangs || this.satuans).find(item => item.id ===
                        {{ old('supplier_id') ?? 'null' }}) || {};
                },

                // Fungsi untuk memilih item dari dropdown
                select(item, index) {
                    this.selected = item;
                    this.search = type === 'barang' ? `${item.kode} - ${item.nama}` : item.nama;
                    this.close();

                    // Jika ini adalah pemilihan barang, update barang_id dan harga
                    if (type === 'barang' && index !== undefined) {
                        const form = document.querySelector('[x-data="purchaseForm()"]').__x.$data;
                        form.formData.items[index].barang_id = item.id;
                        form.formData.items[index].barang_nama = item.nama;
                        form.formData.items[index].hargaSatuan = item.harga_jual || 0;
                        form.calculateItemTotal(index);
                    }

                    // Jika ini adalah pemilihan satuan, update satuan_id
                    if (type === 'satuan' && index !== undefined) {
                        const form = document.querySelector('[x-data="purchaseForm()"]').__x.$data;
                        form.formData.items[index].satuan_id = item.id;
                        form.formData.items[index].satuan_nama = item.nama;
                    }
                },

                // Fungsi untuk highlight item saat navigasi dengan keyboard
                highlightItem(index) {
                    this.highlightedIndex = index;
                },

                // Fungsi navigasi dengan keyboard
                navigateDown(rowIndex) {
                    if (!this.open) {
                        this.openDropdown();
                        return;
                    }

                    const items = type === 'barang' ? this.filteredBarangs :
                        type === 'satuan' ? this.filteredSatuans :
                        this.filteredSuppliers;

                    if (items.length > 0) {
                        this.highlightedIndex = (this.highlightedIndex + 1) % items.length;
                        this.scrollToHighlighted();
                    }
                },

                navigateUp(rowIndex) {
                    if (!this.open) {
                        this.openDropdown();
                        return;
                    }

                    const items = type === 'barang' ? this.filteredBarangs :
                        type === 'satuan' ? this.filteredSatuans :
                        this.filteredSuppliers;

                    if (items.length > 0) {
                        this.highlightedIndex = (this.highlightedIndex - 1 + items.length) % items.length;
                        this.scrollToHighlighted();
                    }
                },

                // Fungsi untuk auto-scroll ke item yang di-highlight
                scrollToHighlighted() {
                    if (this.highlightedIndex >= 0) {
                        setTimeout(() => {
                            const highlightedElement = document.querySelector(
                                `li:nth-child(${this.highlightedIndex + 1})`);
                            if (highlightedElement) {
                                highlightedElement.scrollIntoView({
                                    block: 'nearest'
                                });
                            }
                        }, 10);
                    }
                },

                // Fungsi untuk menutup dropdown
                close() {
                    this.open = false;
                },

                // Fungsi untuk membuka dropdown
                openDropdown() {
                    this.open = true;
                }
            };
        }

        // Fungsi utama untuk form pembelian
        function purchaseForm() {
            return {
                formData: {
                    supplier_id: '',
                    noFaktur: '',
                    tanggal: new Date().toISOString().slice(0, 10),
                    noReferensi: 'P0000001',
                    deskripsi: '',
                    lunas: false,
                    items: [{
                        barang_id: '',
                        barang_nama: '',
                        jumlah: 0,
                        satuan_id: '',
                        satuan_nama: '',
                        hargaSatuan: 0,
                        total: 0
                    }],
                    biayaLain: 0,
                    diskon: 0,
                    diskonType: 'persen', // Default ke persen
                    totalDiskon: 0,
                    totalPajak: 0,
                    uangMuka: 0,
                    subtotal: 0,
                    grandTotal: 0,
                    sisa: 0
                },

                // Fungsi untuk menambah item baru
                addItem() {
                    this.formData.items.push({
                        barang_id: '',
                        barang_nama: '',
                        jumlah: 0,
                        satuan_id: '',
                        satuan_nama: '',
                        hargaSatuan: 0,
                        total: 0
                    });

                    // Set fokus ke input barang baru
                    this.$nextTick(() => {
                        const lastIndex = this.formData.items.length - 1;
                        const lastBarangInput = document.querySelector(`[x-ref="barang"]: nth - child($ {
                                lastIndex + 1
                            })`);
                        if (lastBarangInput) {
                            lastBarangInput.focus();
                        }
                    });
                },

                // Fungsi untuk menghapus item
                removeItem(index) {
                    if (this.formData.items.length > 1) {
                        this.formData.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },

                // Fungsi untuk menghitung total item
                calculateItemTotal(index) {
                    const item = this.formData.items[index];
                    item.total = parseFloat(item.jumlah) * parseFloat(item.hargaSatuan);
                    this.calculateTotal();
                },

                // Fungsi untuk menghitung subtotal
                calculateSubtotal() {
                    this.formData.subtotal = this.formData.items.reduce((sum, item) => sum + (parseFloat(item.total) || 0),
                        0);
                    return this.formData.subtotal;
                },

                // Fungsi untuk menghitung jumlah diskon
                calculateDiscountAmount() {
                    if (this.formData.diskonType === 'persen') {
                        this.formData.totalDiskon = (this.calculateSubtotal() * (parseFloat(this.formData.diskon) / 100)) ||
                            0;
                    } else {
                        this.formData.totalDiskon = parseFloat(this.formData.diskon) || 0;
                    }
                    return this.formData.totalDiskon;
                },

                // Fungsi untuk menghitung pajak
                calculateTax() {
                    // Implementasi logika pajak jika diperlukan
                    this.formData.totalPajak = 0;
                    return this.formData.totalPajak;
                },

                // Fungsi untuk menghitung grand total
                calculateGrandTotal() {
                    this.formData.grandTotal = this.calculateSubtotal() - this.calculateDiscountAmount() + parseFloat(this
                        .formData.biayaLain || 0) + this.calculateTax();
                    return this.formData.grandTotal;
                },

                // Fungsi untuk menghitung sisa pembayaran
                calculateRemaining() {
                    this.formData.sisa = this.calculateGrandTotal() - parseFloat(this.formData.uangMuka || 0);
                    return this.formData.sisa;
                },

                // Fungsi untuk menghitung total keseluruhan
                calculateTotal() {
                    this.calculateSubtotal();
                    this.calculateDiscountAmount();
                    this.calculateTax();
                    this.calculateGrandTotal();
                    this.calculateRemaining();
                },

                // Fungsi untuk format angka
                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(num || 0);
                },

                // Fungsi navigasi dengan keyboard antar baris
                navigateToNextRow(currentIndex) {
                    if (currentIndex < this.formData.items.length - 1) {
                        this.$nextTick(() => {
                            const nextRowInput = document.querySelector(
                                `[name="items[${currentIndex + 1}][jumlah]"]`);
                            if (nextRowInput) {
                                nextRowInput.focus();
                            }
                        });
                    } else {
                        // Jika sudah di baris terakhir, tambah baris baru
                        this.addItem();
                    }
                },

                navigateToPreviousRow(currentIndex) {
                    if (currentIndex > 0) {
                        this.$nextTick(() => {
                            const prevRowInput = document.querySelector(
                                `[name="items[${currentIndex - 1}][jumlah]"]`);
                            if (prevRowInput) {
                                prevRowInput.focus();
                            }
                        });
                    }
                },

                // Inisialisasi form
                init() {
                    // Set tanggal default ke hari ini
                    this.formData.tanggal = new Date().toISOString().slice(0, 10);

                    // Generate nomor referensi default
                    this.formData.noReferensi = 'P' + new Date().getTime().toString().slice(-7);

                    // Setup event listener untuk submit form
                    this.$watch('formData', () => {
                        this.calculateTotal();
                    }, {
                        deep: true
                    });
                }
            };
        }
    </script>
</x-layout>
