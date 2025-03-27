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
        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'kode' => 'required|unique:satuans,kode,' . $id,
            'nama' => 'required',
            'status_satuan' => 'required|in:satuan_satuan,konversi_satuan',
            'keterangan' => 'nullable|string',
        ]);

        $satuan = Satuan::findOrFail($id);
        try {
            // Create array of new values
            $satuan->fill([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'status_satuan' => $request->status_satuan,
                'keterangan' => $request->keterangan,
                'status' => $request->filled('status') ? 'active' : 'inactive'
            ]);

            if ($satuan->isDirty()) {
                $satuan->save();
                return redirect()->back()->with('success', 'Satuan berhasil diperbarui');
            }

            return redirect()->back()->with('info', 'Tidak ada perubahan yang dilakukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
            'selected' => 'required|string',
            'status' => 'nullable|in:active,inactive'
        ], [
            'action.required' => 'Aksi wajib dipilih!',
            'action.in' => 'Aksi yang dipilih tidak valid!',
            'selected.required' => 'Tidak ada data yang dipilih!',
            'selected.string' => 'Format data tidak valid!',
            'status.in' => 'Status harus berupa "active" atau "inactive"!'
        ]);

        $action = $request->input('action');
        $selectedString = $request->input('selected');
        $newStatus = $request->input('status');

        if (empty($selectedString)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        $selectedIds = array_filter(array_map('trim', explode(',', $selectedString)));

        foreach ($selectedIds as $id) {
            if (!preg_match('/^[a-f0-9\-]{36}$/', $id)) {
                return redirect()->back()->with('error', 'ID tidak valid!');
            }
        }

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        try {
            switch ($action) {
                case 'edit':
                    if ($newStatus) {
                        Satuan::whereIn('id', $selectedIds)->update(['status' => $newStatus]);
                        return redirect()->back()->with('success', 'Status customer berhasil diubah!');
                    }
                    return redirect()->back()->with('error', 'Status baru harus dipilih!');

                case 'delete':
                    Satuan::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'Customer berhasil dihapus!');

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
