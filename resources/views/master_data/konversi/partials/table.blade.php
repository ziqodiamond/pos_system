<x-master-table :route="'konversi'" :items="$konversi" :columns="['Barang', 'Satuan Konversi', 'Nilai Konversi', 'Satuan Tujuan']">
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
                    <form class="space-y-6" action="{{ route('konversi.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="barang-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Barang</label>
                            <input type="text" name="barang" id="barang-{{ $item->id }}"
                                value="{{ $item->barang?->nama }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="satuan-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan
                                Konversi</label>
                            <input type="text" name="satuan" id="satuan-{{ $item->id }}"
                                value="{{ $item->satuan?->nama }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="nilai-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai
                                Konversi</label>
                            <input type="number" name="nilai" id="nilai-{{ $item->id }}"
                                value="{{ $item->nilai }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="satuanTujuan-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan
                                Tujuan</label>
                            <input type="text" name="satuanTujuan" id="satuanTujuan-{{ $item->id }}"
                                value="{{ $item->satuanTujuan?->nama }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Save
                        </button>
                    </form>
                </x-base-modal>

                {{-- Delete Modal --}}
                <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Konversi" triggerText="Delete"
                    triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete this konversi?
                    </p>
                    <form class="space-y-6" action="{{ route('konversi.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Delete</button>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectAllCheckbox = document.getElementById('checkbox-all');
        const massEditButton = document.getElementById('massEditButton');
        const massDeleteButton = document.getElementById('massDeleteButton');
        const bulkForm = document.getElementById('bulkForm');
        const bulkActionType = document.getElementById('bulkActionType');

        // Select All Checkbox
        window.toggleCheckboxes = function(source) {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = source.checked;
            });
            toggleMassActionButtons();
        };

        // Cek tiap checkbox apakah ada yang dicentang
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Auto uncheck "Select All" kalau ada yang gak dicentang
                selectAllCheckbox.checked = [...checkboxes].every(cb => cb.checked);
                toggleMassActionButtons();
            });
        });

        // Aktif/nonaktif tombol aksi massal
        function toggleMassActionButtons() {
            const anyChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);
            massEditButton.disabled = !anyChecked;
            massDeleteButton.disabled = !anyChecked;
        }

        // Tombol Mass Edit
        massEditButton.addEventListener('click', () => {
            if (getSelectedIds().length === 0) {
                alert('Pilih minimal satu data untuk diedit!');
                return;
            }
            bulkActionType.value = "edit";
            bulkForm.submit();
        });

        // Tombol Mass Delete + konfirmasi
        massDeleteButton.addEventListener('click', (e) => {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                alert('Pilih minimal satu data untuk dihapus!');
                return;
            }
            const confirmDelete = confirm(
                `Yakin ingin menghapus ${selectedIds.length} data yang dipilih?`);
            if (confirmDelete) {
                bulkActionType.value = "delete";
                bulkForm.submit();
            }
        });

        // Fungsi bantu buat ambil semua ID yang dipilih
        function getSelectedIds() {
            return Array.from(checkboxes)
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => checkbox.value);
        }
    });
</script>
