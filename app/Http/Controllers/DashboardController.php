<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\BreadcrumbController;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        $today = Carbon::today();
        $user = Auth::user();

        // Query base
        $query = Penjualan::with('details')
            ->whereDate('created_at', $today);

        // Query untuk transaksi terbaru
        $latestTransactionsQuery = Penjualan::with(['details', 'kasir']);

        // Filter by kasir_id only if user is not admin or super_admin
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            $query->where('kasir_id', $user->id);
            $latestTransactionsQuery->where('kasir_id', $user->id);
        }

        $penjualanHariIni = $query->get();

        // Ambil 10 transaksi terbaru
        $latestTransactions = $latestTransactionsQuery
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Hitung total transaksi
        $totalTransaksi = $penjualanHariIni->count();

        // Hitung total barang terjual
        $totalBarangTerjual = $penjualanHariIni->flatMap(function ($penjualan) {
            return $penjualan->details;
        })->sum('kuantitas');

        // Hitung total omset
        // Catatan: Pastikan format uang konsisten di seluruh aplikasi
        // Jika nilai uang disimpan dalam sen/kecil, gunakan pembagi 100
        $totalOmset = $penjualanHariIni->sum('grand_total');

        // Jika uang disimpan dalam format sen, uncomment baris di bawah ini
        $totalOmset = $totalOmset / 100;

        // Jika request AJAX, kembalikan data dalam format JSON
        if ($request->ajax()) {
            // Jika request meminta data transaksi terbaru
            if ($request->has('get_latest_transactions')) {
                return Response::json([
                    'latest_transactions' => $latestTransactions->map(function ($transaction) {
                        return [
                            'id' => $transaction->id,
                            'no_ref' => $transaction->no_ref,
                            'tanggal' => Carbon::parse($transaction->created_at)->format('d M Y H:i'),
                            'kasir' => $transaction->kasir->name,
                            'total_barang' => $transaction->details->sum('kuantitas'),
                            'grand_total' => number_format($transaction->grand_total / 100, 0, ',', '.'),
                        ];
                    })
                ]);
            }

            // Response default untuk update statistik
            return Response::json([
                'total_transaksi' => $totalTransaksi,
                'total_barang_terjual' => $totalBarangTerjual,
                'omset' => $totalOmset,
            ]);
        }

        // Jika bukan request AJAX, kembalikan view dengan data yang dibutuhkan
        return view('dashboard', compact(
            'breadcrumbs',
            'totalTransaksi',
            'totalBarangTerjual',
            'totalOmset',
            'latestTransactions'
        ));
    }
}
