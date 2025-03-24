<x-master-table :route="'kategori'" :items="$kategori" :columns="['Kode', 'Nama', 'Status']">
    @forelse ($kategori as $item)
        <tr
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
            <td class="w-4 p-4">
                <div class="flex items-center">
                    <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                        class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                </div>
            </td>
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $item->kode }}
            </th>
            <td class="px-6 py-4">{{ $item->nama }}</td>
            <td class="px-6 py-4">{{ $item->status }}</td>
            <td class="flex items-center px-6 py-4 space-x-2">
                {{-- Edit Modal --}}
                <x-base-modal :id="'editModal-' . $item->id" title="Edit Kategori" triggerText="Edit"
                    triggerClass="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                    <form class="space-y-6" action="{{ route('kategori.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="kode-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                            <input type="text" name="kode" id="kode-{{ $item->id }}"
                                value="{{ $item->kode }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="nama-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                            <input type="text" name="nama" id="nama-{{ $item->id }}"
                                value="{{ $item->nama }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                required>
                        </div>
                        <div>
                            <label for="status-{{ $item->id }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <input type="text" name="status" id="status-{{ $item->id }}"
                                value="{{ $item->status }}"
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
                <x-base-modal :id="'deleteModal-' . $item->id" title="Delete Kategori" triggerText="Delete"
                    triggerClass="font-medium text-red-600 dark:text-red-500 hover:underline">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete this kategori?
                    </p>
                    <form class="space-y-6" action="{{ route('kategori.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Delete
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

{{ $kategori->links() }}

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

        // Check each checkbox to determine if any is checked
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                selectAllCheckbox.checked = [...checkboxes].every(cb => cb.checked);
                toggleMassActionButtons();
            });
        });

        // Enable/disable mass action buttons
        function toggleMassActionButtons() {
            const anyChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);
            massEditButton.disabled = !anyChecked;
            massDeleteButton.disabled = !anyChecked;
        }

        // Mass Edit Button
        massEditButton.addEventListener('click', () => {
            if (getSelectedIds().length === 0) {
                alert('Pilih minimal satu data untuk diedit!');
                return;
            }
            bulkActionType.value = "edit";
            bulkForm.submit();
        });

        // Mass Delete Button with confirmation
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

        // Helper function to get all selected IDs
        function getSelectedIds() {
            return Array.from(checkboxes)
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => checkbox.value);
        }
    });
</script>
