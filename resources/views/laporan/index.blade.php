<x-layout>
    <div class="py-8" x-data="{ periode: '{{ $periode }}' }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                @include('components.breadcrumbs')
            </div>
            <!-- Dashboard Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Laporan</h1>
                    <p class="mt-1 text-sm text-gray-500">Analisis kinerja bisnis Anda secara real-time</p>
                </div>

                <!-- Filter Periode -->
                <div
                    class="mt-4 md:mt-0 bg-white shadow rounded-lg p-3 flex items-center gap-3 border-l-4 border-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <label for="periode" class="font-medium text-gray-700">Periode:</label>
                    <select id="periode" x-model="periode" @change="updateStatistik()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="hari_ini">Hari Ini</option>
                        <option value="kemarin">Kemarin</option>
                        <option value="minggu_ini">Minggu Ini</option>
                        <option value="minggu_kemarin">Minggu Kemarin</option>
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="bulan_kemarin">Bulan Kemarin</option>
                        <option value="tahun_ini">Tahun Ini</option>
                        <option value="tahun_kemarin">Tahun Kemarin</option>
                    </select>
                </div>
            </div>


            <!-- Statistik Utama -->
            <div class="mt-6 grid grid-cols-1 gap-5">
                <!-- Baris 1: 3 Kartu -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- Kartu Omset (Baris 1, Kolom 1) -->
                    <div class="bg-gradient-to-br from-green-50 to-white overflow-hidden shadow-lg rounded-lg border border-green-100"
                        id="card-omset">
                        <div class="px-4 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <dt class="text-sm font-medium text-gray-500">Omset</dt>
                                    <dd
                                        class="mt-1 text-xl font-semibold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">
                                        Rp {{ number_format($omset / 100, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 h-10 flex items-center">
                            <div class="text-sm">
                                <a href="#"
                                    class="font-medium text-green-600 hover:text-green-500 flex items-center">
                                    <span>Lihat detail</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Total Pengeluaran (Baris 1, Kolom 2) -->
                    <div class="bg-gradient-to-br from-red-50 to-white overflow-hidden shadow-lg rounded-lg border border-red-100"
                        id="card-total-pengeluaran">
                        <div class="px-4 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-red-500 rounded-md p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <dt class="text-sm font-medium text-gray-500">Total Pengeluaran</dt>
                                    <dd
                                        class="mt-1 text-xl font-semibold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">
                                        Rp {{ number_format($total_pengeluaran / 100, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 h-10 flex items-center">
                            <div class="text-sm">
                                <a href="#" class="font-medium text-red-600 hover:text-red-500 flex items-center">
                                    <span>Lihat detail</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Laba Kotor (Baris 1, Kolom 3) -->
                    <div class="bg-gradient-to-br from-amber-50 to-white overflow-hidden shadow-lg rounded-lg border border-amber-100"
                        id="card-laba-kotor">
                        <div class="px-4 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-amber-500 rounded-md p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <dt class="text-sm font-medium text-gray-500">Laba Kotor</dt>
                                    <dd
                                        class="mt-1 text-xl font-semibold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">
                                        Rp {{ number_format($laba_kotor / 100, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 h-10 flex items-center">
                            <div class="text-sm">
                                <a href="#"
                                    class="font-medium text-amber-600 hover:text-amber-500 flex items-center">
                                    <span>Lihat detail</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Baris 2: 2 Kartu -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Kartu Total Penjualan (Baris 2, Kolom 1) -->
                    <div class="bg-gradient-to-br from-blue-50 to-white overflow-hidden shadow-lg rounded-lg border border-blue-100"
                        id="card-total-penjualan">
                        <div class="px-4 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <dt class="text-sm font-medium text-gray-500">Total Penjualan</dt>
                                    <dd
                                        class="mt-1 text-xl font-semibold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">
                                        {{ $total_penjualan }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 h-10 flex items-center">
                            <div class="text-sm">
                                <a href="#"
                                    class="font-medium text-blue-600 hover:text-blue-500 flex items-center">
                                    <span>Lihat semua</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Total Pembelian (Baris 2, Kolom 2) -->
                    <div class="bg-gradient-to-br from-purple-50 to-white overflow-hidden shadow-lg rounded-lg border border-purple-100"
                        id="card-total-pembelian">
                        <div class="px-4 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <dt class="text-sm font-medium text-gray-500">Total Pembelian</dt>
                                    <dd
                                        class="mt-1 text-xl font-semibold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">
                                        {{ $total_pembelian }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 h-10 flex items-center">
                            <div class="text-sm">
                                <a href="#"
                                    class="font-medium text-purple-600 hover:text-purple-500 flex items-center">
                                    <span>Lihat semua</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Statistik -->
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Grafik Penjualan Bulanan -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Penjualan Bulanan</h3>
                        </div>
                        <div class="flex items-center">
                            <button
                                class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </button>
                            <button
                                class="ml-2 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="h-80" id="chart-penjualan-bulanan"></div>
                    </div>
                </div>

                <!-- Grafik Produk Terlaris -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-emerald-500 rounded-md p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Top 5 Produk Terlaris</h3>
                        </div>
                        <div class="flex items-center">
                            <button
                                class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </button>
                            <button
                                class="ml-2 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="h-80" id="chart-produk-terlaris"></div>
                    </div>
                </div>
            </div>

            <!-- Statistik Master Data -->
            <div class="mt-8 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
                <div class="px-5 py-5 border-b border-gray-100 bg-gray-50 flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-2 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Master Data</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                        <div
                            class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-lg p-6 text-center shadow">
                            <div
                                class="inline-flex items-center justify-center p-3 bg-blue-100 rounded-full text-blue-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span class="text-gray-500 text-sm font-medium">Total Barang</span>
                            <div class="text-2xl font-bold mt-2 text-gray-900">{{ $total_barang }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-lg p-6 text-center shadow">
                            <div
                                class="inline-flex items-center justify-center p-3 bg-green-100 rounded-full text-green-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-500 text-sm font-medium">Total Supplier</span>
                            <div class="text-2xl font-bold mt-2 text-gray-900">{{ $total_supplier }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-purple-50 to-white border border-purple-100 rounded-lg p-6 text-center shadow">
                            <div
                                class="inline-flex items-center justify-center p-3 bg-purple-100 rounded-full text-purple-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-500 text-sm font-medium">Total Customer</span>
                            <div class="text-2xl font-bold mt-2 text-gray-900">{{ $total_customer }}</div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-amber-50 to-white border border-amber-100 rounded-lg p-6 text-center shadow">
                            <div
                                class="inline-flex items-center justify-center p-3 bg-amber-100 rounded-full text-amber-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <span class="text-gray-500 text-sm font-medium">Total Kategori</span>
                            <div class="text-2xl font-bold mt-2 text-gray-900">{{ $total_kategori }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Laporan -->
            <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header tetap dipertahankan sesuai permintaan -->
                <div class="px-5 py-5 border-b border-gray-100 bg-gray-50 flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-2 mr-3">
                        <!-- Ubah warna ikon header menjadi gray -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Menu Laporan</h3>
                </div>

                <div class="p-5">
                    <!-- Bagian Menu dengan desain baru -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Kategori Keuangan -->
                        <div class="mb-6 col-span-1 md:col-span-2 lg:col-span-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                                    <h2 class="text-lg font-semibold text-gray-800">Laporan Keuagan</h2>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <!-- Mengubah warna background dan ikon menjadi gray -->
                                <a href="{{ route('laporan.laba-rugi') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Laba Rugi</span>
                                </a>
                                <a href="{{ route('laporan.hutang.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Hutang</span>
                                </a>
                                <a href="{{ route('laporan.pajak') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Pajak</span>
                                </a>
                            </div>
                        </div>

                        <!-- Kategori Pembelian -->
                        <div class="mb-6 col-span-1 md:col-span-2 lg:col-span-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                                    <h2 class="text-lg font-semibold text-gray-800">Laporan Pembelian</h2>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <a href="{{ route('laporan.pembelian.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Pembelian</span>
                                </a>
                            </div>
                        </div>

                        <!-- Kategori Penjualan -->
                        <div class="mb-6 col-span-1 md:col-span-2 lg:col-span-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                                    <h2 class="text-lg font-semibold text-gray-800">Laporan Penjualan</h2>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <a href="{{ route('laporan.penjualan.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Penjualan</span>
                                </a>

                                <a href="{{ route('laporan.kasir') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Kasir</span>
                                </a>
                            </div>
                        </div>

                        <!-- Kategori Mutasi Stok -->
                        <div class="col-span-1 md:col-span-2 lg:col-span-4">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                                    <h2 class="text-lg font-semibold text-gray-800">Monitor Stok</h2>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <a href="{{ route('laporan.barang-keluar.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Barang Keluar</span>
                                </a>
                                <a href="{{ route('laporan.barang-masuk.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Barang Masuk</span>
                                </a>
                                <a href="{{ route('laporan.stok-minimum.index') }}"
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800">Stok Minimum</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
    <script>
        // Mendefinisikan routes
        // Perbaikan endpoint untuk AJAX request - menggunakan URL absolut
        const routes = {
            // Menggunakan endpoint yang sudah terdaftar di controller
            statistik: "{{ route('laporan.index') }}/statistik"
        };

        // Fungsi untuk menginisialisasi grafik penjualan bulanan
        function initPenjualanBulananChart(data) {
            // Konfigurasi grafik penjualan bulanan
            const options = {
                series: [{
                    name: 'Penjualan',
                    data: data.map(item => item.total / 100) // Bagi dengan 100
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: data.map(item => item.bulan),
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
                        formatter: function(val) {
                            return formatRupiah(val * 100); // Kalikan dengan 100 karena nilai sudah dibagi
                        }
                    }
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
                        formatter: function(val) {
                            return 'Rp ' + formatRupiah(val * 100); // Kalikan dengan 100 karena nilai sudah dibagi
                        }
                    },
                    marker: {
                        show: true
                    },
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const bulan = w.globals.labels[dataPointIndex];
                        const nilai = formatRupiah(series[seriesIndex][dataPointIndex] * 100);

                        return `<div class="p-2 rounded-md bg-white shadow-md border border-gray-200">
                    <div class="font-semibold text-gray-700">${bulan}</div>
                    <div class="flex items-center mt-1">
                        <span class="bg-indigo-500 w-3 h-3 rounded-full mr-2"></span>
                        <span class="text-gray-600">Penjualan: </span>
                        <span class="font-bold ml-1">Rp ${nilai}</span>
                    </div>
                </div>`;
                    }
                },
                colors: ['#4F46E5']
            };

            // Menginisialisasi dan merender grafik
            const chart = new ApexCharts(document.querySelector("#chart-penjualan-bulanan"), options);
            chart.render();
            return chart;
        }

        // Fungsi untuk menginisialisasi grafik produk terlaris
        function initProdukTerlarisChart(data) {
            // Konfigurasi grafik produk terlaris
            const options = {
                series: [{
                    name: 'Total Terjual',
                    data: data.map(item => item.total_terjual)
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        distributed: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetX: 20,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    }
                },
                xaxis: {
                    categories: data.map(item => item.nama),
                },
                tooltip: {
                    enabled: true,
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit'
                    },
                    marker: {
                        show: true
                    },
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const produk = w.globals.labels[dataPointIndex];
                        const jumlah = series[seriesIndex][dataPointIndex];
                        const warna = w.globals.colors[dataPointIndex];

                        return `<div class="p-2 rounded-md bg-white shadow-md border border-gray-200">
                    <div class="font-semibold text-gray-700">${produk}</div>
                    <div class="flex items-center mt-1">
                        <span style="background-color: ${warna}" class="w-3 h-3 rounded-full mr-2"></span>
                        <span class="text-gray-600">Terjual: </span>
                        <span class="font-bold ml-1">${jumlah} unit</span>
                    </div>
                </div>`;
                    }
                },
                colors: ['#10B981', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899'],
                legend: {
                    show: false
                }
            };

            // Menginisialisasi dan merender grafik
            const chart = new ApexCharts(document.querySelector("#chart-produk-terlaris"), options);
            chart.render();
            return chart;
        }

        // Fungsi untuk memformat angka ke format rupiah
        function formatRupiah(angka) {
            // Bagi dengan 100 untuk mengubah dari sen ke rupiah
            const rupiah = angka / 100;
            // Format dengan pemisah ribuan dan 2 digit desimal
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(rupiah);
        }


        // Fungsi untuk memperbarui statistik berdasarkan periode
        function updateStatistik() {
            // Mendapatkan nilai periode dari dropdown
            const periodeValue = document.getElementById('periode').value;

            // Menampilkan log untuk debugging
            console.log('Updating statistics for period:', periodeValue);
            console.log('Using URL:', `${routes.statistik}?periode=${periodeValue}`);

            // Menampilkan loading state pada kartu statistik
            document.querySelectorAll('[id^="card-"]').forEach(card => {
                const ddElement = card.querySelector('dd');
                if (ddElement) {
                    ddElement.innerHTML = '<span class="animate-pulse">Loading...</span>';
                }
            });

            // Melakukan AJAX request untuk mendapatkan data baru
            fetch(`${routes.statistik}?periode=${periodeValue}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data yang diterima:', data); // Debugging

                    // Memperbarui nilai-nilai di kartu statistik
                    document.querySelector('#card-total-penjualan dd').textContent = data.total_penjualan;
                    document.querySelector('#card-omset dd').textContent = 'Rp ' + formatRupiah(data.omset);
                    document.querySelector('#card-total-pembelian dd').textContent = data.total_pembelian;
                    document.querySelector('#card-total-pengeluaran dd').textContent = 'Rp ' + formatRupiah(data
                        .total_pengeluaran);
                    document.querySelector('#card-laba-kotor dd').textContent = 'Rp ' + formatRupiah(data.laba_kotor);

                    // Memperbarui grafik penjualan bulanan
                    if (window.penjualanBulananChart) {
                        window.penjualanBulananChart.updateSeries([{
                            name: 'Penjualan',
                            data: data.penjualan_bulanan.map(item => item.total / 100) // Bagi dengan 100
                        }]);
                        window.penjualanBulananChart.updateOptions({
                            xaxis: {
                                categories: data.penjualan_bulanan.map(item => item.bulan)
                            }
                        });
                    }

                    // Memperbarui grafik produk terlaris
                    if (window.produkTerlarisChart) {
                        window.produkTerlarisChart.updateSeries([{
                            name: 'Total Terjual',
                            data: data.produk_terlaris.map(item => item.total_terjual)
                        }]);
                        window.produkTerlarisChart.updateOptions({
                            xaxis: {
                                categories: data.produk_terlaris.map(item => item.nama)
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data. Detail: ' + error);

                    // Kembalikan tampilan kartu ke nilai awal jika terjadi error
                    document.querySelectorAll('[id^="card-"] dd').forEach(dd => {
                        dd.textContent = 'Error loading data';
                    });
                });
        }

        // Inisialisasi grafik saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi grafik penjualan bulanan
            const penjualanBulananData = @json($penjualan_bulanan);
            window.penjualanBulananChart = initPenjualanBulananChart(penjualanBulananData);

            // Inisialisasi grafik produk terlaris
            const produkTerlarisData = @json($produk_terlaris);
            window.produkTerlarisChart = initProdukTerlarisChart(produkTerlarisData);

            // Menambahkan event listener pada dropdown periode
            document.getElementById('periode').addEventListener('change', function() {
                updateStatistik();
            });
        });
    </script>

</x-layout>
