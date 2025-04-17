<x-master-table :route="'konversi'" :items="$konversi" :columns="['Barang', 'Satuan Konversi', 'Nilai Konversi', 'Satuan Tujuan', '']">
    @forelse ($konversi as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                        class=" item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>
            <td class="px-6 py-4">{{ $item->barang?->nama ?? 'Universal' }}</td>
            <td class="px-6 py-4">{{ $item->satuan?->nama ?? 'N/A' }}</td>
            <td class="px-6 py-4">{{ $item->nilai_konversi }}</td>
            <td class="px-6 py-4">{{ $item->satuanTujuan?->nama ?? 'N/A' }}</td>
            <td class="flex items-center px-6 py-4 space-x-2">
                {{-- Edit Modal --}}
                <x-base-modal :id="'editModal-' . $item->id" title="Edit Konversi" triggerText="Edit"
                    triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                    <form action="{{ route('konversi.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div x-data="dropdownEditKonversi('{{ $item->satuan?->id ?? '' }}')" x-init="init()">
                            <label for="satuan_konversi"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                Satuan Konversi
                            </label>
                            <div class="relative">
                                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                                    autocomplete="off"
                                    placeholder="{{ $item->satuan?->nama ?? 'Cari Satuan Konversi...' }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                                           focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 
                                           dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                <!-- Dropdown List -->
                                <ul x-show="open" @click.outside="close()" x-cloak
                                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                    <template x-for="satuan in filteredSatuan" :key="satuan.id">
                                        <li @click="select(satuan)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                            <span x-text="satuan.nama"></span>
                                        </li>
                                    </template>
                                </ul>

                                <!-- Hidden Input untuk Form -->
                                <input type="text" name="satuan_konversi_id" :value="selectedId">
                            </div>
                        </div>

                        <!-- Satuan Dasar Component -->
                        <div class="mt-2" x-data="dropdownEditDasar('{{ $item->satuanTujuan?->id ?? '' }}')" x-init="init()">
                            <label for="satuan_dasar"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                Satuan Dasar
                            </label>
                            <div class="relative">
                                <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()"
                                    autocomplete="off"
                                    placeholder="{{ $item->satuanTujuan?->nama ?? 'Cari Satuan Dasar...' }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                <ul x-show="open" @click.outside="close()" x-cloak
                                    class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
                                    <template x-for="satuan in filteredSatuan" :key="satuan.id">
                                        <li @click="select(satuan)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                            <span x-text="satuan.nama"></span>
                                        </li>
                                    </template>
                                </ul>
                                <input type="text" name="satuan_tujuan_id" :value="selectedId">
                            </div>
                        </div>






                        <div class="mt-2">
                            <label for="nilai_konversi" class="block text-sm font-medium text-gray-700">Nilai
                                Konversi</label>
                            <input type="number" step="0.01" name="nilai_konversi" id="nilai_konversi"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                value="{{ $item->nilai_konversi }}">
                            <p class="mt-1 text-sm text-gray-500">
                                Masukkan jumlah satuan dasar yang setara dengan 1 satuan konversi.
                                Contoh: Jika 1 Lusin = 12 Pcs, maka nilai konversinya adalah 12.
                            </p>
                        </div>
                        <button type="submit"
                            class="mt-6 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                    </form>
                </x-base-modal>

                {{-- Delete Modal --}}
                <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Konversi" triggerText="Hapus"
                    triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Apakah anda yakin ingin menghapus konversi ini?
                    </p>
                    <form class="space-y-6" action="{{ route('konversi.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Hapus</button>
                    </form>
                </x-base-modal>
            </td>
        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="6">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $konversi->links() }}
