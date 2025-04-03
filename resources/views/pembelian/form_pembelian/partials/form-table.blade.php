<table class="w-full text-sm text-left text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
            <th scope="col" class="px-4 py-3 w-12 text-center">#</th>
            <th scope="col" class="px-4 py-3">Jenis Barang/Jasa</th>
            <th scope="col" class="px-4 py-3">Jumlah</th>
            <th scope="col" class="px-4 py-3">Satuan Ukur</th>
            <th scope="col" class="px-4 py-3">Harga Satuan</th>
            <th scope="col" class="px-4 py-3">Total</th>
            <th scope="col" class="px-4 py-3 w-12 text-center">#</th>
        </tr>
    </thead>
    <tbody>
        <template x-for="(item, index) in formData.items" :key="index">
            <tr class="bg-white border-b">
                <td class="px-4 py-2 text-center" x-text="index + 1"></td>
                <td class="px-4 py-2">
                    <div x-data="dropdown('barang', index)">
                        <div class="relative">
                            <input type="text" x-model="search" @input="watchSearch()" x-ref="barang"
                                @click="openDropdown()" @keydown.down="navigateDown(index)"
                                @keydown.up="navigateUp(index)" autocomplete="off" placeholder="Cari Barang..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <ul x-show="open" @click.outside="close()" x-cloak
                                class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                <template x-for="(barang, barangIndex) in filteredBarangs" :key="barang.id">
                                    <li @click="select(barang, index)" @mouseenter="highlightItem(barangIndex)"
                                        :class="{ 'bg-gray-100': highlightedIndex === barangIndex }"
                                        class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                        <span x-text="barang.kode + ' - ' + barang.nama"></span>
                                    </li>
                                </template>
                            </ul>
                            <input type="hidden" :name="'items[' + index + '][barang_id]'" x-model="item.barang_id">
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2">
                    <input type="text" :name="'items[' + index + '][jumlah]'"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        x-model="item.jumlah"
                        @input="$el.value = $el.value.replace(/[^0-9]/g, ''); calculateItemTotal(index); calculateTotal()"
                        @keydown.prevent.up="navigateToPreviousRow(index)"
                        @keydown.prevent.down="navigateToNextRow(index)" placeholder="0"
                        :disabled="!getSelectedBarangId(index)">
                </td>
                <td class="px-4 py-2">
                    <div x-data="dropdown('satuan', index)">
                        <div class="relative">
                            <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                                @keydown.down="navigateDown(index)" @keydown.up="navigateUp(index)" autocomplete="off"
                                placeholder="-"
                                :class="{ 'bg-gray-200': !getSelectedBarangId(index), 'bg-gray-50': getSelectedBarangId(index) }"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                            <ul x-show="open && filteredSatuans(index).length > 0" @click.outside="close()" x-cloak
                                class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                <template x-for="(satuan, satuanIndex) in filteredSatuans(index)"
                                    :key="satuan.id">
                                    <li @click="select(satuan, index)" @mouseenter="highlightItem(satuanIndex)"
                                        :class="{ 'bg-gray-100': highlightedIndex === satuanIndex }"
                                        class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                        <span x-text="satuan.nama"></span>
                                    </li>
                                </template>
                            </ul>

                            <!-- Pesan jika belum ada barang yang dipilih -->
                            <div x-show="open && !getSelectedBarangId(index)" @click.outside="close()"
                                class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full p-3 text-center text-gray-500">
                                Pilih barang terlebih dahulu
                            </div>

                            <!-- Pesan jika tidak ada satuan yang tersedia untuk barang yang dipilih -->
                            <div x-show="open && getSelectedBarangId(index) && filteredSatuans(index).length === 0"
                                @click.outside="close()"
                                class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full p-3 text-center text-gray-500">
                                Tidak ada satuan tersedia untuk barang ini
                            </div>

                            <!-- Hidden input untuk menyimpan satuan_id -->
                            <input type="hidden" :name="'items[' + index + '][satuan_id]'" x-model="item.satuan_id">
                        </div>
                    </div>
                </td>
                <td class="px-4 py-2">
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                            Rp
                        </span>
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pl-9S text-right"
                            x-model.lazy="item.hargaSatuan" x-on:focus="$event.target.value = item.hargaSatuan"
                            @blur="$event.target.value = formatNumber(item.hargaSatuan); calculateItemTotal(index)"
                            @keydown.down="navigateToNextRow(index)" @keydown.up="navigateToPreviousRow(index)"
                            placeholder="0" autocomplete="off">
                        <input type="hidden" :ame="'items[' + index + '][harga_satuan]'" x-model="item.hargaSatuan">

                    </div>
                </td>
                <td class="px-4 py-2 whitespace-nowrap">
                    <span class="block w-full p-2.5 text-right"
                        x-text="'Rp ' + formatNumber( calculateItemSubtotalDisplay(index))"></span>
                    <input type="text" :name="'items[' + index + '][total]'" x-model="item.total">
                    <input type="hidden" :name="'items[' + index + '][pajak_id]'" x-model="item.pajak_id">
                    <input type="hidden" :name="'items[' + index + '][nilai_pajak]'" x-model="item.nilai_pajak">
                </td>
                <td class="px-4 py-2 text-center">
                    <button type="button" @click="removeItem(index)" class="text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            </tr>
        </template>
    </tbody>
</table>

<!-- Add Item Button -->
<div class="w-full mt-4">
    <button type="button" @click="addItem()"
        class="w-full py-2 px-4 border border-gray-300 rounded-lg text-blue-500 hover:bg-gray-50">
        <span class="mr-1">+</span> Tambah Baris Baru
    </button>
</div>
