<x-layout>


    <div class="p-2"> @include('components.breadcrumbs')</div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-2xl font-bold">Daftar Pajak</h2>

        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('master_data.pajak.partials.table-header')
            </div>


            {{-- table --}}
            <div id="table-container" class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('master_data.pajak.partials.table')
            </div>


        </div>


    </div>

    <script>
        const reportSearchRoute = @json(route('pajak.index'));

        document.addEventListener('DOMContentLoaded', () => {
            // Buat variabel global untuk menyimpan status filter terakhir yang dipilih per grup (berdasarkan name)
            window.lastSelectedFilter = {};

            // Tambahkan event listener ke input pencarian
            document.getElementById('search').addEventListener('input', handleSearchAndFilter);

            // Tambahkan event listener ke semua checkbox filter (yang memiliki class 'filter-input')
            document.querySelectorAll('.filter-input').forEach(input => {
                input.addEventListener('click', function(event) {
                    const groupName = this.name; // Nama grup dari filter (berdasarkan atribut name)
                    const wasChecked = this
                        .checked; // Cek apakah sebelumnya checkbox dalam kondisi dicentang

                    // Jika user klik filter yang sama dengan sebelumnya, maka toggle: hapus pilihan
                    if (window.lastSelectedFilter[groupName] === this.value) {
                        this.checked = false; // Uncheck
                        window.lastSelectedFilter[groupName] =
                            null; // Reset status terakhir untuk grup ini
                    } else {
                        // Jika pilih filter baru, uncheck semua checkbox dalam grup yang sama
                        document.querySelectorAll(`.filter-input[name="${groupName}"]`).forEach(
                            i => {
                                i.checked = false;
                            });

                        // Centang filter yang baru dipilih
                        this.checked = true;
                        window.lastSelectedFilter[groupName] = this
                            .value; // Simpan status filter yang baru
                    }

                    // Panggil fungsi filter dan search
                    handleSearchAndFilter();

                    // Perbarui tampilan tombol aksi berdasarkan status filter
                    handleStatusFilterChange();
                });
            });

            // Fungsi utama untuk melakukan pencarian dan filtering
            function handleSearchAndFilter() {
                const selectedFilters = {}; // Objek untuk menyimpan filter yang aktif

                // Ambil input search dari user
                const query = document.getElementById('search').value;

                // Ambil semua filter (checkbox) yang dicentang
                document.querySelectorAll('.filter-input:checked').forEach(input => {
                    selectedFilters[input.name] = input.value; // Simpan berdasarkan nama filter (group)
                });

                // Gabungkan query search dan filter jadi string query URL
                const queryString = new URLSearchParams({
                    ...selectedFilters,
                    search: query
                }).toString();

                // Kirim request AJAX ke server (asumsi `reportSearchRoute` adalah route pencarian)
                fetch(`${reportSearchRoute}?${queryString}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Identifikasi sebagai request AJAX
                        }
                    })
                    .then(response => {
                        // Jika response gagal, lempar error
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json(); // Parse response JSON
                    })
                    .then(data => {
                        // Tampilkan hasil pencarian/filter ke dalam elemen #table-container
                        document.getElementById('table-container').innerHTML = data.html;

                        // Re-bind event checkbox karena elemen tabel sudah di-replace
                        rebindCheckboxEvents();
                    })
                    .catch(error => console.error('Error fetching filter results:', error));
            }

            // Fungsi untuk menyesuaikan tampilan tombol aksi berdasarkan status filter yang dipilih
            function handleStatusFilterChange() {
                // Ambil status yang sedang dipilih (jika ada)
                const selectedStatus = document.querySelector('input[name="Status"]:checked')?.value;

                // Ambil semua tombol aksi yang relevan
                const editAction = document.getElementById('editAction');
                const deleteAction = document.getElementById('deleteAction');
                const restoreAction = document.getElementById('restoreAction');
                const forceDeleteAction = document.getElementById('forceDeleteAction');

                // Jika status = deleted, sembunyikan tombol edit/delete, tampilkan tombol restore/force delete
                if (selectedStatus === 'deleted') {
                    editAction?.classList.add('hidden');
                    deleteAction?.classList.add('hidden');
                    restoreAction?.classList.remove('hidden');
                    forceDeleteAction?.classList.remove('hidden');
                } else {
                    // Jika bukan deleted, tampilkan tombol edit/delete, sembunyikan restore/force delete
                    editAction?.classList.remove('hidden');
                    deleteAction?.classList.remove('hidden');
                    restoreAction?.classList.add('hidden');
                    forceDeleteAction?.classList.add('hidden');
                }
            }

            // Fungsi untuk meregistrasi ulang event checkbox setelah konten di-reload via AJAX
            function rebindCheckboxEvents() {
                const selectAllCheckbox = document.getElementById('checkbox-all'); // Checkbox untuk "pilih semua"
                const checkboxes = document.querySelectorAll('.item-checkbox'); // Checkbox individual
                const selectedIdsInput = document.getElementById(
                    'selectedIds'); // Hidden input untuk menyimpan id yang dipilih

                // Jika elemen tidak ditemukan, hentikan fungsi
                if (!selectAllCheckbox || checkboxes.length === 0 || !selectedIdsInput) {
                    return;
                }

                // Event: Saat checkbox "pilih semua" diubah
                selectAllCheckbox.addEventListener('change', () => {
                    // Semua checkbox individual akan mengikuti status "pilih semua"
                    checkboxes.forEach((checkbox) => (checkbox.checked = selectAllCheckbox.checked));
                    updateSelectedIds(); // Perbarui ID yang dipilih
                });

                // Event: Saat checkbox individual diubah
                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        // Jika semua checkbox dicentang, maka "pilih semua" ikut dicentang
                        selectAllCheckbox.checked = [...checkboxes].every(cb => cb.checked);
                        updateSelectedIds(); // Perbarui ID yang dipilih
                    });
                });

                // Fungsi untuk memperbarui nilai dari input hidden berdasarkan checkbox yang dicentang
                function updateSelectedIds() {
                    const selectedIds = Array.from(checkboxes)
                        .filter((checkbox) => checkbox.checked) // Ambil hanya checkbox yang dicentang
                        .map((checkbox) => checkbox.value); // Ambil nilai (id) dari masing-masing checkbox

                    selectedIdsInput.value = selectedIds.join(','); // Gabungkan menjadi string (id1,id2,...)

                    // Pastikan status "select all" tetap sinkron jika jumlah yang dipilih sama dengan jumlah total
                    selectAllCheckbox.checked = checkboxes.length > 0 && checkboxes.length === selectedIds.length;
                }

                // Panggil update pertama kali untuk memastikan nilai awal sinkron
                updateSelectedIds();
            }

            // Jalankan rebind dan atur tombol aksi saat halaman pertama kali dimuat
            rebindCheckboxEvents();
            handleStatusFilterChange();
        });
    </script>

</x-layout>
