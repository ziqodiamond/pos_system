<div class="grid grid-cols-2 gap-4">
    <!-- Kolom Kiri (Harga) -->
    <div x-data="priceCalculatorAdd">
        <!-- Harga Beli -->
        <label for="harga_beli" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_beli" x-model="displayHargaBeli"
                x-on:blur="formatCurrencyInput('hargaBeli')"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <input type="hidden" name="harga_beli" x-model="price.hargaBeli" />
        </div>

        <!-- Harga Pokok -->
        <label for="harga_pokok" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Pokok</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_pokok" x-model="displayHargaPokok"
                x-on:blur="formatCurrencyInput('hargaPokok'); $dispatch('hitung-diskon')" @input="recalculateAll()"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <input type="hidden" name="harga_pokok" x-model="price.hargaPokok" />
        </div>

        <!-- Harga Jual -->
        <label for="harga_jual" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Jual</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_jual" x-model="displayHargaJual"
                x-on:blur="formatCurrencyInput('hargaJual'); $dispatch('hitung-diskon')"
                @input="calculateFromHargaJual()"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <input type="hidden" name="harga_jual" x-model="price.hargaJual" />
        </div>

        <!-- Markup (%) -->
        <label for="markup" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Markup (%) <span class="text-xs text-gray-500">(Laba/HPP*100)</span>
        </label>
        <div class="relative">
            <input type="text" id="markup" x-model="displayMarkup" @input="updateHargaJualFromMarkup()"
                @blur="formatPercentageInput('markup'); $dispatch('hitung-diskon')"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <span class="absolute right-3 top-2.5">%</span>
            <input type="hidden" name="markup" x-model="price.markup" />
        </div>

        <!-- Margin Keuntungan (%) -->
        <label for="margin" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Margin (%) <span class="text-xs text-gray-500">(Laba/Harga Jual*100)</span>
        </label>
        <div class="relative">
            <input type="text" id="margin" x-model="displayMargin" @input="updateHargaJualFromMargin()"
                @blur="formatPercentageInput('margin'); $dispatch('hitung-diskon')"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <span class="absolute right-3 top-2.5">%</span>
            <input type="hidden" name="margin" x-model="price.margin" />
        </div>
    </div>


    <!-- Script untuk menangani format mata uang dan persentase -->
    <script>
        // Mendefinisikan komponen Alpine.js untuk pengelolaan harga
        document.addEventListener('alpine:init', () => {
            // Komponen utama untuk mengelola semua input harga dan perhitungan
            Alpine.data('priceCalculatorAdd', () => ({
                // Objek yang menyimpan semua nilai harga (sebagai nilai numerik)
                price: {
                    hargaBeli: 0,
                    hargaPokok: 0,
                    hargaJual: 0,
                    markup: 0.00,
                    margin: 0.00
                },

                // Nilai untuk display (format Rupiah dan persen)
                displayHargaBeli: '0',
                displayHargaPokok: '0',
                displayHargaJual: '0',
                displayMarkup: '0,00',
                displayMargin: '0,00',

                // Flag untuk mencegah kalkulasi berulang saat memperbarui nilai
                isUpdating: false,

                /**
                 * Inisialisasi komponen
                 */
                init() {
                    // Inisialisasi nilai-nilai awal
                    this.formatAllDisplayValues();
                },

                /**
                 * Format semua nilai tampilan berdasarkan nilai numerik
                 */
                formatAllDisplayValues() {
                    this.displayHargaBeli = this.formatCurrency(this.price.hargaBeli);
                    this.displayHargaPokok = this.formatCurrency(this.price.hargaPokok);
                    this.displayHargaJual = this.formatCurrency(this.price.hargaJual);
                    this.displayMarkup = this.formatPercentage(this.price.markup);
                    this.displayMargin = this.formatPercentage(this.price.margin);
                },

                /**
                 * Format nilai mata uang untuk tampilan
                 * @param {number|string} value - Nilai numerik yang akan diformat
                 * @return {string} Nilai yang telah diformat (misal: "1.000")
                 */
                formatCurrency(value) {
                    if (!value && value !== 0) return '0';

                    let numValue = Number(value);
                    if (isNaN(numValue)) numValue = 0;

                    return Math.round(numValue).toLocaleString('id-ID');
                },

                /**
                 * Format nilai persentase untuk tampilan
                 * @param {number|string} value - Nilai persentase numerik 
                 * @return {string} Nilai yang telah diformat (misal: "10,50")
                 */
                formatPercentage(value) {
                    if (!value && value !== 0) return '0,00';

                    let numValue = parseFloat(value);
                    if (isNaN(numValue)) numValue = 0;

                    return numValue.toFixed(2).replace('.', ',');
                },

                /**
                 * Format input mata uang saat blur
                 * @param {string} field - Nama field yang akan diformat ('hargaBeli', 'hargaPokok', dll)
                 */
                formatCurrencyInput(field) {
                    if (this.isUpdating) return;

                    const displayField = 'display' + field.charAt(0).toUpperCase() + field.slice(1);

                    let inputValue = this[displayField].replace(/[^\d]/g, '');
                    if (inputValue === '' || isNaN(parseInt(inputValue, 10))) {
                        inputValue = '0';
                    }

                    const numValue = parseInt(inputValue, 10);

                    this.price[field] = numValue;
                    this[displayField] = this.formatCurrency(numValue);

                    if (field !== 'hargaBeli') {
                        this.recalculateAll();
                    }
                },

                /**
                 * Format input persentase saat blur
                 * @param {string} field - Nama field yang akan diformat ('markup' atau 'margin')
                 */
                formatPercentageInput(field) {
                    if (this.isUpdating) return;

                    const displayField = 'display' + field.charAt(0).toUpperCase() + field.slice(1);

                    let inputValue = this[displayField].replace(',', '.');
                    if (inputValue === '' || isNaN(parseFloat(inputValue))) {
                        inputValue = '0';
                    }

                    const numValue = parseFloat(inputValue).toFixed(2);

                    this.price[field] = numValue;
                    this[displayField] = this.formatPercentage(numValue);
                },

                /**
                 * Hitung ulang semua nilai terkait (markup dan margin) dari harga pokok dan harga jual
                 */
                recalculateAll() {
                    if (this.isUpdating) return;
                    this.isUpdating = true;

                    try {
                        const hargaPokok = this.price.hargaPokok;
                        const hargaJual = this.price.hargaJual;

                        if (hargaPokok > 0 && hargaJual > 0) {
                            const profit = hargaJual - hargaPokok;

                            this.price.markup = ((profit / hargaPokok) * 100).toFixed(2);
                            this.price.margin = ((profit / hargaJual) * 100).toFixed(2);

                            this.displayMarkup = this.formatPercentage(this.price.markup);
                            this.displayMargin = this.formatPercentage(this.price.margin);
                        } else {
                            this.price.markup = '0.00';
                            this.price.margin = '0.00';
                            this.displayMarkup = '0,00';
                            this.displayMargin = '0,00';
                        }
                    } finally {
                        this.isUpdating = false;
                    }
                },

                /**
                 * Hitung dari input markup
                 */
                updateHargaJualFromMarkup() {
                    if (this.isUpdating) return;
                    this.isUpdating = true;

                    try {
                        const hargaPokok = this.price.hargaPokok;
                        let markup = parseFloat(this.displayMarkup.replace(',', '.'));

                        if (isNaN(markup)) markup = 0;

                        if (hargaPokok > 0) {
                            const hargaJual = Math.round(hargaPokok * (1 + (markup / 100)));

                            this.price.hargaJual = hargaJual;
                            this.displayHargaJual = this.formatCurrency(hargaJual);

                            const profit = hargaJual - hargaPokok;
                            this.price.margin = ((profit / hargaJual) * 100).toFixed(2);
                            this.displayMargin = this.formatPercentage(this.price.margin);
                        }
                    } finally {
                        this.isUpdating = false;
                    }
                },

                /**
                 * Hitung dari input margin
                 */
                updateHargaJualFromMargin() {
                    if (this.isUpdating) return;
                    this.isUpdating = true;

                    try {
                        const hargaPokok = this.price.hargaPokok;
                        let margin = parseFloat(this.displayMargin.replace(',', '.'));

                        if (isNaN(margin)) margin = 0;

                        if (hargaPokok > 0) {
                            let hargaJual;

                            if (margin >= 100) {
                                hargaJual = hargaPokok * 10;
                            } else {
                                hargaJual = Math.round(hargaPokok / (1 - (margin / 100)));
                            }

                            this.price.hargaJual = hargaJual;
                            this.displayHargaJual = this.formatCurrency(hargaJual);

                            const profit = hargaJual - hargaPokok;
                            this.price.markup = ((profit / hargaPokok) * 100).toFixed(2);
                            this.displayMarkup = this.formatPercentage(this.price.markup);
                        }
                    } finally {
                        this.isUpdating = false;
                    }
                },

                /**
                 * Hitung markup dan margin dari harga jual yang diinput
                 */
                calculateFromHargaJual() {
                    if (this.isUpdating) return;

                    // Proses saat blur akan dilakukan untuk mengubah format
                    // Dan recalculateAll() akan dipanggil setelahnya
                }
            }));
        });
    </script>


    <!-- Kolom Kanan (Satuan, Pajak, Diskon, Stok) -->
    <div class="space-y-4">
        <!-- Input pajak -->
        <div x-data="dropdown('pajak')">
            <label for="pajak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pajak</label>
            <div class="relative">
                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
                    placeholder="Cari Pajak..." required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />


                <ul x-show="open" @click.outside="close()" x-cloak
                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                    <template x-for="pajak in filteredPajaks" :key="pajak.id">
                        <li @click="select(pajak)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <span x-text="pajak.nama"></span>
                        </li>
                    </template>
                    <!-- Kalau datanya kosong -->
                    <li x-show="filteredPajaks.length === 0" class="px-2 py-1 text-gray-500 text-sm">
                        Data tidak ditemukan
                    </li>
                </ul>
                <input type="hidden" name="pajak_id" :value="selected.id">
            </div>
        </div>

        <!-- Input satuan  -->
        <div x-data="dropdown('satuan')">
            <label for="pajak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                Satuan</label>
            <div class="relative">
                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
                    placeholder="Cari Satuan..." required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


                <!-- List Dropdown -->
                <ul x-show="open" @click.outside="close()" x-cloak
                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                    <template x-for="item in filteredSatuans" :key="item.id">
                        <li @click="select(item)" class="cursor-pointer px-2 py-1 hover:bg-gray-100">
                            <span x-text="item.nama"></span>
                        </li>
                    </template>
                    <!-- Kalau datanya kosong -->
                    <li x-show="filteredSatuans.length === 0" class="px-2 py-1 text-gray-500 text-sm">
                        Data tidak ditemukan
                    </li>
                </ul>
                <input type="hidden" name="satuan_id" :value="selected.id">
            </div>
        </div>

        <!-- Diskon Field dengan informasi tambahan -->
        <div x-data="diskonCalculatorAdd" @hitung-diskon.window="hitungDiskon()">
            <label for="diskon" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon
                (%)</label>
            <input type="text" id="diskon_display" x-model="displayDiskonPersen" @input="hitungDiskon()"
                @blur="formatPercentageInput()"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <input type="hidden" name="diskon" x-model="diskonPersen" />

            <!-- Informasi diskon -->
            <div class="mt-2 space-y-1 text-xs">
                <p class="text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Total Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="'Rp ' + formatCurrency(diskonNominal)"></span>
                    <input type="hidden" name="diskon_nominal" x-model="diskonNominal" />
                </p>
                <p class="text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Harga Jual Setelah Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="'Rp ' + formatCurrency(hargaSetelahDiskon)"></span>
                </p>
                <p class="text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Margin Setelah Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="formatPercentage(marginSetelahDiskon) + '%'"></span>
                </p>
                <p class="text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Markup Setelah Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="formatPercentage(markupSetelahDiskon) + '%'"></span>
                </p>
            </div>
        </div>

        <div>
            <label for="stok_minimal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok
                Minimal</label>
            <input type="number" id="stok_minimal" name="stok_minimal" min="0"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
        </div>
    </div>

