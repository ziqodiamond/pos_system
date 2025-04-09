<?php

namespace App\Http\Controllers\Pembelian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;
use App\Models\Pembelian;

class DaftarPembelianController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $query = Pembelian::with([
            'supplier',
            'user',
            'faktur',
            'details' => function ($query) {
                $query->with('barang', 'satuan');
            },
        ]);

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
        if ($request->has('Status') && in_array($request->status, ['processing', 'completed', 'canceled'])) {
            $query->where('status', $request->status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $pembelian = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pembelian.daftar-pembelian.index', compact('pembelian'))->render(),
                'pagination' => $pembelian->links()->toHtml(),
            ]);
        }

        return view('pembelian.daftar-pembelian.index', compact('breadcrumbs', 'pembelian'));
    }

    public function completed($id)
    {
        try {
            $pembelian = Pembelian::with(['details.barang'])->findOrFail($id);

            foreach ($pembelian->details as $detail) {
                $barang = $detail->barang;
                $barang->stok += $detail->stok;
                $barang->save();
            }

            $pembelian->status = 'completed';
            $pembelian->tanggal_masuk = now();
            $pembelian->save();

            return redirect()->back()->with('success', 'Pembelian berhasil diselesaikan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $pembelian = Pembelian::findOrFail($id);
            $pembelian->status = 'canceled';
            $pembelian->save();

            return redirect()->back()->with('success', 'Pembelian berhasil dibatalkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
