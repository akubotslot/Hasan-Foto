@extends('layouts.app')

@section('content')

<style>
  #scannerContainer {
    position: relative;
    width: 100%;
    height: 350px;
    overflow: hidden;
}
    #preview {
        width: 100%;
        height: 100%; 
    }

    #scannerLine {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: red;
        animation: scanAnimation 2s infinite linear;
    }

    #scannerDot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 12px;
            height: 12px;
            background: red;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px red;
        }


    @keyframes scanAnimation {
        0% { top: 0; }
        50% { top: 100%; }
        100% { top: 0; }
    }


</style>

<div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Tambah Barang</h2>
    <form action="{{ route('barang.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-gray-700 font-medium">Kode Barang:</label>
            <div class="flex items-center space-x-2">
                <input type="text" id="barcodeResult" name="kode_barang" required 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <button type="button" id="scanButton" class="p-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    <img src="{{ asset('barcode-scan.png') }}" alt="Scan" class="h-6 w-6">
                </button>
            </div>
        </div>
    
        <div>
            <label class="block text-gray-700 font-medium">Nama Barang:</label>
            <input type="text" name="nama_barang" required 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
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
            <input type="number" name="stok_barang" required 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    
        <div>
            <label class="block text-gray-700 font-medium">Harga Satuan:</label>
            <input type="number" name="harga_barang" required 
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
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
    
    @if ($errors->any())
    <script>
        Swal.fire({
            title: 'Terjadi Kesalahan!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            icon: 'error',
        });
    </script>
@endif

</div>

<div id="scannerModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <div id="scannerContainer">
            <video id="preview" class="w-full h-full"></video>
            <div id="scannerDot"></div>
            <div id="scannerLine"></div>
        </div>
        
        <button id="closeScanner" class="mt-2 p-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Tutup</button>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
    document.getElementById("scanButton").addEventListener("click", function () {
    let scannerModal = document.getElementById("scannerModal");
    scannerModal.classList.remove("hidden");
    console.log("▶️ Tombol Scan ditekan, modal scanner ditampilkan.");

    // Cek apakah Quagga sudah berjalan sebelumnya
    if (Quagga.initialized) {
        console.log("🛑 Quagga sudah berjalan, menghentikan dulu...");
        Quagga.stop();
    }

    // Pastikan kamera bisa diakses
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
    .then(stream => {
        let videoElement = document.getElementById("preview");
        videoElement.srcObject = stream;
        videoElement.play();

        videoElement.addEventListener("loadedmetadata", () => {
            console.log("✅ Kamera siap digunakan.");
            startQuagga();
        });
    })
    .catch(error => {
        console.error("❌ Gagal mengakses kamera:", error);
        alert("Tidak bisa mengakses kamera. Pastikan izin kamera telah diberikan.");
    });
});

// Fungsi untuk memulai Quagga setelah kamera aktif
function startQuagga() {
    document.getElementById("scannerLine").style.display = "block";
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector("#preview"), // Pastikan elemen video sudah terlihat
            constraints: {
                width: 640,
                height: 480,
                facingMode: "environment" 
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
        }
    }, function (err) {
        if (err) {
            console.error("❌ ERROR: Gagal mengaktifkan kamera!", err);
            alert("Gagal mengakses kamera. Pastikan izin sudah diberikan.");
            return;
        }
        console.log("✅ Kamera berhasil diaktifkan, memulai scan...");
        Quagga.start();
        Quagga.initialized = true;
    });

    // Ketika barcode terdeteksi
    Quagga.onDetected(function (result) {
        console.log("🎯 Barcode terdeteksi: ", result.codeResult.code);
        document.getElementById("barcodeResult").value = result.codeResult.code;
        closeScanner();
    });

    // Debugging frame
    Quagga.onProcessed(function (result) {
        console.log("🔄 Frame diproses:", result);
    });
}

// Fungsi menutup scanner
function closeScanner() {
    let scannerModal = document.getElementById("scannerModal");
    // Matikan garis scanner saat scanner ditutup
document.getElementById("scannerLine").style.display = "none";

    scannerModal.classList.add("hidden");
    console.log("🛑 Scanner ditutup.");

    // Stop Quagga dan kamera
    Quagga.stop();
    let videoElement = document.getElementById("preview");
    if (videoElement.srcObject) {
        let tracks = videoElement.srcObject.getTracks();
        tracks.forEach(track => track.stop());
    }
}

// Tombol untuk menutup scanner
document.getElementById("closeScanner").addEventListener("click", closeScanner);



</script>
@endsection
