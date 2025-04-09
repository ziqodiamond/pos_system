<x-master-table :route="'penjualan'" :items="$penjualan" :columns="[
    'No Ref',
    'Tanggal Transaksi',
    'Member',
    'Jumlah Item',
    'Subtotal',
    'Diskon',
    'DPP',
    'PPN',
    'Total',
    'Metode Pembayaran',
]">
    <!-- Form Bulk Aksi Terpisah -->

    @forelse ($penjualan as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>

            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->no_ref }}
            </th>
            <td class="px-6 py-4 whitespace-nowrap">{{ $item->created_at->format('H:i:s d-m-Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $item->customer?->nama ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $item->total_item }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">Rp
                {{ number_format($item->subtotal / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">Rp
                {{ number_format($item->total_diskon / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">Rp
                {{ number_format($item->total_pajak / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">Rp {{ number_format($item->dpp / 100, 2, ',', '.') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right">Rp
                {{ number_format($item->grand_total / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($item->metode_pembayaran) }}</td>
            <td class="flex items-center px-6 py-4 space-x-3">
                {{-- Delete / Restore Modal --}}

                {{-- Edit Modal Detail Penjualan --}}
                <div x-data="{ open: false }" class="inline-block">
                    <!-- Trigger -->
                    <button @click="open = true" type="button"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        View
                    </button>

                    <!-- Modal -->
                    <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

                        <!-- Overlay -->
                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="open = false"></div>

                        <!-- Modal Content -->
                        <div class="relative min-h-screen flex items-center justify-center p-4">
                            <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 max-w-7xl w-full">
                                <!-- Header dengan Gradien Premium -->
                                <div
                                    class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-700 ">
                                    <h3 class="text-xl font-bold flex items-center text-gray-900 dark:text-white">
                                        <svg class="w-6 h-6 mr-2 text-amber-400" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Detail Transaksi #{{ $item->no_ref }}
                                    </h3>
                                    <button type="button" @click="open = false"
                                        class="text-gray-800 hover:bg-gray-600 hover:text-white rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="p-6 space-y-8">
                                    <!-- Header Info - Kartu Status Premium -->
                                    <div
                                        class="relative overflow-hidden rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 p-6 text-white shadow-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Kolom 1: Informasi Pelanggan -->
                                            <div>
                                                <p class="text-sm font-medium text-indigo-100">Member</p>
                                                <p class="mt-1 text-2xl font-bold tracking-tight flex items-center">
                                                    {{ $item->customer?->nama ?? 'Non Member' }}
                                                </p>
                                                <p class="mt-1 text-sm text-indigo-200">
                                                    {{ $item->customer?->telepon }}</p>
                                            </div>

                                            <!-- Kolom 2: Informasi Transaksi -->
                                            <div>
                                                <p class="text-sm font-medium text-indigo-100">Tanggal Transaksi</p>
                                                <p class="mt-1 text-xl font-bold tracking-tight">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                                                </p>
                                                <p class="mt-1 text-sm text-indigo-200">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}
                                                </p>
                                            </div>

                                            <!-- Kolom 3: Informasi Kasir -->
                                            <div class="md:text-right">
                                                <p class="text-sm font-medium text-indigo-100">Dilayani Oleh</p>
                                                <p class="mt-1 text-xl font-bold tracking-tight">
                                                    {{ $item->kasir?->name }}
                                                </p>
                                                <p class="mt-1 text-sm text-indigo-200">ID: {{ $item->kasir?->id }}</p>
                                            </div>
                                        </div>

                                        <!-- Icon Background -->
                                        <div class="absolute -right-8 -bottom-8 opacity-10">
                                            <svg class="h-40 w-40" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 6h16M4 12h16m-7 6h7M4 9h16M4 16h5" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 16a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" fill="currentColor" />
                                                <path d="M19 16a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" fill="currentColor" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Main Content Grid -->
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                        <!-- Items Table -->
                                        <div class="lg:col-span-2">
                                            <div
                                                class="relative overflow-hidden rounded-xl border bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                                                <!-- Tabel Header dengan Badge Status -->

                                                <div class="max-h-[400px] overflow-y-auto">
                                                    <table class="w-full text-sm text-gray-500 dark:text-gray-400">
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
                                                                    Diskon</th>
                                                                <th scope="col"
                                                                    class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                                                    Harga</th>
                                                                <th scope="col"
                                                                    class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                                                    Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                            @foreach ($item->details as $detail)
                                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                    <td class="px-6 py-4 flex items-center">
                                                                        <span
                                                                            class="w-2 h-2 mr-2 bg-blue-500 rounded-full"></span>
                                                                        {{ $detail->barang->nama }}
                                                                    </td>
                                                                    <td class="px-6 py-4 text-right font-medium">
                                                                        {{ $detail->kuantitas }}
                                                                    </td>
                                                                    <td class="px-6 py-4 text-center">
                                                                        {{ \App\Models\Pembelian::formatNumber($detail->diskon_nominal) }}
                                                                    </td>
                                                                    <td class="px-6 py-4 text-right">
                                                                        {{ \App\Models\Pembelian::formatNumber($detail->harga_satuan) }}
                                                                    </td>
                                                                    <td class="px-6 py-4 text-right font-medium">
                                                                        {{ \App\Models\Pembelian::formatNumber($detail->total) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Summary Card -->
                                        <div class="space-y-4">


                                            <!-- Ringkasan Pembayaran Card -->
                                            <div
                                                class="rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 p-6 dark:from-gray-800 dark:to-gray-750 shadow-md border border-gray-200 dark:border-gray-600">
                                                <div class="space-y-4">
                                                    <h3
                                                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                                        Ringkasan Pembayaran</h3>
                                                    <div class="space-y-3">
                                                        <div class="flex justify-between text-sm">
                                                            <span
                                                                class="text-gray-600 dark:text-gray-300">Subtotal</span>
                                                            <span class="font-medium text-gray-900 dark:text-white">
                                                                {{ \App\Models\Pembelian::formatNumber($item->subtotal) }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between text-sm">
                                                            <span class="text-gray-600 dark:text-gray-300">Total
                                                                Diskon</span>
                                                            <span class="font-medium text-gray-900 dark:text-white">
                                                                {{ \App\Models\Pembelian::formatNumber($item->total_diskon) }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between text-sm">
                                                            <span class="text-gray-600 dark:text-gray-300">DPP</span>
                                                            <span class="font-medium text-gray-900 dark:text-white">
                                                                {{ \App\Models\Pembelian::formatNumber($item->dpp) }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between text-sm">
                                                            <span class="text-gray-600 dark:text-gray-300">PPN</span>
                                                            <span class="font-medium text-gray-900 dark:text-white">
                                                                {{ \App\Models\Pembelian::formatNumber($item->total_pajak) }}
                                                            </span>
                                                        </div>

                                                        <div
                                                            class="border-t border-gray-200 pt-4 mt-4 dark:border-gray-600">
                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                                                                <span
                                                                    class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                                                    {{ \App\Models\Pembelian::formatNumber($item->grand_total) }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Informasi Pembayaran dan Kembalian -->
                                                        <div
                                                            class="border-t border-gray-200 pt-4 mt-4 dark:border-gray-600">
                                                            <div class="flex justify-between text-sm mt-2">
                                                                <span class="text-gray-600 dark:text-gray-300">Matode
                                                                    Pembayaran</span>
                                                                <span
                                                                    class="font-medium text-gray-900 dark:text-white">
                                                                    {{ ucfirst($item->metode_pembayaran) }}
                                                                </span>
                                                            </div>
                                                            @if ($item->metode_pembayaran === 'tunai')
                                                                <div class="flex justify-between text-sm">
                                                                    <span
                                                                        class="text-gray-600 dark:text-gray-300">Total
                                                                        Bayar</span>
                                                                    <span
                                                                        class="font-medium text-gray-900 dark:text-white">
                                                                        {{ \App\Models\Pembelian::formatNumber($item->total_bayar) }}
                                                                    </span>
                                                                </div>
                                                                <div class="flex justify-between text-sm mt-2">
                                                                    <span
                                                                        class="text-gray-600 dark:text-gray-300">Kembalian</span>
                                                                    <span
                                                                        class="font-medium text-gray-900 dark:text-white">
                                                                        {{ \App\Models\Pembelian::formatNumber($item->kembalian) }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tombol Aksi -->
                                            <div class="flex gap-3 mt-6">
                                                <button type="button"
                                                    class="flex-1 items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-900 inline-flex">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0v3H7V4h6zm-5 7a1 1 0 112 0 1 1 0 01-2 0zm5-1a1 1 0 100 2 1 1 0 000-2z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Cetak Struk
                                                </button>
                                                <button type="button"
                                                    class="flex-1 items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 inline-flex">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Kirim Email
                                                </button>
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
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="4">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $penjualan->links() }}
