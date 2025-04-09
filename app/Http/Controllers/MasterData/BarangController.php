<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Pajak;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Kategori;
use App\Models\Konversi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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


    public function store(Request $request)
    {
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
            'photo_file' => 'nullable|image|max:2048',
        ]);

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
                $konversi = new Konversi();
                $konversi->barang_id = $barang->id;
                $konversi->satuan_id = $satuanKonversiId;
                $konversi->nilai_konversi = $request->nilai[$index];
                $konversi->satuan_tujuan_id = $request->satuan_tujuan_id[$index];
                $konversi->save();
            }
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Barang berhasil disimpan.');
    }

    /**
     * Mengupdate data barang beserta konversi terkait
     * 
     * @param Request $request Request HTTP yang masuk
     * @param string $id UUID dari barang yang akan diupdate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Transaksi database untuk menjamin integritas data
            DB::beginTransaction();

            // Langkah 1: Validasi data request yang masuk
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:barangs,kode,' . $id,
                'kategori_id' => 'required|exists:kategoris,id',
                'status' => 'required|in:aktif,nonaktif',
                'harga_beli' => 'required|numeric|min:0',
                'harga_pokok' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
                'markup' => 'nullable|decimal:0,2',
                'pajak_id' => 'nullable|exists:pajaks,id',
                'satuan_id' => 'required|exists:satuans,id',
                'diskon' => 'required|numeric|min:0',
                'diskon_nominal' => 'required|numeric',
                'stok_minimal' => 'required|numeric|min:0',
                'photo_file' => 'nullable|image|max:2048', // Menambahkan validasi untuk gambar
            ]);

            // Langkah 2: Mencari data barang yang sudah ada
            $barang = Barang::findOrFail($id);

            // Membuat array data yang diupdate untuk pemeriksaan dirty
            $updateData = [
                'nama' => $request->nama,
                'kode' => $request->kode,
                'kategori_id' => $request->kategori_id,
                'status' => $request->status == 'aktif' ? 'active' : 'inactive',
                'harga_beli' => $request->harga_beli,
                'harga_pokok' => $request->harga_pokok,
                'harga_jual' => $request->harga_jual,
                'markup' => $request->markup,
                'pajak_id' => $request->pajak_id,
                'satuan_id' => $request->satuan_id,
                'diskon_value' => $request->diskon,
                'diskon_nominal' => $request->diskon_nominal,
                'stok_minimum' => $request->stok_minimal,
            ];

            // Langkah 3: Memeriksa apakah data benar-benar berubah (dirty checking)
            $isDirty = false;
            foreach ($updateData as $key => $value) {
                if ($barang->$key != $value) {
                    $isDirty = true;
                    break;
                }
            }

            // Hanya update jika data berubah
            if ($isDirty) {
                // Langkah 4: Update data barang dengan data yang telah divalidasi
                $barang->update($updateData);
            }

            // Langkah 5: Memproses update gambar jika disediakan
            if ($request->hasFile('photo_file')) {
                // Validasi file gambar
                if (!$request->file('photo_file')->isValid()) {
                    throw new \Exception('Unggahan gambar tidak valid.');
                }

                // Hapus gambar lama jika ada
                if ($barang->gambar) {
                    // Menggunakan penyimpanan private untuk penanganan file yang lebih aman
                    if (Storage::disk('private')->exists('barang/' . $barang->gambar)) {
                        Storage::disk('private')->delete('barang/' . $barang->gambar);
                    }
                }

                // Membuat nama file unik dengan timestamp dan ekstensi asli
                $filename = time() . '-' . Str::random(10) . '.' . $request->photo_file->extension();

                // Menyimpan di disk private alih-alih di path publik untuk keamanan lebih baik
                $path = $request->photo_file->storeAs('barang', $filename, 'private');

                if (!$path) {
                    throw new \Exception('Gagal menyimpan gambar. Periksa izin direktori.');
                }

                // Update data barang dengan nama file gambar baru
                $barang->update(['gambar' => $filename]);
            }

            // Langkah 6: Memproses konversi satuan
            $updatedKonversiIds = []; // Melacak ID konversi yang telah diproses

            // Mendapatkan jumlah konversi yang akan diproses
            $countKonversi = isset($request->nilai) ? count($request->nilai) : 0;

            // Memproses setiap entri konversi
            for ($index = 0; $index < $countKonversi; $index++) {
                // Memastikan semua data yang diperlukan ada untuk indeks ini
                if (
                    !isset($request->nilai[$index]) ||
                    !isset($request->satuan_konversi_id[$index]) ||
                    !isset($request->satuan_tujuan_id[$index])
                ) {
                    // Catat data tidak lengkap dan lanjutkan ke iterasi berikutnya
                    \Log::warning("Konversi pada indeks {$index} memiliki data tidak lengkap untuk barang ID: {$barang->id}");
                    continue;
                }

                // Mengambil nilai-nilai yang diperlukan
                $satuanId = $request->satuan_konversi_id[$index];
                $satuanTujuanId = $request->satuan_tujuan_id[$index];
                $nilaiKonversi = $request->nilai[$index];
                $konversiId = isset($request->konversi_id[$index]) ? $request->konversi_id[$index] : null;

                // Lewati jika ada nilai yang diperlukan kosong
                if (empty($satuanId) || empty($satuanTujuanId) || empty($nilaiKonversi)) {
                    \Log::warning("Konversi pada indeks {$index} memiliki nilai yang diperlukan kosong untuk barang ID: {$barang->id}");
                    continue;
                }

                // Validasi ID satuan benar-benar ada di database
                if (
                    !DB::table('satuans')->where('id', $satuanId)->exists() ||
                    !DB::table('satuans')->where('id', $satuanTujuanId)->exists()
                ) {
                    \Log::error("ID satuan tidak valid disediakan pada indeks {$index} untuk barang ID: {$barang->id}");
                    continue;
                }

                // Periksa apakah mengupdate konversi yang ada atau membuat yang baru
                if ($konversiId && Str::isUuid($konversiId)) {
                    // Coba temukan konversi yang ada
                    $konversi = $barang->konversi()->where('id', $konversiId)->first();

                    if ($konversi) {
                        // Periksa apakah data konversi berubah
                        $konversiDirty =
                            $konversi->satuan_id != $satuanId ||
                            $konversi->satuan_tujuan_id != $satuanTujuanId ||
                            $konversi->nilai_konversi != $nilaiKonversi;

                        if ($konversiDirty) {
                            // Update konversi yang ada menggunakan query builder
                            $updated = DB::table('konversis')
                                ->where('id', $konversi->id)
                                ->update([
                                    'satuan_id' => $satuanId,
                                    'satuan_tujuan_id' => $satuanTujuanId,
                                    'nilai_konversi' => $nilaiKonversi,
                                    'updated_at' => now()
                                ]);

                            if (!$updated) {
                                \Log::error("Gagal mengupdate konversi ID: {$konversi->id}");
                            }
                        }

                        $updatedKonversiIds[] = $konversi->id;
                    } else {
                        \Log::warning("Konversi ID {$konversiId} tidak ditemukan untuk barang ID: {$barang->id}");
                    }
                } else {
                    // Buat entri konversi baru
                    try {
                        $newKonversiId = (string) Str::uuid();
                        $inserted = DB::table('konversis')->insert([
                            'id' => $newKonversiId,
                            'barang_id' => $barang->id,
                            'satuan_id' => $satuanId,
                            'satuan_tujuan_id' => $satuanTujuanId,
                            'nilai_konversi' => $nilaiKonversi,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        if ($inserted) {
                            $updatedKonversiIds[] = $newKonversiId;
                        } else {
                            \Log::error("Gagal menyisipkan konversi baru untuk barang ID: {$barang->id}");
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error membuat konversi: " . $e->getMessage());
                    }
                }
            }

            // Langkah 7: Hapus konversi yang tidak disertakan dalam request
            try {
                $deletedCount = $barang->konversi()->whereNotIn('id', $updatedKonversiIds)->delete();
                \Log::info("Menghapus {$deletedCount} record konversi yang tidak digunakan untuk barang ID: {$barang->id}");
            } catch (\Exception $e) {
                \Log::error("Error menghapus konversi yang tidak digunakan: " . $e->getMessage());
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->back()->with('success', 'Barang berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangani error validasi
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $e->errors()));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Menangani kasus di mana barang tidak ditemukan
            DB::rollBack();
            \Log::error("Barang tidak ditemukan dengan ID: {$id}");
            return redirect()->route('barang.index')
                ->with('error', 'Barang dengan ID tersebut tidak ditemukan.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Menangani error database
            DB::rollBack();
            \Log::error("Error database saat mengupdate barang: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan pada database. Silakan coba lagi atau hubungi administrator.');
        } catch (\Exception $e) {
            // Menangani exception lainnya
            DB::rollBack();
            \Log::error("Error mengupdate barang: " . $e->getMessage());
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
}
