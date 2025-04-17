<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Setting;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\BreadcrumbController;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar kategori
        $query = Kategori::query();

        // Cek apakah ingin menampilkan data yang sudah di-soft delete
        if ($request->has('Status') && $request->Status === 'deleted') {
            $query->onlyTrashed(); // Menampilkan data yang sudah di-soft delete
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
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
        $kategori->status = $request->has('status') ? 'active' : 'inactive';
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
        $kategori->status = $request->has('status') ? 'active' : 'inactive';
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

    public function forceDelete($id)
    {
        $kategori = Kategori::withTrashed()->findOrFail($id); // Mengambil kategori termasuk yang di-soft delete
        $kategori->forceDelete(); // Menghapus data secara permanen

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus secara permanen.');
    }

    // Metode untuk mengembalikan data yang di-soft delete
    public function restore($id)
    {
        $kategori = Kategori::withTrashed()->findOrFail($id); // Mengambil kategori termasuk yang di-soft delete
        $kategori->restore(); // Mengembalikan data yang di-soft delete

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dipulihkan.');
    }

    public function bulkAction(Request $request)
    {

        $request->validate([
            'action' => 'required|in:edit,delete,restore,forceDelete',
            'selected' => 'required|string',
            'status' => 'nullable|in:active,inactive',
        ], [
            'action.required' => 'Aksi wajib dipilih!',
            'action.in' => 'Aksi yang dipilih tidak valid!',
            'selected.required' => 'Tidak ada data yang dipilih!',
            'selected.string' => 'Format data tidak valid',
            'status.in' => 'Status harus berupa "aktif" atau "nonaktif"!'
        ]);

        $action = $request->input('action');
        $selectedString = $request->input('selected');
        $newStatus = $request->input('status');


        if (empty($request->input('selected'))) {
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
                        Kategori::whereIn('id', $selectedIds)->update(['status' => $newStatus]);
                        return redirect()->back()->with('success', 'Status berhasil diubah!');
                    }
                    return redirect()->back()->with('error', 'Status baru harus dipilih!');

                case 'delete':
                    Kategori::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'Data berhasil dihapus!');

                case 'restore':
                    Kategori::withTrashed()->whereIn('id', $selectedIds)->restore();
                    return redirect()->back()->with('success', 'Data berhasil direstore!');

                case 'forceDelete':
                    Kategori::withTrashed()->whereIn('id', $selectedIds)->forceDelete();
                    return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function generatePDF(Request $request)
    {
        // Mempersiapkan query untuk data kategori
        $query = Kategori::query();

        // Gunakan withCount untuk menghitung jumlah barang pada setiap kategori
        $query->withCount('barang');

        // Dan juga load relasi barang (untuk menampilkan daftar barang)
        $query->with('barang:id,kategori_id,nama');

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kode jika ada
        if ($request->filled('kode')) {
            $query->where('kode', 'like', '%' . $request->kode . '%');
        }

        // Filter berdasarkan nama jika ada
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Urutkan data
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Ambil semua data kategori yang sesuai filter
        $kategoris = $query->get();

        // Hitung total kategori dan barang
        $totalItems = $kategoris->count();
        $totalBarang = $kategoris->sum('barang_count');

        // Ambil data pengaturan toko dari database
        $settingQuery = Setting::whereIn('key', ['toko_nama', 'toko_alamat', 'toko_kota', 'toko_provinsi', 'toko_telepon', 'toko_email', 'toko_npwp'])
            ->pluck('value', 'key')
            ->toArray();

        // Siapkan data toko untuk laporan
        $toko = [
            'nama' => $settingQuery['toko_nama'] ?? env('TOKO_NAMA', 'Nama Toko'),
            'alamat' => $settingQuery['toko_alamat'] ?? env('TOKO_ALAMAT', 'Alamat Toko'),
            'kota' => $settingQuery['toko_kota'] ?? env('TOKO_KOTA', 'Jakarta'),
            'provinsi' => $settingQuery['toko_provinsi'] ?? env('TOKO_PROVINSI', 'DKI Jakarta'),
            'telepon' => $settingQuery['toko_telepon'] ?? env('TOKO_TELEPON', 'Telepon Toko'),
            'email' => $settingQuery['toko_email'] ?? env('TOKO_EMAIL', 'Email Toko'),
            'npwp' => $settingQuery['toko_npwp'] ?? env('TOKO_NPWP', 'NPWP Toko'),
        ];

        // Siapkan data untuk view PDF
        $data = [
            'kategoris' => $kategoris,
            'tanggal_cetak' => now()->format('d-m-Y H:i:s'),
            'filter' => $request->all(),
            'total_items' => $totalItems,
            'total_barang' => $totalBarang,
            'toko' => $toko,
        ];

        // Load view PDF dan convert ke PDF
        $pdf = PDF::loadView('master_data.kategori.pdf.kategori-pdf', $data);

        // Atur paper size ke A4
        $pdf->setPaper('a4', 'portrait');

        // Download PDF dengan nama dinamis
        return $pdf->stream('laporan-kategori-' . now()->format('dmY-His') . '.pdf');
    }
}
