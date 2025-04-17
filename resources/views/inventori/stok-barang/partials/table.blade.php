<x-master-table :route="'stok-barang'" :items="$barang" :columns="['Kode Barang', 'Nama Barang', 'Satuan', 'Total Stok', '']">
    <!-- Form Bulk Aksi Terpisah -->

    @forelse ($barang as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>

            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->kode }}
            </th>
            <td class="px-6 py-4">{{ $item->nama }}</td>
            <td class="px-6 py-4">{{ $item->satuan->nama }}</td>
            <td
                class="px-6 py-4 {{ $item->stok <= $item->stok_minimum && $item->stok > 0 ? 'bg-yellow-200' : ($item->stok == 0 ? 'bg-red-200' : '') }}">
                {{ $item->stok }}
            </td>
            <td class="px-6 py-4">
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

            </td>

        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="10">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $barang->links() }}
