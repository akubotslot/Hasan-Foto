<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penghasilan', function (Blueprint $table) {
            $table->id();
            $table->integer('total_penghasilan');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penghasilan');
    }
};
