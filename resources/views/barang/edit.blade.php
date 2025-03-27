@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Barang</h2>
    <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium">Kode Barang:</label>
            <input type="text" name="kode_barang" value="{{ $barang->kode_barang }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Nama Barang:</label>
            <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Satuan</label>
            <select name="satuan_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Satuan --</option>
                @foreach($satuanList as $satuan)
                    <option value="{{ $satuan->id }}" 
                        {{ isset($barang) && $barang->satuan_id == $satuan->id ? 'selected' : '' }}>
                        {{ $satuan->jenis_satuan }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-gray-700 font-medium">Stok:</label>
            <input type="number" name="stok_barang" value="{{ $barang->stok_barang }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Harga:</label>
            <input type="number" name="harga_barang" value="{{ $barang->harga_barang }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-between mt-4">
            <a href="{{ route('barang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
