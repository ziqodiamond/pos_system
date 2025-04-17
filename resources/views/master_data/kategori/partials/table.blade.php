<x-master-table :route="'kategori'" :items="$kategori" :columns="['Kode', 'Nama', 'Status', '']">
    <!-- Form Bulk Aksi Terpisah -->

    @forelse ($kategori as $item)
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
            <td class="px-6 py-4">{{ $item->status }}</td>

            <td class="flex items-center px-6 py-4 space-x-2">
                {{-- Delete / Restore Modal --}}
                @if ($item->trashed())
                    <x-base-modal :id="'restoreModal-' . $item->id" title="Restore Kategori" triggerText="Restore"
                        triggerClass="font-medium text-green-600 dark:text-green-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to restore this category?
                        </p>
                        <form class="space-y-6" action="{{ route('kategori.restore', $item->id) }}" method="POST">
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
                        <form class="space-y-6" action="{{ route('kategori.forceDelete', $item->id) }}" method="POST">
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
                    <x-base-modal :id="'editModal-' . $item->id" title="Edit Kategori" triggerText="Edit"
                        triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        <form action="{{ route('kategori.update', $item->id) }}" method="POST"
                            id="updateForm-{{ $item->id }}"
                            onsubmit="this.querySelector('button[type=submit]').disabled = true;">
                            @csrf
                            @method('PUT')

                            <!-- Kode Input -->
                            <div>
                                <label for="kode-{{ $item->id }}"
                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                                <input type="text" name="kode" id="kode-{{ $item->id }}"
                                    value="{{ $item->kode }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    required>
                            </div>

                            <!-- Nama Input -->
                            <div class="mt-2">
                                <label for="nama-{{ $item->id }}"
                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                <input type="text" name="nama" id="nama-{{ $item->id }}"
                                    value="{{ $item->nama }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    required>
                            </div>

                            <!-- Status Toggle -->
                            <div class="mt-2" x-data="{ statusChecked: {{ $item->status == 'active' ? 'true' : 'false' }} }">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <label class="inline-flex items-center cursor-pointer mt-1">
                                    <input type="checkbox" name="status" id="status" value="active"
                                        class="sr-only peer" x-model="statusChecked" :checked="statusChecked">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                        x-text="statusChecked ? 'Aktif' : 'Nonaktif'">{{ $item->status == 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-6">
                                Simpan
                            </button>
                        </form>
                    </x-base-modal>


                    {{-- Delete Modal --}}
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

{{ $kategori->links() }}
