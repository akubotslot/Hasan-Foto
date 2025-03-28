@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="bg-white p-6 rounded-lg shadow-md">

        <!-- Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">

            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Penghasilan Hari Ini</h3>
                <p class="text-2xl text-blue-500 font-bold">
                    Rp {{ number_format($penghasilan_hari_ini, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Penghasilan Bulan Ini</h3>
                <p class="text-2xl text-blue-500 font-bold">
                    Rp {{ number_format($penghasilan_bulan_ini, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Pengeluaran Hari Ini</h3>
                <p class="text-2xl text-blue-500 font-bold">
                    Rp {{ number_format($pengeluaran_hari_ini, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Pengeluaran Bulan Ini</h3>
                <p class="text-2xl text-blue-500 font-bold">
                    Rp {{ number_format($pengeluaran_bulan_ini, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Menu Cepat -->
        <div class="mt-6">
            <h3 class="text-lg font-bold">Menu Cepat</h3>
            <div class="grid grid-cols-2 gap-4 mt-2">
                <a href="{{ secure_url(route('barang.create')) }}" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600">
                    + Barang Baru
                </a>
                <a href="{{ route('penggunaan_barang.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-white hover:bg-green-600">
                    + Penggunaan Barang
                </a>
                <a href="{{ route('penghasilan.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-white hover:bg-yellow-600">
                    + Penghasilan
                </a>
                <a href="{{ route('pengeluaran.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-white hover:bg-red-600">
                    + Pengeluaran
                </a>
            </div>
        </div>

        <!-- Penggunaan Barang & Penghasilan Hari Ini (Kiri-Kanan) -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-bold">Penghasilan Terbaru</h3>
                <table class="w-full mt-2 border text-center">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Keterangan</th>
                            <th class="p-2">Jumlah</th>
                            <th class="p-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penghasilan_terbaru as $penghasilan)
                            <tr class="border-t">
                                <td class="p-2">{{ $penghasilan->catatan }}</td>
                                <td class="p-2">Rp {{ number_format($penghasilan->total_penghasilan, 0, ',', '.') }}</td>
                                <td class="p-2">{{ $penghasilan->tanggal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pengeluaran Terbaru -->
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-bold">Pengeluaran Terbaru</h3>
                <table class="w-full mt-2 border text-center">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Keterangan</th>
                            <th class="p-2">Jumlah</th>
                            <th class="p-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengeluaran_terbaru as $pengeluaran)
                            <tr class="border-t">
                                <td class="p-2">{{ $pengeluaran->nama_pengeluaran }}</td>
                                <td class="p-2">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                <td class="p-2">{{ $pengeluaran->tanggal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-bold">Penggunaan Barang Terbaru</h3>
                <table class="w-full mt-2 border text-center">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Barang</th>
                            <th class="p-2">Jumlah</th>
                            <th class="p-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penggunaan_terbaru as $penggunaan)
                            <tr class="border-t">
                                <td class="p-2">{{ $penggunaan->barang->name }}</td>
                                <td class="p-2">{{ $penggunaan->jumlah_dipakai }}</td>
                                <td class="p-2">{{ $penggunaan->tanggal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                -->

            <!-- Notifikasi Stok Hampir Habis -->
            @if ($notifikasi_minimum_stok == 1 && $barang_hampir_habis->count() > 0)
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="fixed bottom-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg w-72">
                    <p class="font-semibold">⚠ Stok Hampir Habis</p>
                    <ul class="text-sm mt-1">
                        @foreach ($barang_hampir_habis as $barang)
                            <li>{{ $barang->nama_barang }} - Sisa {{ $barang->stok_barang }}</li>
                        @endforeach
                    </ul>
                    <button @click="show = false" class="mt-2 text-xs underline">Tutup</button>
                </div>
            @endif
        </div>
    @endsection
