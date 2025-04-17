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
                            'user.index' => ['name' => 'Pengguna', 'url' => route('user.index')],
                        ],
                    ],
                    'pembelian.index' => [
                        'name' => 'Pembelian',
                        'url' => route('pembelian.index'),
                        'children' => [
                            'pembelian.create' => ['name' => 'Form Pembelian', 'url' => route('pembelian.create')],
                            'daftar-pembelian.index' => ['name' => 'Daftar Pembelian', 'url' => route('daftar-pembelian.index')],
                            'faktur.index' => ['name' => 'Faktur Pembelian', 'url' => route('faktur.index')],
                        ],
                    ],
                    'penjualan.index' => [
                        'name' => 'Penjualan',
                        'url' => route('penjualan.index'),
                        'children' => [
                            'transaksi.index' => ['name' => 'Transaksi Baru', 'url' => route('transaksi.index')],
                            'daftar-penjualan.index' => ['name' => 'Daftar Penjualan', 'url' => route('daftar-penjualan.index')],

                        ],
                    ],
                    'inventori.index' => [
                        'name' => 'Inventori',
                        'url' => route('inventori.index'),
                        'children' => [
                            'barang-keluar.index' => ['name' => 'Formulir Barang Keluar', 'url' => route('barang-keluar.index')],
                            'stok-barang.index' => ['name' => 'Stok Barang', 'url' => route('stok-barang.index')],
                            'mutasi.index' => ['name' => 'Mutasi Barang Keluar', 'url' => route('mutasi.index')],
                        ],
                    ],
                    'laporan.index' => [
                        'name' => 'Laporan',
                        'url' => route('laporan.index'),
                        'children' => [
                            'laporan.laba-rugi' => ['name' => 'Laba Rugi', 'url' => route('laporan.laba-rugi')],
                            'laporan.pajak' => ['name' => 'Pajak', 'url' => route('laporan.pajak')],
                            'laporan.hutang.index' => ['name' => 'Hutang', 'url' => route('laporan.hutang.index')],
                            'laporan.pembelian.index' => ['name' => 'Pembelian', 'url' => route('laporan.pembelian.index')],
                            'laporan.penjualan.index' => ['name' => 'Penjualan', 'url' => route('laporan.pajak')],
                            'laporan.kasir' => ['name' => 'Penjualan Kasir', 'url' => route('laporan.kasir')],
                            'laporan.barang-keluar.index' => ['name' => 'Mutasi Barang Keluar', 'url' => route('laporan.barang-keluar.index')],
                            'laporan.barang-masuk.index' => ['name' => 'Mutasi Barang Masuk', 'url' => route('laporan.barang-masuk.index')],
                            'laporan.stok-minimum.index' => ['name' => 'Stok Minimum', 'url' => route('laporan.stok-minimum.index')],
                        ],
                    ],
                    'setting.index' => [
                        'name' => 'Pengaturan',
                        'url' => route('settings.index'),

                    ],
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
