<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreadcrumbController extends Controller
{

    public static function generateBreadcrumbs()
    {
        $currentRoute = request()->route()->getName();

        $breadcrumbTree = [
            'dashboard' => [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
                'children' => [
                    'master-data.index' => [
                        'name' => 'Master Data',
                        'url' => route('master-data.index'),
                        'children' => [
                            'barang.index' => ['name' => 'Barang', 'url' => route('barang.index')],
                            'supplier.index' => ['name' => 'Supplier', 'url' => route('supplier.index')],
                            'customer.index' => ['name' => 'Customer', 'url' => route('customer.index')],
                            'kategori.index' => ['name' => 'Kategori', 'url' => route('kategori.index')],
                            'konversi.index' => ['name' => 'Konversi', 'url' => route('konversi.index')],
                            'pajak.index' => ['name' => 'Pajak', 'url' => route('pajak.index')],
                            'satuan.index' => ['name' => 'Satuan', 'url' => route('satuan.index')],
                        ],
                    ],
                    'pembelian.index' => [
                        'name' => 'Pembelian',
                        'url' => route('pembelian.index'),
                        'children' => [
                            'pembelian.create' => ['name' => 'Form Pembelian', 'url' => route('pembelian.create')],
                            'pembelian.list' => ['name' => 'Daftar Pembelian', 'url' => route('pembelian.list')],
                        ],
                    ],
                    // 'penjualan.index' => [
                    //     'name' => 'Penjualan',
                    //     'url' => route('penjualan.index'),
                    //     'children' => [
                    //         'pembelian.form' => ['name' => 'Form Pembelian', 'url' => route('penjualan.form')],

                    //     ],
                    // ],
                    // 'inventori.index' => [
                    //     'name' => 'Inventori',
                    //     'url' => route('inventori.index'),
                    //     'children' => [
                    //         'pembelian.form' => ['name' => 'Form Pembelian', 'url' => route('inventori.form')],

                    //     ],
                    // ],
                    // 'laporan.index' => [
                    //     'name' => 'Laporan',
                    //     'url' => route('laporan.index'),
                    //     'children' => [
                    //         'pembelian.form' => ['name' => 'Form Pembelian', 'url' => route('laporan.form')],

                    //     ],
                    // ],
                    // 'setting.index' => [
                    //     'name' => 'pembelian',
                    //     'url' => route('setting.index'),
                    //     'children' => [
                    //         'pembelian.form' => ['name' => 'Form Pembelian', 'url' => route('setting.form')],

                    //     ],
                    // ],
                ]
            ],
        ];

        function findBreadcrumb($tree, $route, &$breadcrumbs = [])
        {
            foreach ($tree as $key => $breadcrumb) {
                $breadcrumbs[] = $breadcrumb;

                if ($key === $route) {
                    return true;
                }

                if (isset($breadcrumb['children']) && findBreadcrumb($breadcrumb['children'], $route, $breadcrumbs)) {
                    return true;
                }

                array_pop($breadcrumbs);
            }

            return false;
        }

        $breadcrumbs = [];
        findBreadcrumb($breadcrumbTree, $currentRoute, $breadcrumbs);

        return $breadcrumbs;
    }
}
