<!-- resources/views/faktur/laporan-hutang.blade.php -->
<x-layout>
    <div class="py-4">
        <div class="mb-4">
            @include('components.breadcrumbs')
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Laporan Hutang</h1>

            <!-- Filter dan Pencarian -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('laporan.hutang.index') }}" method="GET"
                    class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                    <!-- Filter Tanggal -->
                    <div class="flex-1">
                        <label for="tanggal_mulai"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                            value="{{ request('tanggal_mulai') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                    <div class="flex-1">
                        <label for="tanggal_akhir"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                    <!-- Filter Status -->
                    <div class="flex-1">
                        <label for="status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <select id="status" name="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Semua</option>
                            <option value="hutang" {{ request('status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <!-- Filter Supplier -->
                    <div class="flex-1">
                        <label for="supplier_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                        <select id="supplier_id" name="supplier_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Semua Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Tombol Filter -->
                    <div class="flex space-x-2">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Filter
                        </button>
                        <a href="{{ route('laporan.hutang.index') }}"
                            class="text-gray-500 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Card Ringkasan -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Faktur -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Faktur</div>
                    <div class="text-2xl font-bold mt-1">{{ $fakturCount }}</div>
                    <div class="mt-4 flex justify-between">
                        <div class="text-xs text-gray-500">Status Hutang</div>
                        <div class="text-xs font-medium">{{ $fakturHutangCount }}</div>
                    </div>
                    <div class="mt-1 flex justify-between">
                        <div class="text-xs text-gray-500">Status Lunas</div>
                        <div class="text-xs font-medium">{{ $fakturLunasCount }}</div>
                    </div>
                </div>

                <!-- Total Hutang -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Hutang</div>
                    <div class="text-2xl font-bold mt-1 text-red-600">Rp {{ number_format($totalHutang, 0, ',', '.') }}
                    </div>
                    <div class="mt-4 text-xs text-gray-500">Total faktur dengan status hutang</div>
                </div>

                <!-- Total Sudah Dibayar -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Sudah Dibayar</div>
                    <div class="text-2xl font-bold mt-1 text-green-600">Rp
                        {{ number_format($totalBayar, 0, ',', '.') }}
                    </div>
                    <div class="mt-4 text-xs text-gray-500">Total pembayaran untuk semua faktur</div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No Faktur</th>
                                    <th scope="col" class="px-6 py-3">Supplier</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Total Tagihan</th>
                                    <th scope="col" class="px-6 py-3">Total Bayar</th>
                                    <th scope="col" class="px-6 py-3">Sisa Hutang</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fakturPembelian as $item)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $item->no_faktur }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->supplier->nama }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($item->tanggal_faktur)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            Rp {{ number_format($item->total_hutang, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($item->status == 'hutang')
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    Hutang
                                                </span>
                                            @else
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                    Lunas
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex items-center space-x-3">

                                            @if ($item->status == 'hutang')
                                                <x-base-modal id="bayarModal-{{ $item->id }}" title="Bayar Faktur"
                                                    triggerText="Bayar"
                                                    triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                    <form action="{{ route('faktur.bayar', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="space-y-4">
                                                            <div>
                                                                <label for="metode_pembayaran"
                                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                    Metode Pembayaran
                                                                </label>
                                                                <select name="metode_pembayaran"
                                                                    id="metode_pembayaran" required
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                                    <option value="">Pilih metode pembayaran
                                                                    </option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="transfer">Transfer Bank</option>
                                                                    <option value="debit">Kartu Debit</option>
                                                                    <option value="kredit">Kartu Kredit</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label for="nominal"
                                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                    Nominal Pembayaran
                                                                </label>
                                                                <div class="relative">
                                                                    <span
                                                                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                                                        Rp
                                                                    </span>
                                                                    <input type="text" name="nominal"
                                                                        id="nominal"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                        placeholder="0" required
                                                                        value="{{ number_format($item->total_hutang, 0, '', '.') }}"
                                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="deskripsi"
                                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                    Deskripsi (Opsional)
                                                                </label>
                                                                <textarea name="deskripsi" id="deskripsi" rows="3"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                    placeholder="Masukkan deskripsi pembayaran (opsional)"></textarea>
                                                            </div>
                                                        </div>
                                                        <button type="submit"
                                                            class="mt-6 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                            Bayar
                                                        </button>
                                                    </form>
                                                </x-base-modal>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="8" class="px-6 py-4 text-center">
                                            Tidak ada data faktur pembelian
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4">
                    {{ $fakturPembelian->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
