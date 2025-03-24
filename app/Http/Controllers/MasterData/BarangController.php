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

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        $barang = Barang::whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
            ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%'])
            ->paginate(10);
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        $pajaks = Pajak::all();
        $satuanKonversis = Satuan::where('status_satuan', 'konversi_satuan')->get();
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
            'pajak_id' => 'required|uuid',
            'satuan_id' => 'required|uuid',
            'diskon' => 'required|numeric',
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
        $barang->diskon_value = $request->diskon;
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
}
