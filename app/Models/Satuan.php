<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuan';
    protected $fillable = ['jenis_satuan'];
    public function barang()
    {
        return $this->hasMany(Barang::class, 'satuan_id', 'id');
    }
}
