<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


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

// API Endpoints untuk aplikasi mobile_logistik
Route::prefix('api')->group(function () {
    Route::get('/barang', function (Illuminate\Http\Request $request) {
        $search = $request->query('search', '');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $barangs = App\Models\Barang::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        })
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            try {
                $start = \Illuminate\Support\Carbon::parse($startDate)->startOfDay();
                $end = \Illuminate\Support\Carbon::parse($endDate)->endOfDay();
                $query->whereHas('historiStoks', function ($q) use ($start, $end) {
                    $q->whereBetween('edited_at', [$start, $end]);
                });
            } catch (\Exception $e) {
                // Abaikan filter jika format tanggal salah
            }
        })
        ->get();

        // Menyematkan data histori perubahan stok terakhir ke setiap barang
        foreach ($barangs as $barang) {
            $latestHistory = $barang->historiStoks()->orderBy('edited_at', 'desc')->first();
            $barang->last_selisih_stok = $latestHistory ? (int) $latestHistory->selisih_stok : 0;
            $barang->last_edited_at = $latestHistory ? $latestHistory->edited_at->toDateTimeString() : '-';
        }
        
        return response()->json([
            'data' => $barangs
        ]);
    });

    Route::get('/histori-stok/{barangId}', [App\Http\Controllers\API\HistoriStokController::class, 'index']);
    Route::get('/all-histori-stok', [App\Http\Controllers\API\HistoriStokController::class, 'all']);
});

