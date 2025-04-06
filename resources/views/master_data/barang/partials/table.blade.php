<x-master-table :route="'barang'" :items="$barang" :columns="['Kode', 'Nama', 'Kategori', 'Harga Beli', 'Harga Pokok', 'Harga Jual', 'Diskon', 'Pajak', 'Status', 'Stok']">
    @forelse ($barang as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->kode }}
            </th>
            <td class="px-6 py-4">
                {{ $item->nama }}
            </td>
            <td class="px-6 py-4">
                {{ $item->kategori->nama }}
            </td>
            <td class="px-6 py-4">
                Rp {{ number_format($item->harga_beli / 100, 2, ',', '.') }}
            </td>
            <td class="px-6 py-4">
                Rp {{ number_format($item->harga_pokok / 100, 2, ',', '.') }}
            </td>
            <td class="px-6 py-4">
                Rp {{ number_format($item->harga_jual / 100, 2, ',', '.') }}
            </td>
            </td>
            <td class="px-6 py-4">
                {{ $item->diskon_value }} %
            </td>
            <td class="px-6 py-4">
                {{ $item->pajak?->nama }}
            </td>
            <td class="px-6 py-4">
                {{ $item->status }}
            </td>
            <td class="px-6 py-4">
                {{ $item->stok }}
            </td>
            <td class="flex items-center px-6 py-4 space-x-2">
                @if ($item->trashed())
                    <x-base-modal :id="'restoreModal-' . $item->id" title="Restore Barang" triggerText="Pulihkan"
                        triggerClass="font-medium text-green-600 dark:text-green-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin memulihkan item {{ $item->nama }}?
                        </p>
                        <form class="space-y-6" action="{{ route('barang.restore', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Pulihkan
                            </button>
                        </form>
                    </x-base-modal>

                    <x-base-modal :id="'hardDeleteModal-' . $item->id" title="Permanent Delete" triggerText="Hapus Permanen"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin menghapus permanen item {{ $item->nama }}? Tindakan ini tidak
                            dapat
                            dibatalkan.
                        </p>
                        <form class="space-y-6" action="{{ route('barang.forceDelete', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Hapus Permanen
                            </button>
                        </form>
                    </x-base-modal>
                @else
                    {{-- Edit Modal --}}
                    @include('master_data.barang.partials.edit-barang')

                    {{-- Delete Modal --}}
                    <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Supplier" triggerText="Delete"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete this supplier?
                        </p>
                        <form class="space-y-6" action="{{ route('supplier.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Delete</button>
                        </form>
                    </x-base-modal>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="8">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $barang->links() }}


