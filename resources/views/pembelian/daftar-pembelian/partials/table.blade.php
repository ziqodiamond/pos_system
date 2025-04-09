<x-master-table :route="'pembelian'" :items="$pembelian" :columns="['No Ref', 'Tanggal Pembelian', 'Tanggal Masuk', 'Supplier', 'Total Barang', 'Subtotal', 'Total', 'Status']">
    <!-- Form Bulk Aksi Terpisah -->

    @forelse ($pembelian as $item)
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
            <td class="px-6 py-4">{{ $item->tanggal_pembelian }}</td>
            <td class="px-6 py-4">{{ $item->tanggal_masuk ?? '-' }}</td>
            <td class="px-6 py-4">{{ $item->supplier->nama }}</td>
            <td class="px-6 py-4">{{ $item->total_item }}</td>
            <td class="px-6 py-4">Rp {{ number_format($item->subtotal / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4">{{ \App\Models\Pembelian::formatNumber($item->total) }}</td>
            <td class="px-6 py-4 text-center">
                @if ($item->status === 'processing')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                        Proses
                    </span>
                @elseif ($item->status === 'completed')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Selesai
                    </span>
                @elseif ($item->status === 'canceled')
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                        Dibatalkan
                    </span>
                @endif
            <td class="flex items-center px-6 py-4 space-x-3">
                {{-- Delete / Restore Modal --}}
                @if ($item->trashed())
                    <x-base-modal :id="'restoreModal-' . $item->id" title="Restore Kategori" triggerText="Restore"
                        triggerClass="font-medium text-green-600 dark:text-green-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin untuk memulihkan pembelian ini?
                        </p>
                        <form class="space-y-6" action="{{ route('pembelian.restore', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Restore
                            </button>
                        </form>
                    </x-base-modal>

                    <x-base-modal :id="'hardDeleteModal-' . $item->id" title="Permanent Delete" triggerText="Delete Permanently"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to permanently delete this category? This action cannot be undone.
                        </p>
                        <form class="space-y-6" action="{{ route('pembelian.forceDelete', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Delete Permanently
                            </button>
                        </form>
                    </x-base-modal>
                @else
                    {{-- Edit Modal --}}
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
                            <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>

                            <!-- Modal Content -->
                            <div class="relative min-h-screen flex items-center justify-center p-4">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 max-w-7xl w-full">
                                    <!-- Header -->
                                    <div
                                        class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-700">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Detail Pembelian #{{ $item->no_ref }}
                                        </h3>
                                        <button type="button" @click="open = false"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
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
                                                    <p class="text-sm font-medium text-blue-100">Supplier</p>
                                                    <p class="mt-1 text-2xl font-bold tracking-tight">
                                                        {{ $item->supplier->nama }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-medium text-blue-100">Tanggal Pembelian</p>
                                                    <p class="mt-1 text-2xl font-bold tracking-tight">
                                                        {{ $item->tanggal_pembelian }}</p>
                                                </div>
                                            </div>
                                            <div class="absolute -right-8 -bottom-8 opacity-10">
                                                <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
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
                                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                        <td class="px-6 py-4">
                                                                            {{ $detail->barang->nama }}</td>
                                                                        <td class="px-6 py-4 text-right font-medium">
                                                                            {{ $detail->qty_user }}</td>
                                                                        <td class="px-6 py-4 text-center">
                                                                            {{ $detail->satuan->nama }}</td>
                                                                        <td class="px-6 py-4 text-right">
                                                                            {{ \App\Models\Pembelian::formatNumber($detail->harga_satuan) }}
                                                                        </td>
                                                                        <td class="px-6 py-4 text-right font-medium">
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
                                            <div class="rounded-xl bg-gray-50 p-6 dark:bg-gray-700 h-fit">
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
                                                            <span class="text-gray-600 dark:text-gray-300">Total
                                                                Diskon</span>
                                                            <span
                                                                class="font-medium text-gray-900 dark:text-white">{{ \App\Models\Pembelian::formatNumber($item->diskon_value) }}</span>
                                                        </div>
                                                        <div class="flex justify-between text-sm">
                                                            <span class="text-gray-600 dark:text-gray-300">Total
                                                                Pajak</span>
                                                            <span
                                                                class="font-medium text-gray-900 dark:text-white">{{ \App\Models\Pembelian::formatNumber($item->pajak_value) }}</span>
                                                        </div>
                                                        <div class="flex justify-between text-sm">
                                                            <span class="text-gray-600 dark:text-gray-300">Biaya
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

                    @if ($item->status === 'processing')
                        <x-base-modal :id="'completeModal-' . $item->id" title="Selesaikan Pembelian" triggerText="Selesai"
                            triggerClass="font-medium text-green-600 dark:text-green-500 hover:underline">
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                Apakah anda yakin untuk menyelesaikan pembelian ini?
                            </p>
                            <form class="space-y-6" action="{{ route('pembelian.selesai', $item->id) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    Selesai
                                </button>
                            </form>
                        </x-base-modal>

                        <x-base-modal :id="'cancelModal-' . $item->id" title="Batalkan Pembelian" triggerText="Batalkan"
                            triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                Apakah anda yakin untuk membatalkan pembelian ini?
                            </p>
                            <form class="space-y-6" action="{{ route('pembelian.batal', $item->id) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Batalkan
                                </button>
                            </form>
                        </x-base-modal>
                    @endif

                    <x-base-modal :id="'deleteModal-' . $item->id" title="Hapus Kategori" triggerText="Hapus"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin menghapus kategori {{ $item->nama }}?
                        </p>
                        <form class="space-y-6" action="{{ route('customer.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Hapus
                            </button>
                        </form>
                    </x-base-modal>
                @endif
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

{{ $pembelian->links() }}
