<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Konversi;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class PembelianController extends Controller
{
    public function index()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('pembelian.index', compact('breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        $barangs = Barang::all();
        $suppliers = Supplier::all();
        $satuans = Satuan::all();
        $konversis = Konversi::all();
        return view('pembelian.form_pembelian.index', compact('breadcrumbs', 'barangs', 'suppliers', 'satuans', 'konversis'));
    }

    public function store(Request $request)
    {
        dd($request);
        return redirect()->back();
    }

    public function list()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('pembelian.daftar_pembelian.index', compact('breadcrumbs'));
    }
}
