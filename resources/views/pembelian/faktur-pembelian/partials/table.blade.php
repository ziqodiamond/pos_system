<x-master-table :route="'faktur'" :items="$faktur" :columns="[
    'Nomor Faktur',
    'Tanggal Faktur',
    'Supplier',
    'Total Item Barang',
    'Subtotal',
    'Total Diskon',
    'Total Pajak',
    'Total',
    'Sisa Tagihan',
    'Status',
]">
    <!-- Form Bulk Aksi Terpisah -->

    @forelse ($faktur as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>

            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->no_faktur }}
            </th>
            <td class="px-6 py-4">{{ $item->tanggal_faktur }}</td>
            <td class="px-6 py-4">{{ $item->supplier->nama }}</td>
            <td class="px-6 py-4">{{ $item->pembelian->total_item }}</td>
            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->subtotal / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->diskon_value / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->pajak_value / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->total_tagihan / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->total_hutang / 100, 2, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if ($item->status == 'lunas')
                    <span
                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-12.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Lunas</span>
                @else
                    <span
                        class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Hutang</span>
                @endif

            <td class="flex items-center px-6 py-4 space-x-2">
                {{-- Delete / Restore Modal --}}

                @if ($item->status != 'lunas')
                    <x-base-modal :id="'bayarModal-' . $item->id" title="Bayar Faktur" triggerText="Bayar"
                        triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        <form action="{{ route('faktur.bayar', $item->id) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="metode_pembayaran"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Metode Pembayaran
                                    </label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                        <option value="">Pilih metode pembayaran</option>
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="debit">Kartu Debit</option>
                                        <option value="kredit">Kartu Kredit</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="nominal"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Nominal Pembayaran
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                            Rp
                                        </span>
                                        <input type="text" name="nominal" id="nominal"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="0" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')">
                                    </div>
                                </div>
                                <div>
                                    <label for="deskripsi"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Deskripsi (Opsional)
                                    </label>
                                    <textarea name="deskripsi" id="deskripsi" rows="3"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Masukkan deskripsi pembayaran (opsional)"></textarea>
                                </div>
                            </div>
                            <button type="submit"
                                class="mt-6 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Bayar
                            </button>
                        </form>
                    </x-base-modal>
                @endif


                {{-- Delete Modal --}}
                <x-base-modal :id="'deleteModal-' . $item->id" title="Hapus Kategori" triggerText="Hapus"
                    triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Apakah anda yakin menghapus kategori {{ $item->nama }}?
                    </p>
                    <form class="space-y-6" action="{{ route('customer.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Hapus
                        </button>
                    </form>
                </x-base-modal>

            </td>
        </tr>
    @empty
        <tr>
            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400" colspan="4">
                Data Kosong
            </td>
        </tr>
    @endforelse
</x-master-table>

{{ $faktur->links() }}
