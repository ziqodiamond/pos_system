<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventori\InventoriController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\MasterData\PajakController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\SatuanController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\KategoriController;
use App\Http\Controllers\MasterData\KonversiController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\Pembelian\PembelianController;
use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Controllers\Pembelian\FakturController;
use App\Http\Controllers\Penjualan\PenjualanController;
use App\Http\Controllers\Penjualan\ReturPenjualanController;
use App\Http\Controllers\Setting\SettingController;
use App\Models\FakturPembelian;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth', 'verified')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
        });

        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
            Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
            Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
            Route::patch('/{id}/restore', [SupplierController::class, 'restore'])->name('supplier.restore');
            Route::delete('/{id}/force-delete', [SupplierController::class, 'forceDelete'])->name('supplier.forceDelete');
            Route::post('/bulk-action', [SupplierController::class, 'bulkAction'])->name('supplier.bulkAction');
        });

        Route::prefix('customer')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
            Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
            Route::put('/{id}', [CustomerController::class, 'update'])->name('customer.update');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
            Route::patch('/{id}/restore', [CustomerController::class, 'restore'])->name('customer.restore');
            Route::delete('/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customer.forceDelete');
            Route::post('/bulk-action', [CustomerController::class, 'bulkAction'])->name('customer.bulkAction');
        });

        Route::prefix('kategori')->group(function () {
            Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
            Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
            Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
            Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
            Route::patch('/{id}/restore', [KategoriController::class, 'restore'])->name('kategori.restore');
            Route::delete('/{id}/force-delete', [KategoriController::class, 'forceDelete'])->name('kategori.forceDelete');
            Route::post('/bulk-action', [KategoriController::class, 'bulkAction'])->name('kategori.bulkAction');
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
        });

        Route::prefix('konversi')->group(function () {
            Route::get('/', [KonversiController::class, 'index'])->name('konversi.index');
            Route::post('/', [KonversiController::class, 'store'])->name('konversi.store');
            Route::put('/{id}', [KonversiController::class, 'update'])->name('konversi.update');
            Route::delete('/{id}', [KonversiController::class, 'destroy'])->name('konversi.destroy');
            Route::post('/bulk-action', [KonversiController::class, 'bulkAction'])->name('konversi.bulkAction');
        });
    });

    Route::prefix('pembelian')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('pembelian.index');

        Route::prefix('create')->group(function () {
            Route::get('/', [PembelianController::class, 'create'])->name('pembelian.create');
            Route::post('/', [PembelianController::class, 'store'])->name('pembelian.store');
        });
        Route::prefix('list')->group(function () {
            Route::get('/', [PembelianController::class, 'list'])->name('pembelian.list');
        });
        Route::prefix('faktur')->group(function () {
            Route::get('/', [FakturController::class, 'index'])->name('faktur.index');
            Route::put('/', [FakturController::class, 'pay'])->name('faktur.pay');
        });
    });


    Route::prefix('penjualan')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');

        Route::prefix('kasir')->group(function () {
            Route::get('/', [PenjualanController::class, 'kasir'])->name('penjualan.kasir');
            Route::get('/', [PenjualanController::class, 'store'])->name('penjualan.store');
        });

        Route::prefix('daily-list')->group(function () {
            Route::get('/', [PenjualanController::class, 'list'])->name('penjualan.list');
        });

        Route::prefix('return')->group(function () {
            Route::get('/', [ReturPenjualanController::class, 'index'])->name('penjualan.index');
            Route::get('/', [ReturPenjualanController::class, 'store'])->name('penjualan.store');
        });

        Route::prefix('return-list')->group(function () {
            Route::get('/', [ReturPenjualanController::class, 'list'])->name('penjualan.index');
        });
    });

    Route::prefix('inventori')->group(function () {
        Route::get('/', [InventoriController::class, 'index'])->name('inventori.index');
    });

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
    });

    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
    });
});

Route::middleware('auth', 'admin', 'super_admin')->group(function () {});

require __DIR__ . '/auth.php';
