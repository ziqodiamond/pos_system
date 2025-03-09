<div x-data="{ addModal: false, step: 1 }" @open-add-modal.window="addModal = true">
    <!-- Modal -->
    <div x-show="addModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Tambah Barang</h2>
                <button @click="addModal = false">
                    <svg class="w-6 h-6 text-gray-800 hover:bg-gray-400 hover:rounded-full p-1 transition duration-200"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Step 1: Nama, Kode, Kategori -->
                <div x-show="step === 1" x-cloak>
                    <div>
                        <div>
                            <label for="nama"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                Barang</label>
                            <input type="text" id="nama" name="nama"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div>
                            <label for="kode"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                                Barang</label>
                            <input type="text" id="kode" name="kode"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div x-data="kategoriDropdown()" class="relative">
                            <label for="kategori"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>

                            <!-- Input untuk Mencari Kategori -->
                            <input type="text" id="kategori" x-ref="kategoriInput" x-model="search"
                                @focus="open = true" @input="checkValid()"
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

                </div>

                <!-- Step 2: Harga, Diskon, Pajak, Satuan -->
                <div x-show="step === 2" x-cloak>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Kolom Kiri (Harga) -->
                        <div>
                            <label for="harga_beli"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli</label>
                            <input type="number" id="harga_beli" name="harga_beli"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />

                            <label for="harga_pokok"
                                class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                                Pokok</label>
                            <input type="number" id="harga_pokok" name="harga_pokok"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />

                            <label for="harga_jual"
                                class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
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
                                <input type="text" x-ref="inputSatuan" x-model="search" @focus="open = true"
                                    @input="checkValid()"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Cari Satuan..." />

                                <input type="hidden" name="satuan_id" x-model="selectedId" />

                                <div class="absolute right-2 top-2">
                                    <a href="{{ route('satuan') }}" target="_blank"
                                        class="text-blue-500 text-lg font-bold">+</a>
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
                            <label
                                class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Pajak</label>
                            <div class="relative">
                                <select name="pajak_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Pilih Pajak</option>
                                    @foreach ($pajaks as $pajak)
                                        <option value="{{ $pajak->id }}">{{ $pajak->nama }}</option>
                                    @endforeach
                                </select>

                                <div class="absolute right-2 top-2">
                                    <a href="{{ route('pajak') }}" target="_blank"
                                        class="text-blue-500 text-lg font-bold">+</a>
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


                            <label for="diskon"
                                class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon
                                (%)</label>
                            <input type="number" id="diskon" name="diskon"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />

                            <label for="stok_minimal"
                                class="block mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok
                                Minimal</label>
                            <input type="number" id="stok_minimal" name="stok_minimal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>


                    </div>

                </div>

                <!-- Step 3: Konversi -->
                <div x-show="step === 3" x-cloak>
                    <div x-data="konversiData(@json($satuans))">
                        <!-- Judul -->
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Konversi Nilai Barang</h2>

                        <template x-for="(konversi, index) in konversis" :key="index">
                            <div class="flex gap-4 items-center">
                                <div class="w-1/2">
                                    <label for="satuan-konversi"
                                        class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Satuan Konversi
                                    </label>
                                    <select x-model="konversi.satuan_konversi"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Pilih Satuan Konversi</option>
                                        <template x-for="satuan in satuanList" :key="satuan.id">
                                            <option :value="satuan.nama" x-text="satuan.nama"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="w-1/3">
                                    <label for="nilai"
                                        class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Nilai Konversi
                                    </label>
                                    <input type="text" id="nilai" x-model="konversi.nilai"
                                        x-on:input="konversi.nilai = konversi.nilai.replace(/[^0-9]/g, '')"
                                        placeholder="Isi Nilai Konversi"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nilai konversi dalam
                                        satuan dasar</p>
                                </div>

                                <!-- Tombol hapus -->
                                <button type="button" @click="konversis.splice(index, 1)"
                                    x-show="konversis.length > 1"
                                    class="mt-6 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </div>
                        </template>

                        <!-- Tombol tambah konversi -->
                        <button type="button" @click="konversis.push({ satuan_konversi: '', nilai: '' })"
                            class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Tambah
                        </button>

                        <!-- Tombol simpan -->
                        <button type="button" @click="$refs.konversiInput.value = JSON.stringify(konversis)"
                            class="mt-3 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            Simpan Konversi
                        </button>
                        <input type="hidden" name="konversi" x-ref="konversiInput">
                    </div>

                    <script>
                        function konversiData(satuanList) {
                            return {
                                konversis: [{
                                    satuan_konversi: '',
                                    nilai: ''
                                }],
                                satuanList: satuanList
                            };
                        }
                    </script>

                </div>

                <!-- Step 4: Foto -->
                <div x-show="step === 4" x-cloak>
                    <div><label class="block">Foto Barang</label>
                        <input type="file" name="foto" class="w-full border p-2 rounded">
                        <!-- Display uploaded image preview -->
                        <img id="fotoPreview" class="mt-2 w-32 h-32 object-cover rounded">
                    </div>

                </div>

                <!-- Navigation Buttons -->
                <div class="mt-4 flex items-center justify-between w-full relative">
                    <!-- Tombol Kembali -->
                    <button type="button" @click="step--" class="px-4 py-2 bg-gray-500 text-white rounded w-20"
                        :class="step === 1 ? 'invisible' : 'visible'">
                        Kembali
                    </button>

                    <!-- Indikator Step -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-1">
                        <template x-for="i in 4">
                            <span class="w-2 h-2 rounded-full transition-all"
                                :class="step === i ? 'bg-blue-500 scale-110' : 'bg-gray-400'"></span>
                        </template>
                    </div>

                    <button type="button" @click="step++" class="px-4 py-2 bg-blue-500 text-white rounded w-20"
                        :class="step === 4 ? 'invisible' : 'visible'">
                        Lanjut
                    </button>

                </div>

                <!-- Simpan -->
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
</div>
