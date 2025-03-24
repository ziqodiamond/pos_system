<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow:md">
    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="p-4">
                <div class="flex items-center">
                    <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                </div>
            </th>
            <th scope="col" class="px-6 py-3">
                Kode
            </th>
            <th scope="col" class="px-6 py-3">
                Nama Barang
            </th>
            <th scope="col" class="px-6 py-3">
                Kategori
            </th>
            <th scope="col" class="px-6 py-3">
                Harga Pokok
            </th>
            <th scope="col" class="px-6 py-3">
                Harga Jual
            </th>
            <th scope="col" class="px-6 py-3">
                Diskon
            </th>
            <th scope="col" class="px-6 py-3">
                Pajak
            </th>
            <th scope="col" class="px-6 py-3">
                Status
            </th>
            <th scope="col" class="px-6 py-3">
                Stok
            </th>
            <th scope="col" class="px-6 py-3">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @forelse ($barang as $item)
            <tr
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input id="checkbox-table-search-3" type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-table-search-3" class="sr-only">checkbox</label>
                    </div>
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
                <td class="flex items-center px-6 py-4">
                    <a href="#" data-modal-target="modal-edit-{{ $item->id }}"
                        data-modal-toggle="modal-edit-{{ $item->id }}"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    <a href="#" data-modal-target="modal-delete-{{ $item->id }}"
                        data-modal-toggle="modal-delete-{{ $item->id }}"
                        class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Remove</a>
                </td>


            </tr>
        @empty
            <tr>
                <td colspan="10" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    Barang Kosong
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $barang->links() }}

@foreach ($barang as $item)
    <!-- Modal Edit -->
    <x-base-modal :id="'edit-' . $item->id" title="Edit Barang">
        <form action="{{ route('barang.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                    Barang</label>
                <input type="text" id="nama" name="nama" value="{{ $item->nama }}"
                    class="w-full p-2 border rounded dark:bg-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                    Jual</label>
                <input type="number" id="harga" name="harga_jual" value="{{ $item->harga_jual }}"
                    class="w-full p-2 border rounded dark:bg-gray-600 dark:text-white">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" data-modal-hide="modal-edit-{{ $item->id }}"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Save</button>
            </div>
        </form>
    </x-base-modal>

    <!-- Modal Delete -->
    <x-base-modal :id="'delete-' . $item->id" title="Delete Barang">
        <p class="text-gray-700 dark:text-gray-300">Yakin ingin menghapus <strong>{{ $item->nama }}</strong>?</p>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" data-modal-hide="modal-delete-{{ $item->id }}"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded">Cancel</button>
            <form action="{{ route('barang.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Delete</button>
            </form>
        </div>
    </x-base-modal>
@endforeach
