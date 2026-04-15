<?php

use App\Http\Controllers\BarangController;
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



Route::resource('/produk', BarangController::class)->names('produk.barang');
Route::get('/laporan-stok',  [BarangController::class, 'laporanStok'])->name('produk.barang.laporan');
