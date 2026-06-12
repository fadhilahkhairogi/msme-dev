<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';

    protected $fillable = [
        'kode',
        'nama',
        'satuan',
        'harga_pokok',
        'harga_jual',
        'stok',
        'min_stok',
        'kategori',
    ];

    protected function casts(): array
    {
        return [
            'harga_pokok' => 'decimal:2',
            'harga_jual' => 'decimal:2',
            'stok' => 'integer',
        ];
    }

    public function historiStoks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HistoriStok::class);
    }
}

