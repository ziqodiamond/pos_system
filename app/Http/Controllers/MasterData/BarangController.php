<?php

namespace App\Http\Controllers\MasterData;

use Exception;
use App\Models\Pajak;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Setting;
use App\Models\Kategori;
use App\Models\Konversi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BreadcrumbController;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $query = Barang::with([
            'kategori',
            'satuan',
            'pajak',
            'konversi' => function ($query) {
                $query->with('satuan', 'satuanTujuan');
            }
        ]);

        // Menampilkan data yang sudah di-soft delete jika status deleted
        if ($request->has('Status') && $request->Status === 'deleted') {
            $query->onlyTrashed();
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter Pajak
        if ($request->has('Pajak') && $request->Pajak != '') {
            $query->where('pajak_id', $request->Pajak);
        }

        // Filter Kategori
        if ($request->has('Kategori') && $request->Kategori != '') {
            $query->where('kategori_id', $request->Kategori);
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

        $barang = $query->paginate(20);

        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        $pajaks = Pajak::all();
        $satuanKonversis = Satuan::where('status_satuan', 'konversi_satuan')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.barang.partials.table', compact('barang'))->render(),
                'pagination' => $barang->links()->toHtml(),
            ]);
        }

        return view('master_data.barang.index', compact('barang', 'kategoris', 'satuans', 'pajaks', 'breadcrumbs', 'satuanKonversis'));
    }


    /**
     * Menyimpan data barang baru ke database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {

            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:barangs,kode',
                'kategori_id' => 'required|uuid',
                'status' => 'required|in:aktif,inaktif',
                'harga_beli' => 'required|numeric',
                'harga_pokok' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'markup' => 'nullable|decimal:0,2', // Allows 2 decimal places
                'pajak_id' => 'required|uuid',
                'satuan_id' => 'required|uuid',
                'diskon' => 'required|numeric',
                'diskon_nominal' => 'required|numeric',
                'stok_minimal' => 'required|numeric',
                'jumlah' => 'array',
                'satuan_konversi_id' => 'array',
                'nilai' => 'array',
                'satuan_tujuan_id' => 'array',
                'satuan_konversi_id.*' => 'nullable|exists:satuans,id',
                'nilai.*' => 'nullable|numeric',
                'satuan_tujuan_id.*' => 'nullable|exists:satuans,id',
                'photo_file' => 'nullable|image|max:2048',
            ]);

            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan data barang
            $barang = new Barang();
            $barang->id = Str::uuid();
            $barang->kategori_id = $request->kategori_id;
            $barang->kode = $request->kode;
            $barang->nama = $request->nama;
            $barang->satuan_id = $request->satuan_id;
            $barang->harga_beli = $request->harga_beli;
            $barang->harga_pokok = $request->harga_pokok;
            $barang->harga_jual = $request->harga_jual;
            $barang->markup = $request->markup ?? 0; // Default to 0 if not provided
            $barang->diskon_value = $request->diskon;
            $barang->diskon_nominal = $request->diskon_nominal;
            $barang->stok_minimum = $request->stok_minimal;
            $barang->stok = 0; // Atau sesuai kebutuhan
            $barang->pajak_id = $request->pajak_id;
            $barang->status = $request->status === 'aktif' ? 'active' : 'inactive';

            if ($request->hasFile('photo_file')) {
                $path = $request->file('photo_file')->store('private_files', 'local');
                $barang->gambar = $path;
            }

            $barang->save();

            // Simpan data konversi
            if (!empty($request->satuan_konversi_id) && !empty($request->nilai) && !empty($request->satuan_tujuan_id)) {
                foreach ($request->satuan_konversi_id as $index => $satuanKonversiId) {
                    // Skip jika satuan konversi kosong
                    if (empty($satuanKonversiId)) continue;

                    $konversi = new Konversi();
                    $konversi->barang_id = $barang->id;
                    $konversi->satuan_id = $satuanKonversiId;
                    $konversi->nilai_konversi = $request->nilai[$index];
                    $konversi->satuan_tujuan_id = $request->satuan_tujuan_id[$index];
                    $konversi->save();
                }
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Barang berhasil disimpan.');
        } catch (ValidationException $e) {
            // Menangani error validasi
            DB::rollBack();

            // Mengubah array error menjadi pesan yang lebih mudah dibaca
            $errorMessages = [];
            foreach ($e->errors() as $field => $errors) {
                $errorMessages[] = $field . ': ' . implode(', ', $errors);
            }

            // Mengirim pesan error sebagai string, bukan array
            $errorMessage = 'Validasi gagal: ' . implode(' | ', $errorMessages);

            return redirect()->back()
                ->withErrors($e->validator)
                ->with('error', $errorMessage);
        } catch (Exception $e) {
            // Menangani error umum
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Update data barang berdasarkan ID 
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|unique:barangs,kode,' . $id,
                'kategori_id' => 'required|uuid',
                'status' => 'required|in:aktif,inaktif',
                'harga_beli' => 'required|numeric',
                'harga_pokok' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'markup' => 'nullable|decimal:0,2', // Mengizinkan 2 desimal
                'margin' => 'nullable|numeric',
                'pajak_id' => 'required|uuid',
                'satuan_id' => 'required|uuid',
                'diskon' => 'required|numeric',
                'stok_minimal' => 'required|numeric',
                'jumlah' => 'array',
                'konversi_id' => 'array',
                'satuan_konversi_id' => 'array',
                'nilai' => 'array',
                'satuan_tujuan_id' => 'array',
                'photo_file' => 'nullable|image|max:2048',
            ]);

            // Ambil data barang yang akan diupdate
            $barang = Barang::findOrFail($id);

            // Update data barang
            $barang->kategori_id = $request->kategori_id;
            $barang->kode = $request->kode;
            $barang->nama = $request->nama;
            $barang->satuan_id = $request->satuan_id;
            $barang->harga_beli = $request->harga_beli;
            $barang->harga_pokok = $request->harga_pokok;
            $barang->harga_jual = $request->harga_jual;
            $barang->markup = $request->markup ?? 0; // Default ke 0 jika tidak disediakan
            $barang->diskon_value = $request->diskon;
            // Pastikan diskon_nominal ada pada request jika dibutuhkan
            $barang->diskon_nominal = $request->diskon_nominal ?? 0;
            $barang->stok_minimum = $request->stok_minimal;
            $barang->pajak_id = $request->pajak_id;
            $barang->status = $request->status === 'aktif' ? 'active' : 'inactive';

            // Handle upload file jika ada
            if ($request->hasFile('photo_file')) {
                // Hapus file lama jika ada
                if ($barang->gambar && Storage::disk('local')->exists($barang->gambar)) {
                    Storage::disk('local')->delete($barang->gambar);
                }

                // Simpan file baru
                $path = $request->file('photo_file')->store('private_files', 'local');
                $barang->gambar = $path;
            }

            $barang->save();

            // Update atau tambah data konversi
            // Pertama, hapus konversi yang tidak ada dalam request tapi ada di database
            if (!empty($request->konversi_id)) {
                // Ambil semua ID konversi yang ada di database untuk barang ini
                $existingIds = Konversi::where('barang_id', $barang->id)->pluck('id')->toArray();

                // Filter ID yang akan dipertahankan dari request
                $keepIds = array_filter($request->konversi_id, function ($id) {
                    return $id !== null;
                });

                // Hapus konversi yang tidak ada di request
                $idsToDelete = array_diff($existingIds, $keepIds);
                if (!empty($idsToDelete)) {
                    Konversi::destroy($idsToDelete);
                }
            }

            // Update atau tambah konversi baru
            if (!empty($request->satuan_konversi_id)) {
                foreach ($request->satuan_konversi_id as $index => $satuanKonversiId) {
                    // Skip jika satuan konversi kosong
                    if (empty($satuanKonversiId)) continue;

                    // Jika ada ID konversi, update data yang sudah ada
                    if (!empty($request->konversi_id[$index])) {
                        $konversi = Konversi::find($request->konversi_id[$index]);
                        if ($konversi) {
                            $konversi->satuan_id = $satuanKonversiId;
                            $konversi->nilai_konversi = $request->nilai[$index] ?? 0;
                            $konversi->satuan_tujuan_id = $request->satuan_tujuan_id[$index] ?? null;
                            $konversi->save();
                        }
                    }
                    // Jika tidak ada ID konversi, tambah konversi baru
                    else {
                        $konversi = new Konversi();
                        $konversi->barang_id = $barang->id;
                        $konversi->satuan_id = $satuanKonversiId;
                        $konversi->nilai_konversi = $request->nilai[$index] ?? 0;
                        $konversi->satuan_tujuan_id = $request->satuan_tujuan_id[$index] ?? null;
                        $konversi->save();
                    }
                }
            }

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Data barang berhasil diperbarui.');
        } catch (ValidationException $e) {
            // Menangani error validasi
            DB::rollBack();

            // Mengubah array error menjadi pesan yang lebih mudah dibaca
            $errorMessages = [];
            foreach ($e->errors() as $field => $errors) {
                $errorMessages[] = $field . ': ' . implode(', ', $errors);
            }

            // Mengirim pesan error sebagai string, bukan array
            $errorMessage = 'Validasi gagal: ' . implode(' | ', $errorMessages);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', $errorMessage);
        } catch (ModelNotFoundException $e) {
            // Menangani jika data tidak ditemukan
            return redirect()->back()
                ->with('error', 'Data barang tidak ditemukan');
        } catch (Exception $e) {
            // Menangani error umum
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();
            return redirect()->back()->with('success', 'Barang berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error deleting barang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus barang.');
        }
    }

    public function restore($id)
    {
        try {
            $barang = Barang::withTrashed()->findOrFail($id);
            $barang->restore();
            return redirect()->back()->with('success', 'Barang berhasil dipulihkan!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error restoring barang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memulihkan barang.');
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();
            $barang = Barang::withTrashed()->findOrFail($id);

            // Delete related conversions
            $barang->konversi()->delete();

            // Delete image if exists
            if ($barang->gambar) {
                Storage::disk('private')->delete('barang/' . $barang->gambar);
            }

            $barang->forceDelete();
            DB::commit();

            return redirect()->back()->with('success', 'Barang berhasil dihapus permanen!');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error force deleting barang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus permanen barang.');
        }
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
                        Barang::whereIn('id', $selectedIds)->update(['status' => $newStatus]);
                        return redirect()->back()->with('success', 'Status barang berhasil diubah!');
                    }
                    return redirect()->back()->with('error', 'Status baru harus dipilih!');

                case 'delete':
                    Barang::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'Barang berhasil dihapus!');

                case 'restore':
                    Barang::withTrashed()->whereIn('id', $selectedIds)->restore();
                    return redirect()->back()->with('success', 'Barang berhasil direstore!');

                case 'forceDelete':
                    DB::beginTransaction();
                    try {
                        // Get all barang with their conversions and images
                        $barangs = Barang::withTrashed()
                            ->whereIn('id', $selectedIds)
                            ->with('konversi')
                            ->get();

                        foreach ($barangs as $barang) {
                            // Delete conversions
                            $barang->konversi()->delete();

                            // Delete image if exists
                            if ($barang->gambar) {
                                Storage::disk('private')->delete('barang/' . $barang->gambar);
                            }

                            // Force delete the barang
                            $barang->forceDelete();
                        }

                        DB::commit();
                        return redirect()->back()->with('success', 'Barang berhasil dihapus permanen!');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error force deleting barang: ' . $e->getMessage());
                        return redirect()->back()->with('error', 'Gagal menghapus permanen barang.');
                    }

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'pdf');

        if ($type == 'pdf') {
            return $this->generatePDF($request);
        } else if ($type == 'excel') {
            // Gunakan class export Excel terpisah
            return Excel::download(new BarangExport($request), 'laporan-barang-' . now()->format('dmY-His') . '.xlsx');
        } else {
            return back()->with('error', 'Tipe ekspor tidak valid');
        }
    }

    public function generatePDF(Request $request)
    {
        // Mempersiapkan query untuk data barang
        $query = Barang::query();

        // Filter berdasarkan kategori jika ada
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan stok minimum jika dicentang
        if ($request->has('filter_stok_minimum')) {
            $query->whereRaw('stok <= stok_minimum');
        }

        // Urutkan data
        $sortBy = $request->input('sort_by', 'nama');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Ambil semua data barang yang sesuai filter
        $barangs = $query->with(['kategori', 'satuan', 'pajak'])->get();

        // Hitung total nilai inventory (harga_pokok * stok)
        $totalNilaiInventory = $barangs->sum(function ($barang) {
            return $barang->harga_pokok * $barang->stok;
        });

        $settingQuery = Setting::whereIn('key', ['toko_nama', 'toko_alamat', 'toko_kota', 'toko_provinsi', 'toko_telepon', 'toko_email', 'toko_npwp'])
            ->pluck('value', 'key')
            ->toArray();

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
            'barangs' => $barangs,
            'tanggal_cetak' => now()->format('d-m-Y H:i:s'),
            'filter' => $request->all(),
            'total_nilai_inventory' => $totalNilaiInventory,
            'total_items' => $barangs->count(),
            'toko' => $toko, // Menambahkan data toko ke view
        ];

        // Load view PDF dan convert ke PDF
        $pdf = PDF::loadView('master_data.barang.pdf.barang-pdf', $data);

        // Atur paper size ke A4
        $pdf->setPaper('a4', 'portrait');

        // Download PDF dengan nama dinamis
        return $pdf->stream('laporan-barang-' . now()->format('dmY-His') . '.pdf');
    }
}
