@extends('layouts.app')

@section('content')
    <style>
        #scannerContainer {
            position: relative;
            width: 80vw;
            /* Lebar maksimal 80% dari viewport width */
            max-width: 600px;
            /* Maksimal 600px agar tidak terlalu lebar di desktop */
            aspect-ratio: 16 / 9;
            /* Proporsi tetap */
            overflow: hidden;
            margin: auto;
            /* Supaya tetap berada di tengah */
        }

        #preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            0% {
                top: 0;
            }

            50% {
                top: 100%;
            }

            100% {
                top: 0;
            }
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
                <select name="satuan_id" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Satuan --</option>
                    @foreach ($satuanList as $satuan)
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
                <a href="{{ route('barang.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
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
            <button id="toggleFlash" class="mt-2 p-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Nyalakan
                Flash</button>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        let flashEnabled = false;
        let track; // Untuk menyimpan track video
        let scanning = false; // Tambahkan flag untuk mencegah spam
        let lastScannedCode = ""; // Simpan kode terakhir yang dideteksi

        // Fungsi untuk mengaktifkan flash
        document.getElementById("toggleFlash").addEventListener("click", function() {
            if (track && track.getCapabilities().torch) {
                flashEnabled = !flashEnabled; // Toggle status flash
                track.applyConstraints({
                    advanced: [{
                        torch: flashEnabled
                    }]
                });
                this.textContent = flashEnabled ? "Matikan Flash" : "Nyalakan Flash";
            } else {
                Swal.fire({
                    title: "Flash tidak didukung",
                    text: "Perangkat ini tidak mendukung penggunaan flash.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });

        document.getElementById("scanButton").addEventListener("click", function() {
            let scannerModal = document.getElementById("scannerModal");
            scannerModal.classList.remove("hidden");

            // Cek apakah Quagga sudah berjalan sebelumnya
            if (Quagga.initialized) {
                Quagga.stop();
            }

            // Pastikan kamera bisa diakses dengan menggunakan kamera belakang
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment",
                        width: {
                            ideal: 1920
                        },
                        height: {
                            ideal: 1080
                        },
                        advanced: [{
                            torch: flashEnabled // Pastikan flashEnabled digunakan di sini
                        }]
                    }
                })
                .then(stream => {
                    let videoElement = document.getElementById("preview");
                    videoElement.srcObject = stream;
                    videoElement.play();

                    // Simpan track video untuk kontrol flash
                    track = stream.getVideoTracks()[0]; // Simpan track di sini

                    videoElement.addEventListener("loadedmetadata", () => {
                        startQuagga();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "Gagal mengakses kamera",
                        text: "Tidak bisa mengakses kamera. Pastikan izin kamera telah diberikan.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
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
                        width: {
                            ideal: 1920
                        },
                        height: {
                            ideal: 1080
                        },
                        facingMode: "environment" // Menggunakan kamera belakang
                    }
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
                }
            }, function(err) {
                if (err) {
                    Swal.fire({
                        title: "Gagal mengaktifkan kamera",
                        text: "Pastikan izin sudah diberikan.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                Quagga.start();
                Quagga.initialized = true;
            });

            // Ketika barcode terdeteksi
            Quagga.onDetected(function(result) {
                document.getElementById("barcodeResult").value = result.codeResult.code;
                closeScanner();
            });
        }

        // Fungsi menutup scanner
        function closeScanner() {
            let scannerModal = document.getElementById("scannerModal");
            document.getElementById("scannerLine").style.display = "none";

            scannerModal.classList.add("hidden");

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
