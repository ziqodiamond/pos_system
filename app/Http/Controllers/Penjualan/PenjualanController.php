<?php

namespace App\Http\Controllers\Penjualan;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Kategori;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\BreadcrumbController;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $kasirId = Auth::id();
        $today = Carbon::today();

        // Ambil data penjualan hari ini untuk kasir yang sedang login
        $penjualanHariIni = Penjualan::with('details')
            ->whereDate('created_at', $today)
            ->where('kasir_id', $kasirId)
            ->get();

        // Hitung total transaksi
        $totalTransaksi = $penjualanHariIni->count();

        // Hitung total barang terjual
        $totalBarangTerjual = $penjualanHariIni->flatMap(function ($penjualan) {
            return $penjualan->details;
        })->sum('qty_base');

        // Hitung total omset
        // Catatan: Pastikan format uang konsisten di seluruh aplikasi
        // Jika nilai uang disimpan dalam sen/kecil, gunakan pembagi 100
        $totalOmset = $penjualanHariIni->sum('grand_total');

        // Jika uang disimpan dalam format sen, uncomment baris di bawah ini
        $totalOmset = $totalOmset / 100;

        // Jika request AJAX, kembalikan data dalam format JSON
        if ($request->ajax()) {
            return Response::json([
                'total_transaksi' => $totalTransaksi,
                'total_barang_terjual' => $totalBarangTerjual,
                'omset' => $totalOmset,
            ]);
        }

        // Jika bukan request AJAX, kembalikan view dengan data yang dibutuhkan
        return view('penjualan.index', compact(
            'breadcrumbs',
            'totalTransaksi',
            'totalBarangTerjual',
            'totalOmset'
        ));
    }
}
