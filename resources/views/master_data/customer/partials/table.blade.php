<x-master-table :route="'customer'" :items="$customers" :columns="['Kode', 'Nama', 'Alamat', 'Telepon', 'Email', 'Tanggal Lahir', 'Status', '']">
    @forelse ($customers as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->kode }}
            </th>
            <td class="px-6 py-4">{{ $item->nama }}</td>
            <td class="px-6 py-4">{{ $item->alamat }}</td>
            <td class="px-6 py-4">{{ $item->telepon }}</td>
            <td class="px-6 py-4">{{ $item->email }}</td>
            <td class="px-6 py-4">{{ $item->tanggal_lahir }}</td>
            <td class="px-6 py-4">{{ $item->status }}</td>
            <td class="flex items-center px-6 py-4 space-x-2">
                @if ($item->trashed())
                    <x-base-modal :id="'restoreModal-' . $item->id" title="Restore Kategori" triggerText="Pulihkan"
                        triggerClass="font-medium text-green-600 dark:text-green-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin memulihkan item {{ $item->nama }}?
                        </p>
                        <form class="space-y-6" action="{{ route('customer.restore', $item->id) }}" method="POST">
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
                        <form class="space-y-6" action="{{ route('customer.forceDelete', $item->id) }}" method="POST">
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
                    <x-base-modal :id="'editModal-' . $item->id" title="Edit Item" triggerText="Edit"
                        triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        <form action="{{ route('customer.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Nama -->
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                                    <input type="text" name="nama" id="nama"
                                        value="{{ old('nama', $item->nama) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal
                                        Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $item->tanggal_lahir) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>

                                <!-- Telepon -->
                                <div>
                                    <label for="telepon"
                                        class="block text-sm font-medium text-gray-700">Telepon</label>
                                    <input type="text" name="telepon" id="telepon"
                                        value="{{ old('telepon', $item->telepon) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $item->email) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>

                                <!-- Alamat -->
                                <div class="col-span-2">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea name="alamat" id="alamat" rows="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat', $item->alamat) }}</textarea>
                                </div>

                                <!-- Status -->
                                <div x-data="{ statusChecked: {{ old('status', $item->status) === 'active' ? 'true' : 'false' }} }">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <label class="inline-flex items-center cursor-pointer mt-2">
                                        <input type="checkbox" name="status" id="status" value="active"
                                            class="sr-only peer" @change="statusChecked = $event.target.checked"
                                            x-model="statusChecked" :checked="statusChecked">
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                            x-text="statusChecked ? 'Aktif' : 'Nonaktif'">Aktif</span>
                                    </label>
                                </div>

                            </div>

                            <button type="submit"
                                class="mt-6 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
                            </button>
                        </form>
                    </x-base-modal>

                    {{-- Delete Modal --}}
                    <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Item" triggerText="Hapus"
                        triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Apakah anda yakin menghapus customer {{ $item->nama }}?
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
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="8">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $customers->links() }}
