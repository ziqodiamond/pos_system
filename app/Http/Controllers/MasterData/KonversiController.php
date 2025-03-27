<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use App\Models\Konversi;
use App\Models\Satuan;
use Illuminate\Http\Request;

class KonversiController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar kategori
        $query = Konversi::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(barang_id) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(satuan_id) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(satuan_tujuan_id) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
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
        $konversi = $query->paginate(10);

        $satuanDasar = Satuan::where('status_satuan', 'satuan_dasar')->get();
        $satuanKonversi = Satuan::where('status_satuan', 'konversi_satuan')->get();

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.konversi.partials.table', compact('konversi'))->render(),
                'pagination' => $konversi->links()->toHtml(),
            ]);
        }

        return view('master_data.konversi.index', compact('konversi', 'breadcrumbs', 'satuanDasar', 'satuanKonversi'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'satuan_konversi_id' => 'required|uuid|exists:satuans,id',
                'satuan_dasar_id' => 'required|uuid|exists:satuans,id',
                'nilai_konversi' => 'required|numeric',
            ]);

            $konversi = new Konversi();
            $konversi->satuan_id = $request->satuan_konversi_id;
            $konversi->nilai_konversi = $request->nilai_konversi;
            $konversi->satuan_tujuan_id = $request->satuan_dasar_id;
            $konversi->save();

            return redirect()->back()->with('success', 'Konversi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input request yang diterima
        $request->validate([
            'satuan_konversi_id' => 'required|uuid|exists:satuans,id',
            'satuan_tujuan_id' => 'required|uuid|exists:satuans,id',
            'nilai_konversi' => 'required|numeric',
        ]);

        try {
            // Temukan data konversi berdasarkan ID
            $konversi = Konversi::findOrFail($id);

            // Update data konversi
            $konversi->satuan_id = $request->satuan_konversi_id;
            $konversi->satuan_tujuan_id = $request->satuan_tujuan_id;
            $konversi->nilai_konversi = $request->nilai_konversi;

            // Simpan perubahan jika ada
            if ($konversi->isDirty()) {
                $konversi->save();
                return redirect()->back()->with('success', 'Satuan berhasil diperbarui');
            }

            return redirect()->back()->with('info', 'Tidak ada perubahan data');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $konversi = Konversi::findOrFail($id);
            $konversi->delete();

            return redirect()->back()->with('success', 'Konversi berhasil dihapus');
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

                case 'delete':
                    Konversi::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'Konversi berhasil dihapus!');

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
