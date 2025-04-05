<x-layout>
    @include('components.breadcrumbs')
    <!-- Add form tag around the purchase form div -->
    <form x-data="purchaseForm()" action="{{ route('pembelian.store') }}" method="POST">
        @csrf
        <!-- Previous breadcrumbs code -->
        <div class="p-4 md:p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pembelian</h1>

            <!-- Purchase Form using Alpine.js -->
            <div class="bg-white rounded-lg shadow-md">

                <!-- Form Header -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border-b">
                    @include('pembelian.form_pembelian.partials.form-header')
                </div>

                <!-- Date, Reference, Description -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border-b">
                    @include('pembelian.form_pembelian.partials.form-input')

                </div>

                <!-- Item Table -->
                <div class="overflow-x-auto p-4">
                    @include('pembelian.form_pembelian.partials.form-table')
                </div>

                <!-- Additional Options -->
                <div class="p-4 border-t">
                    @include('pembelian.form_pembelian.partials.form-summary')
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
                barangs: @json($barangs),
                satuans: type === 'satuan' ? @json($satuans) : [],
                konversis: @json($konversis), // Data konversi satuan

                // Fungsi untuk mendapatkan ID barang yang dipilih pada row tertentu
                getSelectedBarangId(index) {
                    // Mengakses data item pada index tertentu
                    return this.formData?.items?.[index]?.barang_id || null;
                },

                // Fungsi untuk mendapatkan objek barang berdasarkan ID
                getBarangById(id) {
                    // Ubah string menjadi string untuk perbandingan yang aman
                    const targetId = String(id);
                    return this.barangs.find(barang => String(barang.id) === targetId);
                },

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

                // Fungsi untuk mendapatkan satuan yang tersedia berdasarkan barang yang dipilih
                filteredSatuans(index) {
                    // Dapatkan ID barang yang dipilih pada row ini
                    const selectedBarangId = this.getSelectedBarangId(index);

                    // Jika belum ada barang yang dipilih, kembalikan array kosong
                    if (!selectedBarangId) {
                        return [];
                    }

                    // Temukan barang berdasarkan ID (mengubah ke string untuk perbandingan yang aman)
                    const selectedBarang = this.getBarangById(selectedBarangId);

                    if (!selectedBarang) {
                        // Jika barang tidak ditemukan dalam array barangs
                        console.error(`Barang dengan ID ${selectedBarangId} tidak ditemukan dalam daftar`);
                        return [];
                    }

                    // Dapatkan satuan dari barang
                    const satuanIdsFromBarang = selectedBarang.satuan_id ? [String(selectedBarang.satuan_id)] : [];

                    // Dapatkan satuan dari konversi
                    const satuanIdsFromKonversi = this.konversis
                        .filter(konversi => String(konversi.barang_id) === String(selectedBarangId))
                        .map(konversi => String(konversi.satuan_id));

                    // Gabungkan semua ID satuan (pastikan tidak ada duplikat)
                    const combinedSatuanIds = [...new Set([...satuanIdsFromBarang, ...satuanIdsFromKonversi])];

                    // Filter satuan berdasarkan ID
                    let availableSatuans = this.satuans.filter(satuan =>
                        combinedSatuanIds.includes(String(satuan.id))
                    );

                    // Filter berdasarkan pencarian
                    if (this.search.trim() !== '') {
                        availableSatuans = availableSatuans.filter(satuan =>
                            satuan.nama.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }

                    return availableSatuans;
                },

                // Inisialisasi dropdown
                init() {
                    if (type === 'supplier') {
                        this.selected = this.suppliers.find(item => String(item.id) ===
                            String({{ old('supplier_id') ?? 'null' }})) || {};
                    }

                    // Tambahkan event listener untuk mendeteksi perubahan pada items array
                    if (type === 'barang' || type === 'satuan') {
                        // Gunakan Alpine $watch untuk memantau perubahan pada formData.items
                        this.$watch('formData.items', (items) => {
                            // Reset nilai pencarian jika perlu
                            this.updateSearchValue(rowIndex);
                        });
                    }
                },

                // Fungsi baru untuk memperbarui nilai pencarian berdasarkan data saat ini
                updateSearchValue(index) {
                    // Pastikan index valid dan ada di dalam array items
                    if (index < 0 || index >= this.formData.items.length) {
                        this.search = '';
                        this.selected = {};
                        return;
                    }

                    const currentItem = this.formData.items[index];

                    // Update pencarian berdasarkan jenis dropdown
                    if (type === 'barang') {
                        if (currentItem.barang_id) {
                            // Temukan informasi barang
                            const barang = this.getBarangById(currentItem.barang_id);
                            if (barang) {
                                this.search = `${barang.kode} - ${barang.nama}`;
                                this.selected = barang;
                            } else {
                                this.search = currentItem.barang_nama || '';
                            }
                        } else {
                            this.search = '';
                            this.selected = {};
                        }
                    } else if (type === 'satuan') {
                        if (currentItem.satuan_id) {
                            // Temukan informasi satuan
                            const satuan = this.satuans.find(s => String(s.id) === String(currentItem.satuan_id));
                            if (satuan) {
                                this.search = satuan.nama;
                                this.selected = satuan;
                            } else {
                                this.search = currentItem.satuan_nama || '';
                            }
                        } else {
                            this.search = '';
                            this.selected = {};
                        }
                    }
                },

                // Fungsi untuk memilih item dari dropdown
                select(item, index) {
                    this.selected = item;
                    this.search = type === 'barang' ? `${item.kode} - ${item.nama}` : item.nama;

                    switch (type) {
                        case 'supplier':
                            this.formData.supplier_id = item.id;
                            break;
                        case 'barang':
                            this.formData.items[index].barang_id = item.id;
                            this.formData.items[index].barang_nama = item.nama;
                            this.formData.items[index].hargaSatuan = item.harga_beli / 100 || 0;
                            this.formData.items[index].pajak_id = item.pajak_id || null;
                            // Reset satuan yang terpilih jika barang berubah
                            this.formData.items[index].satuan_dasar_id = item.satuan_id;
                            this.formData.items[index].satuan_nama = '';
                            this.formData.items[index].satuan_id = ''; // Reset ID satuan juga
                            // Gunakan window.form jika form adalah objek global, atau sesuaikan dengan cara Anda mengakses formData
                            this.calculateItemTotal(index);
                            break;
                        case 'satuan':
                            this.formData.items[index].satuan_id = item.id;
                            this.formData.items[index].satuan_nama = item.nama;
                            this.formData.items[index].status_satuan = item.status_satuan;
                            this.calculateItemTotal(index);
                            break;
                    }
                    this.close();
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

                    let items;
                    if (type === 'barang') {
                        items = this.filteredBarangs;
                    } else if (type === 'satuan') {
                        items = this.filteredSatuans(rowIndex);
                    } else {
                        items = this.filteredSuppliers;
                    }

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

                    let items;
                    if (type === 'barang') {
                        items = this.filteredBarangs;
                    } else if (type === 'satuan') {
                        items = this.filteredSatuans(rowIndex);
                    } else {
                        items = this.filteredSuppliers;
                    }

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
                barangs: @json($barangs),
                satuans: @json($satuans),
                konversis: @json($konversis),
                pajaks: @json($pajaks),

                formData: {
                    supplier_id: '',
                    noFaktur: '',
                    tanggal: new Date().toISOString().slice(0, 10),
                    noReferensi: 'P0000001',
                    deskripsi: '',
                    lunas: false,
                    include_pajak: false, // Mengontrol apakah harga sudah termasuk pajak
                    items: [{
                        barang_id: '',
                        barang_nama: '',
                        jumlah: 0,
                        jumlah_efektif: 0, // Field baru: jumlah setelah konversi
                        satuan_id: '',
                        satuan_nama: '',
                        satuan_dasar_id: null,
                        status_satuan: '',
                        hargaSatuan: 0,
                        harga_tanpa_pajak: 0, // Harga satuan tanpa pajak
                        harga_diskon: 0, // Field baru: harga setelah diskon satuan
                        harga_pokok: 0, // Field baru: harga_diskon + other_cost + nilai_pajak
                        other_cost: 0, // Field baru: biaya lain yg sudah di alokasikan ke setiap item
                        pajak_id: '',
                        nilai_pajak: 0, // Nilai pajak per satuan
                        total_nilai_pajak: 0, // Total nilai pajak untuk item ini
                        nilai_diskon: 0, // Field baru: nilai diskon per satuan item
                        diskon_per_item: 0, // Diskon yang dialokasikan per item
                        harga_setelah_diskon: 0, // Harga setelah diskon tanpa pajak (total)
                        subtotal: 0, // Subtotal item tanpa pajak (jumlah * harga_tanpa_pajak)
                        total: 0, // Total akhir item termasuk pajak jika include_pajak=true
                        grandTotalItem: 0 // Field baru: harga pokok * jumlah efektif
                    }],
                    biayaLain: 0,
                    diskon: 0,
                    diskonType: 'persen', // Default ke persen
                    totalDiskon: 0,
                    subtotalSebelumDiskon: 0, // Subtotal sebelum diskon
                    subtotalSetelahDiskon: 0, // Subtotal setelah diskon
                    totalPajak: 0,
                    uangMuka: 0,
                    grandTotal: 0,
                    sisa: 0
                },

                // Menyimpan referensi ke komponen dropdown
                dropdownInstances: {},

                // Fungsi untuk menambah item baru
                addItem() {
                    this.formData.items.push({
                        barang_id: '',
                        barang_nama: '',
                        jumlah: 0,
                        jumlah_efektif: 0, // Tambahkan field baru
                        satuan_id: '',
                        satuan_nama: '',
                        satuan_dasar_id: null,
                        status_satuan: '',
                        hargaSatuan: 0,
                        harga_tanpa_pajak: 0,
                        harga_diskon: 0, // Tambahkan field baru
                        harga_pokok: 0, // Tambahkan field baru
                        other_cost: 0, // Tambahkan field baru
                        pajak_id: '',
                        nilai_pajak: 0,
                        total_nilai_pajak: 0,
                        nilai_diskon: 0, // Tambahkan field baru
                        diskon_per_item: 0,
                        harga_setelah_diskon: 0,
                        subtotal: 0,
                        total: 0,
                        grandTotalItem: 0 // Tambahkan field baru
                    });

                    // Set fokus ke input barang baru
                    this.$nextTick(() => {
                        const lastIndex = this.formData.items.length - 1;
                        const lastBarangInput = document.querySelector(`[x-ref="barang${lastIndex}"]`);
                        if (lastBarangInput) {
                            lastBarangInput.focus();
                        }
                    });
                },

                // Fungsi untuk menghapus item
                removeItem(index) {
                    if (this.formData.items.length > 1) {
                        this.formData.items.splice(index, 1);

                        // Reset search field for all remaining dropdowns after removal
                        this.$nextTick(() => {
                            // Trigger update untuk semua dropdown yang tersisa
                            this.refreshAllDropdowns();
                        });

                        this.calculateTotal();
                    }
                },

                // Fungsi untuk menyegarkan semua dropdown setelah penghapusan item
                refreshAllDropdowns() {
                    // Loop melalui semua dropdown barang dan satuan untuk memanggil updateSearchValue
                    const dropdownElements = document.querySelectorAll('[x-data^="dropdown("]');
                    dropdownElements.forEach((element, i) => {
                        if (element.__x) {
                            // Upaya untuk mendapatkan referensi ke instance Alpine.js
                            const instance = element.__x.getUnobservedData();
                            // Jika memiliki method updateSearchValue, panggil
                            if (typeof instance.updateSearchValue === 'function') {
                                instance.updateSearchValue(i);
                            }
                        }
                    });
                },

                // Fungsi untuk mendapatkan nilai konversi dari satuan
                getKonversiNilai(barang_id, satuan_id, satuan_dasar_id) {
                    // Cari konversi spesifik untuk barang
                    const specificKonversi = this.konversis.find(konversi =>
                        String(konversi.barang_id) === String(barang_id) &&
                        String(konversi.satuan_id) === String(satuan_id) &&
                        String(konversi.satuan_tujuan_id) === String(satuan_dasar_id)
                    );

                    if (specificKonversi) {
                        // Konversi spesifik untuk barang ditemukan
                        return specificKonversi.nilai_konversi;
                    }

                    // Cari konversi universal (barang_id kosong)
                    const universalKonversi = this.konversis.find(konversi =>
                        !konversi.barang_id &&
                        String(konversi.satuan_id) === String(satuan_id) &&
                        String(konversi.satuan_tujuan_id) === String(satuan_dasar_id)
                    );

                    if (universalKonversi) {
                        // Konversi universal ditemukan
                        return universalKonversi.nilai_konversi;
                    }

                    // Jika tidak ditemukan, kembalikan 1 (tidak ada konversi)
                    console.warn(
                        `Konversi tidak ditemukan untuk barang_id ${barang_id}, satuan_id ${satuan_id}, satuan_dasar_id ${satuan_dasar_id}`
                    );
                    return 1;
                },

                // Fungsi untuk format angka dengan support sen
                formatNumber(value) {
                    // Memastikan nilai adalah number
                    const numValue = this.cleanNumber(value);
                    // Format dengan 2 desimal untuk sen
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(numValue);
                },

                // Fungsi untuk membersihkan format number dengan support sen
                cleanNumber(formattedNumber) {
                    // Jika input kosong atau undefined
                    if (!formattedNumber && formattedNumber !== 0) {
                        return 0;
                    }

                    // Menangani format Indonesia (1.234,56) dan mengubahnya menjadi nilai numerik
                    if (typeof formattedNumber === 'string') {
                        // Hapus semua titik sebagai pemisah ribuan
                        let cleanedStr = formattedNumber.replace(/\./g, '');
                        // Ganti koma dengan titik untuk desimal
                        cleanedStr = cleanedStr.replace(',', '.');
                        // Parse ke float
                        return parseFloat(cleanedStr) || 0;
                    }

                    // Jika sudah dalam bentuk number
                    return parseFloat(formattedNumber) || 0;
                },

                // Fungsi untuk konversi nilai display ke nilai database (tanpa simbol, dengan 2 desimal)
                toDbValue(value) {
                    // Bersihkan nomor dulu
                    const numValue = this.cleanNumber(value);
                    // Kalikan dengan 100 untuk mengubah ke format integer dengan 2 digit desimal
                    return Math.round(numValue * 100);
                },

                // Fungsi untuk konversi nilai database ke nilai display
                fromDbValue(dbValue) {
                    // Bagi dengan 100 untuk mendapatkan nilai desimal
                    return dbValue / 100;
                },

                // Fungsi untuk menghitung harga tanpa pajak untuk setiap item
                calculateItemBasePrice(index) {
                    const item = this.formData.items[index];
                    const hargaSatuan = this.cleanNumber(item.hargaSatuan);

                    // Jika tidak ada barang atau satuan yang dipilih
                    if (!item.barang_id || hargaSatuan <= 0) {
                        item.harga_tanpa_pajak = hargaSatuan;
                        return hargaSatuan;
                    }

                    // Jika harga termasuk pajak, hitung harga tanpa pajaknya
                    if (this.formData.include_pajak && item.pajak_id) {
                        const pajak = this.pajaks.find(p => String(p.id) === String(item.pajak_id));
                        if (pajak) {
                            const persenPajak = parseFloat(pajak.persen);
                            item.harga_tanpa_pajak = hargaSatuan / (1 + (persenPajak / 100));
                        } else {
                            item.harga_tanpa_pajak = hargaSatuan;
                        }
                    } else {
                        item.harga_tanpa_pajak = hargaSatuan;
                    }

                    return item.harga_tanpa_pajak;
                },

                // Fungsi untuk menghitung jumlah efektif (setelah konversi satuan)
                calculateEffectiveQuantity(index) {
                    const item = this.formData.items[index];
                    const jumlah = this.cleanNumber(item.jumlah);

                    // Jika tidak ada data yang cukup, kembalikan jumlah asli
                    if (!item.barang_id || !item.satuan_id || jumlah <= 0) {
                        item.jumlah_efektif = jumlah; // Simpan dalam item
                        return jumlah;
                    }

                    // Konversi satuan jika perlu
                    let jumlahEfektif = jumlah;
                    if (item.status_satuan === 'konversi_satuan' && item.satuan_dasar_id) {
                        const nilaiKonversi = this.getKonversiNilai(
                            item.barang_id,
                            item.satuan_id,
                            item.satuan_dasar_id
                        );
                        jumlahEfektif = jumlah * nilaiKonversi;
                    }

                    // Simpan jumlah efektif dalam item
                    item.jumlah_efektif = jumlahEfektif;

                    return jumlahEfektif;
                },

                // Fungsi untuk menghitung subtotal display item (untuk tampilan)
                calculateItemSubtotalDisplay(index) {
                    const item = this.formData.items[index];
                    const jumlahEfektif = this.calculateEffectiveQuantity(index);
                    const hargaSatuan = this.cleanNumber(item.hargaSatuan);

                    item.subtotal_display = jumlahEfektif * hargaSatuan;
                    return item.subtotal_display;
                },

                // Fungsi untuk menghitung subtotal item (tanpa pajak)
                calculateItemSubtotal(index) {
                    const item = this.formData.items[index];
                    const jumlahEfektif = this.calculateEffectiveQuantity(index);
                    const hargaTanpaPajak = this.calculateItemBasePrice(index);

                    // Hitung subtotal item
                    item.subtotal = jumlahEfektif * hargaTanpaPajak;
                    return item.subtotal;
                },

                // Fungsi untuk menghitung subtotal seluruh item (sebelum diskon)
                calculateSubtotalBeforeDiscount() {
                    let subtotal = 0;
                    this.formData.items.forEach((item, index) => {
                        subtotal += this.calculateItemSubtotal(index);
                    });

                    this.formData.subtotalSebelumDiskon = subtotal;
                    return subtotal;
                },

                calculateSubtotalDisplay() {
                    let subtotal = 0;
                    this.formData.items.forEach((item, index) => {
                        subtotal += this.calculateItemSubtotalDisplay(index);
                    });

                    return subtotal;
                },

                // Fungsi untuk menghitung total diskon
                calculateTotalDiscount() {
                    const subtotal = this.formData.subtotalSebelumDiskon;

                    if (this.formData.diskonType === 'persen') {
                        const persenDiskon = parseFloat(this.formData.diskon) || 0;
                        this.formData.totalDiskon = subtotal * (persenDiskon / 100);
                    } else {
                        this.formData.totalDiskon = this.cleanNumber(this.formData.diskon) || 0;
                    }

                    return this.formData.totalDiskon;
                },

                // Fungsi untuk mengalokasikan diskon ke masing-masing item
                distributeDiscountToItems() {
                    const totalDiskon = this.formData.totalDiskon;
                    const subtotal = this.formData.subtotalSebelumDiskon;

                    if (totalDiskon <= 0 || subtotal <= 0) {
                        // Reset diskon per item jika tidak ada diskon
                        this.formData.items.forEach(item => {
                            item.diskon_per_item = 0;
                            item.nilai_diskon = 0; // Reset nilai diskon per satuan
                            item.harga_setelah_diskon = item.subtotal;
                            item.harga_diskon = item.jumlah_efektif > 0 ? item.harga_setelah_diskon / item
                                .jumlah_efektif : 0; // Hitung harga diskon satuan
                        });
                        return;
                    }

                    // Alokasikan diskon secara proporsional berdasarkan subtotal item
                    this.formData.items.forEach(item => {
                        const proporsi = item.subtotal / subtotal;
                        item.diskon_per_item = totalDiskon * proporsi;
                        item.harga_setelah_diskon = Math.max(0, item.subtotal - item.diskon_per_item);

                        // Hitung nilai diskon per satuan
                        item.nilai_diskon = item.jumlah_efektif > 0 ? item.diskon_per_item / item.jumlah_efektif :
                        0;

                        // Hitung harga diskon satuan
                        item.harga_diskon = item.jumlah_efektif > 0 ? item.harga_setelah_diskon / item
                            .jumlah_efektif : 0;
                    });
                },

                // Fungsi untuk mengalokasikan biaya lain ke masing-masing item
                distributeOtherCostToItems() {
                    const biayaLain = this.cleanNumber(this.formData.biayaLain) || 0;

                    if (biayaLain <= 0) {
                        // Reset other_cost jika tidak ada biaya lain
                        this.formData.items.forEach(item => {
                            item.other_cost = 0;
                        });
                        return;
                    }

                    // Dapatkan total jumlah efektif
                    let totalJumlahEfektif = 0;
                    this.formData.items.forEach(item => {
                        totalJumlahEfektif += item.jumlah_efektif;
                    });

                    if (totalJumlahEfektif <= 0) {
                        // Reset other_cost jika total jumlah efektif nol
                        this.formData.items.forEach(item => {
                            item.other_cost = 0;
                        });
                        return;
                    }

                    // Alokasikan biaya lain secara proporsional berdasarkan jumlah efektif
                    this.formData.items.forEach(item => {
                        const proporsi = item.jumlah_efektif / totalJumlahEfektif;
                        const totalOtherCost = biayaLain * proporsi;

                        // Other cost per satuan
                        item.other_cost = item.jumlah_efektif > 0 ? totalOtherCost / item.jumlah_efektif : 0;
                    });
                },

                // Fungsi untuk menghitung harga pokok per satuan untuk setiap item
                calculateItemPrimePrice(index) {
                    const item = this.formData.items[index];

                    // Harga pokok = harga diskon + other cost + nilai pajak
                    item.harga_pokok = item.harga_diskon + item.other_cost + item.nilai_pajak;

                    return item.harga_pokok;
                },

                // Fungsi untuk menghitung grand total item
                calculateGrandTotalItem(index) {
                    const item = this.formData.items[index];

                    // Hitung harga pokok terlebih dahulu
                    this.calculateItemPrimePrice(index);

                    // Grand total item = harga pokok * jumlah efektif
                    item.grandTotalItem = item.harga_pokok * item.jumlah_efektif;

                    return item.grandTotalItem;
                },

                // Fungsi untuk menghitung subtotal setelah diskon
                calculateSubtotalAfterDiscount() {
                    let subtotalSetelahDiskon = 0;
                    this.formData.items.forEach(item => {
                        subtotalSetelahDiskon += item.harga_setelah_diskon;
                    });

                    this.formData.subtotalSetelahDiskon = subtotalSetelahDiskon;
                    return subtotalSetelahDiskon;
                },

                // Fungsi untuk menghitung pajak untuk setiap item
                calculateItemTax(index) {
                    const item = this.formData.items[index];

                    // Jika tidak ada pajak yang dipilih
                    if (!item.pajak_id) {
                        item.nilai_pajak = 0;
                        item.total_nilai_pajak = 0;
                        return 0;
                    }

                    // Cari persentase pajak
                    const pajak = this.pajaks.find(p => String(p.id) === String(item.pajak_id));
                    if (!pajak) {
                        item.nilai_pajak = 0;
                        item.total_nilai_pajak = 0;
                        return 0;
                    }

                    const persenPajak = parseFloat(pajak.persen);
                    const jumlahEfektif = item.jumlah_efektif; // Gunakan jumlah efektif yang sudah disimpan

                    // Hitung nilai pajak berdasarkan harga setelah diskon
                    const nilaiPajakPerSatuan = (item.harga_setelah_diskon / jumlahEfektif) * (persenPajak / 100);
                    const totalNilaiPajak = nilaiPajakPerSatuan * jumlahEfektif;

                    item.nilai_pajak = nilaiPajakPerSatuan;
                    item.total_nilai_pajak = totalNilaiPajak;

                    return totalNilaiPajak;
                },

                // Fungsi untuk menghitung total pajak dari semua item
                calculateTotalTax() {
                    let totalPajak = 0;
                    this.formData.items.forEach((item, index) => {
                        totalPajak += this.calculateItemTax(index);
                    });

                    this.formData.totalPajak = totalPajak;
                    return totalPajak;
                },

                // Fungsi untuk menghitung total akhir setiap item (termasuk pajak)
                calculateItemTotal(index) {
                    const item = this.formData.items[index];

                    // Total item adalah harga setelah diskon + pajak
                    item.total = item.harga_setelah_diskon + item.total_nilai_pajak;

                    return item.total;
                },

                // Fungsi untuk menghitung grand total
                calculateGrandTotal() {
                    // Hitung biaya lain yang bersih
                    const biayaLain = this.cleanNumber(this.formData.biayaLain) || 0;

                    // Jika harga belum termasuk pajak, grand total adalah subtotal setelah diskon + total pajak + biaya lain
                    this.formData.grandTotal = this.formData.subtotalSetelahDiskon + this.formData.totalPajak + biayaLain;

                    return this.formData.grandTotal;
                },

                // Fungsi untuk menghitung sisa pembayaran
                calculateRemaining() {
                    const uangMuka = this.cleanNumber(this.formData.uangMuka) || 0;
                    this.formData.sisa = this.formData.grandTotal - uangMuka;
                    return this.formData.sisa;
                },

                // Fungsi utama untuk melakukan semua perhitungan
                calculateTotal() {
                    // 1. Hitung jumlah efektif untuk setiap item
                    this.formData.items.forEach((item, index) => {
                        this.calculateEffectiveQuantity(index);
                    });

                    // 2. Hitung subtotal sebelum diskon (harga tanpa pajak)
                    this.calculateSubtotalBeforeDiscount();

                    // 3. Hitung total diskon
                    this.calculateTotalDiscount();

                    // 4. Alokasikan diskon ke masing-masing item
                    this.distributeDiscountToItems();

                    // 5. Alokasikan biaya lain ke masing-masing item
                    this.distributeOtherCostToItems();

                    // 6. Hitung subtotal setelah diskon
                    this.calculateSubtotalAfterDiscount();

                    // 7. Hitung pajak untuk setiap item
                    this.calculateTotalTax();

                    // 8. Hitung total akhir setiap item (termasuk pajak)
                    this.formData.items.forEach((item, index) => {
                        this.calculateItemTotal(index);

                        // 9. Hitung harga pokok dan grand total item
                        this.calculateItemPrimePrice(index);
                        this.calculateGrandTotalItem(index);
                    });

                    // 10. Hitung grand total
                    this.calculateGrandTotal();

                    // 11. Hitung sisa pembayaran
                    this.calculateRemaining();

                    console.log("Perhitungan selesai:", {
                        subtotalSebelumDiskon: this.formData.subtotalSebelumDiskon,
                        totalDiskon: this.formData.totalDiskon,
                        subtotalSetelahDiskon: this.formData.subtotalSetelahDiskon,
                        totalPajak: this.formData.totalPajak,
                        grandTotal: this.formData.grandTotal,
                        sisa: this.formData.sisa
                    });
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

                    // Mengkonversi semua nilai dari format database ke format display pada inisialisasi
                    // Ini untuk kasus edit data yang sudah ada
                    this.convertAllDbValuesToDisplay();

                    // Setup event listener untuk submit form
                    this.$watch('formData', () => {
                        this.calculateTotal();
                    }, {
                        deep: true
                    });

                    // Hitung total saat awal
                    this.calculateTotal();
                },

                // Fungsi untuk mengkonversi semua nilai dari database ke display
                convertAllDbValuesToDisplay() {
                    // Konversi nilai monetar dari database
                    this.formData.biayaLain = this.fromDbValue(this.formData.biayaLain);
                    this.formData.uangMuka = this.fromDbValue(this.formData.uangMuka);

                    // Konversi nilai diskon jika bukan persen
                    if (this.formData.diskonType !== 'persen') {
                        this.formData.diskon = this.fromDbValue(this.formData.diskon);
                    }

                    // Konversi nilai di setiap item
                    this.formData.items.forEach(item => {
                        item.hargaSatuan = this.fromDbValue(item.hargaSatuan);
                        // Konversi nilai baru jika ada
                        if (item.harga_diskon) item.harga_diskon = this.fromDbValue(item.harga_diskon);
                        if (item.harga_pokok) item.harga_pokok = this.fromDbValue(item.harga_pokok);
                        if (item.other_cost) item.other_cost = this.fromDbValue(item.other_cost);
                        if (item.nilai_diskon) item.nilai_diskon = this.fromDbValue(item.nilai_diskon);
                        if (item.grandTotalItem) item.grandTotalItem = this.fromDbValue(item.grandTotalItem);
                    });
                },

                // Fungsi untuk mempersiapkan data sebelum submit
                prepareForSubmit() {
                    // Konversi semua nilai uang ke format database
                    const formDataForSubmit = JSON.parse(JSON.stringify(this.formData));

                    // Konversi nilai moneter utama
                    formDataForSubmit.biayaLain = this.toDbValue(formDataForSubmit.biayaLain);
                    formDataForSubmit.uangMuka = this.toDbValue(formDataForSubmit.uangMuka);
                    formDataForSubmit.grandTotal = this.toDbValue(formDataForSubmit.grandTotal);
                    formDataForSubmit.sisa = this.toDbValue(formDataForSubmit.sisa);
                    formDataForSubmit.totalPajak = this.toDbValue(formDataForSubmit.totalPajak);
                    formDataForSubmit.subtotalSebelumDiskon = this.toDbValue(formDataForSubmit.subtotalSebelumDiskon);
                    formDataForSubmit.subtotalSetelahDiskon = this.toDbValue(formDataForSubmit.subtotalSetelahDiskon);

                    // Konversi diskon jika bukan persen
                    if (formDataForSubmit.diskonType !== 'persen') {
                        formDataForSubmit.diskon = this.toDbValue(formDataForSubmit.diskon);
                    }
                    formDataForSubmit.totalDiskon = this.toDbValue(formDataForSubmit.totalDiskon);

                    // Konversi setiap item
                    formDataForSubmit.items.forEach(item => {
                        item.hargaSatuan = this.toDbValue(item.hargaSatuan);
                        item.harga_tanpa_pajak = this.toDbValue(item.harga_tanpa_pajak);
                        item.nilai_pajak = this.toDbValue(item.nilai_pajak);
                        item.total_nilai_pajak = this.toDbValue(item.total_nilai_pajak);
                        item.diskon_per_item = this.toDbValue(item.diskon_per_item);
                        item.harga_setelah_diskon = this.toDbValue(item.harga_setelah_diskon);
                        item.subtotal = this.toDbValue(item.subtotal);
                        item.total = this.toDbValue(item.total);

                        // Konversi field baru
                        item.harga_diskon = this.toDbValue(item.harga_diskon);
                        item.harga_pokok = this.toDbValue(item.harga_pokok);
                        item.other_cost = this.toDbValue(item.other_cost);
                        item.nilai_diskon = this.toDbValue(item.nilai_diskon);
                        item.grandTotalItem = this.toDbValue(item.grandTotalItem);
                    });

                    return formDataForSubmit;
                },

                // Fungsi untuk melakukan submit form
                submitForm() {
                    // Persiapkan data untuk submit
                    const formDataForSubmit = this.prepareForSubmit();

                    // Isi nilai hidden input untuk pengiriman form
                    document.getElementById('form_data').value = JSON.stringify(formDataForSubmit);

                    // Submit form
                    document.getElementById('purchase_form').submit();
                }
            };
        }
    </script>
</x-layout>
