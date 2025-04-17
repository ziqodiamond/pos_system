<?php

namespace App\Http\Controllers\Laporan\Pembelian;

use Carbon\Carbon;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PembelianExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\BreadcrumbController;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Inisialisasi tanggal awal dan akhir dari request
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        $status = $request->input('status', '');
        $tahun = Carbon::now()->format('Y');

        // Query dasar pembelian
        $pembelianQuery = Pembelian::with('supplier')
            ->whereBetween('tanggal_pembelian', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal_pembelian', 'desc');

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $pembelianQuery->where('status', $status);
        }

        // Ambil data pembelian dengan pagination
        $pembelian = $pembelianQuery->paginate(10);

        // Hitung total pembelian
        $totalPembelian = Pembelian::whereBetween('tanggal_pembelian', [$tanggalMulai, $tanggalAkhir])
            ->when(!empty($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->sum('total');

        // Hitung total barang dibeli
        $totalBarangDibeli = DetailPembelian::whereHas('pembelian', function ($query) use ($tanggalMulai, $tanggalAkhir, $status) {
            $query->whereBetween('tanggal_pembelian', [$tanggalMulai, $tanggalAkhir]);
            if (!empty($status)) {
                $query->where('status', $status);
            }
        })->sum('qty_base');

        // Hitung total pajak
        $totalPajak = Pembelian::whereBetween('tanggal_pembelian', [$tanggalMulai, $tanggalAkhir])
            ->when(!empty($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->sum('pajak_value');

        // Data untuk chart pembelian bulanan
        $chartData = $this->getChartData($tahun, $status);

        return view('laporan.pembelian.index', compact(
            'breadcrumbs',
            'pembelian',
            'tanggalMulai',
            'tanggalAkhir',
            'status',
            'totalPembelian',
            'totalBarangDibeli',
            'totalPajak',
            'chartData',
            'tahun'
        ));
    }

    private function getChartData($tahun, $status = null)
    {
        // Inisialisasi array bulan
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        // Query data pembelian per bulan
        $dataBulanan = Pembelian::select(
            DB::raw('EXTRACT(MONTH FROM tanggal_pembelian) as bulan'),
            DB::raw('SUM(total) as total')
        )
            ->whereYear('tanggal_pembelian', $tahun)
            ->when(!empty($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_pembelian)'))
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Format data untuk chart
        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $bulan[$i - 1];
            $data[] = $dataBulanan->has($i) ? $dataBulanan[$i]->total : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'pdf');

        if ($type == 'pdf') {
            return $this->generatePDF($request);
        } else if ($type == 'excel') {
            // Gunakan class export Excel terpisah
            return Excel::download(new PembelianExport($request), 'laporan-pembelian-' . now()->format('dmY-His') . '.xlsx');
        } else {
            return back()->with('error', 'Tipe ekspor tidak valid');
        }
    }

    public function generatePDF(Request $request)
    {
        // Inisialisasi tanggal awal dan akhir dari request
        $startDate = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        $status = $request->input('status', '');
        $format = $request->input('format', 'detail');
        $supplierId = $request->input('supplier_id');

        // Query dasar pembelian
        $pembelianQuery = Pembelian::with(['supplier', 'details.barang'])
            ->whereBetween('tanggal_pembelian', [$startDate, $endDate])
            ->orderBy('tanggal_pembelian', 'desc');

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $pembelianQuery->where('status', $status);
        }

        // Filter berdasarkan supplier jika ada
        if (!empty($supplierId)) {
            $pembelianQuery->where('supplier_id', $supplierId);
        }

        // Ambil data pembelian
        $pembelian = $pembelianQuery->get();

        // Inisialisasi variabel dengan nilai default
        $totalPembelian = 0;
        $totalBarangDibeli = 0;
        $totalPajak = 0;
        $totalDiskon = 0;
        $totalBiayaLainnya = 0;

        // Hitung total-total jika ada data pembelian
        if ($pembelian && $pembelian->count() > 0) {
            $totalPembelian = $pembelian->sum('total');

            // Hitung total barang dibeli
            foreach ($pembelian as $p) {
                if ($p->detailPembelian) {
                    $totalBarangDibeli += $p->detailPembelian->sum('qty_base');
                }
            }

            // Hitung total pajak
            $totalPajak = $pembelian->sum('pajak_value');

            // Hitung total diskon
            $totalDiskon = $pembelian->sum('diskon_value');

            // Hitung total biaya lainnya
            $totalBiayaLainnya = $pembelian->sum('biaya_lainnya');
        }

        // Ambil data barang terbanyak dibeli
        $barangTerbanyak = [];
        if ($pembelian && $pembelian->count() > 0) {
            $barangTerbanyak = DetailPembelian::with('barang')
                ->whereHas('pembelian', function ($query) use ($startDate, $endDate, $status, $supplierId) {
                    $query->whereBetween('tanggal_pembelian', [$startDate, $endDate]);
                    if (!empty($status)) {
                        $query->where('status', $status);
                    }
                    if (!empty($supplierId)) {
                        $query->where('supplier_id', $supplierId);
                    }
                })
                ->select('barang_id', DB::raw('SUM(qty_base) as total_qty'), DB::raw('SUM(total) as total_value'))
                ->groupBy('barang_id')
                ->orderBy('total_qty', 'desc')
                ->limit(10)
                ->get();
        }

        // Ambil data toko dari konfigurasi sistem
        $toko = [
            'nama' => config('app.name', 'Sistem POS'),
            'alamat' => config('app.alamat', 'Jalan Sample No. 123'),
            'kota' => config('app.kota', 'Jakarta'),
            'provinsi' => config('app.provinsi', 'DKI Jakarta'),
            'telepon' => config('app.telepon', '021-12345678'),
            'email' => config('app.email', 'info@example.com')
        ];

        // Rincian pembelian per hari
        $rincianPembelian = [];
        if ($startDate && $endDate) {
            $tanggalRange = Carbon::parse($startDate)->daysUntil($endDate);

            foreach ($tanggalRange as $tanggal) {
                $tanggalFormat = $tanggal->format('Y-m-d');
                $pembelianHarian = $pembelian ? $pembelian->filter(function ($p) use ($tanggalFormat) {
                    return Carbon::parse($p->tanggal_pembelian)->format('Y-m-d') === $tanggalFormat;
                }) : collect();

                $rincianPembelian[] = [
                    'tanggal' => $tanggal->format('d-m-Y'),
                    'total_pembelian' => $pembelianHarian->sum('total'),
                    'total_barang' => $pembelianHarian->sum(function ($p) {
                        return $p->detailPembelian ? $p->detailPembelian->sum('qty_base') : 0;
                    }),
                    'pajak' => $pembelianHarian->sum('pajak_value'),
                    'diskon' => $pembelianHarian->sum('diskon_value'),
                    'biaya_lainnya' => $pembelianHarian->sum('biaya_lainnya'),
                    'jumlah_transaksi' => $pembelianHarian->count()
                ];
            }
        }

        // Format tanggal untuk tampilan
        $tanggal_cetak = Carbon::now()->format('d F Y H:i:s');
        $startDateFormatted = $startDate ? Carbon::parse($startDate)->format('d F Y') : '';
        $endDateFormatted = $endDate ? Carbon::parse($endDate)->format('d F Y') : '';

        // Tentukan view berdasarkan format
        $viewName = 'laporan.pembelian.pdf.pembelian-pdf';

        // Generate PDF
        $pdf = PDF::loadView($viewName, compact(
            'pembelian',
            'totalPembelian',
            'totalBarangDibeli',
            'totalPajak',
            'totalDiskon',
            'totalBiayaLainnya',
            'barangTerbanyak',
            'toko',
            'tanggal_cetak',
            'startDate',
            'endDate',
            'startDateFormatted',
            'endDateFormatted',
            'rincianPembelian',
            'status',
            'format',
            'supplierId'
        ));

        // Setting PDF
        $pdf->setPaper('a4', 'portrait');
        $filename = 'Laporan_Pembelian_' . $startDate . '_' . $endDate . '.pdf';

        // Download PDF
        return $pdf->stream($filename);
    }
}
