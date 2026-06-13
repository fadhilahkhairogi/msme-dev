<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\HistoriStok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HistoriStokSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        HistoriStok::query()->delete();

        $data = [
            [
                'barang_id' => 4,
                'selisih_stok' => 7,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-27 16:38:33'),
            ],
            [
                'barang_id' => 1,
                'selisih_stok' => 1,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-27 14:38:28'),
            ],
            [
                'barang_id' => 2,
                'selisih_stok' => -1,
                'jenis' => 'Barang keluar',
                'edited_at' => Carbon::parse('2026-06-25 10:01:24'),
            ],
            [
                'barang_id' => 4,
                'selisih_stok' => 6,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-23 14:37:23'),
            ],
            [
                'barang_id' => 3,
                'selisih_stok' => 3,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-21 06:01:32'),
            ],
            [
                'barang_id' => 3,
                'selisih_stok' => -2,
                'jenis' => 'Barang keluar',
                'edited_at' => Carbon::parse('2026-06-18 19:45:26'),
            ],
            [
                'barang_id' => 4,
                'selisih_stok' => 7,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-18 13:47:34'),
            ],
            [
                'barang_id' => 1,
                'selisih_stok' => 1,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-17 19:41:46'),
            ],
            [
                'barang_id' => 1,
                'selisih_stok' => -1,
                'jenis' => 'Barang keluar',
                'edited_at' => Carbon::parse('2026-06-14 23:47:53'),
            ],
            [
                'barang_id' => 5,
                'selisih_stok' => 3,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-10 04:21:45'),
            ],
            [
                'barang_id' => 2,
                'selisih_stok' => 1,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-09 17:31:03'),
            ],
            [
                'barang_id' => 5,
                'selisih_stok' => 7,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-09 13:40:49'),
            ],
            [
                'barang_id' => 3,
                'selisih_stok' => 4,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-06-05 21:12:32'),
            ],
            [
                'barang_id' => 1,
                'selisih_stok' => 1,
                'jenis' => 'Barang masuk',
                'edited_at' => Carbon::parse('2026-05-31 00:32:58'),
            ],
        ];

        foreach ($data as $row) {
            HistoriStok::create($row);
        }
    }
}
