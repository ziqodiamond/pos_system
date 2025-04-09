<?php

namespace App\Http\Controllers\Inventori;

use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class MutasiBarangController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $query = BarangKeluar::with([
            'user',
            'satuan',
            'barang' => function ($query) {
                $query->with('pajak', 'satuan', 'kategori');
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

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $barangKeluar = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('inventori.mutasi.index', compact('barangKeluar'))->render(),
                'pagination' => $barangKeluar->links()->toHtml(),
            ]);
        }

        return view('inventori.mutasi.index', compact('breadcrumbs', 'barangKeluar'));
    }
}
