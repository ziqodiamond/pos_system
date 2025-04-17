<?php

use App\Models\FakturPembelian;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\MasterData\PajakController;
use App\Http\Controllers\Pembelian\FakturController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\SatuanController;
use App\Http\Controllers\Inventori\InventoriController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\KategoriController;
use App\Http\Controllers\MasterData\KonversiController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\Pembelian\PembelianController;
use App\Http\Controllers\Penjualan\PenjualanController;
use App\Http\Controllers\Penjualan\TransaksiController;
use App\Http\Controllers\Inventori\StokBarangController;
use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Controllers\Inventori\BarangKeluarController;
use App\Http\Controllers\Inventori\MutasiBarangController;
use App\Http\Controllers\Laporan\Keuangan\HutangController;
use App\Http\Controllers\Penjualan\ReturPenjualanController;
use App\Http\Controllers\Laporan\Keuangan\LabaRugiController;
use App\Http\Controllers\Pembelian\DaftarPembelianController;
use App\Http\Controllers\Penjualan\DaftarPenjualanController;
use App\Http\Controllers\Laporan\Keuangan\LaporanPajakController;
use App\Http\Controllers\Laporan\Pembelian\LaporanPembelianController;
use App\Http\Controllers\Laporan\Penjualan\LaporanKasirController;
use App\Http\Controllers\Laporan\Penjualan\LaporanPenjualanController;
use App\Http\Controllers\Laporan\Stok\MutasiKeluarController;
use App\Http\Controllers\Laporan\Stok\MutasiMasukController;
use App\Http\Controllers\Laporan\Stok\StokMinimumController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth', 'verified')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::prefix('penjualan')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');

        Route::prefix('transaksi')->group(function () {
            Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
            Route::post('/', [TransaksiController::class, 'store'])->name('transaksi.store');
            Route::get('/{id}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
        });

        Route::prefix('daftar-penjualan')->group(function () {
            Route::get('/', [DaftarPenjualanController::class, 'index'])->name('daftar-penjualan.index');
        });

        Route::prefix('return')->group(function () {
            Route::get('/', [ReturPenjualanController::class, 'index'])->name('penjualan.index');
            Route::get('/', [ReturPenjualanController::class, 'store'])->name('penjualan.store');
        });

        // Route::prefix('return-list')->group(function () {
        //     Route::get('/', [ReturPenjualanController::class, 'list'])->name('penjualan.index');
        // });
    });
});

