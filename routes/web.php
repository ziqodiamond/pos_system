<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\MasterDataController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\CustomerController;
use App\Http\Controllers\MasterData\KategoriController;
use App\Http\Controllers\MasterData\PajakController;
use App\Http\Controllers\MasterData\SatuanController;
use App\Http\Controllers\MasterData\KonversiController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth', 'verified')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('mater-data')->group(function () {

        Route::get('/', [MasterDataController::class, 'index'])->name('master-data.index');

        Route::prefix('barang')->group(function () {

            Route::get('/', [BarangController::class, 'index'])->name('barang.index');
            Route::post('/', [BarangController::class, 'store'])->name('barang.store');
            Route::post('/{id}', [BarangController::class, 'update'])->name('barang.update');
            Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
        });

        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
            Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
            Route::post('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
        });

        Route::prefix('customer')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
            Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
            Route::post('/{id}', [CustomerController::class, 'update'])->name('customer.update');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
        });

        Route::prefix('kategori')->group(function () {
            Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
            Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
            Route::post('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
            Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        });

        Route::prefix('pajak')->group(function () {
            Route::get('/', [PajakController::class, 'index'])->name('pajak.index');
            Route::post('/', [PajakController::class, 'store'])->name('pajak.store');
            Route::post('/{id}', [PajakController::class, 'update'])->name('pajak.update');
            Route::delete('/{id}', [PajakController::class, 'destroy'])->name('pajak.destroy');
        });

        Route::prefix('satuan')->group(function () {
            Route::get('/', [SatuanController::class, 'index'])->name('satuan.index');
            Route::post('/', [SatuanController::class, 'store'])->name('satuan.store');
            Route::post('/{id}', [SatuanController::class, 'update'])->name('satuan.update');
            Route::delete('/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
        });

        Route::prefix('konversi')->group(function () {
            Route::get('/', [KonversiController::class, 'index'])->name('konversi.index');
            Route::post('/', [KonversiController::class, 'store'])->name('konversi.store');
            Route::post('/{id}', [KonversiController::class, 'update'])->name('konversi.update');
            Route::delete('/{id}', [KonversiController::class, 'destroy'])->name('konversi.destroy');
        });
    });
});

Route::middleware('auth', 'admin', 'super_admin')->group(function () {});

require __DIR__ . '/auth.php';
