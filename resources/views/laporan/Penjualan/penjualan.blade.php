<x-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Laporan Penjualan</h1>
            <div x-data="{ modalOpen: false }">




                <!-- Filter Periode -->
                <div class="bg-white shadow rounded-lg p-4 mt-4 dark:bg-gray-800">
                    <form action="{{ route('laporan.penjualan.index') }}" method="GET"
                        class="flex flex-wrap items-end gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal
                                Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal
                                Akhir</label>
                            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <button type="submit"
                                class="flex items-center justify-center  bg-indigo-600 py-2 px-4 text-white rounded-lg hover:bg-indigo-700">
                                Filter
                            </button>

                        </div>
                        <div>
                            <!-- Trigger Button -->
                            <button type="button" @click="modalOpen = true"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                Export Data
                            </button>
                        </div>
                    </form>
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
                            class="w-full max-w-lg p-6 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">

                            <!-- Modal Header -->
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Export Laporan Penjualan
                                </h3>
                                <button @click="modalOpen = false" type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white transition duration-200">
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
                                            <option value="no_ref"
                                                {{ request('sort_by') == 'no_ref' ? 'selected' : '' }}>No. Referensi
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
                                            <option value="desc"
                                                {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Menurun (Z-A)
                                            </option>
                                            <option value="asc"
                                                {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Menaik (A-Z)
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

                            <!-- Script Validasi Form -->
                            <script>
                                // Komentar: Script untuk validasi form sebelum submit
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Validasi tanggal (memastikan tanggal mulai tidak lebih besar dari tanggal akhir)
                                    document.getElementById('reportForm').addEventListener('submit', function(e) {
                                        var tanggalMulai = new Date(document.getElementById('tanggal_mulai').value);
                                        var tanggalAkhir = new Date(document.getElementById('tanggal_akhir').value);

                                        if (tanggalMulai > tanggalAkhir) {
                                            e.preventDefault();
                                            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cards Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Total Penjualan -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-md bg-indigo-500 p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">
                                Total Penjualan
                            </p>
                            <p class="text-xl font-semibold text-gray-900">
                                Rp {{ number_format($totalPenjualan / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Barang Terjual -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-md bg-green-500 p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">
                                Total Barang Terjual
                            </p>
                            <p class="text-xl font-semibold text-gray-900">
                                {{ number_format($totalBarangTerjual, 0, ',', '.') }} Item
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Omset -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-md bg-purple-500 p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">
                                Total Omset
                            </p>
                            <p class="text-xl font-semibold text-gray-900">
                                Rp {{ number_format($totalOmset / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Tabel Penjualan -->
            <div class="mt-6 bg-white shadow rounded-lg overflow-hidden px-4">
                <div class=" py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Daftar Penjualan
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Ref
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Metode Pembayaran
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($penjualan as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->no_ref }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->customer->nama ?? 'Umum' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->metode_pembayaran }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($item->grand_total / 100, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada data penjualan pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $penjualan->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-layout>
