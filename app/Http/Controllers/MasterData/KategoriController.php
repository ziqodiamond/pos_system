<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {

        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        $kategori = Kategori::whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
            ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%'])
            ->paginate(10);
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
        $kategori->save();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }
}
