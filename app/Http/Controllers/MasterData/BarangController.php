<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Pajak;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

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
        return view('master_data.barang.index', compact('barang', 'kategoris', 'satuans', 'pajaks', 'breadcrumbs'));
    }

    public function store(Request $request)
    {

        dd($request->all());
        $request->validate([
            'kategori_id' => 'required',
            'kode' => 'required|unique:barangs',
            'nama' => 'required',
            'satuan_id' => 'required',
            'harga_beli' => 'required',
            'harga_pokok' => 'required',
            'harga_jual' => 'required',
            'diskon_value' => 'required',
            'stok_minimum' => 'required',
            'stok' => 'required',
            'pajak_id' => 'required',
            'status' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = new Barang();
        $barang->kategori_id = $request->kategori_id;
        $barang->kode = $request->kode;
        $barang->nama = $request->nama;
        $barang->satuan_id = $request->satuan_id;
        $barang->harga_beli = $request->harga_beli;
        $barang->harga_pokok = $request->harga_pokok;
        $barang->harga_jual = $request->harga_jual;
        $barang->diskon_value = $request->diskon_value;
        $barang->stok_minimum = $request->stok_minimum;
        $barang->stok = $request->stok;
        $barang->pajak_id = $request->pajak_id;
        $barang->status = $request->status;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambar_name = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('images'), $gambar_name);
            $barang->gambar = $gambar_name;
        }
        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }
}
