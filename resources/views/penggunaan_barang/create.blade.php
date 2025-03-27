@extends('layouts.app')

@section('content')


<div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Tambah Penggunaan Barang</h2>
    @if (session('error'))
    <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
    @endif
    <form action="{{ route('penggunaan_barang.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Input Kode Barang -->
        <div>
            <label class="block text-gray-700 font-medium">Kode Barang:</label>
            <input type="number" id="kode_barang" name="kode_barang" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Dropdown Pilih Barang -->
        <div>
            <label class="block text-gray-700 font-medium">Pilih Barang:</label>
            <select id="barang_select" name="barang_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Pilih Barang</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id }}" data-kode="{{ $item->kode_barang }}">{{ $item->nama_barang }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Jumlah Dipakai:</label>
            <input type="number" name="jumlah_dipakai" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Tanggal & Waktu:</label>
            <input type="datetime-local" name="tanggal" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
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



<script>
    document.addEventListener("DOMContentLoaded", function() {
    const barangSelect = document.getElementById("barang_select");
    const kodeBarangInput = document.getElementById("kode_barang");

    // Ambil parameter kode_barang dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const scannedBarcode = urlParams.get("kode_barang");

    if (scannedBarcode) {
        kodeBarangInput.value = scannedBarcode;

        // Pilih barang yang sesuai dengan barcode
        let found = false;
        for (let option of barangSelect.options) {
            if (option.getAttribute("data-kode") === scannedBarcode) {
                barangSelect.value = option.value;
                found = true;
                break;
            }
        }

        // Jika barang tidak ditemukan, kosongkan dropdown
        if (!found) {
            barangSelect.value = "";
        }
    }

    // Event untuk memilih barang dari dropdown
    barangSelect.addEventListener("change", function() {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const kodeBarang = selectedOption.getAttribute("data-kode");
        kodeBarangInput.value = kodeBarang;
    });

    // Event untuk mencari barang berdasarkan kode barang
    kodeBarangInput.addEventListener("input", function() {
        const inputKode = kodeBarangInput.value;
        let found = false;

        for (let option of barangSelect.options) {
            if (option.getAttribute("data-kode") === inputKode) {
                barangSelect.value = option.value;
                found = true;
                break;
            }
        }

        // Jika tidak ditemukan, kosongkan dropdown
        if (!found) {
            barangSelect.value = "";
        }
    });
});

</script>
@endsection
