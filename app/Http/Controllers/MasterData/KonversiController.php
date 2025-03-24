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
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required',
            'satuan_id' => 'required',
            'nilai_konversi' => 'required|numeric',
            'satuan_tujuan_id' => 'required',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $konversi = Konversi::findOrFail($id);
        $konversi->barang_id = $request->barang_id;
        $konversi->satuan_id = $request->satuan_id;
        $konversi->nilai_konversi = $request->nilai_konversi;
        $konversi->satuan_tujuan_id = $request->satuan_tujuan_id;
        $konversi->status = $request->status;
        $konversi->save();

        return redirect()->back()->with('success', 'Konversi berhasil diperbarui');
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
                Konversi::whereIn('id', $selectedIds)->update(['status' => 'aktif']);
                return redirect()->back()->with('success', 'Status berhasil diubah!');
            }

            // Aksi massal: Hapus data
            if ($action === 'delete') {
                Konversi::whereIn('id', $selectedIds)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            // Kalau action gak dikenali
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
