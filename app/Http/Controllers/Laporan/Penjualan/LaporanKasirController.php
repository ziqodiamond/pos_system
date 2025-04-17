<?php

namespace App\Http\Controllers\Laporan\Penjualan;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class LaporanKasirController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan kasir
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        // Pengaturan periode default (hari ini) jika tidak ada tanggal yang dipilih
        $tanggal = $request->tanggal ?? Carbon::now()->format('Y-m-d');
        $tanggalObj = Carbon::parse($tanggal);

        // Mendapatkan statistik jumlah kasir dan transaksi
        $jumlahKasir = User::whereHas('penjualan', function ($query) use ($tanggalObj) {
            $query->whereDate('created_at', $tanggalObj);
        })->count();

        $jumlahTransaksi = Penjualan::whereDate('created_at', $tanggalObj)->count();

        // Filter dan pengurutan
        $sortBy = $request->sort_by ?? 'transaksi';
        $sortDirection = $request->sort_direction ?? 'desc';

        // Query untuk mendapatkan data kasir dan jumlah transaksinya
        $kasirQuery = User::whereIn('role', ['kasir', 'admin', 'super_admin'])
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                DB::raw('COUNT(penjualans.id) as jumlah_transaksi'),
                DB::raw('SUM(penjualans.grand_total) as total_penjualan')
            ])
            ->leftJoin('penjualans', function ($join) use ($tanggalObj) {
                $join->on('users.id', '=', 'penjualans.kasir_id')
                    ->whereDate('penjualans.created_at', $tanggalObj);
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at');

        // Menerapkan pengurutan (sorting)
        if ($sortBy === 'transaksi') {
            $kasirQuery->orderBy('jumlah_transaksi', $sortDirection);
        } elseif ($sortBy === 'usia_akun') {
            $kasirQuery->orderBy('users.created_at', $sortDirection);
        } elseif ($sortBy === 'total_penjualan') {
            $kasirQuery->orderBy('total_penjualan', $sortDirection);
        } else {
            $kasirQuery->orderBy('users.name', 'asc');
        }

        // Pagination
        $daftarKasir = $kasirQuery->paginate(10)->withQueryString();

        return view('laporan.penjualan.kasir', compact('breadcrumbs', 'daftarKasir', 'jumlahKasir', 'jumlahTransaksi', 'tanggal', 'sortBy', 'sortDirection'));
    }

    /**
     * Mendapatkan detail transaksi kasir untuk ditampilkan dalam modal
     * 
     * @param Request $request
     * @param int $kasirId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailTransaksi(Request $request, $kasirId)
    {
        $tanggal = $request->tanggal ?? Carbon::now()->format('Y-m-d');
        $tanggalObj = Carbon::parse($tanggal);

        $transaksi = Penjualan::with(['detailPenjualan', 'customer'])
            ->where('kasir_id', $kasirId)
            ->whereDate('created_at', $tanggalObj)
            ->latest()
            ->paginate(5);

        return response()->json([
            'transaksi' => $transaksi,
            'pagination' => [
                'total' => $transaksi->total(),
                'per_page' => $transaksi->perPage(),
                'current_page' => $transaksi->currentPage(),
                'last_page' => $transaksi->lastPage(),
                'from' => $transaksi->firstItem(),
                'to' => $transaksi->lastItem()
            ]
        ]);
    }
}
