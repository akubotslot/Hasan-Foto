<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::paginate(10); // Maksimal 10 item per halaman
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $satuanList = Satuan::all(); // Ambil semua satuan
        return view('barang.create', compact('satuanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'nullable|unique:barang,kode_barang',
            'nama_barang' => 'required',
            'satuan_id' => 'required|exists:satuan,id',
            'stok_barang' => 'required|integer',
            'harga_barang' => 'required|numeric'
        ], [
            'kode_barang.required' => 'Kode Barang wajib diisi.',
            'kode_barang.unique' => 'Kode Barang sudah digunakan.',
            'nama_barang.required' => 'Nama Barang wajib diisi.',
            'nama_barang.unique' => 'Nama Barang sudah digunakan.',
            'satuan.required' => 'Satuan wajib diisi.',
            'stok_barang.required' => 'Stok Barang wajib diisi.',
            'stok_barang.integer' => 'Stok Barang harus berupa angka.',
            'harga_barang.required' => 'Harga Barang wajib diisi.',
            'harga_barang.numeric' => 'Harga Barang harus berupa angka.',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $satuanList = Satuan::all(); // Ambil semua satuan dari database
        return view('barang.edit', compact('barang', 'satuanList'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'nullable|unique:barang,kode_barang,' . $barang->id,
            'nama_barang' => 'required',
            'satuan_id' => 'required|exists:satuan,id',
            'stok_barang' => 'required|integer',
            'harga_barang' => 'required|numeric'
        ], [
            'kode_barang.required' => 'Kode Barang wajib diisi.',
            'kode_barang.unique' => 'Kode Barang sudah digunakan.',
            'nama_barang.required' => 'Nama Barang wajib diisi.',
            'nama_barang.unique' => 'Nama Barang sudah digunakan.',
            'satuan.required' => 'Satuan wajib diisi.',
            'stok_barang.required' => 'Stok Barang wajib diisi.',
            'stok_barang.integer' => 'Stok Barang harus berupa angka.',
            'harga_barang.required' => 'Harga Barang wajib diisi.',
            'harga_barang.integer' => 'Harga Barang harus berupa angka.',
        ]);

        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'satuan_id' => $request->satuan_id,
            'stok_barang' => $request->stok_barang,
            'harga_barang' => $request->harga_barang,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
