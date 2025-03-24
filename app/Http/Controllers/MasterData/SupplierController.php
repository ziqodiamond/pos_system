<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar supplier
        $query = Supplier::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
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
        $supplier = $query->paginate(10);

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.supplier.partials.table', compact('supplier'))->render(),
                'pagination' => $supplier->links()->toHtml(),
            ]);
        }

        return view('master_data.supplier.index', compact('supplier', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:suppliers',
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kontak' => 'required',
            'email' => 'required|email',
            'catatan' => 'nullable|string',
        ]);

        $supplier = new Supplier();
        $supplier->kode = $request->kode;
        $supplier->nama = $request->nama;
        $supplier->alamat = $request->alamat;
        $supplier->kota = $request->kota;
        $supplier->kontak = $request->kontak;
        $supplier->email = $request->email;
        $supplier->catatan = $request->catatan;
        $supplier->status = $request->has('status') ? 'active' : 'inactive';
        $supplier->save();

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:suppliers,kode,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kontak' => 'required',
            'email' => 'required|email',
            'catatan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->kode = $request->kode;
        $supplier->nama = $request->nama;
        $supplier->alamat = $request->alamat;
        $supplier->kota = $request->kota;
        $supplier->kontak = $request->kontak;
        $supplier->email = $request->email;
        $supplier->catatan = $request->catatan;
        $supplier->status = $request->status;
        $supplier->save();

        return redirect()->back()->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            return redirect()->back()->with('success', 'Supplier berhasil dihapus');
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
                Supplier::whereIn('id', $selectedIds)->update(['status' => 'aktif']);
                return redirect()->back()->with('success', 'Status berhasil diubah!');
            }

            // Aksi massal: Hapus data
            if ($action === 'delete') {
                Supplier::whereIn('id', $selectedIds)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            // Kalau action gak dikenali
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
