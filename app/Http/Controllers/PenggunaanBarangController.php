<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaanBarang;
use App\Models\Barang;

class PenggunaanBarangController extends Controller
{
    public function index()
    { 
        $penggunaan_barang = PenggunaanBarang::paginate(10); 
        return view('penggunaan_barang.index', compact('penggunaan_barang'));
    }
   

    public function create()
    {
        $barang = Barang::all();
        return view('penggunaan_barang.create', compact('barang'));
    }

    public function store(Request $request)
{
    $request->validate([
        'barang_id' => 'required|exists:barang,id',
        'jumlah_dipakai' => 'required|integer|min:1',
        'tanggal' => 'required|date',
    ]);

    $barang = Barang::find($request->barang_id);

    if (!$barang) {
        return redirect()->back()->with('error', 'Barang tidak ditemukan.');
    }

    // Pastikan stok cukup
    if ($barang->stok_barang < $request->jumlah_dipakai) {
        return redirect()->back()->with('error', 'Stok barang tidak cukup. Stok saat ini: ' . $barang->stok_barang);
    }

    // Simpan penggunaan barang
    $penggunaan = PenggunaanBarang::create([
        'barang_id' => $request->barang_id,
        'jumlah_dipakai' => $request->jumlah_dipakai,
        'tanggal' => $request->tanggal,
    ]);

    // Kurangi stok barang
    $barang->stok_barang -= $request->jumlah_dipakai;
    $barang->save();

    return redirect()->route('penggunaan_barang.index')->with('success', 'Penggunaan barang berhasil ditambahkan dan stok berkurang.');
}
   
    public function edit($id)
    {
        $penggunaan_barang = PenggunaanBarang::findOrFail($id);
        $barang = Barang::all();
        return view('penggunaan_barang.edit', compact('penggunaan_barang', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_dipakai' => 'required|integer|min:1',
            'tanggal' => 'required|date', 
        ]);
    
        $penggunaan = PenggunaanBarang::findOrFail($id);
        $barang = Barang::find($request->barang_id);
    
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }
    
        $jumlah_lama = $penggunaan->jumlah_dipakai; // Simpan jumlah lama
        $jumlah_baru = $request->jumlah_dipakai; // Jumlah baru yang diinput
    
        // Jika jumlah baru lebih besar, cek apakah stok cukup
        if ($jumlah_baru > $jumlah_lama) {
            $selisih = $jumlah_baru - $jumlah_lama;
            if ($barang->stok_barang < $selisih) {
                return redirect()->back()->with('error', 'Stok barang tidak cukup. Stok saat ini: ' . $barang->stok_barang);
            }
            $barang->stok_barang -= $selisih; // Kurangi stok
        } 
        // Jika jumlah baru lebih kecil, tambahkan selisih ke stok
        else {
            $selisih = $jumlah_lama - $jumlah_baru;
            $barang->stok_barang += $selisih; // Tambahkan stok
        }
    
        $barang->save(); // Simpan perubahan stok
    
        // Update data penggunaan barang
        $penggunaan->update([
            'barang_id' => $request->barang_id,
            'jumlah_dipakai' => $jumlah_baru,
            'tanggal' => $request->tanggal,
        ]);
    
        return redirect()->route('penggunaan_barang.index')->with('success', 'Penggunaan barang berhasil diperbarui dan stok disesuaikan.');
    }
    

    public function destroy($id)
    {
        $penggunaan = PenggunaanBarang::findOrFail($id);
    
        // Kembalikan stok barang
        $barang = Barang::find($penggunaan->barang_id);
        if ($barang) {
            $barang->stok_barang += $penggunaan->jumlah_dipakai;
            $barang->save();
        }
    
        $penggunaan->delete();
    
        return redirect()->route('penggunaan_barang.index')->with('success', 'Penggunaan barang dihapus dan stok dikembalikan.');
    }
    

    public function cekBarang($kode_barang)
{
    $barang = Barang::where('kode_barang', $kode_barang)->first();

    if ($barang) {
        return response()->json(['exists' => true]);
    } else {
        return response()->json(['exists' => false]);
    }
}


}
