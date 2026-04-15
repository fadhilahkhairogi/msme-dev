<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'BRG001', 'nama' => 'Beras Premium 5kg', 'satuan' => 'sak', 'harga_pokok' => 65000, 'harga_jual' => 75000, 'stok' => 2, 'min_stok' => 2, 'kategori' => 'sembako'],
            ['kode' => 'BRG002', 'nama' => 'Minyak Goreng 2L', 'satuan' => 'botol', 'harga_pokok' => 32000, 'harga_jual' => 38000, 'stok' => 0, 'min_stok' => 3, 'kategori' => 'sembako'],
            ['kode' => 'BRG003', 'nama' => 'Gula Pasir 1kg', 'satuan' => 'pack', 'harga_pokok' => 14000, 'harga_jual' => 17000, 'stok' => 5, 'min_stok' => 4, 'kategori' => 'sembako'],
            ['kode' => 'BRG004', 'nama' => 'Tepung Terigu 1kg', 'satuan' => 'pack', 'harga_pokok' => 12000, 'harga_jual' => 15000, 'stok' => 20, 'min_stok' => 1, 'kategori' => 'sembako'],
            ['kode' => 'BRG005', 'nama' => 'Kopi Bubuk 250g', 'satuan' => 'pack', 'harga_pokok' => 18000, 'harga_jual' => 23000, 'stok' => 10, 'min_stok' => 0, 'kategori' => 'minuman'],
        ];

        foreach ($data as $row) {
            Barang::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
