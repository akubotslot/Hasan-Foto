@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Tambah Pengeluaran</h2>
    <form action="{{ route('pengeluaran.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium">Nama Pengeluaran:</label>
            <input type="text" name="nama_pengeluaran" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Jumlah:</label>
            <input type="number" name="jumlah" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Tanggal:</label>
            <input type="datetime-local" name="tanggal" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Keterangan:</label>
            <textarea name="keterangan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div class="flex justify-between mt-4">
            <a href="{{ route('pengeluaran.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
