<?php

namespace App\Http\Controllers\Laporan\Stok;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class StokMinimumController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Mengambil parameter filter
        $kategoriId = $request->input('kategori_id');
        $persentaseMinimum = $request->input('persentase_minimum', 100); // Default 100% dari stok minimum

        // Query dasar untuk barang
        $query = Barang::with(['kategori:id,nama', 'satuan:id,nama'])
            ->select('barangs.*')
            ->selectRaw('(stok / stok_minimum * 100) as persentase_stok')
            ->whereRaw('stok_minimum > 0') // Hanya barang yang memiliki stok minimum
            ->whereRaw('(stok / stok_minimum * 100) <= ?', [$persentaseMinimum]);

        // Filter berdasarkan kategori jika dipilih
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        // Mengurutkan berdasarkan persentase stok (terendah dulu)
        $barang = $query->orderByRaw('(stok / stok_minimum * 100) ASC')->paginate(10);

        // Mengambil data untuk statistik
        $totalBarang = $query->count();


        // Menghitung jumlah barang dengan stok kritis (di bawah stok minimum)
        $barangDibawahMinimum = Barang::whereRaw('stok < stok_minimum')->count();

        // Menghitung jumlah barang dengan stok = 0
        $barangKosong = Barang::where('stok', 0)->where('stok_minimum', '>', 0)->count();

        // Mengambil daftar kategori untuk dropdown filter
        $kategoriList = Kategori::pluck('nama', 'id');

        return view('laporan.stok.stok-minimum', compact(
            'breadcrumbs',
            'barang',
            'totalBarang',
            'barangDibawahMinimum',
            'barangKosong',
            'kategoriList',
            'kategoriId',
            'persentaseMinimum'
        ));
    }
}
