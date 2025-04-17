<?php

namespace App\Http\Controllers\Laporan\Stok;

use Carbon\Carbon;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class MutasiKeluarController extends Controller
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

        // Query barang keluar berdasarkan periode yang dipilih
        $query = BarangKeluar::whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalAkhir]);

        // Data untuk statistik
        $totalBarangKeluar = $query->sum('kuantitas');
        $totalNilaiBarangKeluar = $query->sum('subtotal');
        $jumlahTransaksi = $query->distinct('id')->count();

        // Mengambil jenis barang keluar yang paling banyak
        $jenisBarangTerbanyak = BarangKeluar::whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw('jenis, SUM(kuantitas) as total_kuantitas')
            ->groupBy('jenis')
            ->orderByDesc('total_kuantitas')
            ->first();

        $jenisBarangTerbanyakNama = $jenisBarangTerbanyak ? $jenisBarangTerbanyak->jenis : '-';

        // Mengambil data untuk tabel dengan pagination
        $barangKeluar = BarangKeluar::whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal_keluar', 'desc')
            ->paginate(10);

        return view('laporan.stok.barang-keluar', compact(
            'breadcrumbs',
            'barangKeluar',
            'tanggalMulai',
            'tanggalAkhir',
            'totalBarangKeluar',
            'totalNilaiBarangKeluar',
            'jumlahTransaksi',
            'jenisBarangTerbanyakNama'
        ));
    }
}
