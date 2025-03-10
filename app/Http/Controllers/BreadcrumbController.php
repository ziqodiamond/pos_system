<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreadcrumbController extends Controller
{
    public static function generateBreadcrumbs()
    {
        $currentRoute = request()->route()->getName();
        $breadcrumbs = [];

        // Daftar breadcrumb manual berdasarkan route
        $breadcrumbList = [

            'master-data.index' => ['name' => 'Master Data', 'url' => route('master-data.index')],
            'barang.index' => ['name' => 'Barang', 'url' => route('barang.index')],
            'supplier.index' => ['name' => 'Supplier', 'url' => route('supplier.index')],
            'customer.index' => ['name' => 'Customer', 'url' => route('customer.index')],
            'kategori.index' => ['name' => 'Kategori', 'url' => route('kategori.index')],
            'pajak.index' => ['name' => 'Pajak', 'url' => route('pajak.index')],
            'satuan.index' => ['name' => 'Satuan', 'url' => route('satuan.index')],

        ];

        // Looping untuk mencari breadcrumb berdasarkan rute saat ini
        foreach ($breadcrumbList as $route => $breadcrumb) {
            $breadcrumbs[] = $breadcrumb;
            if ($route == $currentRoute) {
                break;
            }
        }

        return $breadcrumbs;
    }
}
