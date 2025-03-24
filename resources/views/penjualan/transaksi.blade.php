<!-- Main Content -->
<main class="container mx-auto p-4 mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Selection Area -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Produk</h2>
                    <div class="relative">
                        <input type="text" placeholder="Cari produk..."
                            class="border rounded-md px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            x-model="searchQuery" @input="searchProducts">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="flex overflow-x-auto space-x-2 mb-4 pb-2">
                    <template x-for="category in categories" :key="category.id">
                        <button class="px-4 py-2 rounded-full text-sm whitespace-nowrap"
                            :class="selectedCategory === category.id ? 'bg-blue-600 text-white' :
                                'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                            x-text="category.name" @click="filterByCategory(category.id)"></button>
                    </template>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-lg transition-shadow"
                            @click="addToCart(product)">
                            <div class="aspect-square bg-gray-100 rounded-md mb-2 flex items-center justify-center">
                                <img :src="product.image" alt="Product" class="max-h-full max-w-full object-contain">
                            </div>
                            <h3 class="font-medium text-sm" x-text="product.name"></h3>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-blue-600 font-bold" x-text="formatCurrency(product.price)"></span>
                                <span x-show="product.stock > 0" class="text-xs text-gray-500"
                                    x-text="'Stok: ' + product.stock"></span>
                                <span x-show="product.stock <= 0" class="text-xs text-red-500">Habis</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Cart Area -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-4">
                <h2 class="text-xl font-semibold mb-4">Keranjang Belanja</h2>

                <!-- Cart Items -->
                <div class="max-h-80 overflow-y-auto mb-4">
                    <template x-if="cart.items.length === 0">
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                            <p>Keranjang kosong</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart.items" :key="index">
                        <div class="flex justify-between items-center py-2 border-b">
                            <div class="flex-1">
                                <h4 class="font-medium" x-text="item.name"></h4>
                                <div class="flex items-center text-sm text-gray-600">
                                    <span x-text="formatCurrency(item.price)"></span>
                                    <span class="mx-1">x</span>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300"
                                            @click="decreaseQuantity(index)">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" class="w-10 text-center border rounded"
                                            x-model.number="item.quantity" min="1"
                                            @change="updateCartItem(index)">
                                        <button
                                            class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300"
                                            @click="increaseQuantity(index)">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold" x-text="formatCurrency(item.price * item.quantity)"></div>
                                <button class="text-red-500 hover:text-red-700 text-sm" @click="removeFromCart(index)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Cart Summary -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-medium" x-text="formatCurrency(cart.subtotal)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak (11%)</span>
                        <span x-text="formatCurrency(cart.tax)"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span x-text="formatCurrency(cart.total)"></span>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="method in paymentMethods" :key="method.id">
                                <button class="border rounded-md py-2 px-3 flex items-center justify-center"
                                    :class="selectedPaymentMethod === method.id ? 'border-blue-500 bg-blue-50' :
                                        'hover:bg-gray-50'"
                                    @click="selectPaymentMethod(method.id)">
                                    <i :class="method.icon + ' mr-2'"></i>
                                    <span x-text="method.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="selectedPaymentMethod === 'cash'">
                        <label class="block text-sm font-medium mb-1">Uang Diterima</label>
                        <div class="flex space-x-2">
                            <input type="number"
                                class="border rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                                x-model.number="cashAmount" @input="calculateChange">
                        </div>
                        <div class="mt-2" x-show="cashAmount > 0">
                            <span class="text-sm">Kembalian: </span>
                            <span class="font-medium" x-text="formatCurrency(cashChange)"></span>
                        </div>
                    </div>

                    <button
                        class="w-full bg-blue-600 text-white rounded-md py-3 font-medium hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                        :disabled="!canCheckout" @click="checkout">
                        Proses Pembayaran
                    </button>

                    <button
                        class="w-full border border-red-500 text-red-500 rounded-md py-2 font-medium hover:bg-red-50"
                        @click="clearCart">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Receipt Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-show="showReceiptModal"
    x-transition>
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Struk Pembayaran</h2>
            <button @click="showReceiptModal = false" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="border-t border-b py-4 mb-4">
            <div class="text-center mb-4">
                <h3 class="font-bold text-lg">Kasir Daya</h3>
                <p class="text-sm text-gray-600">Jl. Contoh No. 123, Jakarta</p>
                <p class="text-sm text-gray-600" x-text="receiptDateTime"></p>
            </div>

            <div class="space-y-2">
                <template x-for="(item, index) in receiptItems" :key="index">
                    <div class="flex justify-between text-sm">
                        <div>
                            <span x-text="item.name"></span>
                            <span class="text-gray-600" x-text="' x ' + item.quantity"></span>
                        </div>
                        <span x-text="formatCurrency(item.price * item.quantity)"></span>
                    </div>
                </template>
            </div>

            <div class="border-t mt-4 pt-2 space-y-1">
                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span x-text="formatCurrency(receiptSubtotal)"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Pajak (11%)</span>
                    <span x-text="formatCurrency(receiptTax)"></span>
                </div>
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span x-text="formatCurrency(receiptTotal)"></span>
                </div>
                <template x-if="receiptPaymentMethod === 'cash'">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span>Tunai</span>
                            <span x-text="formatCurrency(receiptCashAmount)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Kembalian</span>
                            <span x-text="formatCurrency(receiptCashChange)"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="text-center text-sm">
            <p class="font-medium">Terima kasih atas kunjungan Anda!</p>
            <p class="text-gray-600 mt-1">No. Transaksi: INV-<span x-text="receiptNumber"></span></p>
        </div>

        <div class="mt-6 flex space-x-2">
            <button class="flex-1 bg-blue-600 text-white rounded-md py-2 font-medium hover:bg-blue-700"
                @click="printReceipt">
                <i class="fas fa-print mr-2"></i> Cetak
            </button>
            <button class="flex-1 border border-blue-600 text-blue-600 rounded-md py-2 font-medium hover:bg-blue-50"
                @click="newTransaction">
                Transaksi Baru
            </button>
        </div>
    </div>
</div>
