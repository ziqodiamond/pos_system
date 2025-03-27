<x-layout>


    @include('components.breadcrumbs')
    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Daftar Konversi</h2>
            <button type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Export Data
            </button>
        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('master_data.konversi.partials.table-header')
            </div>


            {{-- table --}}
            <div id="table-container" class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('master_data.konversi.partials.table')
            </div>
            @foreach ($konversi as $iten)
                <script>
                    // Fungsi untuk dropdown edit konversi dengan inisialisasi satuan terpilih
                    // Fungsi dropdown edit konversi dengan inisialisasi yang lebih baik
                    function dropdownEditKonversi(initialId = '') {
                        return {
                            // Status dropdown
                            open: false,
                            // Kata kunci pencarian
                            search: '',
                            // ID satuan yang dipilih
                            selectedId: initialId,
                            // Objek satuan yang dipilih
                            selected: {},
                            // Daftar satuan konversi dari database
                            satuanList: @json($satuanKonversi),

                            // Fungsi untuk menyaring satuan berdasarkan pencarian
                            get filteredSatuan() {
                                return this.search.trim() === '' ?
                                    this.satuanList :
                                    this.satuanList.filter(satuan => satuan.nama.toLowerCase().includes(this.search.toLowerCase()));
                            },

                            // Inisialisasi dropdown dengan memilih satuan sesuai kondisi
                            init() {
                                this.selectedId = initialId;
                                this.selected = this.satuanList.find(satuan => satuan.id === this.selectedId) || {};

                            },

                            // Metode untuk memilih satuan
                            select(satuan) {
                                this.selected = satuan;
                                this.selectedId = satuan.id; // Perbarui selectedId
                                this.search = satuan.nama;
                                this.close();
                            },

                            // Tutup dropdown
                            close() {
                                this.open = false;
                            },

                            // Buka dropdown
                            openDropdown() {
                                this.open = true;
                            },

                            // Pantau perubahan pencarian
                            watchSearch() {
                                this.open = true;
                            }
                        };
                    }

                    // Fungsi untuk dropdown edit satuan dasar dengan inisialisasi satuan terpilih
                    function dropdownEditDasar(initialId = '') {
                        return {
                            open: false,
                            search: '',
                            // ID satuan yang dipilih
                            selectedId: initialId,
                            selected: {},
                            satuanList: @json($satuanDasar),

                            get filteredSatuan() {
                                return this.search.trim() === '' ?
                                    this.satuanList :
                                    this.satuanList.filter(satuan => satuan.nama.toLowerCase().includes(this.search.toLowerCase()));
                            },

                            // Inisialisasi dropdown dengan memilih satuan sesuai kondisi
                            init() {
                                this.selectedId = initialId;
                                this.selected = this.satuanList.find(satuan => satuan.id === this.selectedId) || {};

                            },

                            select(satuan) {

                                this.search = satuan.nama;
                                this.selected = satuan;
                                this.selectedId = satuan.id; // Perbarui selectedId
                                this.close();
                            },

                            close() {
                                this.open = false;
                            },

                            openDropdown() {
                                this.open = true;
                            },

                            watchSearch() {
                                this.open = true;
                            }
                        };
                    }

                    // Fungsi untuk dropdown satuan dasar dengan inisialisasi satuan terpilih
                    function dropdownDasar() {
                        return {
                            open: false,
                            search: '',
                            selected: {},
                            satuanList: @json($satuanDasar),

                            get filteredSatuan() {
                                return this.search.trim() === '' ?
                                    this.satuanList :
                                    this.satuanList.filter(satuan => satuan.nama.toLowerCase().includes(this.search.toLowerCase()));
                            },

                            // Inisialisasi dropdown dengan memilih satuan sesuai kondisi
                            init() {
                                // Ambil ID satuan dari input lama
                                let selectedId = {{ old('satuan_dasar_id') ?? 'null' }};

                                // Cari satuan yang sesuai dengan ID
                                this.selected = this.satuanList.find(satuan => satuan.id === selectedId) || {};

                                // Atur nama pencarian sesuai satuan terpilih
                                this.search = this.selected.nama || '';
                            },

                            select(satuan) {
                                this.selected = satuan;
                                this.search = satuan.nama;
                                this.close();
                            },

                            close() {
                                this.open = false;
                            },

                            openDropdown() {
                                this.open = true;
                            },

                            watchSearch() {
                                this.open = true;
                            }
                        };
                    }

                    // Fungsi untuk dropdown satuan konversi dengan inisialisasi satuan terpilih
                    function dropdownKonversi() {
                        return {
                            open: false,
                            search: '',
                            selected: {},
                            satuanList: @json($satuanKonversi),

                            get filteredSatuan() {
                                return this.search.trim() === '' ?
                                    this.satuanList :
                                    this.satuanList.filter(satuan => satuan.nama.toLowerCase().includes(this.search.toLowerCase()));
                            },

                            // Inisialisasi dropdown dengan memilih satuan sesuai kondisi
                            init() {
                                // Ambil ID satuan dari input lama
                                let selectedId = {{ old('satuan_konversi_id') ?? 'null' }};

                                // Cari satuan yang sesuai dengan ID
                                this.selected = this.satuanList.find(satuan => satuan.id === selectedId) || {};

                                // Atur nama pencarian sesuai satuan terpilih
                                this.search = this.selected.nama || '';
                            },

                            select(satuan) {
                                this.selected = satuan;
                                this.search = satuan.nama;
                                this.close();
                            },

                            close() {
                                this.open = false;
                            },

                            openDropdown() {
                                this.open = true;
                            },

                            watchSearch() {
                                this.open = true;
                            }
                        };
                    }
                </script>
            @endforeach





        </div>


    </div>

    <script>
        const reportSearchRoute = @json(route('konversi.index'));

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
    </script>

</x-layout>
