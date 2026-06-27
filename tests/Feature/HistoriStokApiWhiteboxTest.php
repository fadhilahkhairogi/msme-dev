<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\HistoriStok;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HistoriStokApiWhiteboxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $barang = Barang::create([
            'kode' => 'API1', 'nama' => 'Kopi', 'kategori' => 'Minuman',
            'satuan' => 'Pcs', 'harga_pokok' => 15000, 'harga_jual' => 20000,
            'stok' => 40, 'min_stok' => 10,
        ]);

        $barang2 = Barang::create([
            'kode' => 'API2', 'nama' => 'Teh', 'kategori' => 'Minuman',
            'satuan' => 'Pcs', 'harga_pokok' => 5000, 'harga_jual' => 8000,
            'stok' => 60, 'min_stok' => 20,
        ]);

        HistoriStok::create([
            'barang_id'    => $barang->id,
            'selisih_stok' => 20,
            'jenis'        => 'Barang masuk',
            'edited_at'    => '2026-05-15 10:00:00',
        ]);

        HistoriStok::create([
            'barang_id'    => $barang2->id,
            'selisih_stok' => -5,
            'jenis'        => 'Barang keluar',
            'edited_at'    => '2026-06-20 14:00:00',
        ]);

        HistoriStok::create([
            'barang_id'    => $barang->id,
            'selisih_stok' => 10,
            'jenis'        => 'Barang masuk',
            'edited_at'    => '2026-06-27 16:00:00',
        ]);
    }

    // WB-04 all() V(G)=5 P1: tanpa filter — semua data
    #[Test]
    public function wb04_tc1_path1_tanpa_filter_apapun()
    {
        $response = $this->getJson('/api/all-histori-stok');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    // WB-04 all() V(G)=5 P2: search Kopi — 2 data histori
    #[Test]
    public function wb04_tc2_path2_dengan_search()
    {
        $response = $this->getJson('/api/all-histori-stok?search=Kopi');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    // WB-04 all() V(G)=5 P3: search + tanggal valid — 1 data terfilter
    #[Test]
    public function wb04_tc3_path3_dengan_search_dan_tanggal_valid()
    {
        $response = $this->getJson('/api/all-histori-stok?search=Kopi&start_date=2026-06-01&end_date=2026-06-30');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    // WB-04 all() V(G)=5 P4: tanggal + jenis Barang keluar — 1 data
    #[Test]
    public function wb04_tc4_path4_dengan_tanggal_dan_jenis()
    {
        $response = $this->getJson('/api/all-histori-stok?start_date=2026-01-01&end_date=2026-12-31&jenis=Barang+keluar');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Barang keluar', $response->json('data.0.jenis'));
    }

    // WB-04 all() V(G)=5 P5: tanggal invalid — filter diabaikan, return semua
    #[Test]
    public function wb04_tc5_path5_dengan_tanggal_invalid()
    {
        $response = $this->getJson('/api/all-histori-stok?start_date=invalid&end_date=invalid');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }
}
