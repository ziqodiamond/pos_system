<x-layout>
    <div class="py-4">
        <div class="mb-4">
            @include('components.breadcrumbs')
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Laporan Pajak </h1>

            {{-- Filter Periode --}}
            <div x-data="{ modalOpen: false }" class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
                <form action="{{ route('laporan.pajak') }}" method="GET"
                    class="flex flex-wrap justify-between items-end gap-4">
                    <div class="flex items-end gap-4 flex-1">
                        {{-- Bulan Field dengan Label di atas --}}
                        <div class="flex flex-col flex-1">
                            <label for="bulan"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan:</label>
                            <select id="bulan" name="bulan"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create(null, $i, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tahun Field dengan Label di atas --}}
                        <div class="flex flex-col flex-1">
                            <label for="tahun"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun:</label>
                            <select id="tahun" name="tahun"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @for ($i = Carbon\Carbon::now()->year; $i >= Carbon\Carbon::now()->year - 5; $i--)
                                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        {{-- Tombol Filter --}}
                        <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                            Filter
                        </button>

                        {{-- Tombol Export PDF --}}
                        <button type="button" @click="modalOpen = true"
                            class="flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </form>

                {{-- Modal Background Overlay --}}
                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" x-cloak
                    class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50"
                    @click.self="modalOpen = false">

                    {{-- Modal Content --}}
                    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90"
                        class="w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">

                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Export Laporan Pajak
                            </h3>
                            <button @click="modalOpen = false" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Filter Form --}}
                        <form action="{{ route('laporan.pajak.pdf') }}" method="GET" target="_blank">
                            <div class="space-y-4">
                                {{-- Periode Pajak (Bulan dan Tahun) --}}
                                <div>
                                    <label for="bulan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                                    <select id="bulan" name="bulan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::createFromDate(null, $i, 1)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label for="tahun"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                                    <select id="tahun" name="tahun"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                                {{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Format Laporan --}}
                                <div>
                                    <label for="format_laporan"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Format
                                        Laporan</label>
                                    <select id="format_laporan" name="format_laporan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="detailed">Detail Lengkap</option>
                                        <option value="summary">Ringkasan</option>
                                    </select>
                                </div>

                                {{-- Include Kategori Pajak --}}
                                <div class="flex items-center">
                                    <input id="include_kategori" type="checkbox" name="include_kategori" value="1"
                                        checked
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="include_kategori"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sertakan
                                        Ringkasan per Kategori</label>
                                </div>

                                {{-- Include Grafik Tren Pajak --}}
                                <div class="flex items-center">
                                    <input id="include_grafik" type="checkbox" name="include_grafik" value="1"
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="include_grafik"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sertakan
                                        Grafik Tren Pajak</label>
                                </div>

                                {{-- Opsi untuk Sertakan Detail Barang --}}
                                <div class="flex items-center">
                                    <input id="include_detail_barang" type="checkbox" name="include_detail_barang"
                                        value="1"
                                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="include_detail_barang"
                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sertakan
                                        Detail per Barang</label>
                                </div>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="flex items-center justify-end mt-6 space-x-2">
                                <button type="button" @click="modalOpen = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg class="inline w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Generate PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pajak -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Pajak Masukan -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">PPN Masukan</p>
                            <p class="text-2xl font-bold text-gray-800">Rp
                                {{ number_format($totalPajakMasukan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pajak Keluaran -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">PPN Keluaran</p>
                            <p class="text-2xl font-bold text-gray-800">Rp
                                {{ number_format($totalPajakKeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pajak Yang Harus Dibayar -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">PPN Yang Harus Dibayar</p>
                            <p
                                class="text-2xl font-bold {{ $pajakDibayarkan >= 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $pajakDibayarkan >= 0 ? 'Rp ' . number_format($pajakDibayarkan, 0, ',', '.') : '(Rp ' . number_format(abs($pajakDibayarkan), 0, ',', '.') . ')' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Pajak -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pajak 6 Bulan Terakhir</h2>
                    <div class="h-80">
                        <canvas id="chartPajak"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabel dan Detail -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Pajak Per Kategori -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pajak Per Kategori</h2>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kategori</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pajak</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            %</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($ringkasanPajakPerKategori as $ringkasan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $ringkasan->kategori }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                                Rp {{ number_format($ringkasan->total_pajak, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                                {{ number_format(($ringkasan->total_pajak / ($totalPajakKeluaran > 0 ? $totalPajakKeluaran : 1)) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabel Pajak -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg lg:col-span-2">
                    <div class="p-6" x-data="{ activeTab: 'masukan' }">
                        <div class="border-b border-gray-200 mb-4">
                            <nav class="-mb-px flex">
                                <button class="py-4 px-6 border-b-2 font-medium text-sm"
                                    :class="activeTab === 'masukan' ? 'border-indigo-500 text-indigo-600' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    @click="activeTab = 'masukan'">
                                    Pajak Masukan
                                </button>
                                <button class="py-4 px-6 border-b-2 font-medium text-sm"
                                    :class="activeTab === 'keluaran' ? 'border-indigo-500 text-indigo-600' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    @click="activeTab = 'keluaran'">
                                    Pajak Keluaran
                                </button>
                            </nav>
                        </div>

                        <!-- Tabel Pajak Masukan -->
                        <div x-show="activeTab === 'masukan'">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No. Ref</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Barang</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                PPN %</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah Barang</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nilai Pajak</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($pajakMasukan as $pajak)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $pajak->no_ref }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ Carbon\Carbon::parse($pajak->tanggal)->format('d M Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pajak->nama_barang }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                                    {{ $pajak->persentase_pajak ?? 0 }}%</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    {{ $pajak->jumlah_barang }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                                    Rp {{ number_format($pajak->nilai_pajak, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    Tidak ada data pajak masukan untuk periode ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-3 text-right text-sm font-medium text-gray-700">Total
                                                Pajak Masukan:</td>
                                            <td class="px-6 py-3 text-right text-sm font-medium text-gray-700">Rp
                                                {{ number_format($totalPajakMasukan, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Tabel Pajak Keluaran -->
                        <div x-show="activeTab === 'keluaran'" style="display: none;">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No. Ref</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Barang</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                PPN %</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah Barang</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nilai Pajak</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($pajakKeluaran as $pajak)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $pajak->no_ref }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ Carbon\Carbon::parse($pajak->tanggal)->format('d M Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pajak->nama_barang }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    {{ $pajak->persentase_pajak ?? 0 }}%</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    {{ $pajak->jumlah_barang ?? 0 }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                                    Rp {{ number_format($pajak->nilai_pajak, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    Tidak ada data pajak keluaran untuk periode ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-3 text-right text-sm font-medium text-gray-700">Total
                                                Pajak Keluaran:</td>
                                            <td class="px-6 py-3 text-right text-sm font-medium text-gray-700">Rp
                                                {{ number_format($totalPajakKeluaran, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Data grafikPajak untuk chart
        const bulan = @json(array_column($grafikPajak, 'bulan'));
        const pajakMasukan = @json(array_column($grafikPajak, 'pajak_masukan'));
        const pajakKeluaran = @json(array_column($grafikPajak, 'pajak_keluaran'));
        const pajakDibayarkan = @json(array_column($grafikPajak, 'pajak_dibayarkan'));

        // Inisialisasi Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartPajak').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulan,
                    datasets: [{
                            label: 'PPN Masukan',
                            data: pajakMasukan,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        },
                        {
                            label: 'PPN Keluaran',
                            data: pajakKeluaran,
                            backgroundColor: 'rgba(16, 185, 129, 0.5)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1
                        },
                        {
                            label: 'PPN Dibayarkan',
                            data: pajakDibayarkan,
                            type: 'line',
                            fill: false,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgb(239, 68, 68)',
                            tension: 0.1,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(context.raw);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Mengubah angka menjadi format mata uang
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</x-layout>
