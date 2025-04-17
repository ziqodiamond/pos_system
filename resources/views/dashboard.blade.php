<x-layout>
    <div class="pb-8">
        {{-- Header Dashboard dengan informasi pengguna --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-600 mt-1">Data statistik penjualan hari ini, {{ date('d M Y') }}</p>
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

        {{-- Tabel 10 Transaksi Terbaru --}}
        <div class="bg-white rounded-xl shadow-lg mb-8">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h2>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full">10
                    Terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="latest-transactions-table">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">No. Invoice</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Kasir</th>
                            <th class="px-6 py-3 text-right">Total Barang</th>
                            <th class="px-6 py-3 text-right">Grand Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestTransactions as $transaction)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $transaction->no_ref }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->kasir->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    {{ $transaction->details->sum('kuantitas') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp
                                    {{ number_format($transaction->grand_total / 100, 0, ',', '.') }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Definisi URL untuk fetch data statistik
        const reportRoute = @json(route('dashboard'));

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

        // Fungsi untuk mengupdate tabel transaksi terbaru
        function updateLatestTransactions() {
            fetch(reportRoute + '?get_latest_transactions=1', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update tabel transaksi terbaru
                    const tableBody = document.querySelector('#latest-transactions-table tbody');

                    // Jika tidak ada transaksi
                    if (data.latest_transactions.length === 0) {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi</td>
                            </tr>
                        `;
                        return;
                    }

                    // Jika ada transaksi, update tabel
                    let html = '';
                    data.latest_transactions.forEach(transaction => {
                        html += `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium">${transaction.no_ref}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">${transaction.tanggal}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">${transaction.kasir}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">${transaction.total_barang}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rp ${transaction.grand_total}</td>
                              
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching latest transactions:', error);
                });
        }

        // Update pertama kali halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Update statistik dan transaksi terbaru
            updateStatistics();
            updateLatestTransactions();

            // Update statistik setiap 5 detik
            setInterval(updateStatistics, 5000);

            // Update transaksi terbaru setiap 15 detik
            setInterval(updateLatestTransactions, 15000);
        });
    </script>
</x-layout>
