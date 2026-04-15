<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{


    public function laporanStok()
    {
        // ambil semua Barang
        $daftar_Barang = Barang::get();

        $totalHargaBeli = $daftar_Barang->sum('harga_pokok');
        $totalHargaJual = $daftar_Barang->sum('harga_jual');
        $totalStok = $daftar_Barang->sum('stok');

        // $pdf = PDF::loadView('produk.barang.stok_report', [
        //     'daftarBarang'       => $daftar_Barang,
        //     'totalHargaBeli'  => $totalHargaBeli,
        //     'totalHargaJual' => $totalHargaJual,
        //     'totalStok'     => $totalStok
        // ]);

        // return $pdf->stream('laporan-stok-barang.pdf');

        return view('produk.barang.laporan', compact('daftar_Barang', 'totalHargaBeli', 'totalHargaJual', 'totalStok'));
    }
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        //debugging hapus cache (cache agar loading cepet)
        Cache::forget('produk.barang.dashboard.data');

        $daftarBarang = $query->latest()->paginate(2)->withQueryString();

        $dataDashboard = Cache::remember('produk.barang.dashboard.data', 600, function () use ($daftarBarang) {

            // Stok yang kuranggg
            $daftarBarangStokKurang = Barang::whereColumn('stok', '<=', 'min_stok')
                ->select('nama', 'stok', 'min_stok')
                ->latest()
                ->take(10)
                ->get()
                ->toArray();

            // Persenn kesehatan stok (cukupp)
            $banyakBarang = Barang::count();
            $BarangCukup = Barang::whereColumn('stok', '>', 'min_stok')->count();
            $persentaseKesehatanStok = $banyakBarang > 0 ? round(($BarangCukup / $banyakBarang) * 100) : 0;

            // Buat dashboard yang bagian atas 
            $totalHargaBeli = Barang::sum(DB::raw('harga_pokok * stok'));
            // $totalHargaBeli = 0;
            $totalHargaJual = Barang::sum(DB::raw('harga_jual * stok'));
            // $totalHargaJual = -1;
            $prakiraanKeuntungan = $totalHargaBeli > 0 ? (($totalHargaJual - $totalHargaBeli) / $totalHargaBeli) * 100 : 0;

            // Data diagram lingkaran
            $totalStok = Barang::sum('stok');

            // ini query eloquent untuk kategori stok
            $stokKategori = Barang::select('kategori', DB::raw('SUM(stok) as total_stok'))->groupBy('kategori')->get();
            // dump($stokKategori); // ini utk logging debugging
            $labelGrafik = [];
            $dataDiagram = [];
            $stokLain = 0;

            foreach ($stokKategori as $sk) {
                if ($totalStok > 0 && ($sk->total_stok / $totalStok) > 0.1) {
                    $labelGrafik[] = $sk->kategori ? $sk->kategori : 'Tanpa Kategori';
                    $dataDiagram[] = (int) $sk->total_stok;
                } else {
                    $stokLain += $sk->total_stok;
                }
            }

            if ($stokLain > 0) {
                $labelGrafik[] = 'Kategori lain';
                $dataDiagram[] = (int) $stokLain;
            }

            return compact('daftarBarangStokKurang', 'persentaseKesehatanStok', 'labelGrafik', 'dataDiagram', 'totalHargaBeli', 'totalHargaJual', 'prakiraanKeuntungan');
        });

        // buat dashboard
        $dashboard = [
            'daftarBarangStokKurang' => call_user_func(function () use ($dataDashboard) {
                return collect($dataDashboard['daftarBarangStokKurang'])->map(fn($item) => (object) $item);
            }),
            'persentaseKesehatanStok' => $dataDashboard['persentaseKesehatanStok'],
            'totalHargaBeli'    => $dataDashboard['totalHargaBeli'],
            'totalHargaJual'   => $dataDashboard['totalHargaJual'],
            'prakiraanKeuntungan' => $dataDashboard['prakiraanKeuntungan'],
            'labelGrafik'         => $dataDashboard['labelGrafik'],
            'dataDiagram'   => $dataDashboard['dataDiagram']
        ];

        // dump($dashboard); //debugging caching

        return view('produk.barang.index', compact('daftarBarang', 'dashboard'));
    }

    public function create()
    {
        return view('produk.barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kode'        => 'required|string|max:255',
            'kategori'     => 'required|string|max:255',
            'harga_pokok'   => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'min_stok'   => 'required|integer|min:0',
        ]);

        Barang::create($validated);

        // Clear Cache
        Cache::forget('produk.barang.dashboard.data');

        return redirect()->route('produk.barang.index')
            ->with('Sukses', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $Barang)
    {
        $categories = ['a', 'b'];
        return view('produk.barang.edit', compact('Barang', 'categories'));
    }

    public function update(Request $request, Barang $Barang)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'barcode'     => 'nullable|string|max:255',
            'harga_pokok'   => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'min_stok'   => 'required|integer|min:0',
        ]);

        $Barang->update($validated);

        // Kosongin cachenya
        Cache::forget('produk.barang.dashboard.data');

        return redirect()->route('produk.barang.index')
            ->with('sukses', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $Barang)
    {
        $Barang->delete();

        // cache diksongkan
        Cache::forget('produk.barang.dashboard.data');

        return redirect()->route('produk.barang.index')
            ->with('sukses', 'Barang berhasil dihapus.');
    }
}
