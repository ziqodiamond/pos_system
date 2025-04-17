<!--
File: resources/views/laporan/pembelian/index.blade.php
Deskripsi: Halaman untuk menampilkan laporan pembelian
-->
<x-layout>
    <div class="py-4">
        <div class="mb-4">
            @include('components.breadcrumbs')
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Laporan Pembelian</h1>

            <div x-data="{ modalOpen: false }">
                <!-- Filter Periode -->
                <div class="bg-white shadow rounded-lg p-4 mt-4">
                    <form action="{{ route('laporan.pembelian.index') }}" method="GET"
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
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="proses" {{ $status == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="h-10 bg-indigo-600 px-4 text-white rounded-md hover:bg-indigo-700">
                                Filter
                            </button>

                            <!-- Trigger Button -->
                            <button type="button" @click="modalOpen = true"
                                class="h-10 flex items-center justify-center px-4 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                Export Data
                            </button>
                        </div>
                    </form>
                </div>
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
            <!-- Cards Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Total Pembelian -->
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
                                Total Pembelian
                            </p>
                            <p class="text-xl font-semibold text-gray-900">
                                Rp {{ number_format($totalPembelian / 100, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Barang Dibeli -->
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
                                Total Barang Dibeli
                            </p>
                            <p class="text-xl font-semibold text-gray-900">
                                {{ number_format($totalBarangDibeli, 0, ',', '.') }} Item
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Pajak -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-md bg-purple-500 p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm font-medium text-gray-500 truncate">
                                Total Pajak
                            </p>
                            <p class="text-xl font-semibold text-gray-900">
                                Rp {{ number_format($totalPajak / 100, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Pembelian -->
            <div class="mt-6 bg-white shadow rounded-lg overflow-hidden px-4">
                <div class="py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Daftar Pembelian
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
                                    Tanggal Pembelian
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Masuk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Supplier
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
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
                            @forelse ($pembelian as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->no_ref }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->supplier->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">

                                        @if ($item->status == 'processing')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Proses
                                            </span>
                                        @elseif($item->status == 'received')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Diterima
                                            </span>
                                        @elseif($item->status == 'complated')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @elseif($item->status == 'cenceled')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Batal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($item->total / 100, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div x-data="{ open: false }" class="inline-block">
                                            <!-- Trigger -->
                                            <button @click="open = true" type="button"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                View
                                            </button>

                                            <!-- Modal -->
                                            <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" x-cloak>

                                                <!-- Overlay -->
                                                <div class="fixed inset-0 bg-black bg-opacity-50"
                                                    @click="open = false"></div>

                                                <!-- Modal Content -->
                                                <div
                                                    class="relative min-h-screen flex items-center justify-center p-4">
                                                    <div
                                                        class="relative bg-white rounded-lg shadow dark:bg-gray-800 max-w-7xl w-full">
                                                        <!-- Header -->
                                                        <div
                                                            class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-700">
                                                            <h3
                                                                class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                Detail Pembelian #{{ $item->no_ref }}
                                                            </h3>
                                                            <button type="button" @click="open = false"
                                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <svg class="w-3 h-3" aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 14 14">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <!-- Body -->
                                                        <div class="p-6 space-y-8">
                                                            <!-- Header Info -->
                                                            <div
                                                                class="relative overflow-hidden rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white shadow-lg">
                                                                <div class="grid grid-cols-2 gap-6">
                                                                    <div>
                                                                        <p class="text-sm font-medium text-blue-100">
                                                                            Supplier</p>
                                                                        <p
                                                                            class="mt-1 text-2xl font-bold tracking-tight">
                                                                            {{ $item->supplier->nama }}</p>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <p class="text-sm font-medium text-blue-100">
                                                                            Tanggal Pembelian</p>
                                                                        <p
                                                                            class="mt-1 text-2xl font-bold tracking-tight">
                                                                            {{ $item->tanggal_pembelian }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="absolute -right-8 -bottom-8 opacity-10">
                                                                    <svg class="h-32 w-32" fill="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                                                    </svg>
                                                                </div>
                                                            </div>

                                                            <!-- Main Content Grid -->
                                                            <div class="grid grid-cols-3 gap-6">
                                                                <!-- Items Table -->
                                                                <div class="col-span-2">
                                                                    <div
                                                                        class="relative overflow-hidden rounded-xl border bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                                                        <div class="max-h-[400px] overflow-y-auto">
                                                                            <table
                                                                                class="w-full text-sm text-gray-500 dark:text-gray-400">
                                                                                <thead
                                                                                    class="sticky top-0 bg-gray-50 text-xs uppercase dark:bg-gray-700">
                                                                                    <tr>
                                                                                        <th scope="col"
                                                                                            class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                                                            Produk</th>
                                                                                        <th scope="col"
                                                                                            class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                                                                            Qty</th>
                                                                                        <th scope="col"
                                                                                            class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">
                                                                                            Satuan</th>
                                                                                        <th scope="col"
                                                                                            class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                                                                            Harga</th>
                                                                                        <th scope="col"
                                                                                            class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                                                                            Total</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                                    @foreach ($item->details as $detail)
                                                                                        <tr
                                                                                            class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                                            <td class="px-6 py-4">
                                                                                                {{ $detail->barang->nama }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-6 py-4 text-right font-medium">
                                                                                                {{ $detail->qty_user }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-6 py-4 text-center">
                                                                                                {{ $detail->satuan->nama }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-6 py-4 text-right">
                                                                                                {{ \App\Models\Pembelian::formatNumber($detail->harga_satuan) }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-6 py-4 text-right font-medium">
                                                                                                {{ \App\Models\Pembelian::formatNumber($detail->subtotal) }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Summary Card -->
                                                                <div
                                                                    class="rounded-xl bg-gray-50 p-6 dark:bg-gray-700 h-fit">
                                                                    <div class="space-y-4">
                                                                        <h3
                                                                            class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                                                            Ringkasan Pembayaran</h3>
                                                                        <div class="space-y-3">
                                                                            <div class="flex justify-between text-sm">
                                                                                <span
                                                                                    class="text-gray-600 dark:text-gray-300">Subtotal</span>
                                                                                <span
                                                                                    class="font-medium text-gray-900 dark:text-white">{{ \App\Models\Pembelian::formatNumber($item->total) }}</span>
                                                                            </div>
                                                                            <div class="flex justify-between text-sm">
                                                                                <span
                                                                                    class="text-gray-600 dark:text-gray-300">Total
                                                                                    Diskon</span>
                                                                                <span
                                                                                    class="font-medium text-gray-900 dark:text-white">{{ \App\Models\Pembelian::formatNumber($item->diskon_value) }}</span>
                                                                            </div>
                                                                            <div class="flex justify-between text-sm">
                                                                                <span
                                                                                    class="text-gray-600 dark:text-gray-300">Total
                                                                                    Pajak</span>
                                                                                <span
                                                                                    class="font-medium text-gray-900 dark:text-white">{{ \App\Models\Pembelian::formatNumber($item->pajak_value) }}</span>
                                                                            </div>
                                                                            <div class="flex justify-between text-sm">
                                                                                <span
                                                                                    class="text-gray-600 dark:text-gray-300">Biaya
                                                                                    Lainnya</span>
                                                                                <span
                                                                                    class="font-medium text-gray-900 dark:text-white">{{ \App\Models\Pembelian::formatNumber($item->biaya_lainnya) }}</span>
                                                                            </div>
                                                                            <div
                                                                                class="border-t border-gray-200 pt-4 mt-4 dark:border-gray-600">
                                                                                <div class="flex justify-between">
                                                                                    <span
                                                                                        class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                                                                    <span
                                                                                        class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Pembelian::formatNumber($item->total) }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada data pembelian pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $pembelian->withQueryString()->links() }}
                </div>
            </div>


        </div>
    </div>


</x-layout>
