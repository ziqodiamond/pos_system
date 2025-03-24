<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar kategori
        $query = Satuan::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter status
        if ($request->has('Jenis') && in_array($request->status, ['satuan_satuan', 'satuan_konversi'])) {
            $query->where('status_satuan', $request->status);
        }

        // Filter status
        if ($request->has('Status') && in_array($request->status, ['aktif', 'nonaktif'])) {
            $query->where('status', $request->status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $satuan = $query->paginate(10);

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.satuan.partials.table', compact('satuan'))->render(),
                'pagination' => $satuan->links()->toHtml(),
            ]);
        }

        return view('master_data.satuan.index', compact('satuan', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:satuans',
            'nama' => 'required',
            'status_satuan' => 'required|in:satuan_dasar,konversi_satuan',
            'keterangan' => 'nullable|string',
        ]);

        $satuan = new Satuan();
        $satuan->kode = $request->kode;
        $satuan->nama = $request->nama;
        $satuan->status_satuan = $request->status_satuan;
        $satuan->keterangan = $request->keterangan;
        $satuan->status = $request->has('status') ? 'active' : 'inactive';
        $satuan->save();

        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:satuans,kode,' . $id,
            'nama' => 'required',
            'status_satuan' => 'required|in:satuan_satuan,satuan_konversi',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $satuan = Satuan::findOrFail($id);
        $satuan->kode = $request->kode;
        $satuan->nama = $request->nama;
        $satuan->status_satuan = $request->status_satuan;
        $satuan->keterangan = $request->keterangan;
        $satuan->status = $request->status;
        $satuan->save();

        return redirect()->back()->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $satuan = Satuan::findOrFail($id);
            $satuan->delete();

            return redirect()->back()->with('success', 'Satuan berhasil dihapus');
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
                Satuan::whereIn('id', $selectedIds)->update(['status' => 'aktif']);
                return redirect()->back()->with('success', 'Status berhasil diubah!');
            }

            // Aksi massal: Hapus data
            if ($action === 'delete') {
                Satuan::whereIn('id', $selectedIds)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            // Kalau action gak dikenali
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
