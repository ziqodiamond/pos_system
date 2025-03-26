<x-base-modal :id="'editModal-' . $item->id" title="Edit Barang" triggerText="Edit"
    triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
    <form action="{{ route('barang.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Step 1: Nama, Kode, Kategori -->
        <div x-show="step === 1" x-cloak>
            @include('master_data.barang.partials.edit-barang.step1')
        </div>

        <!-- Step 2: Harga, Diskon, Pajak, Satuan -->
        <div x-show="step === 2" x-cloak>
            @include('master_data.barang.partials.edit-barang.step2')
        </div>

        <!-- Step 3: Konversi -->
        <div x-show="step === 3" x-cloak>
            @include('master_data.barang.partials.edit-barang.step3')
        </div>

        <!-- Step 4: Foto -->
        <div x-show="step === 4" x-cloak>
            @include('master_data.barang.partials.edit-barang.step4')
        </div>

        <script>
            function dropdownEdit(type) {
                return {
                    open: false,
                    search: '',
                    selected: {},
                    kategoris: type === 'kategori' ? @json($kategoris) : [],
                    pajaks: type === 'pajak' ? @json($pajaks) : [],
                    satuans: type === 'satuan' ? @json($satuans) : [],
                    satuanKonversis: type === 'satuanKonversi' ? @json($satuanKonversis) : [],
                    satuanTujuans: type === 'satuanTujuan' ? @json($satuans) : [],

                    watchSearch() {
                        this.open = true; // Membuka dropdown saat ada input
                    },
                    get filteredKategoris() {
                        return this.search.trim() === '' ? this.kategoris : this.kategoris.filter(kategori => kategori.nama
                            .toLowerCase().includes(this.search.toLowerCase()));
                    },
                    init() {
                        console.log('Menginisialisasi dropdown untuk tipe:', type);
                        if (type === 'kategori') {
                            const id = {{ old('kategori_id', $item->kategori_id ?? 'null') }};
                            this.selected = this.kategoris.find(kategori => kategori.id === id) || {};
                            this.search = this.selected.nama || ''; // Mengatur pencarian ke nama kategori yang dipilih
                        }
                        // Logika ser upa untuk tipe lainnya (pajak, satuan, dll.)
                    },
                    select(item) {
                        this.selected = item; // Menetapkan item yang dipilih
                        this.search = item.nama; // Mengatur pencarian ke nama item yang dipilih
                        this.close(); // Menutup dropdown setelah pemilihan
                    },
                    close() {
                        this.open = false; // Menutup dropdown
                    },
                    openDropdown() {
                        this.open = true; // Membuka dropdown
                    }
                };
            }
        </script>
        {{-- <script>
            function dropdownEdit(type) {
                return {
                    open: false,
                    search: '',
                    watchSearch() {
                        this.open = true;
                    },
                    selected: {},
                    kategoris: type === 'kategori' ? @json($kategoris) : [],
                    pajaks: type === 'pajak' ? @json($pajaks) : [],
                    satuans: type === 'satuan' ? @json($satuans) : [],
                    satuanKonversis: type === 'satuanKonversi' ? @json($satuanKonversis) : [],
                    satuanTujuans: type === 'satuanTujuan' ? @json($satuans) : [],

                    get filteredKategoris() {
                        return this.search.trim() === '' ? this.kategoris : this.kategoris.filter(kategori => kategori.nama
                            .toLowerCase().includes(this.search.toLowerCase()));
                    },
                    get filteredPajaks() {
                        return this.search.trim() === '' ? this.pajaks : this.pajaks.filter(pajak => pajak.nama
                            .toLowerCase().includes(this.search.toLowerCase()));
                    },
                    get filteredSatuans() {
                        return this.search.trim() === '' ? this.satuans : this.satuans.filter(satuan => satuan.nama
                            .toLowerCase().includes(this.search.toLowerCase()));
                    },
                    get filteredSatuanKonversis() {
                        return this.search.trim() === '' ?
                            this.satuanKonversis :
                            this.satuanKonversis.filter(satuan => satuan.nama.toLowerCase().includes(this
                                .search.toLowerCase()));
                    },
                    get filteredSatuanTujuans() {
                        return this.search.trim() === '' ? this.satuanTujuans : this.satuanTujuans.filter(satuan =>
                            satuan.nama.toLowerCase().includes(this.search.toLowerCase()));
                    },

                    init() {
                        console.log('Initializing dropdown for type:', type);
                        console.log('Available categories:', this.kategoris);

                        if (type === 'kategori') {
                            const id = {{ old('kategori_id', $item->kategori_id ?? 'null') }};
                            console.log('Searching for category ID:', id);
                            this.selected = this.kategoris.find(kategori => kategori.id === id) || {};
                            console.log('Selected category:', this.selected);
                            this.search = this.selected.nama || ''; // Set search ke nama kategori yang dipilih
                        } else if (type === 'pajak') {
                            const id = {{ old('pajak_id', $item->pajak_id ?? 'null') }};
                            console.log('Searching for tax ID:', id);
                            this.selected = this.pajaks.find(pajak => pajak.id === id) || {};
                            console.log('Selected tax:', this.selected);
                            this.search = this.selected.nama || ''; // Set search ke nama pajak yang dipilih
                        } else if (type === 'satuan') {
                            const id = {{ old('satuan_id', $item->satuan_id ?? 'null') }};
                            console.log('Searching for unit ID:', id);
                            this.selected = this.satuans.find(satuan => satuan.id === id) || {};
                            console.log('Selected unit:', this.selected);
                            this.search = this.selected.nama || ''; // Set search ke nama satuan yang dipilih
                        } else if (type === 'satuanKonversi') {
                            const id = {{ old('satuan_konversi_id', $item->satuan_konversi_id ?? 'null') }};
                            console.log('Searching for conversion unit ID:', id);
                            this.selected = this.satuanKonversis.find(satuanKonversi => satuanKonversi.id === id) || {};
                            console.log('Selected conversion unit:', this.selected);
                            this.search = this.selected.nama || ''; // Set search ke nama satuan konversi yang dipilih
                        } else if (type === 'satuanTujuan') {
                            const id = {{ old('satuan_tujuan_id', $item->satuan_tujuan_id ?? 'null') }};
                            console.log('Searching for target unit ID:', id);
                            this.selected = this.satuanTujuans.find(satuanTujuan => satuanTujuan.id === id) || {};
                            console.log('Selected target unit:', this.selected);
                            this.search = this.selected.nama || ''; // Set search ke nama satuan tujuan yang dipilih
                        }
                    },

                    select(item) {
                        this.selected = item;
                        this.search = item.nama;
                        this.close();
                    },
                    close() {
                        this.open = false;
                    },
                    openDropdown() {
                        this.open = true;
                    }
                };
            }
        </script> --}}

        <!-- Navigation Buttons -->
        <div class="mt-4 flex items-center justify-between w-full relative">
            <!-- Tombol Kembali -->
            <button type="button" @click="step--"
                class="flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded w-28"
                :class="step === 1 ? 'invisible' : 'visible'">
                <svg class="w-6 h-6 text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14M5 12l4-4m-4 4 4 4" />
                </svg>
                <span class="text-sm">Kembali</span>
            </button>

            <!-- Indikator Step -->
            <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-1">
                <template x-for="i in 4">
                    <span class="w-2 h-2 rounded-full transition-all"
                        :class="step === i ? 'bg-blue-500 scale-110' : 'bg-gray-400'"></span>
                </template>
            </div>

            <button type="button" @click="step++"
                class="flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded w-28"
                :class="step === 4 ? 'invisible' : 'visible'">

                <span class="text-sm">Lanjut</span>
                <svg class="w-6 h-6 text-white ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
            </button>
        </div>

        <!-- Simpan -->
        <button type="submit"
            class="mt-4 w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded flex items-center justify-center">
            <svg class="w-5 h-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 16 16">
                <path d="M11 2H9v3h2z" />
                <path
                    d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
            </svg>
            <span class="text-sm">Simpan</span>
        </button>

        <button type="button" @click="addModal = false"
            class="mt-2 w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center justify-center">
            <svg class="w-5 h-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18 17.94 6M18 18 6.06 6" />
            </svg>
            <span class="text-sm">Tutup</span>
        </button>
    </form>
</x-base-modal>
