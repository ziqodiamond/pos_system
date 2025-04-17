<div x-data="satuanUkurData()">
    <!-- Header -->
    <div class="flex items-center gap-2 mb-4">
        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M12 4a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm-2.952.462c-.483.19-.868.432-1.19.71-.363.315-.638.677-.831.93l-.106.14c-.21.268-.36.418-.574.527C6.125 6.883 5.74 7 5 7a1 1 0 0 0 0 2c.364 0 .696-.022 1-.067v.41l-1.864 4.2a1.774 1.774 0 0 0 .821 2.255c.255.133.538.202.825.202h2.436a1.786 1.786 0 0 0 1.768-1.558 1.774 1.774 0 0 0-.122-.899L8 9.343V8.028c.2-.188.36-.38.495-.553.062-.079.118-.15.168-.217.185-.24.311-.406.503-.571a1.89 1.89 0 0 1 .24-.177A3.01 3.01 0 0 0 11 7.829V20H5.5a1 1 0 1 0 0 2h13a1 1 0 1 0 0-2H13V7.83a3.01 3.01 0 0 0 1.63-1.387c.206.091.373.19.514.29.31.219.532.465.811.78l.025.027.02.023v1.78l-1.864 4.2a1.774 1.774 0 0 0 .821 2.255c.255.133.538.202.825.202h2.436a1.785 1.785 0 0 0 1.768-1.558 1.773 1.773 0 0 0-.122-.899L18 9.343v-.452c.302.072.633.109 1 .109a1 1 0 1 0 0-2c-.48 0-.731-.098-.899-.2-.2-.12-.363-.293-.651-.617l-.024-.026c-.267-.3-.622-.7-1.127-1.057a5.152 5.152 0 0 0-1.355-.678 3.001 3.001 0 0 0-5.896.04Z"
                    clip-rule="evenodd" />
            </svg>

        </div>
        <h2 class="text-slate-700 font-medium">Konversi Satuan Barang</h2>
    </div>

    <!-- Penjelasan Form -->
    <p class="text-xs text-gray-600 mb-4">
        Form ini digunakan untuk mengonversi satuan barang. Jumlah selalu 1, yang menunjukkan satuan konversi.
        Nilai yang Anda masukkan adalah jumlah satuan tujuan yang setara dengan satu satuan konversi.
        Contoh: Jika Anda mengonversi "box" ke "pcs" dan memasukkan nilai "20", maka 1 box setara dengan 20 pcs.
    </p>

    <!-- Tabel Konversi -->
    <div>
        <!-- Table Header -->
        <div class="grid grid-cols-5 gap-2 mb-2 text-xs font-medium text-slate-600">
            <div>Jumlah</div>
            <div>Satuan Konversi</div>
            <div>Nilai</div>
            <div>Satuan Tujuan</div>
            <div class="text-center">#</div>
        </div>

        <!-- Konversi Items Container -->
        <div class="h-[190px] max-h-[190px] overflow-y-auto">
            <template x-for="(konversi, index) in konversis" :key="index">
                <div class="grid grid-cols-5 gap-2 mb-2 konversi-item">
                    <!-- Jumlah -->
                    <div>
                        <input type="number" name="jumlah[]" x-model="konversi.jumlah" readonly
                            class="w-full bg-gray-100 border border-gray-200 rounded px-2 py-1 text-sm" placeholder="1">
                    </div>

                    <!-- Satuan Konversi -->
                    <div x-data="dropdown('satuanKonversi')" class="relative">
                        <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                            autocomplete="off" placeholder="Cari Satuan..."
                            class="w-full bg-gray-100 border border-gray-200 rounded px-2 py-1 text-sm hover:placeholder-shown"
                            title="Cari Satuan Konversi">
                        <ul x-show="open" @click.outside="close()" x-cloak
                            class="absolute z-50 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                            <template x-for="satuan in filteredSatuanKonversis" :key="satuan.id">
                                <li @click="select(satuan)" class="cursor-pointer px-2 py-1 hover:bg-gray-100">
                                    <span x-text="satuan.nama"></span>
                                </li>
                            </template>
                            <li x-show="filteredSatuanKonversis.length === 0" class="px-2 py-1 text-gray-500 text-sm">
                                Data
                                tidak ditemukan</li>
                        </ul>
                        <input type="hidden" name="satuan_konversi_id[]" :value="selected.id">
                    </div>

                    <!-- Nilai -->
                    <div>
                        <input type="text" name="nilai[]" x-model="konversi.nilai"
                            @input="if (parseFloat($event.target.value) < 0) { $event.target.value = 0; } formatNilai(index)"
                            class="w-full bg-gray-100 border border-gray-200 rounded px-2 py-1 text-sm"
                            placeholder="Nilai...">
                    </div>

                    <!-- Satuan Tujuan -->
                    <div x-data="dropdown('satuanTujuan')" class="relative">
                        <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                            autocomplete="off" placeholder="Cari Satuan..."
                            class="w-full bg-gray-100 border border-gray-200 rounded px-2 py-1 text-sm hover:placeholder-shown"
                            title="Cari Satuan Tujuan">
                        <ul x-show="open" @click.outside="close()" x-cloak
                            class="absolute z-50 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                            <template x-for="satuan in filteredSatuanTujuans" :key="satuan.id">
                                <li @click="select(satuan)" class="cursor-pointer px-2 py-1 hover:bg-gray-100">
                                    <span x-text="satuan.nama"></span>
                                </li>
                            </template>
                            <li x-show="filteredSatuanTujuans.length === 0" class="px-2 py-1 text-gray-500 text-sm">Data
                                tidak ditemukan</li>
                        </ul>
                        <input type="hidden" name="satuan_tujuan_id[]" :value="selected.id">
                    </div>

                    <!-- Delete Button -->
                    <div class="flex justify-center">
                        <button type="button" class="text-red-500 hover:bg-red-50 rounded p-1"
                            @click="removeKonversi(index)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Add Button -->
    <div class="border border-dashed border-gray-200 rounded p-2 mb-4">
        <button type="button" @click="addKonversi()"
            class="w-full flex items-center justify-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Konversi
        </button>
    </div>
</div>

<script>
    function satuanUkurData() {
        return {
            konversis: [], // Awalnya kosong, tidak ada baris konversi
            addKonversi() {
                this.konversis.push({
                    jumlah: 1,
                    nilai: '',
                    satuan_konversi_id: null,
                    satuan_tujuan_id: null,
                });
            },

            removeKonversi(index) {
                if (this.konversis.length > 0) {
                    this.konversis.splice(index, 1);
                }
            },

            formatNilai(index) {
                this.konversis[index].nilai = this.konversis[index].nilai.replace(/[^\d.]/g, '');
            },
        };
    }
</script>
