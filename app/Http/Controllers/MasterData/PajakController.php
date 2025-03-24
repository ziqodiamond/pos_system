<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use App\Models\Pajak;
use Illuminate\Http\Request;

class PajakController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar pajak
        $query = Pajak::query();

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
        $pajak = $query->paginate(10);

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.pajak.partials.table', compact('pajak'))->render(),
                'pagination' => $pajak->links()->toHtml(),
            ]);
        }

        return view('master_data.pajak.index', compact('pajak', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:pajaks',
            'nama' => 'required',
            'persen' => 'required',
        ]);

        $pajak = new Pajak();
        $pajak->kode = $request->kode;
        $pajak->nama = $request->nama;
        $pajak->persen = $request->persen;
        // Jika checkbox tidak dicentang, maka status tidak ada di request sehingga dianggap 'nonaktif'
        $pajak->status = $request->has('status') ? 'active' : 'inactive';
        $pajak->save();

        return redirect()->back()->with('success', 'Pajak berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:pajaks,kode,' . $id,
            'nama' => 'required',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $pajak = Pajak::findOrFail($id);
        $pajak->kode = $request->kode;
        $pajak->nama = $request->nama;
        $pajak->keterangan = $request->keterangan;
        $pajak->status = $request->status;
        $pajak->save();

        return redirect()->back()->with('success', 'Pajak berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $pajak = Pajak::findOrFail($id);
            $pajak->delete();

            return redirect()->back()->with('success', 'Pajak berhasil dihapus');
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
                Pajak::whereIn('id', $selectedIds)->update(['status' => 'aktif']);
                return redirect()->back()->with('success', 'Status berhasil diubah!');
            }

            // Aksi massal: Hapus data
            if ($action === 'delete') {
                Pajak::whereIn('id', $selectedIds)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            // Kalau action gak dikenali
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
