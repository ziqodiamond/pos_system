<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Setting;
use App\Models\Konversi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

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

        $barangs = Barang::select('id', 'kode', 'nama')->get();

        $satuanDasar = Satuan::where('status_satuan', 'satuan_dasar')->get();
        $satuanKonversi = Satuan::where('status_satuan', 'konversi_satuan')->get();

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.konversi.partials.table', compact('konversi'))->render(),
                'pagination' => $konversi->links()->toHtml(),
            ]);
        }

        return view('master_data.konversi.index', compact('konversi', 'breadcrumbs', 'satuanDasar', 'satuanKonversi', 'barangs'));
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

    public function generatePDF(Request $request)
    {
        // Mempersiapkan query untuk data konversi
        $query = Konversi::query();

        // Join dengan tabel terkait untuk mendapatkan nama-nama yang dibutuhkan
        $query->with(['barang', 'satuan', 'satuanTujuan']);

        // Filter berdasarkan tipe konversi jika ada
        if ($request->filled('tipe_konversi')) {
            if ($request->tipe_konversi === 'universal') {
                $query->whereNull('barang_id');
            } elseif ($request->tipe_konversi === 'barang') {
                $query->whereNotNull('barang_id');
            }
        }

        // Filter berdasarkan barang jika ada
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        // Filter berdasarkan satuan asal jika ada
        if ($request->filled('satuan_id')) {
            $query->where('satuan_id', $request->satuan_id);
        }

        // Filter berdasarkan satuan tujuan jika ada
        if ($request->filled('satuan_tujuan_id')) {
            $query->where('satuan_tujuan_id', $request->satuan_tujuan_id);
        }

        // Urutkan data
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Ambil semua data konversi yang sesuai filter
        $konversis = $query->get();

        // Hitung total data konversi
        $totalItems = $konversis->count();

        // Ambil nama barang dan satuan jika ada filter
        $filterInfo = [];
        if ($request->filled('barang_id')) {
            $barang = Barang::find($request->barang_id);
            $filterInfo['barang_nama'] = $barang ? $barang->nama : null;
        }
        if ($request->filled('satuan_id')) {
            $satuan = Satuan::find($request->satuan_id);
            $filterInfo['satuan_nama'] = $satuan ? $satuan->nama : null;
        }

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
            'konversis' => $konversis,
            'tanggal_cetak' => now()->format('d-m-Y H:i:s'),
            'filter' => array_merge($request->all(), $filterInfo),
            'total_items' => $totalItems,
            'toko' => $toko,
        ];

        // Load view PDF dan convert ke PDF
        $pdf = PDF::loadView('master_data.konversi.pdf.konversi-pdf', $data);

        // Atur paper size ke A4
        $pdf->setPaper('a4', 'portrait');

        // Download PDF dengan nama dinamis
        return $pdf->stream('laporan-konversi-satuan-' . now()->format('dmY-His') . '.pdf');
    }
}