<!-- Script untuk semua fungsi Alpine.js -->
<script>
    document.addEventListener('alpine:init', () => {
        // Komponen untuk perhitungan harga
        Alpine.data('priceCalculator', (itemId, item = {}) => ({
            // Menyimpan item ID untuk referensi
            itemId: itemId,
            itemData: item ?? {},

            // Objek yang menyimpan semua nilai harga (dalam satuan sen)
            price: {
                hargaBeli: 0, // Akan diisi dari database
                hargaPokok: 0, // Akan diisi dari database
                hargaJual: 0, // Akan diisi dari database
                markup: null, // Gunakan nilai dari database atau null
                margin: null // Gunakan nilai dari database atau null
            },

            // Nilai untuk display (format Rupiah dan persen)
            displayHargaBeli: '',
            displayHargaPokok: '',
            displayHargaJual: '',
            displayMarkup: '',
            displayMargin: '',

            // Flag untuk mencegah kalkulasi berulang saat memperbarui nilai
            isUpdating: false,

            /**
             * Inisialisasi komponen
             * Digunakan untuk memuat data berdasarkan item ID
             */
            init() {
                // Isi data harga dari database - Perbaikan: menggunakan this.itemData bukan itemData yang tidak terdefinisi
                this.price.hargaBeli = this.itemData.harga_beli || 0;
                this.price.hargaPokok = this.itemData.harga_pokok || 0;
                this.price.hargaJual = this.itemData.harga_jual || 0;
                this.price.markup = this.itemData.markup || null;
                this.price.margin = this.itemData.margin || null;

                // Konversi nilai sen ke Rupiah untuk display
                this.formatAllDisplayValues();

                // Pastikan margin dan markup dihitung langsung saat halaman dimuat
                this.$nextTick(() => {
                    // Jika markup atau margin tidak tersedia, hitung dari harga pokok dan harga jual
                    if (this.price.markup === null || this.price.margin === null) {
                        // Pastikan harga pokok dan harga jual valid untuk perhitungan
                        if (this.price.hargaJual > 0 && this.price.hargaPokok > 0) {
                            this.recalculateAll();
                        }
                    }
                });
            },

            /**
             * Format semua nilai tampilan berdasarkan nilai numerik
             */
            formatAllDisplayValues() {
                this.displayHargaBeli = this.formatCurrency(this.price.hargaBeli / 100);
                this.displayHargaPokok = this.formatCurrency(this.price.hargaPokok / 100);
                this.displayHargaJual = this.formatCurrency(this.price.hargaJual / 100);

                // Format nilai markup dan margin jika tersedia
                if (this.price.markup !== null) {
                    this.displayMarkup = this.formatPercentage(this.price.markup);
                } else {
                    this.displayMarkup = '0,00';
                    this.price.markup = 0;
                }

                if (this.price.margin !== null) {
                    this.displayMargin = this.formatPercentage(this.price.margin);
                } else {
                    this.displayMargin = '0,00';
                    this.price.margin = 0;
                }
            },

            /**
             * Format nilai mata uang untuk tampilan
             * @param {number|string} value - Nilai numerik yang akan diformat
             * @return {string} Nilai yang telah diformat (misal: "800.000,12")
             */
            formatCurrency(value) {
                // Konversi ke string dan pastikan ada nilai
                if (!value && value !== 0) return '0,00';

                // Konversi ke float dengan 2 desimal
                let numValue = parseFloat(value);
                if (isNaN(numValue)) numValue = 0;

                // Format dengan pemisah ribuan (titik) dan desimal (koma)
                let parts = numValue.toFixed(2).split('.');

                // Tambahkan pemisah ribuan (titik) untuk bagian integer
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Gabungkan kembali dengan koma sebagai pemisah desimal
                return parts.join(',');
            },

            /**
             * Format nilai persentase untuk tampilan
             * @param {number|string} value - Nilai persentase numerik 
             * @return {string} Nilai yang telah diformat (misal: "10,50")
             */
            formatPercentage(value) {
                if (!value && value !== 0) return '0,00';

                // Konversi ke float dan format dengan 2 desimal
                let numValue = parseFloat(value);
                if (isNaN(numValue)) numValue = 0;

                // Format dengan 2 desimal dan ganti titik dengan koma
                return numValue.toFixed(2).replace('.', ',');
            },

            /**
             * Format input mata uang saat blur
             * @param {string} field - Nama field yang akan diformat ('hargaBeli', 'hargaPokok', dll)
             */
            formatCurrencyInput(field) {
                if (this.isUpdating) return;

                // Tentukan field display yang sesuai
                const displayField = 'display' + field.charAt(0).toUpperCase() + field.slice(1);

                // Ambil nilai dari display dan konversi format (contoh: "800.000,12" -> 80000012)
                let inputValue = this[displayField].replace(/\./g, '').replace(',', '.');
                if (inputValue === '' || isNaN(parseFloat(inputValue))) {
                    inputValue = '0';
                }

                // Konversi ke float, kalikan 100 untuk menyimpan sen, dan simpan sebagai integer
                const numValue = Math.round(parseFloat(inputValue) * 100);

                // Update nilai hidden (dalam satuan sen, misal 80000012 untuk Rp 800.000,12)
                this.price[field] = numValue;

                // Update display dengan format (tampilkan dalam Rp)
                this[displayField] = this.formatCurrency(numValue / 100);

                // Recalculate jika diperlukan (kecuali untuk harga beli)
                if (field !== 'hargaBeli') {
                    this.recalculateAll();
                }

                // Hitung ulang diskon jika diskonCalculator ada
                this.$nextTick(() => {
                    // Cari komponen diskonCalculator dengan itemId yang sama
                    const diskonComponent = document.querySelector(
                        `[x-data="diskonCalculator('${this.itemId}')"]`);
                    if (diskonComponent && diskonComponent.__x) {
                        diskonComponent.__x.getUnobservedData().hitungDiskon();
                    }
                });
            },

            /**
             * Format input persentase saat blur
             * @param {string} field - Nama field yang akan diformat ('markup' atau 'margin')
             */
            formatPercentageInput(field) {
                if (this.isUpdating) return;

                // Tentukan field display yang sesuai
                const displayField = 'display' + field.charAt(0).toUpperCase() + field.slice(1);

                // Ambil nilai dari display
                let inputValue = this[displayField].replace(',', '.');
                if (inputValue === '' || isNaN(parseFloat(inputValue))) {
                    inputValue = '0';
                }

                // Konversi ke float dengan 2 desimal
                const numValue = parseFloat(inputValue).toFixed(2);

                // Update nilai hidden
                this.price[field] = numValue;

                // Update display dengan format
                this[displayField] = this.formatPercentage(numValue);

                // Recalculate nilai terkait
                if (field === 'markup') {
                    this.updateHargaJualFromMarkup();
                } else if (field === 'margin') {
                    this.updateHargaJualFromMargin();
                }
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

                    // Hanya lakukan perhitungan jika kedua nilai tersedia dan valid
                    if (hargaPokok > 0 && hargaJual > 0) {
                        // Hitung profit
                        const profit = hargaJual - hargaPokok;

                        // Hitung markup: (profit / hargaPokok) * 100
                        this.price.markup = ((profit / hargaPokok) * 100).toFixed(2);

                        // Hitung margin: (profit / hargaJual) * 100
                        this.price.margin = ((profit / hargaJual) * 100).toFixed(2);

                        // Update display
                        this.displayMarkup = this.formatPercentage(this.price.markup);
                        this.displayMargin = this.formatPercentage(this.price.margin);
                    } else if (hargaPokok === 0 && hargaJual > 0) {
                        // Jika harga pokok 0, margin masih bisa dihitung tapi markup tidak terbatas
                        this.price.margin = '100.00';
                        this.price.markup = '0.00'; // Tidak bisa dihitung
                        this.displayMargin = '100,00';
                        this.displayMarkup = '0,00';
                    } else {
                        // Reset nilai jika tidak bisa dihitung
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
                    // Ambil harga pokok dan markup
                    const hargaPokok = this.price.hargaPokok;
                    let markup = parseFloat(this.displayMarkup.replace(',', '.'));

                    if (isNaN(markup)) markup = 0;

                    // Hanya lakukan perhitungan jika harga pokok valid
                    if (hargaPokok > 0) {
                        // Hitung harga jual: hargaPokok * (1 + markup/100)
                        // Pertahankan presisi dengan tidak membulatkan nilai
                        const hargaJual = Math.round(hargaPokok * (1 + (markup / 100)));

                        // Update nilai
                        this.price.hargaJual = hargaJual;
                        this.displayHargaJual = this.formatCurrency(hargaJual / 100);

                        // Update margin
                        const profit = hargaJual - hargaPokok;
                        this.price.margin = ((profit / hargaJual) * 100).toFixed(2);
                        this.displayMargin = this.formatPercentage(this.price.margin);

                        // Update diskon calculator jika ada
                        this.$nextTick(() => {
                            const diskonComponent = document.querySelector(
                                `[x-data="diskonCalculator('${this.itemId}')"]`);
                            if (diskonComponent && diskonComponent.__x) {
                                diskonComponent.__x.getUnobservedData().hitungDiskon();
                            }
                        });
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
                    // Ambil harga pokok dan margin
                    const hargaPokok = this.price.hargaPokok;
                    let margin = parseFloat(this.displayMargin.replace(',', '.'));

                    if (isNaN(margin)) margin = 0;

                    // Hanya lakukan perhitungan jika harga pokok valid
                    if (hargaPokok > 0) {
                        let hargaJual;

                        // Hindari division by zero
                        if (margin >= 100) {
                            hargaJual = hargaPokok * 10; // Nilai tinggi sebagai batas
                        } else {
                            // Hitung harga jual: hargaPokok / (1 - margin/100)
                            // Pertahankan presisi dengan pembulatan ke sen
                            hargaJual = Math.round(hargaPokok / (1 - (margin / 100)));
                        }

                        // Update nilai
                        this.price.hargaJual = hargaJual;
                        this.displayHargaJual = this.formatCurrency(hargaJual / 100);

                        // Update markup
                        const profit = hargaJual - hargaPokok;
                        this.price.markup = ((profit / hargaPokok) * 100).toFixed(2);
                        this.displayMarkup = this.formatPercentage(this.price.markup);

                        // Update diskon calculator jika ada
                        this.$nextTick(() => {
                            const diskonComponent = document.querySelector(
                                `[x-data="diskonCalculator('${this.itemId}')"]`);
                            if (diskonComponent && diskonComponent.__x) {
                                diskonComponent.__x.getUnobservedData().hitungDiskon();
                            }
                        });
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
                this.formatCurrencyInput('hargaJual');
                // recalculateAll() sudah dipanggil dalam formatCurrencyInput
            }
        }));

        // Komponen untuk mengelola diskon dan efeknya
        Alpine.data('diskonCalculator', (itemId, item = {}) => ({
            itemId: itemId,
            itemData: item ?? {},
            diskonPersen: 0,
            displayDiskonPersen: '0,00',
            diskonNominal: 0,
            hargaSetelahDiskon: 0,
            marginSetelahDiskon: 0,
            markupSetelahDiskon: 0,

            init() {
                // Isi data diskon dari database - Perbaikan: menggunakan this.itemData bukan itemData yang tidak terdefinisi
                this.diskonPersen = this.itemData.diskon_value || 0;
                this.displayDiskonPersen = this.formatPercentage(this.diskonPersen);

                // Hitung diskon pada inisialisasi
                this.$nextTick(() => {
                    this.hitungDiskon();
                });
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
                // Dapatkan nilai dari komponen priceCalculator dengan ID yang sama
                const priceComponent = document.querySelector(
                    `[x-data="priceCalculator('${this.itemId}')"]`);

                if (!priceComponent || !priceComponent.__x) return;

                const priceData = priceComponent.__x.getUnobservedData();

                // Ambil nilai harga jual dan harga pokok dari komponen
                let hargaJual = priceData.price.hargaJual || 0;
                let hargaPokok = priceData.price.hargaPokok || 0;

                // Hitung nominal diskon
                const diskonPersenFloat = parseFloat(this.diskonPersen);
                this.diskonNominal = Math.round((hargaJual * diskonPersenFloat) / 100);

                // Hitung harga setelah diskon
                this.hargaSetelahDiskon = hargaJual - this.diskonNominal;

                // Hitung margin dan markup setelah diskon
                if (this.hargaSetelahDiskon > 0) {
                    const profitSetelahDiskon = this.hargaSetelahDiskon - hargaPokok;

                    // Hitung margin setelah diskon (dengan presisi 2 desimal)
                    this.marginSetelahDiskon = (profitSetelahDiskon / this.hargaSetelahDiskon) *
                        100;

                    // Hitung markup setelah diskon (jika harga pokok > 0)
                    if (hargaPokok > 0) {
                        this.markupSetelahDiskon = (profitSetelahDiskon / hargaPokok) * 100;
                    } else {
                        this.markupSetelahDiskon = 0;
                    }
                } else {
                    this.marginSetelahDiskon = 0;
                    this.markupSetelahDiskon = 0;
                }
            },

            formatCurrency(value) {
                if (!value && value !== 0) return '0,00';

                // Konversi ke float dengan 2 desimal
                let numValue = parseFloat(value);
                if (isNaN(numValue)) numValue = 0;

                // Format dengan pemisah ribuan (titik) dan desimal (koma)
                let parts = numValue.toFixed(2).split('.');

                // Tambahkan pemisah ribuan (titik) untuk bagian integer
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Gabungkan kembali dengan koma sebagai pemisah desimal
                return parts.join(',');
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
