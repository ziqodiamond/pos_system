<div class="grid grid-cols-2 gap-4">
    <!-- Kolom Kiri (Harga) -->
    <div>
        <label for="harga_beli" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_beli" x-data
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');"
                x-on:change="document.getElementById('harga_beli').value = $event.target.value.replace(/\./g, '')"
                value="{{ number_format($item->harga_beli, 0, ',', '.') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                min="0" required />
        </div>
        <input type="hidden" id="harga_beli" name="harga_beli" value="{{ $item->harga_beli }}">

        <label for="harga_pokok" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Pokok</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_pokok" x-data
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');"
                x-on:change="document.getElementById('harga_pokok').value = $event.target.value.replace(/\./g, '')"
                value="{{ number_format($item->harga_pokok, 0, ',', '.') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                min="0" required />
        </div>
        <input type="hidden" id="harga_pokok" name="harga_pokok" value="{{ $item->harga_pokok }}">


        <label for="harga_jual" class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Jual</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_jual" x-data
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');"
                x-on:change="document.getElementById('harga_jual').value = $event.target.value.replace(/\./g, '')"
                value="{{ number_format($item->harga_jual, 0, ',', '.') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                min="0" required />
        </div>
        <input type="hidden" id="harga_jual" name="harga_jual" value="{{ $item->harga_jual }}">
    </div>

    <!-- Kolom Kanan (Satuan, Pajak, Diskon, Stok) -->
    <div class="space-y-4">
        <div x-data="dropdownEdit('pajak')">
            <label for="pajak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pajak</label>
            <div class="relative">
                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
                    placeholder="Cari Pajak..." value="{{ $item->pajak->nama }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />


                <ul x-show="open" @click.outside="close()" x-cloak
                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                    <template x-for="pajak in filteredPajaks" :key="pajak.id">
                        <li @click="select(pajak)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <span x-text="pajak.nama"></span>
                        </li>
                    </template>
                    <!-- Kalau datanya kosong -->
                    <li x-show="filteredPajaks.length === 0" class="px-2 py-1 text-gray-500 text-sm">
                        Data tidak ditemukan
                    </li>
                </ul>
                <input type="hidden" name="pajak_id" value="{{ $item->pajak->id }}" :value="selected.id">
            </div>
        </div>

        <div x-data="dropdownEdit('satuan')">
            <label for="pajak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                Satuan</label>
            <div class="relative">
                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
                    placeholder="Cari Satuan..." value="{{ $item->satuan->nama }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


                <!-- List Dropdown -->
                <ul x-show="open" @click.outside="close()" x-cloak
                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                    <template x-for="item in filteredSatuans" :key="item.id">
                        <li @click="select(item)" class="cursor-pointer px-2 py-1 hover:bg-gray-100">
                            <span x-text="item.nama"></span>
                        </li>
                    </template>
                    <!-- Kalau datanya kosong -->
                    <li x-show="filteredSatuans.length === 0" class="px-2 py-1 text-gray-500 text-sm">
                        Data tidak ditemukan
                    </li>
                </ul>
                <input type="hidden" name="satuan_id" value="{{ $item->satuan->id }}" :value="selected.id">
            </div>
        </div>


        <div>
            <label for="diskon" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon
                (%)</label>
            <input type="number" id="diskon" name="diskon" min="0" max="100"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
        </div>

        <div>
            <label for="stok_minimal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok
                Minimal</label>
            <input type="number" id="stok_minimal" name="stok_minimal" min="0"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
        </div>
    </div>

</div>
