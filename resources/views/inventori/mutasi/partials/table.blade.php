<x-master-table :route="'mutasi'" :items="$barangKeluar" :columns="['Tanggal Keluar', 'Kode Barang', 'Nama Barang', 'QTY', 'Satuan', 'HPP', 'Total', 'Jenis', 'Keterangan']">
    <!-- Form Bulk Aksi Terpisah -->

    @forelse ($barangKeluar as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>

            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->tanggal_keluar }}
            </th>
            <td class="px-6 py-4">{{ $item->barang?->kode ?? 'N/A' }}</td>
            <td class="px-6 py-4">{{ $item->nama_barang ?? '-' }}</td>
            <td class="px-6 py-4">{{ $item->kuantitas }}</td>
            <td class="px-6 py-4">{{ $item->satuan->nama }}</td>
            <td class="px-6 py-4">Rp {{ number_format($item->harga_satuan / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4">Rp {{ number_format($item->subtotal / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4">{{ $item->jenis }}</td>
            <td class="px-6 py-4">{{ $item->keterangan }}</td>

        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="10">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $barangKeluar->links() }}
