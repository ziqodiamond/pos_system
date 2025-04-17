<x-layout>

    <div class="p-2"> @include('components.breadcrumbs')</div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-2xl font-bold font-old">Daftar Barang</h2>
            <!-- Alpine.js Modal dan Trigger dalam satu x-data -->
            <div x-data="{ modalOpen: false }">
                <!-- Trigger Button -->
                <button type="button" @click="modalOpen = true"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    Export Data
                </button>

                <!-- Modal Background Overlay -->
                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" x-cloak
                    class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50"
                    @click.self="modalOpen = false">

                    <!-- Modal Content -->
                    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90"
                        class="w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">

                        <!-- Modal Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Export Laporan Data Barang
                            </h3>
                            <button @click="modalOpen = false" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Filter Form -->
                        <form action="{{ route('barang.export') }}" method="GET" target="_blank">
                            <div class="space-y-4">
                                <!-- Filter Kategori -->
                                <div>
                                    <label for="kategori_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                    <select id="kategori_id" name="kategori_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Status -->
                                <div>
                                    <label for="status"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                    <select id="status" name="status"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Semua Status</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>

                                <!-- Filter Stok Minimum -->
                                <div class="flex items-center">
                                    <input id="filter_stok_minimum" type="checkbox" name="filter_stok_minimum"
                                        value="1"
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="filter_stok_minimum"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tampilkan
                                        hanya barang dengan stok di bawah minimum</label>
                                </div>

                                <!-- Pengurutan -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Urutkan
                                        Berdasarkan</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="sort_by"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="nama">Nama Barang</option>
                                            <option value="kode">Kode Barang</option>
                                            <option value="kategori_id">Kategori</option>
                                            <option value="stok">Stok</option>
                                            <option value="harga_jual">Harga Jual</option>
                                        </select>

                                        <select name="sort_order"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="asc">Naik (A-Z)</option>
                                            <option value="desc">Turun (Z-A)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex items-center justify-end mt-6 space-x-2">

                                <button type="submit" name="type" value="excel"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Generate Excel
                                </button>
                                <button type="submit" name="type" value="pdf"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Generate PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End of Alpine.js Modal dan Trigger -->

        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('master_data.barang.partials.table-header')
            </div>


            {{-- table --}}
            <div id="table-container" class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('master_data.barang.partials.table')
            </div>

        </div>


    </div>
    <script>
        const reportSearchRoute = @json(route('barang.index'));

        document.addEventListener('DOMContentLoaded', () => {
            // Buat variabel global untuk menyimpan status filter terakhir yang dipilih per grup (berdasarkan name)
            window.lastSelectedFilter = {};

            // Tambahkan event listener ke input pencarian
            document.getElementById('search').addEventListener('input', handleSearchAndFilter);

            // Tambahkan event listener ke semua checkbox filter (yang memiliki class 'filter-input')
            document.querySelectorAll('.filter-input').forEach(input => {
                input.addEventListener('click', function(event) {
                    const groupName = this.name; // Nama grup dari filter (berdasarkan atribut name)
                    const wasChecked = this
                        .checked; // Cek apakah sebelumnya checkbox dalam kondisi dicentang

                    // Jika user klik filter yang sama dengan sebelumnya, maka toggle: hapus pilihan
                    if (window.lastSelectedFilter[groupName] === this.value) {
                        this.checked = false; // Uncheck
                        window.lastSelectedFilter[groupName] =
                            null; // Reset status terakhir untuk grup ini
                    } else {
                        // Jika pilih filter baru, uncheck semua checkbox dalam grup yang sama
                        document.querySelectorAll(`.filter-input[name="${groupName}"]`).forEach(
                            i => {
                                i.checked = false;
                            });

                        // Centang filter yang baru dipilih
                        this.checked = true;
                        window.lastSelectedFilter[groupName] = this
                            .value; // Simpan status filter yang baru
                    }

                    // Panggil fungsi filter dan search
                    handleSearchAndFilter();

                    // Perbarui tampilan tombol aksi berdasarkan status filter
                    handleStatusFilterChange();
                });
            });

            // Fungsi utama untuk melakukan pencarian dan filtering
            function handleSearchAndFilter() {
                const selectedFilters = {}; // Objek untuk menyimpan filter yang aktif

                // Ambil input search dari user
                const query = document.getElementById('search').value;

                // Ambil semua filter (checkbox) yang dicentang
                document.querySelectorAll('.filter-input:checked').forEach(input => {
                    selectedFilters[input.name] = input.value; // Simpan berdasarkan nama filter (group)
                });

                // Gabungkan query search dan filter jadi string query URL
                const queryString = new URLSearchParams({
                    ...selectedFilters,
                    search: query
                }).toString();

                // Kirim request AJAX ke server (asumsi `reportSearchRoute` adalah route pencarian)
                fetch(`${reportSearchRoute}?${queryString}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Identifikasi sebagai request AJAX
                        }
                    })
                    .then(response => {
                        // Jika response gagal, lempar error
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json(); // Parse response JSON
                    })
                    .then(data => {
                        // Tampilkan hasil pencarian/filter ke dalam elemen #table-container
                        document.getElementById('table-container').innerHTML = data.html;

                        // Re-bind event checkbox karena elemen tabel sudah di-replace
                        rebindCheckboxEvents();
                    })
                    .catch(error => console.error('Error fetching filter results:', error));
            }

            // Fungsi untuk menyesuaikan tampilan tombol aksi berdasarkan status filter yang dipilih
            function handleStatusFilterChange() {
                // Ambil status yang sedang dipilih (jika ada)
                const selectedStatus = document.querySelector('input[name="Status"]:checked')?.value;

                // Ambil semua tombol aksi yang relevan
                const editAction = document.getElementById('editAction');
                const deleteAction = document.getElementById('deleteAction');
                const restoreAction = document.getElementById('restoreAction');
                const forceDeleteAction = document.getElementById('forceDeleteAction');

                // Jika status = deleted, sembunyikan tombol edit/delete, tampilkan tombol restore/force delete
                if (selectedStatus === 'deleted') {
                    editAction?.classList.add('hidden');
                    deleteAction?.classList.add('hidden');
                    restoreAction?.classList.remove('hidden');
                    forceDeleteAction?.classList.remove('hidden');
                } else {
                    // Jika bukan deleted, tampilkan tombol edit/delete, sembunyikan restore/force delete
                    editAction?.classList.remove('hidden');
                    deleteAction?.classList.remove('hidden');
                    restoreAction?.classList.add('hidden');
                    forceDeleteAction?.classList.add('hidden');
                }
            }

            // Fungsi untuk meregistrasi ulang event checkbox setelah konten di-reload via AJAX
            function rebindCheckboxEvents() {
                const selectAllCheckbox = document.getElementById('checkbox-all'); // Checkbox untuk "pilih semua"
                const checkboxes = document.querySelectorAll('.item-checkbox'); // Checkbox individual
                const selectedIdsInput = document.getElementById(
                    'selectedIds'); // Hidden input untuk menyimpan id yang dipilih

                // Jika elemen tidak ditemukan, hentikan fungsi
                if (!selectAllCheckbox || checkboxes.length === 0 || !selectedIdsInput) {
                    return;
                }

                // Event: Saat checkbox "pilih semua" diubah
                selectAllCheckbox.addEventListener('change', () => {
                    // Semua checkbox individual akan mengikuti status "pilih semua"
                    checkboxes.forEach((checkbox) => (checkbox.checked = selectAllCheckbox.checked));
                    updateSelectedIds(); // Perbarui ID yang dipilih
                });

                // Event: Saat checkbox individual diubah
                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        // Jika semua checkbox dicentang, maka "pilih semua" ikut dicentang
                        selectAllCheckbox.checked = [...checkboxes].every(cb => cb.checked);
                        updateSelectedIds(); // Perbarui ID yang dipilih
                    });
                });

                // Fungsi untuk memperbarui nilai dari input hidden berdasarkan checkbox yang dicentang
                function updateSelectedIds() {
                    const selectedIds = Array.from(checkboxes)
                        .filter((checkbox) => checkbox.checked) // Ambil hanya checkbox yang dicentang
                        .map((checkbox) => checkbox.value); // Ambil nilai (id) dari masing-masing checkbox

                    selectedIdsInput.value = selectedIds.join(','); // Gabungkan menjadi string (id1,id2,...)

                    // Pastikan status "select all" tetap sinkron jika jumlah yang dipilih sama dengan jumlah total
                    selectAllCheckbox.checked = checkboxes.length > 0 && checkboxes.length === selectedIds.length;
                }

                // Panggil update pertama kali untuk memastikan nilai awal sinkron
                updateSelectedIds();
            }

            // Jalankan rebind dan atur tombol aksi saat halaman pertama kali dimuat
            rebindCheckboxEvents();
            handleStatusFilterChange();
        });

        /**
         * Fungsi dropdownEdit yang diperbarui untuk menangani indeks dengan benar
         * @param {string} type - Tipe dropdown (kategori, pajak, satuan, dll)
         * @param {number} itemId - ID item yang dipilih
         * @param {number|null} uniqueIndex - Indeks unik untuk membedakan instance dropdown
         * @returns {object} - Object dengan properti dan metode untuk dropdown
         */
        function dropdownEdit(type, itemId, uniqueIndex = null) {
            return {
                open: false,
                search: '',
                selected: {},
                itemId: itemId,
                uniqueIndex: uniqueIndex, // Parameter untuk membedakan instance dropdown
                kategoris: type === 'kategori' ? @json($kategoris) : [],
                pajaks: type === 'pajak' ? @json($pajaks) : [],
                satuans: type === 'satuan' ? @json($satuans) : [],
                satuanKonversis: type === 'satuanKonversi' ? @json($satuanKonversis) : [],
                satuanTujuans: type === 'satuanTujuan' ? @json($satuans) : [],

                // Variabel untuk menyimpan ID yang dipilih khusus untuk instance ini
                selectedKategoriId: null,
                selectedPajakId: null,
                selectedSatuanId: null,
                selectedSatuanKonversiId: null,
                selectedSatuanTujuanId: null,

                // Metode untuk mendapatkan nama variabel dengan indeks
                getVariableName(baseName) {
                    return baseName;
                },

                // Fungsi untuk menonton input pencarian
                watchSearch() {
                    this.open = true; // Membuka dropdown saat ada input
                },

                // Filter data berdasarkan pencarian
                filteredKategoris() {
                    return this.search.trim() === '' ? this.kategoris : this.kategoris.filter(kategori =>
                        kategori.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                filteredPajaks() {
                    return this.search.trim() === '' ? this.pajaks : this.pajaks.filter(pajak =>
                        pajak.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                filteredSatuans() {
                    return this.search.trim() === '' ? this.satuans : this.satuans.filter(satuan =>
                        satuan.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                get filteredSatuanKonversis() {
                    return this.search.trim() === '' ? this.satuanKonversis : this.satuanKonversis.filter(
                        satuanKonversi =>
                        satuanKonversi.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                get filteredSatuanTujuans() {
                    return this.search.trim() === '' ? this.satuanTujuans : this.satuanTujuans.filter(satuanTujuan =>
                        satuanTujuan.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                // Inisialisasi dropdown
                init() {
                    // Sesuaikan inisialisasi untuk setiap tipe dropdown
                    switch (type) {
                        case 'kategori':
                            this.selected = this.kategoris.find(kategori => kategori.id === this.itemId) || {};
                            this.selectedKategoriId = this.itemId;
                            this.search = this.selected.nama || '';
                            break;
                        case 'pajak':
                            this.selected = this.pajaks.find(pajak => pajak.id === this.itemId) || {};
                            this.selectedPajakId = this.itemId;
                            this.search = this.selected.nama || '';
                            break;
                        case 'satuan':
                            this.selected = this.satuans.find(satuan => satuan.id === this.itemId) || {};
                            this.selectedSatuanId = this.itemId;
                            this.search = this.selected.nama || '';
                            break;
                        case 'satuanKonversi':
                            this.selected = this.satuanKonversis.find(satuanKonversi => satuanKonversi.id === this
                                .itemId) || {};
                            this.selectedSatuanKonversiId = this.itemId;
                            this.search = this.selected.nama || '';
                            break;
                        case 'satuanTujuan':
                            this.selected = this.satuanTujuans.find(satuanTujuan => satuanTujuan.id === this.itemId) || {};
                            this.selectedSatuanTujuanId = this.itemId;
                            this.search = this.selected.nama || '';
                            break;
                    }
                },

                // Memilih item dari dropdown
                select(item) {
                    this.selected = item;
                    this.search = item.nama;

                    // Set ID sesuai tipe dropdown
                    switch (type) {
                        case 'kategori':
                            this.selectedKategoriId = item.id;
                            break;
                        case 'pajak':
                            this.selectedPajakId = item.id;
                            break;
                        case 'satuan':
                            this.selectedSatuanId = item.id;
                            break;
                        case 'satuanKonversi':
                            this.selectedSatuanKonversiId = item.id;
                            // Update nilai di parent component untuk memastikan data terkoneksi
                            if (this.uniqueIndex !== null) {
                                this.$parent.konversis[this.uniqueIndex].satuan_konversi_id = item.id;
                                this.$parent.konversis[this.uniqueIndex].satuan_konversi_nama = item.nama;
                            }
                            break;
                        case 'satuanTujuan':
                            this.selectedSatuanTujuanId = item.id;
                            // Update nilai di parent component untuk memastikan data terkoneksi
                            if (this.uniqueIndex !== null) {
                                this.$parent.konversis[this.uniqueIndex].satuan_tujuan_id = item.id;
                                this.$parent.konversis[this.uniqueIndex].satuan_tujuan_nama = item.nama;
                            }
                            break;
                    }

                    this.close(); // Tutup dropdown setelah memilih
                },

                close() {
                    this.open = false; // Menutup dropdown
                },

                openDropdown() {
                    this.open = true; // Membuka dropdown
                }
            };
        }

        /**
         * Fungsi untuk mengelola data konversi satuan
         * @param {Array} initialData - Data awal konversi
         * @returns {object} - Object dengan properti dan metode untuk mengelola konversi
         */
        function satuanUkurData(initialData = []) {
            return {
                konversis: initialData.length > 0 ? initialData.map(konversi => ({
                    id: konversi.id || null, // Menyimpan ID jika ada
                    jumlah: 1, // Selalu 1 sesuai permintaan
                    nilai: konversi.nilai_konversi || '', // Ambil dari nilai_konversi
                    satuan_konversi_id: konversi.satuan_id || null,
                    satuan_konversi_nama: konversi.satuan?.nama || '',
                    satuan_tujuan_id: konversi.satuan_tujuan_id || null,
                    satuan_tujuan_nama: konversi.satuan_tujuan?.nama || '',
                })) : [{ // Jika tidak ada data, buat satu baris kosong
                    id: null,
                    jumlah: 1,
                    nilai: '',
                    satuan_konversi_id: null,
                    satuan_konversi_nama: '',
                    satuan_tujuan_id: null,
                    satuan_tujuan_nama: '',
                }],

                // Mendapatkan nilai ID untuk satuan konversi berdasarkan indeks
                selectedSatuanKonversiId_(index) {
                    return this.konversis[index]?.satuan_konversi_id || '';
                },

                // Mendapatkan nilai ID untuk satuan tujuan berdasarkan indeks
                selectedSatuanTujuanId_(index) {
                    return this.konversis[index]?.satuan_tujuan_id || '';
                },

                // Menambah item konversi baru
                addKonversi() {
                    this.konversis.push({
                        id: null, // ID null karena ini data baru
                        jumlah: 1, // Selalu 1
                        nilai: '',
                        satuan_konversi_id: null,
                        satuan_konversi_nama: '',
                        satuan_tujuan_id: null,
                        satuan_tujuan_nama: '',
                    });
                },

                // Menghapus item konversi berdasarkan indeks
                removeKonversi(index) {
                    // Periksa apakah masih ada lebih dari satu item
                    if (this.konversis.length > 1) {
                        // Hapus item pada indeks yang diberikan
                        this.konversis.splice(index, 1);

                        // Log untuk debugging
                        console.log(`Konversi pada indeks ${index} dihapus. Sisa konversi: ${this.konversis.length}`);
                    } else {
                        // Tampilkan pesan jika mencoba menghapus item terakhir
                        console.log('Tidak dapat menghapus item terakhir. Minimal harus ada satu konversi.');
                        alert('Minimal harus ada satu konversi satuan.');
                    }
                },

                // Memformat nilai input agar hanya angka dan satu titik desimal
                formatNilai(index) {
                    // Pastikan indeks valid
                    if (index >= 0 && index < this.konversis.length) {
                        // Hapus karakter selain angka dan titik
                        this.konversis[index].nilai = this.konversis[index].nilai.replace(/[^\d.]/g, '');

                        // Memastikan hanya ada satu titik desimal
                        const parts = this.konversis[index].nilai.split('.');
                        if (parts.length > 2) {
                            this.konversis[index].nilai = parts[0] + '.' + parts.slice(1).join('');
                        }
                    }
                },
            };
        }
    </script>


</x-layout>