</div>

<!-- Script untuk menghitung diskon -->
<script>
    document.addEventListener('alpine:init', () => {
        // Komponen untuk mengelola diskon dan efeknya
        Alpine.data('diskonCalculatorAdd', () => ({
            diskonPersen: 0,
            displayDiskonPersen: '0,00',
            diskonNominal: 0,
            hargaSetelahDiskon: 0,
            marginSetelahDiskon: 0,
            markupSetelahDiskon: 0,

            init() {
                // Inisialisasi store global
                if (!Alpine.store('globals')) {
                    Alpine.store('globals', {
                        hargaJual: 0,
                        hargaPokok: 0
                    });
                }
            },

            formatPercentageInput() {
                let inputValue = this.displayDiskonPersen.replace(',', '.');
                if (inputValue === '' || isNaN(parseFloat(inputValue))) {
                    inputValue = '0';
                }

                // Konversi ke float dengan 2 desimal
                const numValue = parseFloat(inputValue).toFixed(2);

                // Update nilai hidden dan display
                this.diskonPersen = numValue;
                this.displayDiskonPersen = this.formatPercentage(numValue);

                // Hitung ulang diskon
                this.hitungDiskon();
            },

            hitungDiskon() {
                // Baca langsung dari input DOM (sudah diformat saat blur)
                const hargaJualElem = document.getElementById('display_harga_jual');
                const hargaPokokElem = document.getElementById('display_harga_pokok');

                if (!hargaJualElem || !hargaPokokElem) return;

                // Bersihkan titik pemisah ribuan, langsung parse integer
                const hargaJual = parseInt(hargaJualElem.value.replace(/\./g, '')) || 0;
                const hargaPokok = parseInt(hargaPokokElem.value.replace(/\./g, '')) || 0;

                // Hitung nominal diskon
                const diskonPersenFloat = parseFloat(this.diskonPersen) || 0;
                this.diskonNominal = Math.round((hargaJual * diskonPersenFloat) / 100);

                // Harga setelah diskon
                this.hargaSetelahDiskon = hargaJual - this.diskonNominal;

                // Hitung margin dan markup setelah diskon
                if (this.hargaSetelahDiskon > 0) {
                    const profit = this.hargaSetelahDiskon - hargaPokok;
                    this.marginSetelahDiskon = (profit / this.hargaSetelahDiskon) * 100;
                    this.markupSetelahDiskon = hargaPokok > 0 ? (profit / hargaPokok) * 100 : 0;
                } else {
                    this.marginSetelahDiskon = 0;
                    this.markupSetelahDiskon = 0;
                }
            },

            formatCurrency(value) {
                if (!value && value !== 0) return '0';

                // Bulatkan ke integer, tanpa sen
                let numValue = Math.round(parseFloat(value));
                if (isNaN(numValue)) numValue = 0;

                return numValue.toLocaleString('id-ID');
            },

            formatPercentage(value) {
                if (!value && value !== 0) return '0,00';

                // Format percentage dengan 2 angka di belakang koma
                const numValue = parseFloat(value);
                return numValue.toFixed(2).replace('.', ',');
            }
        }));
    });
</script>
