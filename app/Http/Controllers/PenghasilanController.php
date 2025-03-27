<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghasilan;

class PenghasilanController extends Controller
{
    public function index()
    { 
        $penghasilan = Penghasilan::paginate(10); // Maksimal 10 item per halaman
        return view('penghasilan.index', compact('penghasilan'));
    }

    public function create()
    {
        return view('penghasilan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_penghasilan' => 'required|numeric',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date'
        ]);

        Penghasilan::create($request->all());

        return redirect()->route('penghasilan.index')->with('success', 'Penghasilan berhasil dicatat.');
    }

    public function edit(Penghasilan $penghasilan)
    {
        return view('penghasilan.edit', compact('penghasilan'));
    }

    public function update(Request $request, Penghasilan $penghasilan)
    {
        $request->validate([
            'total_penghasilan' => 'required|numeric',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date'
        ]);

        $penghasilan->update($request->all());

        return redirect()->route('penghasilan.index')->with('success', 'Penghasilan berhasil diperbarui.');
    }

    public function destroy(Penghasilan $penghasilan)
    {
        $penghasilan->delete();
        return redirect()->route('penghasilan.index')->with('success', 'Penghasilan berhasil dihapus.');
    }
}
