<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar supplier
        $query = Supplier::query();

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
            'npwp' => 'nullable|unique:suppliers',
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kontak' => 'required',
            'email' => 'required|email',
            'catatan' => 'nullable|string',
        ]);

        $supplier = new Supplier();
        $supplier->kode = $request->kode;
        $supplier->npwp = $request->npwp;
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
            'npwp' => 'nullable|unique:suppliers,npwp,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kontak' => 'required',
            'email' => 'required|email',
            'catatan' => 'nullable|string',
        ]);

        $supplier = Supplier::findOrFail($id);

        // Cek perubahan data
        $dataToUpdate = [];
        $fields = ['kode', 'npwp', 'nama', 'alamat', 'kota', 'kontak', 'email', 'catatan'];

        foreach ($fields as $field) {
            if ($request->$field !== $supplier->$field) {
                $dataToUpdate[$field] = $request->$field;
            }
        }

        // Cek status apakah berubah
        $newStatus = $request->has('status') ? 'active' : 'inactive';
        if ($newStatus !== $supplier->status) {
            $dataToUpdate['status'] = $newStatus;
        }

        // Kalau ada yang berubah, simpan
        if (!empty($dataToUpdate)) {
            $supplier->update($dataToUpdate);
            return redirect()->back()->with('success', 'Supplier berhasil diperbarui');
        }

        return redirect()->back()->with('info', 'Tidak ada perubahan data');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->delete(); // Soft Delete
        return redirect()->back()->with('success', 'Supplier berhasil dihapus sementara');
    }

    public function restore($id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);

        $supplier->restore(); // Restore data yang terhapus
        return redirect()->back()->with('success', 'Supplier berhasil dipulihkan');
    }

    public function forceDelete($id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);

        $supplier->forceDelete(); // Hapus permanen
        return redirect()->back()->with('success', 'Supplier berhasil dihapus permanen');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:edit,delete,restore,forceDelete',
            'selected' => 'required|string',
            'status' => 'nullable|in:active,inactive'
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
                        Supplier::whereIn('id', $selectedIds)->update(['status' => $newStatus]);
                        return redirect()->back()->with('success', 'Status berhasil diubah!');
                    }
                    return redirect()->back()->with('error', 'Status baru harus dipilih!');

                case 'delete':
                    Supplier::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'Data berhasil dihapus!');

                case 'restore':
                    Supplier::withTrashed()->whereIn('id', $selectedIds)->restore();
                    return redirect()->back()->with('success', 'Data berhasil direstore!');

                case 'forceDelete':
                    Supplier::withTrashed()->whereIn('id', $selectedIds)->forceDelete();
                    return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghasilkan file PDF untuk laporan data supplier
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        // Mempersiapkan query untuk data supplier
        $query = Supplier::query();

        // Cek apakah menampilkan data yang sudah dihapus
        if ($request->has('status') && $request->status === 'deleted') {
            $query->onlyTrashed(); // Menampilkan data yang sudah di-soft delete
        }

        // Filter berdasarkan status jika ada
        if ($request->filled('status') && $request->status !== 'deleted') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kota jika ada
        if ($request->filled('kota')) {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        // Urutkan data
        $sortBy = $request->input('sort_by', 'nama');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Ambil semua data supplier yang sesuai filter
        $suppliers = $query->with(['pembelian'])->get();

        // Hitung total transaksi pembelian
        $totalTransaksi = $suppliers->sum(function ($supplier) {
            return $supplier->pembelian->count();
        });

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
            'suppliers' => $suppliers,
            'tanggal_cetak' => now()->format('d-m-Y H:i:s'),
            'filter' => $request->all(),
            'total_transaksi' => $totalTransaksi,
            'total_items' => $suppliers->count(),
            'toko' => $toko,
        ];

        // Load view PDF dan convert ke PDF
        $pdf = PDF::loadView('master_data.supplier.pdf.supplier-pdf', $data);

        // Atur paper size ke A4
        $pdf->setPaper('a4', 'portrait');

        // Download PDF dengan nama dinamis
        return $pdf->stream('laporan-supplier-' . now()->format('dmY-His') . '.pdf');
    }
}
