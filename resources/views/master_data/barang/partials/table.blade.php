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
                    {{ $item->harga_pokok }}

                </td>
                <td class="px-6 py-4">
                    {{ $item->harga_jual }}
                </td>
                <td class="px-6 py-4">
                    {{ $item->diskon }}
                </td>
                <td class="px-6 py-4">
                    {{ $item->pajak }}
                </td>
                <td class="px-6 py-4">
                    {{ $item->stok }}
                </td>
                <td class="flex items-center px-6 py-4">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Remove</a>
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
