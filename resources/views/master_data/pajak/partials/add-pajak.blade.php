<!-- Modal -->
<div x-show="addModal" @click.away="addModal = false"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Tambah Pajak</h2>
            <button @click="addModal = false">
                <svg class="w-6 h-6 text-gray-800 hover:bg-gray-400 hover:rounded-full p-1 transition duration-200"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18 17.94 6M18 18 6.06 6" />
                </svg>
            </button>
        </div>

        <form action="{{ route('pajak.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="persen" class="block text-sm font-medium text-gray-700">Persen</label>
                <div class="mt-1 relative flex items-center">
                    <input type="number" name="persen" id="persen" placeholder="0"
                        class="block w-full border-gray-300 rounded-md shadow-sm pr-8">
                    <span class="absolute right-3 text-gray-500">%</span>
                </div>
            </div>
            <div class="mt-4" x-data="{ statusChecked: true }">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <label class="inline-flex items-center cursor-pointer mt-2">
                    <input type="checkbox" name="status" id="status" value="aktif" class="sr-only peer"
                        @change="statusChecked = $event.target.checked" x-model="statusChecked" checked>
                    <div
                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                        x-text="statusChecked ? 'Aktif' : 'Nonaktif'">Aktif</span>
                </label>
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
