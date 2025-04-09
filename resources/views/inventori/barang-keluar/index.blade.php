<x-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" x-data="barangKeluarForm()">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Form Barang Keluar</h2>
                        <p class="text-gray-600">Catat barang yang keluar dari inventori (expire, rusak, konsumsi, dll)
                        </p>
                    </div>

                    <form action="{{ route('barang-keluar.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Input hidden untuk menyimpan data -->
                        <input type="hidden" name="barang_id" x-model="selectedBarang.id">
                        <input type="hidden" name="nama_barang" x-model="selectedBarang.nama">
                        <input type="hidden" name="satuan_id" x-model="barangSatuanId">
                        <!-- Input hidden untuk kuantitas yang sudah dikonversi -->
                        <input type="hidden" name="kuantitas" x-model="kuantitasKonversi">
                        <!-- Input untuk tampilan kuantitas asli (yg diinput user) -->
                        <input type="hidden" name="kuantitas_display" x-model="kuantitas">

                        <!-- Grid layout untuk form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kolom kiri -->
                            <div class="space-y-6">
                                <!-- Pencarian Barang dengan Dropdown yang Dapat Dicari -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Barang</label>
                                    <div class="relative" x-data="{ open: false }">
                                        <input type="text" x-model="searchTerm" x-on:click="open = true"
                                            x-on:keydown.escape="open = false"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                            placeholder="Ketik kode atau nama barang...">
                                        <div x-show="open" x-on:click.away="open = false"
                                            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <template x-for="barang in filteredBarangs" :key="barang.id">
                                                <div x-on:click="selectBarang(barang); open = false"
                                                    class="cursor-pointer hover:bg-gray-100 px-4 py-2">
                                                    <div class="flex justify-between">
                                                        <span>
                                                            <span class="font-medium" x-text="barang.kode"></span> -
                                                            <span x-text="barang.nama"></span>
                                                        </span>
                                                        <span class="text-gray-500"
                                                            x-text="'Stok: ' + barang.stok"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="filteredBarangs.length === 0"
                                                class="px-4 py-2 text-sm text-gray-500">
                                                Tidak ada barang yang ditemukan
                                            </div>
                                        </div>
                                    </div>
                                    @error('barang_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Detail Barang (Tampilan Info yang Lebih Lengkap) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Detail Barang</label>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-md text-sm">
                                        <div x-show="selectedBarang.id">
                                            <div class="flex justify-between mb-1">
                                                <p class="font-medium text-gray-900"
                                                    x-text="selectedBarang.kode + ' - ' + selectedBarang.nama"></p>
                                            </div>
                                            <p class="text-gray-600 mb-1"
                                                x-text="'Stok Tersedia: ' + selectedBarang.stok + ' ' + selectedSatuanDasar.nama">
                                            </p>
                                        </div>
                                        <p x-show="!selectedBarang.id" class="text-gray-500 italic">Silahkan pilih
                                            barang
                                            terlebih dahulu</p>
                                    </div>
                                </div>

                                <!-- Tanggal Keluar -->
                                <div>
                                    <label for="tanggal_keluar" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Keluar
                                    </label>
                                    <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                        value="{{ date('Y-m-d') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required>
                                    @error('tanggal_keluar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom kanan -->
                            <div class="space-y-6">
                                <!-- Kuantitas dengan Label Satuan Dinamis -->
                                <div>
                                    <label for="kuantitas_input" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kuantitas
                                    </label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <input type="number" id="kuantitas_input" min="1" x-model="kuantitas"
                                            x-on:input="calculateTotal()"
                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300"
                                            required :disabled="!selectedBarang.id">
                                        <span
                                            class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <span x-text="selectedSatuan.nama || 'Satuan'"></span>
                                        </span>
                                    </div>
                                    <!-- Info nilai konversi -->
                                    <p class="mt-1 text-xs text-gray-500" x-show="nilaiKonversi > 1">
                                        <span
                                            x-text="'1 ' + selectedSatuan.nama + ' = ' + nilaiKonversi + ' ' + selectedSatuanDasar.nama"></span>
                                        <br>
                                        <span
                                            x-text="'Total kuantitas: ' + kuantitasKonversi + ' ' + selectedSatuanDasar.nama"></span>
                                    </p>
                                    @error('kuantitas')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pilih Satuan (untuk konversi) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Satuan</label>
                                    <select x-model="selectedSatuanId" x-on:change="updateSatuanDanTotal()"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                        :disabled="!selectedBarang.id">
                                        <option value="">-- Pilih Satuan --</option>
                                        <template x-for="satuan in availableSatuans" :key="satuan.id">
                                            <option :value="satuan.id" x-text="satuan.nama"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Jenis Barang Keluar -->
                                <div>
                                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Barang Keluar
                                    </label>
                                    <select id="jenis" name="jenis"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                        required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="expire">Barang Expire</option>
                                        <option value="rusak">Barang Rusak</option>
                                        <option value="konsumsi">Konsumsi Internal</option>
                                        <option value="retur">Retur ke Supplier</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('jenis')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan (full width) -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                                Keterangan
                            </label>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Tambahkan keterangan detail tentang barang keluar..."></textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol aksi -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('barang-keluar.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :disabled="!isFormValid" :class="{ 'opacity-50 cursor-not-allowed': !isFormValid }">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function barangKeluarForm() {
            // Data dari server
            const barangs = @json($barangs);
            const satuans = @json($satuans);
            const konversis = @json($konversis);

            return {
                // State untuk input dan pengelolaan data
                selectedBarang: {
                    id: '',
                    kode: '',
                    nama: '',
                    stok: 0,
                    satuan_id: '',
                    kategori: '',
                    lokasi: ''
                },
                barangSatuanId: '', // Hidden input untuk satuan_id asli barang
                searchTerm: '',
                kuantitas: '', // Kuantitas yang diinput user
                kuantitasKonversi: '', // Kuantitas yang sudah dikonversi ke satuan dasar
                selectedSatuanId: '',
                selectedSatuan: {
                    id: '',
                    nama: ''
                },
                selectedSatuanDasar: {
                    id: '',
                    nama: ''
                },
                nilaiKonversi: 1,

                // Computed properties
                get filteredBarangs() {
                    if (!this.searchTerm) return barangs;

                    const searchLower = this.searchTerm.toLowerCase();
                    return barangs.filter(barang =>
                        barang.nama.toLowerCase().includes(searchLower) ||
                        (barang.kode && barang.kode.toLowerCase().includes(searchLower))
                    );
                },

                get availableSatuans() {
                    if (!this.selectedBarang.id) return [];

                    // Buat array untuk menyimpan semua satuan yang tersedia
                    let satuanOptions = [];

                    // Tambahkan satuan dasar barang sebagai opsi pertama
                    const satuanDasar = satuans.find(s => s.id == this.selectedBarang.satuan_id);

                    if (satuanDasar) {
                        satuanOptions.push({
                            id: satuanDasar.id,
                            nama: satuanDasar.nama,
                            nilaiKonversi: 1 // Satuan dasar selalu 1
                        });
                    }

                    // Tambahkan satuan konversi dari satuan dasar ke satuan lain yang bisa dipakai
                    const relevantKonversi = konversis.filter(k =>
                        k.barang_id == this.selectedBarang.id &&
                        k.satuan_tujuan_id == this.selectedBarang
                        .satuan_id // hanya ambil konversi ke satuan dasar barang
                    );

                    relevantKonversi.forEach(konversi => {
                        const satuanAsal = satuans.find(s => s.id == konversi.satuan_id);

                        if (satuanAsal) {
                            satuanOptions.push({
                                id: satuanAsal.id,
                                nama: satuanAsal.nama,
                                nilaiKonversi: konversi.nilai_konversi
                            });
                        }
                    });

                    return satuanOptions;
                },

                get isFormValid() {
                    return this.selectedBarang.id &&
                        this.kuantitas > 0 &&
                        this.selectedSatuanId;
                },

                // Methods
                init() {
                    // Inisialisasi komponen
                },

                selectBarang(barang) {
                    this.selectedBarang = barang;
                    this.barangSatuanId = barang.satuan_id;

                    // Reset dan atur satuan dasar
                    this.selectedSatuanId = barang.satuan_id;

                    // Temukan informasi satuan dasar
                    const satuanDasar = satuans.find(s => s.id == barang.satuan_id);
                    if (satuanDasar) {
                        this.selectedSatuanDasar = {
                            id: satuanDasar.id,
                            nama: satuanDasar.nama
                        };
                        this.selectedSatuan = {
                            id: satuanDasar.id,
                            nama: satuanDasar.nama
                        };
                    }

                    this.searchTerm = barang.nama;
                    this.nilaiKonversi = 1;
                    this.calculateTotal();
                },

                updateSatuanDanTotal() {
                    if (!this.selectedSatuanId) {
                        this.selectedSatuan = {
                            id: '',
                            nama: ''
                        };
                        this.nilaiKonversi = 1;
                        return;
                    }

                    // Cari satuan yang dipilih dari available satuans
                    const satuanOption = this.availableSatuans.find(s => s.id == this.selectedSatuanId);

                    if (satuanOption) {
                        this.selectedSatuan = {
                            id: satuanOption.id,
                            nama: satuanOption.nama
                        };

                        this.nilaiKonversi = satuanOption.nilaiKonversi;
                    }

                    this.calculateTotal();
                },

                calculateTotal() {
                    const kuantitas = parseFloat(this.kuantitas) || 0;

                    // Hitung kuantitas terkonversi
                    this.kuantitasKonversi = Math.round(kuantitas * this.nilaiKonversi);
                }
            };
        }
    </script>
</x-layout>
