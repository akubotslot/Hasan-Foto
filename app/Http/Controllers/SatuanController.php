<?php

namespace App\Http\Controllers;

use App\Models\satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $satuan = satuan::all();
        return view('satuan.index', compact('satuan'));
        
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
    public function store(Request $request)
    {
        $request->validate([
            'jenis_satuan' => 'required'
        ]);

        satuan::create($request->all());
        return redirect()->route('pengaturan.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(satuan $satuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(satuan $satuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, satuan $satuan)
    {
        $request->validate([
            'jenis_satuan' => 'required'
        ]);

        $satuan->update($request->all());
        return redirect()->route('pengaturan.index')->with('success', 'Satuan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(satuan $satuan)
    {
        $satuan->delete();
        return redirect()->route('pengaturan.index')->with('success', 'Satuan berhasil dihapus');
        
    }
}
