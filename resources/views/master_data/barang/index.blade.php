<x-layout>

    @include('components.breadcrumbs')
    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Daftar Barang</h2>
            <button type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Export Data
            </button>
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
            let lastSelectedFilter = {}; // Menyimpan state terakhir tiap grup filter

            // Setup event listeners

            document.getElementById('search').addEventListener('input', handleSearchAndFilter);
            document.querySelectorAll('.filter-input').forEach(input => {
                input.addEventListener('click', handleUnselect);
            });

            function handleUnselect(event) {
                const input = event.target;
                const groupName = input.name;

                // Kalau klik filter yang sama, unselect
                if (lastSelectedFilter[groupName] === input) {
                    input.checked = false;
                    lastSelectedFilter[groupName] = null;
                } else {
                    lastSelectedFilter[groupName] = input;
                }

                handleSearchAndFilter();
            }

            function handleSearchAndFilter() {
                const selectedFilters = {};

                // Ambil nilai search
                const query = encodeURIComponent(document.getElementById('search').value);

                // Ambil semua filter yang dicek
                document.querySelectorAll('.filter-input:checked').forEach(input => {
                    selectedFilters[input.name] = input.value;
                });

                const queryString = new URLSearchParams({
                    ...selectedFilters,
                    search: query
                }).toString();

                fetch(`${reportSearchRoute}?${queryString}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('table-container').innerHTML = data.html;

                        // ✅ Re-bind event setelah fetch selesai
                        rebindCheckboxEvents();
                    })
                    .catch(error => console.error('Error fetching filter results:', error));
            }

            // 💪 Fungsi buat pasang ulang event checkbox setelah data ke-fetch
            function rebindCheckboxEvents() {
                const selectAllCheckbox = document.getElementById('checkbox-all');
                const checkboxes = document.querySelectorAll('.item-checkbox');
                const selectedIdsInput = document.getElementById('selectedIds');

                // Select All Checkbox Event
                selectAllCheckbox.addEventListener('change', () => {
                    checkboxes.forEach((checkbox) => (checkbox.checked = selectAllCheckbox.checked));
                    updateSelectedIds();
                });

                // Event checkbox individual
                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        selectAllCheckbox.checked = [...checkboxes].every(cb => cb.checked);
                        updateSelectedIds();
                    });
                });

                // Fungsi update selected IDs
                function updateSelectedIds() {
                    const selectedIds = Array.from(checkboxes)
                        .filter((checkbox) => checkbox.checked)
                        .map((checkbox) => checkbox.value);

                    selectedIdsInput.value = selectedIds.join(',');

                    // Cek ulang Select All (kalau semua ke-check, otomatis aktif)
                    selectAllCheckbox.checked = checkboxes.length === selectedIds.length;
                }

                // Pastikan ulang selected IDs tetap tersimpan
                updateSelectedIds();
            }

            // 🔥 Panggil rebind pertama kali (buat table awal sebelum fetch)
            rebindCheckboxEvents();
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

                filteredSatuanKonversis() {
                    return this.search.trim() === '' ? this.satuanKonversis : this.satuanKonversis.filter(satuanKonversi =>
                        satuanKonversi.nama.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                filteredSatuanTujuans() {
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
