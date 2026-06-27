<?php

namespace Tests\Feature;

use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BarangControllerWhiteboxTest extends TestCase
{
    use RefreshDatabase;

    // WB-01 store() V(G)=2 P1: validasi gagal — nama kosong, return 422
    #[Test]
    public function wb01_tc1_path1_validasi_gagal_karena_nama_kosong()
    {
        $response = $this->post(route('produk.barang.store'), [
            'kode'        => 'G01',
            'nama'        => '',
            'kategori'    => 'Sembako',
            'satuan'      => 'Kg',
            'harga_pokok' => 10000,
            'harga_jual'  => 13000,
            'stok'        => 20,
            'min_stok'    => 5,
        ]);

        $response->assertSessionHasErrors('nama');
    }

    // WB-01 store() V(G)=2 P2: validasi sukses — data tersimpan, redirect
    #[Test]
    public function wb01_tc2_path2_tambah_produk_sukses()
    {
        $payload = [
            'kode'        => 'BRG001',
            'nama'        => 'Gula Pasir',
            'kategori'    => 'Sembako',
            'satuan'      => 'Kg',
            'harga_pokok' => 10000,
            'harga_jual'  => 13000,
            'stok'        => 20,
            'min_stok'    => 5,
        ];

        $response = $this->post(route('produk.barang.store'), $payload);

        $response->assertRedirect(route('produk.barang.index'));
        $response->assertSessionHas('Sukses');
        $this->assertDatabaseHas('barangs', ['kode' => 'BRG001']);
    }

    // WB-02 update() V(G)=3 P1: validasi gagal — nama kosong, return 422
    #[Test]
    public function wb02_tc1_path1_validasi_gagal_saat_edit()
    {
        $barang = Barang::create([
            'kode' => 'OLD', 'nama' => 'Barang Lama', 'kategori' => 'Umum',
            'satuan' => 'Pcs', 'harga_pokok' => 5000, 'harga_jual' => 7000,
            'stok' => 50, 'min_stok' => 10,
        ]);

        $response = $this->put(route('produk.barang.update', $barang->id), [
            'nama'    => '',
            'kode'    => 'OLD',
            'kategori'=> 'Umum',
            'harga_jual' => 7000,
            'stok'    => 50,
            'min_stok'=> 10,
        ]);

        $response->assertSessionHasErrors('nama');
    }

    // WB-02 update() V(G)=3 P2: edit tanpa ubah stok — tidak ada histori baru
    #[Test]
    public function wb02_tc2_path2_edit_tanpa_perubahan_stok()
    {
        $barang = Barang::create([
            'kode' => 'BRG', 'nama' => 'Barang Test', 'kategori' => 'Umum',
            'satuan' => 'Pcs', 'harga_pokok' => 5000, 'harga_jual' => 7000,
            'stok' => 50, 'min_stok' => 10,
        ]);

        $response = $this->put(route('produk.barang.update', $barang->id), [
            'nama'      => 'Barang Test Updated',
            'kode'      => 'BRG',
            'kategori'  => 'Umum',
            'harga_jual'=> 7500,
            'stok'      => 50,
            'min_stok'  => 10,
        ]);

        $response->assertRedirect(route('produk.barang.index'));
        $this->assertDatabaseHas('barangs', ['id' => $barang->id, 'nama' => 'Barang Test Updated']);
        $this->assertDatabaseMissing('histori_stok', ['barang_id' => $barang->id]);
    }

    // WB-02 update() V(G)=3 P3: stok 50→60 — histori masuk tercatat (selisih +10)
    #[Test]
    public function wb02_tc3_path3_edit_dengan_stok_bertambah_histori_masuk()
    {
        $barang = Barang::create([
            'kode' => 'BRG2', 'nama' => 'Barang Test 2', 'kategori' => 'Umum',
            'satuan' => 'Pcs', 'harga_pokok' => 5000, 'harga_jual' => 7000,
            'stok' => 50, 'min_stok' => 10,
        ]);

        $response = $this->put(route('produk.barang.update', $barang->id), [
            'nama'      => 'Barang Test 2',
            'kode'      => 'BRG2',
            'kategori'  => 'Umum',
            'harga_jual'=> 7000,
            'stok'      => 60,
            'min_stok'  => 10,
        ]);

        $response->assertRedirect(route('produk.barang.index'));
        $this->assertDatabaseHas('barangs', ['id' => $barang->id, 'stok' => 60]);
        $this->assertDatabaseHas('histori_stok', [
            'barang_id'    => $barang->id,
            'selisih_stok' => 10,
            'jenis'        => 'Barang masuk',
        ]);
    }

    // WB-02 update() V(G)=3 P3: stok 50→30 — histori keluar tercatat (selisih -20)
    #[Test]
    public function wb02_tc4_path3_edit_dengan_stok_berkurang_histori_keluar()
    {
        $barang = Barang::create([
            'kode' => 'BRG3', 'nama' => 'Barang Test 3', 'kategori' => 'Umum',
            'satuan' => 'Pcs', 'harga_pokok' => 5000, 'harga_jual' => 7000,
            'stok' => 50, 'min_stok' => 10,
        ]);

        $response = $this->put(route('produk.barang.update', $barang->id), [
            'nama'      => 'Barang Test 3',
            'kode'      => 'BRG3',
            'kategori'  => 'Umum',
            'harga_jual'=> 7000,
            'stok'      => 30,
            'min_stok'  => 10,
        ]);

        $response->assertRedirect(route('produk.barang.index'));
        $this->assertDatabaseHas('barangs', ['id' => $barang->id, 'stok' => 30]);
        $this->assertDatabaseHas('histori_stok', [
            'barang_id'    => $barang->id,
            'selisih_stok' => -20,
            'jenis'        => 'Barang keluar',
        ]);
    }

    // WB-03 index() V(G)=3 P1: tanpa search, cache hit — dashboard dari cache
    #[Test]
    public function wb03_tc1_path1_index_cache_hit_tanpa_search()
    {
        Barang::create([
            'kode' => 'A', 'nama' => 'Produk A', 'kategori' => 'X',
            'satuan' => 'Pcs', 'harga_pokok' => 100, 'harga_jual' => 200,
            'stok' => 10, 'min_stok' => 5,
        ]);

        $this->get(route('produk.barang.index'))->assertOk();
        $response = $this->get(route('produk.barang.index'));

        $response->assertOk();
        $response->assertSee('Produk A');
    }

    // WB-03 index() V(G)=3 P2: search Beras — produk terfilter, dashboard dihitung ulang
    #[Test]
    public function wb03_tc2_path2_index_dengan_search()
    {
        Barang::create([
            'kode' => 'B', 'nama' => 'Beras', 'kategori' => 'Sembako',
            'satuan' => 'Kg', 'harga_pokok' => 10000, 'harga_jual' => 15000,
            'stok' => 20, 'min_stok' => 5,
        ]);
        Barang::create([
            'kode' => 'C', 'nama' => 'Minyak', 'kategori' => 'Sembako',
            'satuan' => 'L', 'harga_pokok' => 12000, 'harga_jual' => 16000,
            'stok' => 30, 'min_stok' => 10,
        ]);

        $response = $this->get(route('produk.barang.index', ['search' => 'Beras']));

        $response->assertOk();
        $response->assertSee('Beras');
        $response->assertDontSee('Minyak');
    }

    // WB-03 index() V(G)=3 P3: tanpa search, cache kosong — dashboard dihitung ulang
    #[Test]
    public function wb03_tc3_path3_index_tanpa_search_cache_kosong()
    {
        \Illuminate\Support\Facades\Cache::forget('produk.barang.dashboard.data');

        Barang::create([
            'kode' => 'D', 'nama' => 'Roti', 'kategori' => 'Makanan',
            'satuan' => 'Pcs', 'harga_pokok' => 5000, 'harga_jual' => 8000,
            'stok' => 3, 'min_stok' => 5,
        ]);

        $response = $this->get(route('produk.barang.index'));

        $response->assertOk();
        $response->assertSee('Roti');

        $this->assertTrue(\Illuminate\Support\Facades\Cache::has('produk.barang.dashboard.data'));
    }
}
