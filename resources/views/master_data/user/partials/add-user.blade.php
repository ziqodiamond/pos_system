<!-- Modal -->
<div x-show="addModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl" @click.outside="addModal = false">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Tambah User</h2>
            <button @click="addModal = false">
                <svg class="w-6 h-6 text-gray-800 hover:bg-gray-400 hover:rounded-full p-1 transition duration-200"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18 17.94 6M18 18 6.06 6" />
                </svg>
            </button>
        </div>

        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Pilih Role</option>
                        <option value="kasir">Kasir</option>
                        <option value="gudang">Gudang</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="mt-4 w-full px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Simpan
            </button>
            <button type="button" @click="addModal = false"
                class="mt-2 w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Tutup
            </button>
        </form>
    </div>
</div>
