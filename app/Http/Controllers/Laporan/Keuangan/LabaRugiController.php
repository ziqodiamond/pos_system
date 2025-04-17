<?php

namespace App\Http\Controllers\Laporan\Keuangan;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class LabaRugiController extends Controller
{
    /**
     * Menampilkan halaman laporan laba rugi
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Mendapatkan tanggal awal dan akhir dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Mengubah format tanggal menjadi Carbon untuk memudahkan pengolahan
        $startDateObj = Carbon::parse($startDate)->startOfDay();
        $endDateObj = Carbon::parse($endDate)->endOfDay();

        // Menghitung total pendapatan dari penjualan (grand_total sudah termasuk pajak dan diskon)
        $totalPendapatan = Penjualan::whereBetween('created_at', [$startDateObj, $endDateObj])
            ->sum('grand_total');

        // Menghitung total pajak penjualan
        $totalPajak = Penjualan::whereBetween('created_at', [$startDateObj, $endDateObj])
            ->sum('total_pajak');

        // Menghitung HPP dari detail penjualan (menggunakan harga_pokok)
        $totalHPP = DetailPenjualan::whereHas('penjualan', function ($query) use ($startDateObj, $endDateObj) {
            $query->whereBetween('created_at', [$startDateObj, $endDateObj]);
        })->sum(DB::raw('kuantitas * harga_pokok'));

        // Menghitung biaya operasional dari barang keluar (selain penjualan)
        $biayaOperasional = BarangKeluar::whereBetween('tanggal_keluar', [$startDateObj, $endDateObj])
            ->whereIn('jenis', ['rusak', 'hilang', 'expire', 'konsumsi'])
            ->sum('subtotal');

        // Menghitung laba kotor (sebelum pengurangan biaya operasional dan pajak)
        $labaKotor = $totalPendapatan - $totalHPP;

        // Menghitung laba bersih (setelah pengurangan biaya operasional dan pajak)
        $labaBersih = $labaKotor - $biayaOperasional - $totalPajak;

        // Data untuk grafik pendapatan bulanan
        $pendapatanBulanan = $this->getPendapatanBulanan($startDateObj, $endDateObj);

        // Data untuk grafik produk terlaris
        $produkTerlaris = $this->getProdukTerlaris($startDateObj, $endDateObj);

        // Data untuk tabel rincian laba rugi
        $rincianLabaRugi = $this->getRincianLabaRugi($startDateObj, $endDateObj);

        return view('laporan.keuangan.laba-rugi', compact(
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalHPP',
            'biayaOperasional',
            'totalPajak',
            'labaKotor',
            'labaBersih',
            'pendapatanBulanan',
            'produkTerlaris',
            'rincianLabaRugi',
            'breadcrumbs'
        ));
    }

    /**
     * Mendapatkan data pendapatan bulanan untuk grafik
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    private function getPendapatanBulanan($startDate, $endDate)
    {
        // Membuat interval berdasarkan range tanggal
        $diffInMonths = $startDate->diffInMonths($endDate) + 1;

        if ($diffInMonths > 12) {
            $diffInMonths = 12; // Batasi maksimal 12 bulan
        }

        $data = [];

        // Jika periode kurang dari sebulan, tampilkan data harian
        if ($diffInMonths == 1 && $startDate->month == $endDate->month) {
            $penjualan = Penjualan::selectRaw('DATE(created_at) as tanggal, SUM(grand_total) as total_pendapatan, SUM(total_pajak) as total_pajak')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();

            foreach ($penjualan as $item) {
                $tglParsed = Carbon::parse($item->tanggal);
                $startOfDay = $tglParsed->copy()->startOfDay();
                $endOfDay = $tglParsed->copy()->endOfDay();

                // Menghitung HPP untuk tanggal ini
                $hpp = DetailPenjualan::whereHas('penjualan', function ($query) use ($startOfDay, $endOfDay) {
                    $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
                })->sum(DB::raw('kuantitas * harga_pokok'));

                // Menghitung biaya operasional
                $operasional = BarangKeluar::whereDate('tanggal_keluar', $tglParsed)
                    ->whereIn('jenis', ['rusak', 'hilang', 'expire', 'konsumsi'])
                    ->sum('subtotal');

                $data[] = [
                    'periode' => $tglParsed->format('d M'),
                    'pendapatan' => $item->total_pendapatan / 100, // Konversi ke format rupiah
                    'hpp' => $hpp / 100,
                    'operasional' => $operasional / 100,
                    'pajak' => $item->total_pajak / 100
                ];
            }
        } else {
            // Jika periode lebih dari sebulan, tampilkan data bulanan
            for ($i = 0; $i < $diffInMonths; $i++) {
                $currentDate = clone $startDate;
                $currentDate->addMonths($i);

                $bulanAwal = $currentDate->copy()->startOfMonth();
                $bulanAkhir = $currentDate->copy()->endOfMonth();

                // Jika melebihi endDate, batasi
                if ($bulanAkhir->gt($endDate)) {
                    $bulanAkhir = $endDate;
                }

                // Total pendapatan (sudah termasuk pajak dan diskon)
                $totalPendapatan = Penjualan::whereBetween('created_at', [$bulanAwal, $bulanAkhir])
                    ->sum('grand_total');

                // Total pajak
                $totalPajak = Penjualan::whereBetween('created_at', [$bulanAwal, $bulanAkhir])
                    ->sum('total_pajak');

                // Total HPP
                $totalHPP = DetailPenjualan::whereHas('penjualan', function ($query) use ($bulanAwal, $bulanAkhir) {
                    $query->whereBetween('created_at', [$bulanAwal, $bulanAkhir]);
                })->sum(DB::raw('kuantitas * harga_pokok'));

                // Total biaya operasional
                $totalOps = BarangKeluar::whereBetween('tanggal_keluar', [$bulanAwal, $bulanAkhir])
                    ->whereIn('jenis', ['rusak', 'hilang', 'expire', 'konsumsi'])
                    ->sum('subtotal');

                $data[] = [
                    'periode' => $currentDate->format('M Y'),
                    'pendapatan' => $totalPendapatan / 100, // Konversi ke format rupiah
                    'hpp' => $totalHPP / 100,
                    'operasional' => $totalOps / 100,
                    'pajak' => $totalPajak / 100
                ];
            }
        }

        return $data;
    }

    /**
     * Mendapatkan data produk terlaris
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Support\Collection
     */
    private function getProdukTerlaris($startDate, $endDate)
    {
        return DetailPenjualan::select(
            'barang_id',
            'nama_barang',
            DB::raw('SUM(kuantitas) as total_terjual'),
            DB::raw('SUM(total) as total_pendapatan'),
            DB::raw('SUM(kuantitas * harga_pokok) as total_hpp'),
            DB::raw('SUM(total) - SUM(kuantitas * harga_pokok) as profit')
        )
            ->whereHas('penjualan', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('barang_id', 'nama_barang')
            ->orderByDesc('profit')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->total_pendapatan = $item->total_pendapatan / 100; // Konversi ke format rupiah
                $item->total_hpp = $item->total_hpp / 100; // Konversi ke format rupiah
                $item->profit = $item->profit / 100; // Konversi ke format rupiah
                $item->margin = $item->total_hpp > 0 ? round(($item->profit / $item->total_hpp) * 100, 2) : 0;
                return $item;
            });
    }

    /**
     * Mendapatkan rincian laporan laba rugi
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    private function getRincianLabaRugi($startDate, $endDate)
    {
        // Data penjualan per hari dalam periode
        $penjualanHarian = Penjualan::selectRaw('DATE(created_at) as tanggal, SUM(grand_total) as total_pendapatan, SUM(total_pajak) as total_pajak')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $result = [];

        foreach ($penjualanHarian as $penjualan) {
            $tanggal = Carbon::parse($penjualan->tanggal);
            $startOfDay = $tanggal->copy()->startOfDay();
            $endOfDay = $tanggal->copy()->endOfDay();

            // Menghitung HPP untuk tanggal ini
            $hpp = DetailPenjualan::whereHas('penjualan', function ($query) use ($startOfDay, $endOfDay) {
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            })->sum(DB::raw('kuantitas * harga_pokok'));

            // Menghitung biaya operasional (barang rusak, hilang, dll)
            $biayaOps = BarangKeluar::whereDate('tanggal_keluar', $tanggal)
                ->whereIn('jenis', ['rusak', 'hilang', 'expire', 'konsumsi'])
                ->sum('subtotal');

            // Menghitung laba
            $labaKotor = $penjualan->total_pendapatan - $hpp;
            $labaBersih = $labaKotor - $biayaOps - $penjualan->total_pajak;

            $result[] = [
                'tanggal' => $tanggal->format('d M Y'),
                'pendapatan' => $penjualan->total_pendapatan / 100,
                'hpp' => $hpp / 100,
                'laba_kotor' => $labaKotor / 100,
                'biaya_operasional' => $biayaOps / 100,
                'pajak' => $penjualan->total_pajak / 100,
                'laba_bersih' => $labaBersih / 100,
            ];
        }

        return $result;
    }

    /**
     * Mengekspor laporan laba rugi ke Excel
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // TODO: Implementasi ekspor ke Excel
        return back()->with('success', 'Fitur ekspor akan segera tersedia');
    }

    /**
     * Menghasilkan dan mengunduh laporan laba rugi dalam format PDF
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        // Mendapatkan tanggal awal dan akhir dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Mengubah format tanggal menjadi Carbon untuk memudahkan pengolahan
        $startDateObj = Carbon::parse($startDate)->startOfDay();
        $endDateObj = Carbon::parse($endDate)->endOfDay();

        // Menghitung total pendapatan dari penjualan (grand_total sudah termasuk pajak dan diskon)
        $totalPendapatan = Penjualan::whereBetween('created_at', [$startDateObj, $endDateObj])
            ->sum('grand_total');

        // Menghitung total pajak penjualan
        $totalPajak = Penjualan::whereBetween('created_at', [$startDateObj, $endDateObj])
            ->sum('total_pajak');

        // Menghitung HPP dari detail penjualan (menggunakan harga_pokok)
        $totalHPP = DetailPenjualan::whereHas('penjualan', function ($query) use ($startDateObj, $endDateObj) {
            $query->whereBetween('created_at', [$startDateObj, $endDateObj]);
        })->sum(DB::raw('kuantitas * harga_pokok'));

        // Menghitung biaya operasional dari barang keluar (selain penjualan)
        $biayaOperasional = BarangKeluar::whereBetween('tanggal_keluar', [$startDateObj, $endDateObj])
            ->whereIn('jenis', ['rusak', 'hilang', 'expire', 'konsumsi'])
            ->sum('subtotal');

        // Menghitung laba kotor (sebelum pengurangan biaya operasional dan pajak)
        $labaKotor = $totalPendapatan - $totalHPP;

        // Menghitung laba bersih (setelah pengurangan biaya operasional dan pajak)
        $labaBersih = $labaKotor - $biayaOperasional - $totalPajak;

        // Data untuk grafik produk terlaris
        $produkTerlaris = $this->getProdukTerlaris($startDateObj, $endDateObj);

        // Data untuk tabel rincian laba rugi
        $rincianLabaRugi = $this->getRincianLabaRugi($startDateObj, $endDateObj);

        // Mendapatkan data toko dari config
        $toko = [
            'nama' => $settingQuery['toko_nama'] ?? env('TOKO_NAMA', 'Nama Toko'),
            'alamat' => $settingQuery['toko_alamat'] ?? env('TOKO_ALAMAT', 'Alamat Toko'),
            'kota' => $settingQuery['toko_kota'] ?? env('TOKO_KOTA', 'Jakarta'),
            'provinsi' => $settingQuery['toko_provinsi'] ?? env('TOKO_PROVINSI', 'DKI Jakarta'),
            'telepon' => $settingQuery['toko_telepon'] ?? env('TOKO_TELEPON', 'Telepon Toko'),
            'email' => $settingQuery['toko_email'] ?? env('TOKO_EMAIL', 'Email Toko'),
            'npwp' => $settingQuery['toko_npwp'] ?? env('TOKO_NPWP', 'NPWP Toko'),
        ];

        // Tanggal cetak laporan
        $tanggalCetak = Carbon::now()->format('d/m/Y H:i:s');

        // Nama file PDF
        $fileName = 'laporan-laba-rugi-' . $startDate . '-' . $endDate . '.pdf';

        // Format angka untuk tampilan laporan
        foreach ($rincianLabaRugi as &$item) {
            $item['pendapatan'] = number_format($item['pendapatan'], 0, ',', '.');
            $item['hpp'] = number_format($item['hpp'], 0, ',', '.');
            $item['laba_kotor'] = number_format($item['laba_kotor'], 0, ',', '.');
            $item['biaya_operasional'] = number_format($item['biaya_operasional'], 0, ',', '.');
            $item['pajak'] = number_format($item['pajak'], 0, ',', '.');
            $item['laba_bersih'] = number_format($item['laba_bersih'], 0, ',', '.');
        }

        foreach ($produkTerlaris as &$item) {
            $item->total_pendapatan = number_format($item->total_pendapatan, 0, ',', '.');
            $item->total_hpp = number_format($item->total_hpp, 0, ',', '.');
            $item->profit = number_format($item->profit, 0, ',', '.');
        }

        $data = [
            'toko' => $toko,
            'tanggal_cetak' => $tanggalCetak,
            'startDate' => Carbon::parse($startDate)->format('d/m/Y'),
            'endDate' => Carbon::parse($endDate)->format('d/m/Y'),
            'totalPendapatan' => number_format($totalPendapatan / 100, 0, ',', '.'),
            'totalHPP' => number_format($totalHPP / 100, 0, ',', '.'),
            'biayaOperasional' => number_format($biayaOperasional / 100, 0, ',', '.'),
            'totalPajak' => number_format($totalPajak / 100, 0, ',', '.'),
            'labaKotor' => number_format($labaKotor / 100, 0, ',', '.'),
            'labaBersih' => number_format($labaBersih / 100, 0, ',', '.'),
            'produkTerlaris' => $produkTerlaris,
            'rincianLabaRugi' => $rincianLabaRugi,
        ];

        // Buat PDF dengan Dompdf
        $pdf = PDF::loadView('laporan.keuangan.pdf.laba-rugi-pdf', $data);

        // Set paper ke ukuran A4
        $pdf->setPaper('a4', 'portrait');

        // Download PDF
        return $pdf->stream($fileName);
    }
}
