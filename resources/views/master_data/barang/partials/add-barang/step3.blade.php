<div>
    <div x-data="konversiData(@json($satuans))">
        <!-- Judul -->
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Konversi Nilai Barang</h2>

        <template x-for="(konversi, index) in konversis" :key="index">
            <div class="flex gap-4 items-center">
                <div class="w-1/2">
                    <label for="satuan-konversi" class="block text-sm font-medium text-gray-900 dark:text-white">
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
                    <label for="nilai" class="block text-sm font-medium text-gray-900 dark:text-white">
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
                <button type="button" @click="konversis.splice(index, 1)" x-show="konversis.length > 1"
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
