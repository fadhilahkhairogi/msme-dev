<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/login', function () {
    return view('auth.login');
});



Route::resource('/produk', BarangController::class)->parameters([
    'produk' => 'barang'
])->names('produk.barang');
Route::get('/laporan-stok',  [BarangController::class, 'laporanStok'])->name('produk.barang.laporan');


Route::resource('/supplier', SupplierController::class)->names('produk.supplier');
