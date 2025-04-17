<?php

namespace App\Http\Controllers\Laporan\Stok;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class MutasiMasukController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Mengambil parameter tanggal dari request atau default ke bulan ini
        $tanggalMulai = $request->input('tanggal_mulai')
            ? Carbon::parse($request->input('tanggal_mulai'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $tanggalAkhir = $request->input('tanggal_akhir')
            ? Carbon::parse($request->input('tanggal_akhir'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // Query barang masuk berdasarkan periode yang dipilih
        $query = DetailPembelian::whereBetween('created_at', [$tanggalMulai, $tanggalAkhir]);

        // Data untuk statistik
        $totalBarangMasuk = $query->sum('qty_base');
        $totalNilaiBarangMasuk = $query->sum('total');
        $jumlahTransaksi = $query->distinct('pembelian_id')->count();

        // Mengambil data untuk tabel dengan pagination
        $barangMasuk = DetailPembelian::whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->with(['barang:id,nama,kode', 'satuan:id,nama', 'satuanDasar:id,nama', 'pembelian:id,no_ref,created_at,status'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('laporan.stok.barang-masuk', compact(
            'breadcrumbs',
            'barangMasuk',
            'tanggalMulai',
            'tanggalAkhir',
            'totalBarangMasuk',
            'totalNilaiBarangMasuk',
            'jumlahTransaksi',

        ));
    }
}
