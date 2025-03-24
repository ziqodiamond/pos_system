<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar kategori
        $query = Kategori::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter status
        if ($request->has('status') && in_array($request->status, ['aktif', 'nonaktif'])) {
            $query->where('status', $request->status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $kategori = $query->paginate(10);

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.kategori.partials.table', compact('kategori'))->render(),
                'pagination' => $kategori->links()->toHtml(),
            ]);
        }

        return view('master_data.kategori.index', compact('kategori', 'breadcrumbs'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kategoris',
            'nama' => 'required',
        ]);

        $kategori = new Kategori();
        $kategori->kode = $request->kode;
        $kategori->nama = $request->nama;
        $kategori->save();

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:kategoris,kode,' . $id,
            'nama' => 'required',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->kode = $request->kode;
        $kategori->nama = $request->nama;
        $kategori->save();

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {

        $request->validate([
            'action' => 'required|in:edit,delete',
            'selected' => 'required|array',
            'selected.*' => 'uuid', // Validasi semua id harus UUID
        ]);

        $action = $request->input('action');
        $selectedIds = $request->input('selected');

        // Cek dulu kalau gak ada data yang dipilih
        if (!$selectedIds || count($selectedIds) == 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        try {
            // Aksi massal: Ubah status jadi "Aktif"
            if ($action === 'edit') {
                Kategori::whereIn('id', $selectedIds)->update(['status' => 'Aktif']);
                return redirect()->back()->with('success', 'Status berhasil diubah!');
            }

            // Aksi massal: Hapus data
            if ($action === 'delete') {
                Kategori::whereIn('id', $selectedIds)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            // Kalau action gak dikenali
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
