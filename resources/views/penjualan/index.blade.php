<x-layout>
    <div class="pb-8">
        {{-- Breadcrumbs dengan margin bottom --}}
        <div class="mb-6">
            @include('components.breadcrumbs')
        </div>

        {{-- Header Dashboard dengan informasi pengguna --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Penjualan</h1>
            <p class="text-sm text-gray-600 mt-1">Data statistik pribadi Anda hari ini, {{ date('d M Y') }}</p>
        </div>

        {{-- Statistics Cards dengan desain modern dan elegan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            {{-- Total Transaksi Hari Ini --}}
            <div
                class="bg-white rounded-xl shadow-lg p-6 border-b-4 border-indigo-600 hover:shadow-xl transition duration-300 overflow-hidden relative group">
                <div class="absolute right-0 top-0 bg-indigo-600 text-white text-xs font-medium px-2 py-1 rounded-bl">
                    Hari Ini
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Transaksi Anda</p>
                        <h3 id="total-transaksi" class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $totalTransaksi ?? 0 }}</h3>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-4 group-hover:bg-indigo-200 transition-all duration-300">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Barang Terjual --}}
            <div
                class="bg-white rounded-xl shadow-lg p-6 border-b-4 border-blue-600 hover:shadow-xl transition duration-300 overflow-hidden relative group">
                <div class="absolute right-0 top-0 bg-blue-600 text-white text-xs font-medium px-2 py-1 rounded-bl">
                    Hari Ini
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Barang Anda Terjual</p>
                        <h3 id="total-barang" class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $totalBarangTerjual ?? 0 }}</h3>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4 group-hover:bg-blue-200 transition-all duration-300">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Omset --}}
            <div
                class="bg-white rounded-xl shadow-lg p-6 border-b-4 border-emerald-600 hover:shadow-xl transition duration-300 overflow-hidden relative group">
                <div class="absolute right-0 top-0 bg-emerald-600 text-white text-xs font-medium px-2 py-1 rounded-bl">
                    Hari Ini
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Omset Anda</p>
                        <h3 id="total-omset" class="text-3xl font-bold text-gray-800 mt-2">Rp
                            {{ number_format($totalOmset ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-emerald-100 rounded-full p-4 group-hover:bg-emerald-200 transition-all duration-300">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Judul Menu Penjualan dengan desain modern --}}
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-3">
                <div class="h-8 w-1 bg-indigo-600 rounded-full"></div>
                <h2 class="text-xl font-bold text-gray-800">Menu Cepat</h2>
            </div>
            <span class="text-sm text-gray-500">Akses Fitur Utama</span>
        </div>

        {{-- Menu grid dengan desain premium --}}
        <div class="grid gap-6 grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
            {{-- Kasir (Transaksi Baru) --}}
            <a href="{{ route('transaksi.index') }}"
                class="flex flex-col items-center justify-center p-5 bg-white rounded-xl shadow-md hover:bg-indigo-50 hover:shadow-lg w-full h-36 transition duration-300 ease-in-out transform hover:scale-105 border border-gray-100 group">
                <div class="p-3 bg-indigo-100 rounded-full mb-3 group-hover:bg-indigo-200 transition-all duration-300">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <span class="font-semibold text-center text-sm">Kasir</span>
                <span class="text-xs text-gray-500 mt-1">Transaksi baru</span>
            </a>

            {{-- Mutasi Penjualan (Daftar Penjualan) --}}
            <a href="{{ route('daftar-penjualan.index') }}"
                class="flex flex-col items-center justify-center p-5 bg-white rounded-xl shadow-md hover:bg-blue-50 hover:shadow-lg w-full h-36 transition duration-300 ease-in-out transform hover:scale-105 border border-gray-100 group">
                <div class="p-3 bg-blue-100 rounded-full mb-3 group-hover:bg-blue-200 transition-all duration-300">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <span class="font-semibold text-center text-sm">Mutasi Penjualan</span>
                <span class="text-xs text-gray-500 mt-1">Riwayat transaksi</span>
            </a>
        </div>
    </div>

    <script>
        // Definisi URL untuk fetch data statistik
        const reportRoute = @json(route('penjualan.index'));

        // Fungsi untuk mengupdate statistik melalui AJAX tanpa animasi
        function updateStatistics() {
            // Gunakan URL yang sudah didefinisikan
            fetch(reportRoute, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin' // Kirim cookies untuk autentikasi
                })
                .then(response => {
                    // Cek apakah response berhasil
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update elemen dengan data baru tanpa animasi
                    document.getElementById('total-transaksi').textContent = data.total_transaksi;
                    document.getElementById('total-barang').textContent = data.total_barang_terjual;

                    // Format angka menggunakan format mata uang Indonesia
                    document.getElementById('total-omset').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(
                        data.omset);
                })
                .catch(error => {
                    console.error('Error fetching statistics:', error);
                });
        }

        // Update pertama kali halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateStatistics();

            // Update setiap 5 detik
            setInterval(updateStatistics, 5000);
        });
    </script>
</x-layout>
