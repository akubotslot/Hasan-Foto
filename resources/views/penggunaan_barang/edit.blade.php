@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Penggunaan Barang</h2>

    @if (session('error'))
    <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif

    <form action="{{ route('penggunaan_barang.update', $penggunaan_barang->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium">Pilih Barang:</label>
            <select name="barang_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                @foreach($barang as $item)
                    <option value="{{ $item->id }}" {{ $penggunaan_barang->barang_id == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_barang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Jumlah Dipakai:</label>
            <input type="number" name="jumlah_dipakai" value="{{ $penggunaan_barang->jumlah_dipakai }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Tanggal & Waktu:</label>
            <input type="datetime-local" name="tanggal" value="{{ \Carbon\Carbon::parse($penggunaan_barang->tanggal)->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-between mt-4">
            <a href="{{ route('penggunaan_barang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
