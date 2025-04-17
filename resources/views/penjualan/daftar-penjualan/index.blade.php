<x-layout>


    @include('components.breadcrumbs')
    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-2 px-1">
            <h2 class="text-2xl font-bold">Daftar Penjualan</h2>
            <div x-data="{ modalOpen: false }">
                <!-- Trigger Button -->
                <button type="button" @click="modalOpen = true"
                    class="h-10 flex items-center justify-center px-4 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
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
                                Export Laporan Pembelian
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


                        <!-- Form Laporan Penjualan -->
                        <form action="{{ route('laporan.penjualan.export') }}" method="GET" target="_blank"
                            id="reportForm" class="space-y-6">

                            <!-- Judul Form -->
                            <div class="pb-2 mb-4 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300">Filter Laporan
                                    Penjualan</h4>
                            </div>

                            <!-- Tanggal Range -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Tanggal Mulai -->
                                <div>
                                    <label for="tanggal_mulai"
                                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tanggal Mulai
                                    </label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        value="{{ request('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>

                                <!-- Tanggal Akhir -->
                                <div>
                                    <label for="tanggal_akhir"
                                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tanggal Akhir
                                    </label>
                                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        value="{{ request('tanggal_akhir', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <!-- Kasir dan Metode Pembayaran -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Kasir -->
                                <div>
                                    <label for="kasir_id"
                                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kasir
                                    </label>
                                    <select name="kasir_id" id="kasir_id"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">-- Semua Kasir --</option>
                                        <!-- Daftar kasir akan di-generate secara dinamis -->
                                    </select>
                                </div>

                                <!-- Metode Pembayaran -->
                                <div>
                                    <label for="metode_pembayaran"
                                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Metode Pembayaran
                                    </label>
                                    <select name="metode_pembayaran" id="metode_pembayaran"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">-- Semua Metode --</option>
                                        <option value="cash"
                                            {{ request('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash
                                        </option>
                                        <option value="transfer"
                                            {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>
                                            Transfer</option>
                                        <option value="card"
                                            {{ request('metode_pembayaran') == 'card' ? 'selected' : '' }}>Kartu
                                            Kredit/Debit</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Pengurutan -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Sort By -->
                                <div>
                                    <label for="sort_by"
                                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Urutkan Berdasarkan
                                    </label>
                                    <select name="sort_by" id="sort_by"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="created_at"
                                            {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal
                                        </option>
                                        <option value="no_ref" {{ request('sort_by') == 'no_ref' ? 'selected' : '' }}>
                                            No. Referensi
                                        </option>
                                        <option value="grand_total"
                                            {{ request('sort_by') == 'grand_total' ? 'selected' : '' }}>Grand Total
                                        </option>
                                    </select>
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="sort_order"
                                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Urutan
                                    </label>
                                    <select name="sort_order" id="sort_order"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                            Menurun (Z-A)
                                        </option>
                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                            Menaik (A-Z)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex justify-end pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" name="type" value="excel"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Export Excel
                                </button>
                                <button type="submit" name="type" value="pdf"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Export PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div id="table-header" class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('penjualan.daftar-penjualan.partials.table-header')
            </div>


            {{-- table --}}
            <div id="table-container" class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('penjualan.daftar-penjualan.partials.table')
            </div>

        </div>


    </div>

    <script>
        const reportSearchRoute = @json(route('daftar-penjualan.index'));

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
