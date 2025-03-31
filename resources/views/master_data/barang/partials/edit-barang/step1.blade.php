<div>
    <div class="mb-4">
        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Barang</label>
        <input type="text" id="nama" name="nama" value="{{ $item->nama }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />
    </div>
    <div class="mb-4">
        <label for="kode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Barang</label>
        <input type="text" id="kode" name="kode" value="{{ $item->kode }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />
    </div>
    <div x-data="dropdownEdit('kategori', '{{ $item->kategori?->id }}')" x-init="init()" class="mb-4">
        <label for="kategori" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
        <div class="relative">
            <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
                placeholder="{{ $item->kategori?->nama ?? 'Cari Kategori...' }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            <ul x-show="open" @click.outside="close()" x-cloak
                class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                <template x-for="kategori in filteredKategoris" :key="kategori.id">
                    <li @click="select(kategori)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                        <span x-text="kategori.nama"></span>
                    </li>
                </template>
            </ul>
            <input type="hidden" name="kategori_id" :value="selectedKategoriId">
        </div>
    </div>

    <!-- Toggle Status -->
    <div class="mt-4 mb-4" x-data="{ statusChecked: true }">
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
</div>
