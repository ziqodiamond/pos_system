<?php

namespace App\Http\Controllers\Penjualan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('penjualan.index', compact('breadcrumbs'));
    }

    public function transaksi()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('penjualan.transaksi', compact('breadcrumbs'));
    }
}
