<?php

namespace App\Http\Controllers\Penjualan;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class DaftarPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $query = Penjualan::with([
            'kasir',
            'customer',
            'retur',
            'details' => function ($query) {
                $query->with('barang', 'satuan');
            },
        ]);

        // Cek apakah ingin menampilkan data yang sudah di-soft delete
        if ($request->has('Status') && $request->Status === 'deleted') {
            $query->onlyTrashed(); // Menampilkan data yang sudah di-soft delete
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(no_ref) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(supplier) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(user) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(user) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter status
        if ($request->has('Metode Pembayaran') && in_array($request->status, ['tunai', 'kartu', 'qris', 'transfer'])) {
            $query->where('metode_pembayaran', $request->status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $penjualan = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('penjualan.daftar-pembelian.index', compact('penjualan'))->render(),
                'pagination' => $penjualan->links()->toHtml(),
            ]);
        }

        return view('penjualan.daftar-penjualan.index', compact('breadcrumbs', 'penjualan'));
    }
}
