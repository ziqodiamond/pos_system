<div class="grid grid-cols-2 gap-4">
    @php
        $itemData = [
            'harga_beli' => $item->harga_beli,
            'harga_pokok' => $item->harga_pokok,
            'harga_jual' => $item->harga_jual,
            'markup' => $item->markup,
            'margin' => $item->margin,
            'diskon_value' => $item->diskon_value,
        ];
    @endphp
    <!-- Kolom Kiri (Harga) -->
    <div x-data="priceCalculator('{{ $item->id }}', @js($itemData))" data-item-id="{{ $item->id }}">

        <!-- Harga Beli -->
        <label for="harga_beli_{{ $item->id }}"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_beli_{{ $item->id }}" x-model="displayHargaBeli"
                x-on:blur="formatCurrencyInput('hargaBeli')"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <!-- Perbaikan: mengubah input text menjadi hidden -->
            <input type="hidden" name="harga_beli" x-model="price.hargaBeli" />
        </div>

        <!-- Harga Pokok -->
        <label for="harga_pokok_{{ $item->id }}"
            class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Pokok</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <input type="text" id="display_harga_pokok_{{ $item->id }}" x-model="displayHargaPokok"
                x-on:blur="formatCurrencyInput('hargaPokok')" @input="recalculateAll()"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <input type="hidden" name="harga_pokok" x-model="price.hargaPokok" />
        </div>

        <!-- Harga Jual -->
        <label for="harga_jual_{{ $item->id }}"
            class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
            Jual</label>
        <div class="relative">
            <span class="absolute left-3 top-2.5">Rp</span>
            <!-- Perbaikan: menambahkan tanda kutip penutup setelah id dan memperbaiki x-model -->
            <input type="text" id="display_harga_jual_{{ $item->id }}" x-model="displayHargaJual"
                x-on:blur="formatCurrencyInput('hargaJual')" @input="recalculateAll()"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <input type="hidden" name="harga_jual" x-model="price.hargaJual" />
        </div>

        <!-- Markup (%) -->
        <label for="markup_{{ $item->id }}"
            class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Markup (%) <span class="text-xs text-gray-500">(Laba/HPP*100)</span>
        </label>
        <div class="relative">
            <input type="text" id="markup_{{ $item->id }}" x-model="displayMarkup"
                @input="updateHargaJualFromMarkup()" @blur="formatPercentageInput('markup')"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <span class="absolute right-3 top-2.5">%</span>
            <input type="hidden" name="markup" x-model="price.markup" />
        </div>

        <!-- Margin Keuntungan (%) -->
        <label for="margin_{{ $item->id }}"
            class="block mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Margin (%) <span class="text-xs text-gray-500">(Laba/Harga Jual*100)</span>
        </label>
        <div class="relative">
            <input type="text" id="margin_{{ $item->id }}" x-model="displayMargin" x-effect="recalculateAll()"
                @input="updateHargaJualFromMargin()" @blur="formatPercentageInput('margin')"
                class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
            <span class="absolute right-3 top-2.5">%</span>
            <!-- Perbaikan: mengubah input text menjadi hidden -->
            <input type="hidden" name="margin" x-model="price.margin" />
        </div>
    </div>


    <!-- Kolom Kanan (Satuan, Pajak, Diskon, Stok) -->
    <div class="space-y-4">
        <!-- Input pajak (menggunakan dropdownEdit yang sudah ada) -->
        <div x-data="dropdownEdit('pajak', '{{ $item->pajak?->id }}')" x-init="init()">
            <label for="pajak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pajak</label>
            <div class="relative">
                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
                    placeholder="{{ $item->pajak?->nama ?? 'Cari Pajak...' }}" value="{{ $item->pajak->nama }}"
                    required
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
                <input type="hidden" name="pajak_id" :value="selectedPajakId">
            </div>
        </div>

        <!-- Input satuan (menggunakan dropdownEdit yang sudah ada) -->
        <div x-data="dropdownEdit('satuan', '{{ $item->satuan?->id }}')" x-init="init()">
            <label for="satuan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                Satuan</label>
            <div class="relative">
                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                    autocomplete="off" placeholder="{{ $item->satuan?->nama ?? 'Cari Satuan...' }}" required
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
                <input type="hidden" name="satuan_id" :value="selectedSatuanId">
            </div>
        </div>


        <!-- Diskon Field dengan informasi tambahan -->
        <div x-data="diskonCalculator('{{ $item->id }}', @js($itemData))" x-init="$watch('displayDiskonPersen', () => hitungDiskon())" @input-change.window="hitungDiskon()"
            class="diskon-calculator">
            <label for="diskon_{{ $item->id }}"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Diskon (%)
            </label>

            <!-- Input untuk diskon (display value) -->
            <input type="text" id="diskon_display_{{ $item->id }}" x-model="displayDiskonPersen"
                @input="hitungDiskon()" @change="formatPercentageInput()" @blur="formatPercentageInput()"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />

            <!-- Input hidden untuk nilai sebenarnya yang dikirim ke server -->
            <input type="hidden" name="diskon" x-model="diskonPersen" />

            <!-- Informasi diskon dengan styling Tailwind yang lebih baik -->
            <div class="mt-3 space-y-2 text-xs p-3 bg-gray-50 rounded-lg dark:bg-gray-800">
                <p class="flex justify-between text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Total Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="'Rp ' + formatCurrency(diskonNominal / 100)"></span>
                </p>
                <p class="flex justify-between text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Harga Jual Setelah Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="'Rp ' + formatCurrency(hargaSetelahDiskon / 100)"></span>
                </p>
                <p class="flex justify-between text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Margin Setelah Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="formatPercentage(marginSetelahDiskon) + '%'"></span>
                </p>
                <p class="flex justify-between text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Markup Setelah Diskon:</span>
                    <span class="text-blue-600 dark:text-blue-400"
                        x-text="formatPercentage(markupSetelahDiskon) + '%'"></span>
                </p>
            </div>
        </div>
        <div>
            <label for="stok_minimal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok
                Minimal</label>
            <input type="number" id="stok_minimal" name="stok_minimal" min="0"
                value="{{ $item->stok_minimum }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required />
        </div>
    </div>
</div>
