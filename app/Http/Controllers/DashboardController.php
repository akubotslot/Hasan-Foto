<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\PenggunaanBarang;
use App\Models\Penghasilan;
use App\Models\Pengeluaran;
use App\Models\Pengaturan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Barang
        $total_barang = Barang::count();

        // Total Penggunaan Barang
        $total_penggunaan = PenggunaanBarang::count();

        // Penghasilan Hari Ini
        $penghasilan_hari_ini = Penghasilan::whereDate('tanggal', Carbon::now())->sum('total_penghasilan');

        // Penghasilan Bulan Ini
        $penghasilan_bulan_ini = Penghasilan::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('total_penghasilan');

        // Data Penggunaan Barang Terbaru (5 data terakhir)
        $penggunaan_terbaru = PenggunaanBarang::latest()->take(5)->get();

        // Data Penghasilan Hari Ini (5 data terakhir)
        $penghasilan_terbaru = Penghasilan::latest()->take(5)->get();

        $pengeluaran_hari_ini = Pengeluaran::whereDate('tanggal', Carbon::now())->sum('jumlah');

        $pengeluaran_bulan_ini = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        $pengeluaran_terbaru = Pengeluaran::latest()->limit(5)->get();

        $pengaturan = Pengaturan::first();

        $batas_minimum_stok = Pengaturan::first()->batas_minimum_stok; // Default 5 jika tidak ada data

        $notifikasi_minimum_stok = $pengaturan->notifikasi_minimum_stok;

        $barang_hampir_habis = Barang::where('stok_barang', '<=', $batas_minimum_stok)->get();



        return view('dashboard', compact(
            'pengeluaran_hari_ini', 
            'pengeluaran_bulan_ini', 
            'pengeluaran_terbaru',
            'penghasilan_hari_ini', 
            'penghasilan_bulan_ini',
            'penggunaan_terbaru',
            'batas_minimum_stok',
            'barang_hampir_habis',
            'notifikasi_minimum_stok',
            'penghasilan_terbaru'
        ));
    }
}
