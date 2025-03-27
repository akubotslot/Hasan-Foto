<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Satuan;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengaturan = Pengaturan::all();
        $satuan = Satuan::all();
        return view('pengaturan.index', compact('pengaturan', 'satuan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaturan $pengaturan)
    {
        $request->validate([
            'batas_minimum_stok' => 'required|integer',
            'notifikasi_minimum_stok' => 'nullable|boolean',
        ]);
    
        // Perbarui data
        $pengaturan->update([
            'batas_minimum_stok' => $request->batas_minimum_stok,
            'notifikasi_minimum_stok' => $request->has('notifikasi_minimum_stok') ? 1 : 0,
        ]);
    
        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateNotifikasi($id, Request $request)
    {
        $pengaturan = Pengaturan::find($id);
        if ($pengaturan) {
            $pengaturan->notifikasi_minimum_stok = $request->notifikasi_minimum_stok;
            $pengaturan->save();
    
            return response()->json(['success' => true, 'message' => 'Notifikasi berhasil diperbarui']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function updateMinimumStok(Request $request, $id)
    {
        $pengaturan = Pengaturan::findOrFail($id);
        $pengaturan->batas_minimum_stok = $request->batas_minimum_stok;
        $pengaturan->save();
    
        return response()->json(['message' => 'Minimum stok berhasil diperbarui'], 200);
    }
    
}
