<x-layout>


    @include('components.breadcrumbs')
    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Daftar Pembelian</h2>
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

                        <!-- Form Export Laporan -->
                        <form action="{{ route('laporan.pembelian.export') }}" method="GET" target="_blank"
                            id="reportForm">
                            <div class="space-y-4">
                                <!-- Periode Laporan -->
                                <div>
                                    <label for="tanggal_mulai"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Tanggal Mulai
                                    </label>
                                    <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                        value="{{ $tanggalMulai ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>

                                <div>
                                    <label for="tanggal_akhir"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Tanggal Akhir
                                    </label>
                                    <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                                        value="{{ $tanggalAkhir ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>

                                <!-- Status Transaksi -->
                                <div>
                                    <label for="status"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Status Transaksi
                                    </label>
                                    <select id="status" name="status"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="proses" {{ ($status ?? '') == 'proses' ? 'selected' : '' }}>
                                            Proses</option>
                                        <option value="selesai" {{ ($status ?? '') == 'selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                        <option value="batal" {{ ($status ?? '') == 'batal' ? 'selected' : '' }}>Batal
                                        </option>
                                    </select>
                                </div>

                                <!-- Pilihan Format Laporan -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Format Laporan
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <input id="format-detail" type="radio" name="format" value="detail"
                                                checked
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="format-detail"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Detail
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="format-summary" type="radio" name="format" value="summary"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="format-summary"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Ringkasan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Supplier (Opsional) -->
                                <div>
                                    <label for="supplier_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Supplier (Opsional)
                                    </label>
                                    <select id="supplier_id" name="supplier_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Semua Supplier</option>
                                        @foreach ($suppliers ?? [] as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tombol Submit -->
                                <div
                                    class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" @click="modalOpen = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                        Batal
                                    </button>
                                    <button type="submit" name="type" value="excel"
                                        class="px-4 py-2 mr-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div id="table-header" class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('pembelian.daftar-pembelian.partials.table-header')
            </div>


            {{-- table --}}
            <div id="table-container" class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('pembelian.daftar-pembelian.partials.table')
            </div>

        </div>


    </div>

    <script>
        const reportSearchRoute = @json(route('daftar-pembelian.index'));

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
