<?php

namespace App\Http\Controllers\Laporan\Penjualan;

use Carbon\Carbon;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PenjualanExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\BreadcrumbController;

class LaporanPenjualanController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Mengambil tanggal dari request atau default ke awal dan akhir bulan ini
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Konversi ke objek Carbon untuk manipulasi tanggal
        $startDate = Carbon::parse($tanggalMulai)->startOfDay();
        $endDate = Carbon::parse($tanggalAkhir)->endOfDay();

        // Query untuk mendapatkan data penjualan dalam periode yang dipilih beserta relasinya
        $penjualan = Penjualan::with(['details', 'kasir', 'customer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Menghitung total penjualan (grand_total) dalam periode yang dipilih
        // Karena data uang disimpan dalam integer (rupiah sen), maka tidak perlu dibagi 100 di sini
        $totalPenjualan = Penjualan::whereBetween('created_at', [$startDate, $endDate])
            ->sum('grand_total');

        // Menghitung total barang terjual dalam periode yang dipilih
        $totalBarangTerjual = DetailPenjualan::whereHas('penjualan', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('kuantitas');

        // Menghitung total omset (sama dengan totalPenjualan dalam konteks ini)
        $totalOmset = $totalPenjualan;

        return view('laporan.penjualan.penjualan', compact(
            'breadcrumbs',
            'penjualan',
            'totalPenjualan',
            'totalBarangTerjual',
            'totalOmset',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'pdf');

        if ($type == 'pdf') {
            return $this->generatePDF($request);
        } else if ($type == 'excel') {
            // Gunakan class export Excel terpisah
            return Excel::download(new PenjualanExport($request), 'laporan-pembelian-' . now()->format('dmY-His') . '.xlsx');
        } else {
            return back()->with('error', 'Tipe ekspor tidak valid');
        }
    }
    /**
     * Fungsi untuk menghasilkan laporan PDF data penjualan
     * 
     * @param Request $request - Request dari pengguna yang berisi filter dan parameter lainnya
     * @return Response - Mengembalikan file PDF yang dihasilkan
     */
    public function generatePDF(Request $request)
    {
        // Validasi request
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'kasir_id' => 'nullable|exists:users,id',
            'metode_pembayaran' => 'nullable|string',
            'sort_by' => 'nullable|in:no_ref,created_at,grand_total',
            'sort_order' => 'nullable|in:asc,desc',
        ]);

        // Mengambil filter dari request
        $filter = [
            'tanggal_mulai' => $request->tanggal_mulai ?? Carbon::now()->startOfMonth()->format('Y-m-d'),
            'tanggal_akhir' => $request->tanggal_akhir ?? Carbon::now()->format('Y-m-d'),
            'kasir_id' => $request->kasir_id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'sort_by' => $request->sort_by ?? 'created_at',
            'sort_order' => $request->sort_order ?? 'desc',
        ];

        // Query data penjualan berdasarkan filter
        $query = Penjualan::with(['details', 'kasir', 'customer'])
            ->whereDate('created_at', '>=', $filter['tanggal_mulai'])
            ->whereDate('created_at', '<=', $filter['tanggal_akhir']);

        // Filter berdasarkan kasir jika ada
        if (!empty($filter['kasir_id'])) {
            $query->where('kasir_id', $filter['kasir_id']);
        }

        // Filter berdasarkan metode pembayaran jika ada
        if (!empty($filter['metode_pembayaran'])) {
            $query->where('metode_pembayaran', $filter['metode_pembayaran']);
        }

        // Urutkan data sesuai dengan filter
        $query->orderBy($filter['sort_by'], $filter['sort_order']);

        // Ambil data penjualan
        $penjualans = $query->get();

        // Hitung total data dan ringkasan
        $total_items = $penjualans->count();
        $total_penjualan = $penjualans->sum('grand_total');
        $total_diskon = $penjualans->sum('total_diskon');
        $total_pajak = $penjualans->sum('total_pajak');
        $total_barang = $penjualans->flatMap(function ($penjualan) {
            return $penjualan->details;
        })->sum('kuantitas');

        // Siapkan data untuk dikirim ke view PDF
        $data = [
            'penjualans' => $penjualans,
            'total_items' => $total_items,
            'total_penjualan' => $total_penjualan,
            'total_diskon' => $total_diskon,
            'total_pajak' => $total_pajak,
            'total_barang' => $total_barang,
            'filter' => $filter,
            'tanggal_cetak' => Carbon::now()->format('d/m/Y H:i:s'),
        ];

        // Ambil nama kasir jika ada filter kasir
        if (!empty($filter['kasir_id'])) {
            $data['nama_kasir'] = User::find($filter['kasir_id'])->name ?? 'Tidak Ditemukan';
        }

        // Generate PDF menggunakan package PDF yang tersedia (contoh: barryvdh/laravel-dompdf)
        $pdf = PDF::loadView('laporan.penjualan.pdf.penjualan-pdf', $data);

        // Tambahkan header ke PDF
        $pdf->setPaper('a4', 'landscape');

        // Atur nama file untuk diunduh
        $filename = 'Laporan_Penjualan_' . Carbon::now()->format('Ymd_His') . '.pdf';

        // Kirim PDF sebagai response
        return $pdf->stream($filename);
    }
}
