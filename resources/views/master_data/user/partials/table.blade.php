<x-master-table :route="'user'" :items="$users" :columns="['Kode', 'Name', 'Email', 'Role', '']">
    @forelse ($users as $item)
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
            <td class="px-6 py-4">{{ $item->name }}</td>
            <td class="px-6 py-4">{{ $item->email }}</td>
            <td class="px-6 py-4">{{ ucfirst($item->role) }}</td>
            <td class="flex items-center px-6 py-4 space-x-2">

                <x-base-modal :id="'editModal-' . $item->id" title="Edit User" triggerText="Edit"
                    triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                    <form action="{{ route('user.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $item->name) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $item->email) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password
                                    (kosongkan jika tidak ingin mengubah)
                                </label>
                                <input type="password" name="password" id="password"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select name="role" id="role"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="kasir" {{ $item->role === 'kasir' ? 'selected' : '' }}>Kasir
                                    </option>
                                    <option value="gudang" {{ $item->role === 'gudang' ? 'selected' : '' }}>Gudang
                                    </option>
                                    <option value="admin" {{ $item->role === 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="super_admin" {{ $item->role === 'super_admin' ? 'selected' : '' }}>
                                        Super Admin</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-6 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                    </form>
                </x-base-modal>

                <x-base-modal :id="'deleteModal-' . $item->id" title="Delete User" triggerText="Hapus"
                    triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Apakah anda yakin menghapus user {{ $item->name }}?
                    </p>
                    <form class="space-y-6" action="{{ route('user.destroy', $item->id) }}" method="POST">
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
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="4">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $users->links() }}
