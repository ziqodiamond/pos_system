<div>
    <div>
        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
            Barang</label>
        <input type="text" id="nama" name="nama"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />
    </div>
    <div>
        <label for="kode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
            Barang</label>
        <input type="text" id="kode" name="kode"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required />
    </div>
    <div x-data="kategoriDropdown()" class="relative">
        <label for="kategori" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>

        <!-- Input untuk Mencari Kategori -->
        <input type="text" id="kategori" x-ref="kategoriInput" x-model="search" @focus="open = true"
            @input="checkValid()"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cari Kategori..." />

        <!-- Input Hidden untuk ID Kategori -->
        <input type="hidden" name="kategori_id" x-model="selectedId" />

        <!-- Dropdown List -->
        <div x-show="open"
            class="absolute w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600"
            x-transition>
            <ul class="max-h-48 overflow-y-auto">
                @foreach ($kategoris as $kategori)
                    <li class="px-3 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                        @click="selectKategori('{{ $kategori->id }}', '{{ $kategori->nama }}')">
                        {{ $kategori->nama }}
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Pesan Error -->
        <p x-show="!valid && search !== ''" class="text-red-500 text-sm mt-1">Kategori tidak valid!
        </p>
    </div>

    <script>
        function kategoriDropdown() {
            return {
                search: '',
                open: false,
                selectedId: null,
                valid: false,
                kategoriList: @json($kategoris->pluck('nama')),

                checkValid() {
                    // Ambil nilai input kategori
                    let inputValue = this.$refs.kategoriInput.value;

                    // Cek apakah input ada di daftar kategori
                    if (this.kategoriList.includes(inputValue)) {
                        this.valid = true;
                    } else {
                        this.valid = false;
                        this.selectedId = null;
                    }
                },

                selectKategori(id, nama) {
                    this.search = nama;
                    this.selectedId = id;
                    this.valid = true;
                    this.open = false;
                }
            };
        }
    </script>


</div>
