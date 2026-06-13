<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->integer('selisih_stok');
            $table->string('jenis');
            $table->timestamp('edited_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_stok');
    }
};
