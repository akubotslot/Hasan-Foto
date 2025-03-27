<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengaturanTable extends Migration
{
    public function up()
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->integer('batas_minimum_stok');
            $table->boolean('notifikasi_minimum_stok')->nullable();
            $table->timestamps();
        });
        // Isi data default setelah tabel dibuat
        DB::table('pengaturan')->insert([
        'batas_minimum_stok' => 5,
        'notifikasi_minimum_stok' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan');
    }
}