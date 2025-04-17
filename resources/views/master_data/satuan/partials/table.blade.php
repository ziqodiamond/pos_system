<x-master-table :route="'satuan'" :items="$satuan" :columns="['Kode', 'Nama', 'Jenis', 'Keterangan', 'Status', '']">
    @forelse ($satuan as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                        class=" item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->kode }}
            </th>
            <td class="px-6 py-4">{{ $item->nama }}</td>
            <td class="px-6 py-4">{{ $item->status_satuan }}</td>
            <td class="px-6 py-4">{{ $item->keterangan }}</td>
            <td class="px-6 py-4">{{ $item->status }}</td>
            <td class="flex items-center px-6 py-4 space-x-2">
                {{-- Edit Modal --}}
                <x-base-modal :id="'editModal-' . $item->id" title="Edit Satuan" triggerText="Edit"
                    triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                    <form action="{{ route('satuan.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="kode-{{ $item->id }}"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                            <input type="text" name="kode" id="kode-{{ $item->id }}"
                                value="{{ $item->kode }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div class="mt-2">
                            <label for="nama-{{ $item->id }}"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                            <input type="text" name="nama" id="nama-{{ $item->id }}"
                                value="{{ $item->nama }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div class="mt-2">
                            <label for="status_satuan-{{ $item->id }}"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Jenis</label>
                            <select name="status_satuan" id="status_satuan-{{ $item->id }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                                <option value="satuan_dasar"
                                    {{ $item->status_satuan == 'satuan_dasar' ? 'selected' : '' }}>Satuan Dasar
                                </option>
                                <option value="konversi_satuan"
                                    {{ $item->status_satuan == 'konversi_satuan' ? 'selected' : '' }}>Satuan Konversi
                                </option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="keterangan-{{ $item->id }}"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan-{{ $item->id }}"
                                value="{{ $item->keterangan }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div x-data="{ statusChecked: {{ json_encode(old('status', $item->status ?? 'inactive') === 'active') }} }" class="mt-2">
                            <label for="status" class="mb-1 block text-sm font-medium text-gray-700">Status</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" id="status" value="active" class="sr-only peer"
                                    x-model="statusChecked">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                                </div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                    x-text="statusChecked ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </div>
                        <button type="submit"
                            class="mt-6 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                    </form>
                </x-base-modal>

                {{-- Delete Modal --}}
                <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Kategori" triggerText="Hapus"
                    triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Apakah anda yakin menghapus satuan {{ $item->nama }}?
                    </p>
                    <form class="space-y-6" action="{{ route('satuan.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Hapus</button>


                    </form>
                </x-base-modal>
            </td>

        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="7">
                Data Kosong
            </td>
        </tr>
    @endforelse

</x-master-table>


{{ $satuan->links() }}
