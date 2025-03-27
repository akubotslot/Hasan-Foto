<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique()->nullable();
            $table->string('nama_barang');
            $table->unsignedBigInteger('satuan_id')->nullable(); // Tambahkan kolom satuan_id
            $table->integer('stok_barang');
            $table->integer('harga_barang');
            $table->timestamps();
        
            // Tambahkan foreign key
            $table->foreign('satuan_id')->references('id')->on('satuan')->onDelete('set null');
        });
        
        
        
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
