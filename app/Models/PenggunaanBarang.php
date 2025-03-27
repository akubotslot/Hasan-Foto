<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanBarang extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_barang';
    protected $fillable = ['barang_id', 'jumlah_dipakai', 'tanggal'];

    public function barang()
{
    return $this->belongsTo(Barang::class, 'barang_id');
}

}
