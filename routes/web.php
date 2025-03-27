<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenggunaanBarangController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SatuanController;

 
 Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
 
 // CRUD Barang
 Route::resource('barang', BarangController::class);
 
 // CRUD Penggunaan Barang
 Route::resource('penggunaan_barang', PenggunaanBarangController::class);
 
 // CRUD Penghasilan
 Route::resource('penghasilan', PenghasilanController::class);
 
    // CRUD Pengeluaran
 Route::resource('pengeluaran', PengeluaranController::class);
 
    // CRUD Setting
 Route::resource('pengaturan', PengaturanController::class);

      // CRUD Satuan
Route::resource('satuan', SatuanController::class);

 Route::get('/cek-barang/{kode_barang}', [PenggunaanBarangController::class, 'cekBarang'])->name('cek.barang');
 
 Route::put('/pengaturan/{pengaturan}', [PengaturanController::class, 'update'])->name('pengaturan.update');

 Route::post('/update-notifikasi/{id}', [PengaturanController::class, 'updateNotifikasi']);

 Route::post('/update-minimum-stok/{id}', [PengaturanController::class, 'updateMinimumStok']);
