<x-master-table :route="'barang'" :items="$barang" :columns="['Kode', 'Nama', 'Kategori', 'Harga Pokok', 'Harga Jual', 'Diskon', 'Pajak', 'Status', 'Stok']">
    @forelse ($barang as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->kode }}
            </th>
            <td class="px-6 py-4">
                {{ $item->nama }}
            </td>
            <td class="px-6 py-4">
                {{ $item->kategori->nama }}
            </td>
            <td class="px-6 py-4">
                Rp {{ number_format($item->harga_pokok, 0, ',', '.') }}
            </td>
            <td class="px-6 py-4">
                Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
            </td>
            <td class="px-6 py-4">
                {{ $item->diskon_value }} %
            </td>
            <td class="px-6 py-4">
                {{ $item->pajak?->nama }}
            </td>
            <td class="px-6 py-4">
                {{ $item->status }}
            </td>
            <td class="px-6 py-4">
                {{ $item->stok }}
            </td>
            <td class="flex items-center px-6 py-4 space-x-2">
                @if ($item->trashed())
                    <x-base-modal :id="'restoreModal-' . $item->id" title="Restore Barang" triggerText="Pulihkan"
                        triggerClass="font-medium text-green-600 dark:text-green-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin memulihkan item {{ $item->nama }}?
                        </p>
                        <form class="space-y-6" action="{{ route('barang.restore', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button
                                class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Pulihkan
                            </button>
                        </form>
                    </x-base-modal>

                    <x-base-modal :id="'hardDeleteModal-' . $item->id" title="Permanent Delete" triggerText="Hapus Permanen"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin menghapus permanen item {{ $item->nama }}? Tindakan ini tidak
                            dapat
                            dibatalkan.
                        </p>
                        <form class="space-y-6" action="{{ route('barang.forceDelete', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Hapus Permanen
                            </button>
                        </form>
                    </x-base-modal>
                @else
                    {{-- Edit Modal --}}
                    @include('master_data.barang.partials.edit-barang')

                    {{-- Delete Modal --}}
                    <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Supplier" triggerText="Delete"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete this supplier?
                        </p>
                        <form class="space-y-6" action="{{ route('supplier.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Delete</button>
                        </form>
                    </x-base-modal>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="8">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $barang->links() }}
