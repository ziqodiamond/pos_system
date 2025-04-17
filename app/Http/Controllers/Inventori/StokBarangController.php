<?php

namespace App\Http\Controllers\Inventori;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class StokBarangController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $query = Barang::with([
            'kategori',
            'satuan',
            'pajak',
            'detailPembelian',
            'konversi' => function ($query) {
                $query->with('satuan', 'satuanTujuan');
            }
        ]);

        // Menampilkan data yang sudah di-soft delete jika status deleted
        if ($request->has('Status') && $request->Status === 'deleted') {
            $query->onlyTrashed();
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter Pajak
        if ($request->has('Pajak') && $request->Pajak != '') {
            $query->where('pajak_id', $request->Pajak);
        }

        // Filter Kategori
        if ($request->has('Kategori') && $request->Kategori != '') {
            $query->where('kategori_id', $request->Kategori);
        }

        // Filter status
        if ($request->has('Status') && in_array($request->Status, ['active', 'inactive'])) {
            $query->where('status', $request->Status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        $barang = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('inventori.stok-barang.partials.table', compact('barang'))->render(),
                'pagination' => $barang->links()->toHtml(),
            ]);
        }

        return view('inventori.stok-barang.index', compact('barang', 'breadcrumbs',));
    }
}
