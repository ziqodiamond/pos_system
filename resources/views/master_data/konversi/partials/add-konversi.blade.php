<!-- Modal -->
<div x-show="addModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl" @click.outside="addModal = false">

        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Tambah Konversi</h2>
            <button @click="addModal = false">
                <svg class="w-6 h-6 text-gray-800 hover:bg-gray-400 hover:rounded-full p-1 transition duration-200"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M18 18L6 6" />
                </svg>
            </button>
        </div>

        <!-- Form Tambah Konversi -->
        <form action="{{ route('konversi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Satuan Konversi Component -->
            <div class="mt-4" x-data="dropdownKonversi()">
                <label for="satuan_konversi"
                    class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan Konversi</label>
                <div class="relative">
                    <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                        autocomplete="off" placeholder="Cari Satuan Konversi..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <ul x-show="open" @click.outside="close()" x-cloak
                        class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                        <template x-for="satuan in filteredSatuan" :key="satuan.id">
                            <li @click="select(satuan)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                <span x-text="satuan.nama"></span>
                            </li>
                        </template>
                    </ul>
                    <input type="hidden" name="satuan_konversi_id" :value="selected.id">
                </div>
            </div>

            <!-- Satuan Dasar Component -->
            <div class="mt-4" x-data="dropdownDasar()">
                <label for="satuan_dasar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan
                    Dasar</label>
                <div class="relative">
                    <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                        autocomplete="off" placeholder="Cari Satuan Dasar..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    <ul x-show="open" @click.outside="close()" x-cloak
                        class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                        <template x-for="satuan in filteredSatuan" :key="satuan.id">
                            <li @click="select(satuan)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                <span x-text="satuan.nama"></span>
                            </li>
                        </template>
                    </ul>
                    <input type="hidden" name="satuan_dasar_id" :value="selected.id">
                </div>
            </div>

            <script></script>



            <!-- Input Nilai Konversi -->
            <div class="mt-4">
                <label for="nilai_konversi" class="block text-sm font-medium text-gray-700">Nilai
                    Konversi</label>
                <input type="number" step="0.01" name="nilai_konversi" id="nilai_konversi"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <p class="mt-1 text-sm text-gray-500">
                    Masukkan jumlah satuan dasar yang setara dengan 1 satuan konversi.
                    Contoh: Jika 1 Lusin = 12 Pcs, maka nilai konversinya adalah 12.
                </p>
            </div>

            <!-- Tombol Simpan dan Tutup -->
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="addModal = false" class="px-4 py-2 bg-red-500 text-white rounded">
                    Tutup
                </button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>



    </div>
</div>
