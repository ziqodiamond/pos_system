<div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
    <div class="w-full md:w-1/2">
        <form class="flex items-center">
            <label for="search" class="sr-only">Cari</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" id="search"
                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 
                       focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 
                       dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Cari Berdasarkan Kode dan Nama Customer">
            </div>
        </form>
    </div>
    <div
        class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
        <div x-data="{ addModal: false, step: 1 }" @open-add-modal.window="addModal = true">
            <button type="button" @click="$dispatch('open-add-modal')"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Tambah Customer
            </button>

            @include('master_data.customer.partials.add-customer')
        </div>
        <div class="flex items-center w-full space-x-3 md:w-auto">
            <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                type="button">
                <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
                Aksi
            </button>
            <div id="actionsDropdown" x-data="{ showModal: false, actionType: '', confirmMessage: '', selectedStatus: '' }"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="actionsDropdownButton">
                    <!-- Tombol default -->
                    <li class="hover:bg-gray-100 dark:hover:bg-gray-600" id="editAction">
                        <button type="button"
                            @click="showModal=true; actionType='edit'; confirmMessage='Edit massal customer terpilih?'"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            Edit
                        </button>
                    </li>
                    <li class="hover:bg-gray-100 dark:hover:bg-gray-600" id="deleteAction">
                        <button type="button"
                            @click="showModal=true; actionType='delete'; confirmMessage='Yakin ingin menghapus customer terpilih?'"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            Hapus
                        </button>
                    </li>

                    <!-- Tombol tambahan jika status 'Dihapus' -->
                    <li class="hover:bg-gray-100 dark:hover:bg-gray-600 hidden" id="restoreAction">
                        <button type="button"
                            @click="showModal=true; actionType='restore'; confirmMessage='Yakin ingin merestore customer terpilih?'"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            Restore
                        </button>
                    </li>
                    <li class="hover:bg-gray-100 dark:hover:bg-gray-600 hidden" id="forceDeleteAction">
                        <button type="button"
                            @click="showModal=true; actionType='forceDelete'; confirmMessage='Yakin ingin menghapus permanen customer terpilih?'"
                            class="block w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-red-400">
                            Hapus Permanen
                        </button>
                    </li>
                </ul>


                <!-- Modal Konfirmasi dalam Dropdown -->
                <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
                    x-cloak>
                    <div class="bg-white p-6 rounded-lg shadow-lg dark:bg-gray-700">
                        <h2 class="text-lg font-semibold mb-4" x-text="confirmMessage"></h2>
                        <form action="{{ route('customer.bulkAction') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" :value="actionType">
                            <input type="hidden" name="selected" id="selectedIds" value="">

                            <!-- Jika Edit, Tampilkan Status -->
                            <template x-if="actionType === 'edit'">
                                <div class="mb-4">
                                    <label for="status"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                                        Status:</label>
                                    <select id="status" name="status" x-model="selectedStatus"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-600 dark:text-white">
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </select>
                                </div>
                            </template>

                            <!-- Tombol Konfirmasi -->
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded mb-2">
                                Ya
                            </button>
                            <button type="button" @click="showModal=false"
                                class="w-full bg-gray-300 hover:bg-gray-400 text-black font-semibold py-2 rounded">
                                Batal
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <x-master-filter :filterGroups="[
                'Urutkan' => [['kode' => 'terbaru', 'nama' => 'Terbaru'], ['kode' => 'terlama', 'nama' => 'Terlama']],
                'Status' => [
                    ['kode' => 'active', 'nama' => 'Aktif'],
                    ['kode' => 'inactive', 'nama' => 'Nonaktif'],
                    ['kode' => 'deleted', 'nama' => 'Dihapus'],
                ],
            ]" />

            <script>
                function handleStatusFilterChange() {
                    const selectedStatus = document.querySelector('input[name="Status"]:checked')?.value;

                    const editAction = document.getElementById('editAction');
                    const deleteAction = document.getElementById('deleteAction');
                    const restoreAction = document.getElementById('restoreAction');
                    const forceDeleteAction = document.getElementById('forceDeleteAction');

                    if (selectedStatus === 'deleted') {
                        editAction.classList.add('hidden');
                        deleteAction.classList.add('hidden');
                        restoreAction.classList.remove('hidden');
                        forceDeleteAction.classList.remove('hidden');
                    } else {
                        editAction.classList.remove('hidden');
                        deleteAction.classList.remove('hidden');
                        restoreAction.classList.add('hidden');
                        forceDeleteAction.classList.add('hidden');
                    }
                }

                // Panggil fungsi pas filter berubah
                document.querySelectorAll('.filter-input').forEach(input => {
                    input.addEventListener('click', function() {
                        const allInputs = document.querySelectorAll('.filter-input');

                        // Uncheck semua input kecuali yang diklik
                        allInputs.forEach(i => i.checked = false);

                        // Toggle hanya yang diklik (kalau sebelumnya unchecked, jadi checked)
                        this.checked = !this.checked;

                        handleStatusFilterChange(); // Panggil ulang biar tombol update
                    });
                });

                // Panggil juga saat pertama load
                handleStatusFilterChange();
            </script>
        </div>
    </div>
</div>
