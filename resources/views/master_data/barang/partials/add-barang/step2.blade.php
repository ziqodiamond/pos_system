<div class="grid grid-cols-2 gap-4">
    <!-- Kolom Kiri (Harga) -->
    <div>
        <label for="harga_beli" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli</label>
        <input type="number" id="harga_beli" name="harga_beli"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />

        <label for="harga_pokok" class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Pokok</label>
        <input type="number" id="harga_pokok" name="harga_pokok"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />

        <label for="harga_jual" class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Jual</label>
        <input type="number" id="harga_jual" name="harga_jual"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />
    </div>

    <!-- Kolom Kanan (Satuan, Pajak, Diskon, Stok) -->
    <div>

        <!-- Satuan -->
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan
            Dasar</label>
        <div x-data="dropdownSearch(@json($satuans), 'satuan_id')" class="relative">
            <input type="text" x-ref="inputSatuan" x-model="search" @focus="open = true" @input="checkValid()"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Cari Satuan..." />

            <input type="hidden" name="satuan_id" x-model="selectedId" />

            <div class="absolute right-2 top-2">
                <a href="{{ route('satuan.index') }}" target="_blank" class="text-blue-500 text-lg font-bold">+</a>
            </div>

            <div x-show="open"
                class="absolute w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600"
                x-transition>
                <ul class="max-h-48 overflow-y-auto">
                    <template x-for="item in filteredOptions" :key="item.id">
                        <li class="px-3 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                            @click="selectOption(item.id, item.nama)">
                            <span x-text="item.nama"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <p x-show="!valid && search !== ''" class="text-red-500 text-sm mt-1">Satuan tidak
                valid!</p>
        </div>

        <!-- Pajak -->
        <label class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Pajak</label>
        <div class="relative">
            <select name="pajak_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Pilih Pajak</option>
                @foreach ($pajaks as $pajak)
                    <option value="{{ $pajak->id }}">{{ $pajak->nama }}</option>
                @endforeach
            </select>

            <div class="absolute right-2 top-2">
                <a href="{{ route('pajak.index') }}" target="_blank" class="text-blue-500 text-lg font-bold">+</a>
            </div>
        </div>

        <!-- Alpine.js untuk Dropdown Pencarian -->
        <script>
            function dropdownSearch(options, modelName) {
                return {
                    search: '',
                    open: false,
                    selectedId: null,
                    valid: false,
                    filteredOptions: options,

                    checkValid() {
                        let inputValue = this.search.trim();
                        let foundItem = options.find(option => option.nama.toLowerCase() === inputValue.toLowerCase());

                        if (foundItem) {
                            this.valid = true;
                            this.selectedId = foundItem.id;
                        } else {
                            this.valid = false;
                            this.selectedId = null;
                        }
                    },

                    selectOption(id, nama) {
                        this.search = nama;
                        this.selectedId = id;
                        this.valid = true;
                        this.open = false;
                    }
                };
            }
        </script>


        <label for="diskon" class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon
            (%)</label>
        <input type="number" id="diskon" name="diskon"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />

        <label for="stok_minimal" class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok
            Minimal</label>
        <input type="number" id="stok_minimal" name="stok_minimal"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />
    </div>


</div>
