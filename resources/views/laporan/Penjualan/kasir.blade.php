<x-layout>

    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Laporan Penjualan Kasir') }}
    </h2>


    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filter periode -->
            <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
                <form action="{{ route('laporan.kasir') }}" method="GET" class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label for="tanggal" class="font-medium text-gray-700">Pilih Tanggal:</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Tampilkan
                    </button>
                </form>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                <!-- Card statistik jumlah kasir -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">Jumlah Kasir Aktif</h3>
                            <p class="text-gray-500">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="p-3 text-white bg-blue-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold">{{ $jumlahKasir }}</p>
                    </div>
                </div>

                <!-- Card statistik jumlah transaksi -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">Jumlah Transaksi</h3>
                            <p class="text-gray-500">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="p-3 text-white bg-green-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold">{{ $jumlahTransaksi }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Kasir -->
            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Daftar Kasir</h3>

                        <!-- Filter/Sorting -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Urutkan:</span>
                            <a href="{{ route('laporan.kasir', ['tanggal' => $tanggal, 'sort_by' => 'transaksi', 'sort_direction' => $sortBy == 'transaksi' && $sortDirection == 'desc' ? 'asc' : 'desc']) }}"
                                class="px-3 py-1 text-sm {{ $sortBy == 'transaksi' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} rounded-md hover:bg-blue-200">
                                Transaksi {{ $sortBy == 'transaksi' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                            </a>
                            <a href="{{ route('laporan.kasir', ['tanggal' => $tanggal, 'sort_by' => 'usia_akun', 'sort_direction' => $sortBy == 'usia_akun' && $sortDirection == 'desc' ? 'asc' : 'desc']) }}"
                                class="px-3 py-1 text-sm {{ $sortBy == 'usia_akun' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} rounded-md hover:bg-blue-200">
                                Usia Akun {{ $sortBy == 'usia_akun' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                            </a>
                            <a href="{{ route('laporan.kasir', ['tanggal' => $tanggal, 'sort_by' => 'total_penjualan', 'sort_direction' => $sortBy == 'total_penjualan' && $sortDirection == 'desc' ? 'asc' : 'desc']) }}"
                                class="px-3 py-1 text-sm {{ $sortBy == 'total_penjualan' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} rounded-md hover:bg-blue-200">
                                Total Penjualan
                                {{ $sortBy == 'total_penjualan' ? ($sortDirection == 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Nama Kasir
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Terdaftar Sejak
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Jumlah Transaksi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Total Penjualan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($daftarKasir as $kasir)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $kasir->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $kasir->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $kasir->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $kasir->jumlah_transaksi }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp
                                                {{ number_format($kasir->total_penjualan ?? 0, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                                            @if ($kasir->jumlah_transaksi > 0)
                                                <button type="button"
                                                    @click="showDetailTransaksi({{ $kasir->id }}, '{{ $kasir->name }}', '{{ $tanggal }}')"
                                                    class="px-3 py-1 font-medium text-blue-600 rounded-md hover:text-blue-900 hover:bg-blue-100 focus:outline-none">
                                                    Detail
                                                </button>
                                            @else
                                                <span class="text-gray-400">Tidak ada transaksi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center whitespace-nowrap">
                                            <div class="text-sm text-gray-500">Tidak ada data kasir</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $daftarKasir->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi dengan Alpine.js -->
    <div x-data="detailTransaksi()" x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:align-middle sm:max-w-4xl">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                        Detail Transaksi Kasir: <span x-text="kasirName"></span>
                    </h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Tanggal: <span x-text="formatDate(tanggal)"></span>
                    </p>
                </div>

                <div class="mt-4 border-t border-gray-200">
                    <div x-show="loading" class="flex items-center justify-center py-12">
                        <svg class="w-12 h-12 text-blue-600 animate-spin" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>

                    <div x-show="!loading && transaksi.length === 0" class="py-12 text-center">
                        <p class="text-gray-500">Tidak ada transaksi untuk ditampilkan.</p>
                    </div>

                    <div x-show="!loading && transaksi.length > 0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            No Ref</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Waktu</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Customer</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Jumlah Item</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Total</th>
                                        <th
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(item, index) in transaksi" :key="index">
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span x-text="item.no_ref"
                                                    class="text-sm font-medium text-gray-900"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span x-text="formatDateTime(item.created_at)"
                                                    class="text-sm text-gray-500"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span x-text="item.customer ? item.customer.name : 'Umum'"
                                                    class="text-sm text-gray-500"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span x-text="item.detail_penjualan ? item.detail_penjualan.length : 0"
                                                    class="text-sm text-gray-500"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span x-text="formatRupiah(item.grand_total)"
                                                    class="text-sm font-medium text-gray-900"></span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span x-text="item.metode_pembayaran"
                                                    class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div
                            class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between flex-1 sm:hidden">
                                <button @click="loadPage(pagination.current_page - 1)"
                                    :disabled="pagination.current_page === 1"
                                    :class="{ 'cursor-not-allowed opacity-50': pagination.current_page === 1 }"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Sebelumnya
                                </button>
                                <button @click="loadPage(pagination.current_page + 1)"
                                    :disabled="pagination.current_page === pagination.last_page"
                                    :class="{
                                        'cursor-not-allowed opacity-50': pagination.current_page === pagination
                                            .last_page
                                    }"
                                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Selanjutnya
                                </button>
                            </div>
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Menampilkan
                                        <span class="font-medium" x-text="pagination.from"></span>
                                        sampai
                                        <span class="font-medium" x-text="pagination.to"></span>
                                        dari
                                        <span class="font-medium" x-text="pagination.total"></span>
                                        transaksi
                                    </p>
                                </div>
                                <div>
                                    <nav class="inline-flex -space-x-px rounded-md shadow-sm isolate"
                                        aria-label="Pagination">
                                        <button @click="loadPage(pagination.current_page - 1)"
                                            :disabled="pagination.current_page === 1"
                                            :class="{ 'cursor-not-allowed opacity-50': pagination.current_page === 1 }"
                                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                            <span class="sr-only">Sebelumnya</span>
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <template
                                            x-for="page in generatePagination(pagination.current_page, pagination.last_page)">
                                            <button @click="loadPage(page)"
                                                :class="{
                                                    'z-10 bg-blue-50 border-blue-500 text-blue-600': page === pagination
                                                        .current_page,
                                                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !==
                                                        pagination.current_page
                                                }"
                                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium border">
                                                <span x-text="page"></span>
                                            </button>
                                        </template>

                                        <button @click="loadPage(pagination.current_page + 1)"
                                            :disabled="pagination.current_page === pagination.last_page"
                                            :class="{
                                                'cursor-not-allowed opacity-50': pagination.current_page === pagination
                                                    .last_page
                                            }"
                                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                            <span class="sr-only">Selanjutnya</span>
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function detailTransaksi() {
            return {
                isOpen: false,
                loading: false,
                kasirId: null,
                kasirName: '',
                tanggal: '',
                transaksi: [],
                pagination: {
                    total: 0,
                    per_page: 5,
                    current_page: 1,
                    last_page: 1,
                    from: 0,
                    to: 0
                },

                // Menampilkan modal detail transaksi
                showDetailTransaksi(id, name, date) {
                    this.isOpen = true;
                    this.kasirId = id;
                    this.kasirName = name;
                    this.tanggal = date;
                    this.loadTransaksi(1);
                },

                // Menutup modal
                closeModal() {
                    this.isOpen = false;
                    this.transaksi = [];
                },

                // Mengambil data transaksi dari backend
                loadTransaksi(page = 1) {
                    this.loading = true;

                    fetch(`/laporan/penjualan/kasir/${this.kasirId}/detail?tanggal=${this.tanggal}&page=${page}`)
                        .then(response => response.json())
                        .then(data => {
                            this.transaksi = data.transaksi.data;
                            this.pagination = data.pagination;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.loading = false;
                        });
                },

                // Memuat halaman pagination
                loadPage(page) {
                    if (page < 1 || page > this.pagination.last_page) {
                        return;
                    }

                    this.loadTransaksi(page);
                },

                // Format tanggal
                formatDate(dateString) {
                    const options = {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                },

                // Format tanggal dan waktu
                formatDateTime(dateTimeString) {
                    const options = {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return new Date(dateTimeString).toLocaleDateString('id-ID', options);
                },

                // Format mata uang Rupiah
                formatRupiah(angka) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
                },

                // Menghasilkan array untuk pagination
                generatePagination(currentPage, lastPage) {
                    // Menampilkan maksimal 5 halaman
                    let pages = [];

                    if (lastPage <= 5) {
                        // Jika total halaman <= 5, tampilkan semua
                        for (let i = 1; i <= lastPage; i++) {
                            pages.push(i);
                        }
                    } else {
                        // Jika halaman aktif dekat dengan awal
                        if (currentPage <= 3) {
                            pages = [1, 2, 3, 4, 5];
                        }
                        // Jika halaman aktif dekat dengan akhir
                        else if (currentPage >= lastPage - 2) {
                            pages = [lastPage - 4, lastPage - 3, lastPage - 2, lastPage - 1, lastPage];
                        }
                        // Jika halaman aktif di tengah
                        else {
                            pages = [currentPage - 2, currentPage - 1, currentPage, currentPage + 1, currentPage + 2];
                        }
                    }

                    return pages;
                }
            }
        }
    </script>


</x-layout>
