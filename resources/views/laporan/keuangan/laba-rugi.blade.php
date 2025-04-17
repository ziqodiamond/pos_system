<x-layout>
    <div class="py-4">
        <div class="mb-4">
            @include('components.breadcrumbs')
        </div>
        <div class=" max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Laporan Laba Rugi</h1>

            <!-- Filter Periode -->
            <div x-data="{ modalOpen: false }" class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
                <form action="{{ route('laporan.laba-rugi') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="start_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                            Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="end_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                            Akhir</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                            Filter
                        </button>

                        <!-- PDF Export Button -->
                        <button type="button" @click="modalOpen = true"
                            class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Export PDF
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
                        class="w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">

                        <!-- Modal Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Export Laporan Laba Rugi
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
                        <form action="{{ route('laporan.laba-rugi.pdf') }}" method="GET" target="_blank">
                            <div class="space-y-4">
                                <!-- Periode Laporan -->
                                <div>
                                    <label for="start_date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                        Awal</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>

                                <div>
                                    <label for="end_date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                        Akhir</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>

                                <!-- Format Laporan -->
                                <div>
                                    <label for="format_laporan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Format
                                        Laporan</label>
                                    <select id="format_laporan" name="format_laporan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="detailed">Detail Lengkap</option>
                                        <option value="summary">Ringkasan</option>
                                    </select>
                                </div>

                                <!-- Include Produk Terlaris -->
                                <div class="flex items-center">
                                    <input id="include_produk" type="checkbox" name="include_produk" value="1"
                                        checked
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="include_produk"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sertakan
                                        Daftar Produk Terlaris</label>
                                </div>

                                <!-- Include Grafik (if you want to use a charting library in PDF) -->
                                <div class="flex items-center">
                                    <input id="include_grafik" type="checkbox" name="include_grafik" value="1"
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="include_grafik"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sertakan
                                        Grafik Tren Pendapatan</label>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex items-center justify-end mt-6 space-x-2">
                                <button type="button" @click="modalOpen = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                    Batal
                                </button>
                                <button type="submit"
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

            {{-- Statistik Utama (Cards) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Pendapatan --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                Rp {{ number_format($totalPendapatan / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Laba Kotor --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Laba Kotor</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                Rp {{ number_format($labaKotor / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Laba Bersih --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Laba Bersih</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                Rp {{ number_format($labaBersih / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Statistik (Cards) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- HPP --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total HPP</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                Rp {{ number_format($totalHPP / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Biaya Operasional --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Barang Keluar Non-Penjualan
                            </p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                Rp {{ number_format($biayaOperasional / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Total Pajak --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pajak</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                Rp {{ number_format($totalPajak / 100, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Laba Rugi -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6 p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Grafik Laba Rugi</h2>
                <div id="chart-laba-rugi" class="h-80"></div>
            </div>

            <!-- Tabel Rincian Laba Rugi -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg p-4 mb-6">
                <div class=" py-1 sm:px-6 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Rincian Laba Rugi
                    </h2>
                </div>

                <div class="overflow-x-auto ">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50
                        dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Pendapatan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    HPP
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Laba Kotor
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Biaya Operasional
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Pajak
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Laba Bersih
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($rincianLabaRugi as $item)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item['tanggal'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item['hpp'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item['laba_kotor'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item['biaya_operasional'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item['pajak'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $item['laba_bersih'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        Rp {{ number_format($item['laba_bersih'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="7">
                                        Data Kosong
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg px-4">
                <div class=" py-5 sm:px-6 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Produk Terlaris
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Total Terjual
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Total Pendapatan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Total HPP
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Profit
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Margin
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($produkTerlaris as $index => $item)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $index + 1 }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->nama_barang }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ number_format($item->total_terjual, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item->total_hpp, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($item->profit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $item->margin }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="7">
                                        Data Kosong
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
    <script>
        // Data untuk grafik
        const pendapatanData = @json($pendapatanBulanan);

        // Fungsi untuk memformat nilai ke format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Grafik Laba Rugi (Pendapatan vs HPP vs Operasional vs Pajak)
        function initGrafikLabaRugi() {
            // Mendefinisikan warna yang akan digunakan untuk setiap kategori
            const warnaPendapatan = '#4F46E5'; // Indigo untuk pendapatan
            const warnaHPP = '#EAB308'; // Kuning untuk HPP
            const warnaOperasional = '#EF4444'; // Merah untuk Biaya Operasional
            const warnaPajak = '#8B5CF6'; // Ungu untuk Pajak

            const options = {
                series: [{
                    name: 'Pendapatan',
                    data: pendapatanData.map(item => item.pendapatan)
                }, {
                    name: 'HPP',
                    data: pendapatanData.map(item => item.hpp)
                }, {
                    name: 'Biaya Operasional',
                    data: pendapatanData.map(item => item.operasional)
                }, {
                    name: 'Pajak',
                    data: pendapatanData.map(item => item.pajak)
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    stacked: false,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 4,
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: pendapatanData.map(item => item.periode),
                    position: 'bottom',
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)'
                    },
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + formatRupiah(value);
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    enabled: true,
                    shared: true,
                    intersect: false,
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit'
                    },
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + formatRupiah(value);
                        }
                    },
                    marker: {
                        show: true
                    },
                    // Kustomisasi tooltip mirip dengan contoh yang diberikan
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        // Mendapatkan periode (bulan/tahun)
                        const periode = w.globals.categoryLabels[dataPointIndex];

                        // Mengambil data untuk periode yang dipilih
                        const pendapatan = series[0][dataPointIndex];
                        const hpp = series[1][dataPointIndex];
                        const operasional = series[2][dataPointIndex];
                        const pajak = series[3][dataPointIndex];

                        // Menghitung total biaya dan profit
                        const totalBiaya = hpp + operasional + pajak;
                        const profit = pendapatan - totalBiaya;

                        // Membuat HTML tooltip kustom
                        return `<div class="p-2 rounded-md bg-white shadow-md border border-gray-200">
                    <div class="font-semibold text-gray-700">${periode}</div>
                    <div class="flex items-center mt-1">
                        <span style="background-color: ${warnaPendapatan}" class="w-3 h-3 rounded-full mr-2"></span>
                        <span class="text-gray-600">Pendapatan: </span>
                        <span class="font-bold ml-1">Rp ${formatRupiah(pendapatan)}</span>
                    </div>
                    <div class="flex items-center mt-1">
                        <span style="background-color: ${warnaHPP}" class="w-3 h-3 rounded-full mr-2"></span>
                        <span class="text-gray-600">HPP: </span>
                        <span class="font-bold ml-1">Rp ${formatRupiah(hpp)}</span>
                    </div>
                    <div class="flex items-center mt-1">
                        <span style="background-color: ${warnaOperasional}" class="w-3 h-3 rounded-full mr-2"></span>
                        <span class="text-gray-600">Operasional: </span>
                        <span class="font-bold ml-1">Rp ${formatRupiah(operasional)}</span>
                    </div>
                    <div class="flex items-center mt-1">
                        <span style="background-color: ${warnaPajak}" class="w-3 h-3 rounded-full mr-2"></span>
                        <span class="text-gray-600">Pajak: </span>
                        <span class="font-bold ml-1">Rp ${formatRupiah(pajak)}</span>
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Biaya:</span>
                            <span class="font-bold">Rp ${formatRupiah(totalBiaya)}</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-gray-600">Profit:</span>
                            <span class="font-bold ${profit >= 0 ? 'text-green-600' : 'text-red-600'}">
                                Rp ${formatRupiah(profit)}
                            </span>
                        </div>
                    </div>
                </div>`;
                    }
                },
                colors: [warnaPendapatan, warnaHPP, warnaOperasional, warnaPajak], // Menggunakan variabel warna
                theme: {
                    mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    markers: {
                        width: 12,
                        height: 12,
                        strokeWidth: 0,
                        radius: 12,
                        offsetX: 0,
                        offsetY: 0
                    },
                    labels: {
                        colors: document.documentElement.classList.contains('dark') ? '#fff' : '#333',
                    }
                }
            };

            // Inisialisasi dan render grafik
            const chart = new ApexCharts(document.querySelector("#chart-laba-rugi"), options);
            chart.render();
            return chart;
        }

        // Inisialisasi grafik
        document.addEventListener('DOMContentLoaded', function() {
            // Menambahkan pesan komentar untuk membantu debugging
            console.log('Menginisialisasi grafik laba rugi'); // Komentar debugging

            // Panggil fungsi untuk menginisialisasi grafik
            const chart = initGrafikLabaRugi();

            // Menambahkan keterangan warna di bawah grafik
            const containerEl = document.querySelector("#chart-laba-rugi").parentNode;
            const legendEl = document.createElement('div');
            legendEl.className = 'mt-4 flex flex-wrap gap-4 text-sm justify-center';
            legendEl.innerHTML = `
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full mr-2" style="background-color: #4F46E5"></span>
                    <span>Pendapatan (Indigo)</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full mr-2" style="background-color: #EAB308"></span>
                    <span>HPP (Kuning)</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full mr-2" style="background-color: #EF4444"></span>
                    <span>Biaya Operasional (Merah)</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 inline-block rounded-full mr-2" style="background-color: #8B5CF6"></span>
                    <span>Pajak (Ungu)</span>
                </div>
            `;
            containerEl.appendChild(legendEl);

            // Listener untuk mode gelap/terang
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const isDarkMode = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            theme: {
                                mode: isDarkMode ? 'dark' : 'light'
                            },
                            legend: {
                                labels: {
                                    colors: isDarkMode ? '#fff' : '#333'
                                }
                            }
                        });
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true
            });
        });
    </script>
</x-layout>
