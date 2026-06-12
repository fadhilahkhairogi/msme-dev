<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HistoriStok;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HistoriStokController extends Controller
{
    // Ambil histori per barang (bisa filter tanggal & jenis)
    public function index($barangId, Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $jenis = $request->query('jenis');

        $query = HistoriStok::where('barang_id', $barangId);

        if ($startDate && $endDate) {
            try {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('edited_at', [$start, $end]);
            } catch (\Exception $e) {
                // Abaikan filter jika format tanggal tidak valid
            }
        }

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $histori = $query->orderBy('edited_at', 'desc')->get();

        return response()->json([
            'data' => $histori
        ]);
    }

    // Ambil semua data histori stok
    public function all(Request $request)
    {
        $search = $request->query('search');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $jenis = $request->query('jenis');

        $query = HistoriStok::with('barang');

        if ($search) {
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($startDate && $endDate) {
            try {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('edited_at', [$start, $end]);
            } catch (\Exception $e) {
                // Abaikan filter jika format tanggal tidak valid
            }
        }

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $histori = $query->orderBy('edited_at', 'desc')->get();

        return response()->json([
            'data' => $histori
        ]);
    }
}
