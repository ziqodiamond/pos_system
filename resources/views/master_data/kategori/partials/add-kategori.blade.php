<!-- Modal -->
<div x-show="addModal" @click.away="addModal = false"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Tambah Kategori</h2>
            <button @click="addModal = false">
                <svg class="w-6 h-6 text-gray-800 hover:bg-gray-400 hover:rounded-full p-1 transition duration-200"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18 17.94 6M18 18 6.06 6" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kategori.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700">Kode</label>
                <input type="text" name="kode" id="kode"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mt-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" id="nama"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mt-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="mt-4 w-full px-4 py-2 bg-green-500 text-white rounded">
                Simpan
            </button>
            <button type="button" @click="addModal = false"
                class="mt-2 w-full px-4 py-2 bg-red-500 text-white rounded">
                Tutup
            </button>
        </form>

    </div>
</div>
