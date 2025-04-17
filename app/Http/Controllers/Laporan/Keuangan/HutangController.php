<?php

namespace App\Http\Controllers\Laporan\Keuangan;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\FakturPembelian;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class HutangController extends Controller
{
    public function index(Request $request)
    {

        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Memulai query dasar
        $query = FakturPembelian::with('supplier');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_faktur', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal_faktur', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Menghitung statistik untuk card ringkasan
        // Total faktur berdasarkan status
        $fakturCount = (clone $query)->count();
        $fakturHutangCount = (clone $query)->where('status', 'hutang')->count();
        $fakturLunasCount = (clone $query)->where('status', 'lunas')->count();

        // Total hutang dan total bayar
        $totalHutang = (clone $query)->where('status', 'hutang')->sum('total_hutang');
        $totalBayar = (clone $query)->sum('total_bayar');

        // Mengambil data suppliers untuk filter dropdown
        $suppliers = Supplier::orderBy('nama')->get();

        // Mengambil data faktur pembelian dengan pagination
        $fakturPembelian = $query->orderBy('tanggal_faktur', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('laporan.keuangan.hutang', compact(
            'fakturPembelian',
            'suppliers',
            'breadcrumbs',
            'fakturCount',
            'fakturHutangCount',
            'fakturLunasCount',
            'totalHutang',
            'totalBayar'
        ));
    }
}