Route::middleware('auth', 'role:gudang,admin,super_admin')->group(function () {
    Route::prefix('master-data')->group(function () {

        Route::get('/', [MasterDataController::class, 'index'])->name('master-data.index');

        Route::prefix('barang')->group(function () {
            Route::get('/', [BarangController::class, 'index'])->name('barang.index');
            Route::post('/', [BarangController::class, 'store'])->name('barang.store');
            Route::put('/{id}', [BarangController::class, 'update'])->name('barang.update');
            Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
            Route::patch('/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
            Route::delete('/{id}/force-delete', [BarangController::class, 'forceDelete'])->name('barang.forceDelete');
            Route::post('/bulk-action', [BarangController::class, 'bulkAction'])->name('barang.bulkAction');
            Route::get('/export', [BarangController::class, 'export'])->name('barang.export');
        });

        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
            Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
            Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
            Route::patch('/{id}/restore', [SupplierController::class, 'restore'])->name('supplier.restore');
            Route::delete('/{id}/force-delete', [SupplierController::class, 'forceDelete'])->name('supplier.forceDelete');
            Route::post('/bulk-action', [SupplierController::class, 'bulkAction'])->name('supplier.bulkAction');
            Route::get('/pdf', [SupplierController::class, 'generatePDF'])->name('supplier.pdf');
        });

        Route::prefix('customer')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
            Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
            Route::put('/{id}', [CustomerController::class, 'update'])->name('customer.update');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
            Route::patch('/{id}/restore', [CustomerController::class, 'restore'])->name('customer.restore');
            Route::delete('/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customer.forceDelete');
            Route::post('/bulk-action', [CustomerController::class, 'bulkAction'])->name('customer.bulkAction');
            Route::get('/pdf', [CustomerController::class, 'generatePDF'])->name('customer.pdf');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/', [UserController::class, 'store'])->name('user.store');
            Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
            Route::patch('/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
            Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('user.forceDelete');
            Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('user.bulkAction');
            Route::get('/pdf', [UserController::class, 'generatePDF'])->name('user.pdf');
        });

        Route::prefix('kategori')->group(function () {
            Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
            Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
            Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
            Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
            Route::patch('/{id}/restore', [KategoriController::class, 'restore'])->name('kategori.restore');
            Route::delete('/{id}/force-delete', [KategoriController::class, 'forceDelete'])->name('kategori.forceDelete');
            Route::post('/bulk-action', [KategoriController::class, 'bulkAction'])->name('kategori.bulkAction');
            Route::get('/pdf', [KategoriController::class, 'generatePDF'])->name('kategori.pdf');
        });

        Route::prefix('pajak')->group(function () {
            Route::get('/', [PajakController::class, 'index'])->name('pajak.index');
            Route::post('/', [PajakController::class, 'store'])->name('pajak.store');
            Route::put('/{id}', [PajakController::class, 'update'])->name('pajak.update');
            Route::delete('/{id}', [PajakController::class, 'destroy'])->name('pajak.destroy');
            Route::patch('/{id}/restore', [PajakController::class, 'restore'])->name('pajak.restore');
            Route::delete('/{id}/force-delete', [PajakController::class, 'forceDelete'])->name('pajak.forceDelete');
            Route::post('/bulk-action', [PajakController::class, 'bulkAction'])->name('pajak.bulkAction');
        });

        Route::prefix('satuan')->group(function () {
            Route::get('/', [SatuanController::class, 'index'])->name('satuan.index');
            Route::post('/', [SatuanController::class, 'store'])->name('satuan.store');
            Route::put('/{id}', [SatuanController::class, 'update'])->name('satuan.update');
            Route::delete('/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
            Route::post('/bulk-action', [SatuanController::class, 'bulkAction'])->name('satuan.bulkAction');
            Route::get('/pdf', [SatuanController::class, 'generatePDF'])->name('satuan.pdf');
        });

        Route::prefix('konversi')->group(function () {
            Route::get('/', [KonversiController::class, 'index'])->name('konversi.index');
            Route::post('/', [KonversiController::class, 'store'])->name('konversi.store');
            Route::put('/{id}', [KonversiController::class, 'update'])->name('konversi.update');
            Route::delete('/{id}', [KonversiController::class, 'destroy'])->name('konversi.destroy');
            Route::post('/bulk-action', [KonversiController::class, 'bulkAction'])->name('konversi.bulkAction');
            Route::get('/pdf', [KonversiController::class, 'generatePDF'])->name('konversi.pdf');
        });
    });

    Route::prefix('inventori')->group(function () {
        Route::get('/', [InventoriController::class, 'index'])->name('inventori.index');

        Route::prefix('barang-keluar')->group(function () {
            Route::get('/', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
            Route::post('/', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
        });
        Route::prefix('stok-barang')->group(function () {
            Route::get('/', [StokBarangController::class, 'index'])->name('stok-barang.index');
        });
        Route::prefix('mutasi')->group(function () {
            Route::get('/', [MutasiBarangController::class, 'index'])->name('mutasi.index');
        });
    });
});
Route::middleware('auth', 'role:admin,super_admin')->group(function () {
    Route::prefix('pembelian')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('pembelian.index');

        Route::prefix('create')->group(function () {
            Route::get('/', [PembelianController::class, 'create'])->name('pembelian.create');
            Route::post('/', [PembelianController::class, 'store'])->name('pembelian.store');
        });
        Route::prefix('list')->group(function () {
            Route::get('/', [DaftarPembelianController::class, 'index'])->name('daftar-pembelian.index');
            Route::put('/{id}', [DaftarPembelianController::class, 'update'])->name('pembelian.update');
            Route::patch('/completed/{id}', [DaftarPembelianController::class, 'received'])->name('pembelian.terima');
            Route::patch('/cencel/{id}', [DaftarPembelianController::class, 'cencel'])->name('pembelian.batal');
            Route::delete('/{id}', [DaftarPembelianController::class, 'destroy'])->name('pembelian.destroy');
            Route::patch('/{id}/restore', [DaftarPembelianController::class, 'restore'])->name('pembelian.restore');
            Route::delete('/{id}/force-delete', [DaftarPembelianController::class, 'forceDelete'])->name('pembelian.forceDelete');
            Route::post('/bulk-action', [DaftarPembelianController::class, 'bulkAction'])->name('pembelian.bulkAction');
        });
        Route::prefix('faktur')->group(function () {
            Route::get('/', [FakturController::class, 'index'])->name('faktur.index');
            Route::post('/{id}', [FakturController::class, 'bayar'])->name('faktur.bayar');
        });
    });

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/statistik', [LaporanController::class, 'getStatistikByPeriode'])->name('laporan.statistik');


        Route::prefix('Keuangan')->group(function () {
            Route::prefix('laba-rugi')->group(function () {
                Route::get('/', [LabaRugiController::class, 'index'])->name('laporan.laba-rugi');
                Route::get('/export', [LabaRugiController::class, 'export'])->name('laporan.laba-rugi.export');
                Route::get('/pdf', [LabaRugiController::class, 'generatePDF'])->name('laporan.laba-rugi.pdf');
            });
            Route::prefix('pajak')->group(function () {
                Route::get('/', [LaporanPajakController::class, 'index'])->name('laporan.pajak');
                Route::get('/cetak', [LaporanPajakController::class, 'cetak'])->name('laporan.pajak-cetak');
                Route::get('/pdf', [LaporanPajakController::class, 'generatePDF'])->name('laporan.pajak.pdf');
            });
            Route::prefix('hutang')->group(function () {
                Route::get('/', [HutangController::class, 'index'])->name('laporan.hutang.index');
            });
        });
        Route::prefix('pembelian')->group(function () {
            Route::get('/', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian.index');
            Route::get('/export', [LaporanPembelianController::class, 'export'])->name('laporan.pembelian.export');
        });
        Route::prefix('penjualan')->group(function () {


            Route::get('/', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan.index');
            Route::get('/export', [LaporanPenjualanController::class, 'export'])->name('laporan.penjualan.export');


            Route::prefix('kasir')->group(function () {
                Route::get('/', [LaporanKasirController::class, 'index'])->name('laporan.kasir');
                Route::get('/{kasirId}/detail', [LaporanKasirController::class, 'getDetailTransaksi'])->name('laporan.penjualan.kasir.detail');
            });
        });

        Route::prefix('mutasi')->group(function () {
            Route::prefix('barang-keluar')->group(function () {
                Route::get('/', [MutasiKeluarController::class, 'index'])->name('laporan.barang-keluar.index');
                // Route::get('/', [MutasiKeluarController::class, 'generatePDF'])->name('laporan.barang-keluar.pdf');
            });
            Route::prefix('barang-masuk')->group(function () {
                Route::get('/', [MutasiMasukController::class, 'index'])->name('laporan.barang-masuk.index');
            });
            Route::prefix('stok-minimum')->group(function () {
                Route::get('/', [StokMinimumController::class, 'index'])->name('laporan.stok-minimum.index');
            });
        });
    });
});

Route::middleware('auth', 'super_admin')->group(function () {
    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/update', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__ . '/auth.php';
