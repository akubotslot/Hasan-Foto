@extends('layouts.app')

@section('title', 'Penggunaan Barang')

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
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold ">Riwayat Penggunaan Barang</h2>
        <div class="flex items-center gap-2 ">
            <button type="button" id="scanButton" class="p-2 bg-gray-300 rounded-lg hover:bg-gray-400 flex items-center">
                <img src="{{ asset('barcode-scan.png') }}" alt="Scan" class="h-6 w-6">
            </button>
            <a href="{{ route('penggunaan_barang.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                Tambah Penggunaan Barang
            </a>
            
        </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full mt-4">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2 text-sm md:text-base">No</th>
                        <th class="px-4 py-2 text-sm md:text-base">Tanggal</th>
                        <th class="px-4 py-2 text-sm md:text-base">Nama Barang</th>
                        <th class="px-4 py-2 text-sm md:text-base">Jumlah</th>
                        <th class="px-4 py-2 text-sm md:text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penggunaan_barang as $item)
                        <tr class="border-b hover:bg-gray-100 transition duration-150">
                            <td class="px-4 py-2 text-sm md:text-base">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">{{ $item->tanggal }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">{{ $item->barang->nama_barang }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">{{ $item->jumlah_dipakai }}</td>

                            <td class="px-4 py-2 flex space-x-2">
                                <a href="{{ route('penggunaan_barang.edit', $item->id) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-sm">
                                    Edit
                                </a>
                                <button onclick="hapusPenggunaan({{ $item->id }})"
                                    class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm">
                                    Hapus
                                </button>
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('penggunaan_barang.destroy', $item->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $penggunaan_barang->links('pagination::tailwind') }}
        </div>
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
    <audio id="barcodeSound" src="{{ asset('barcode_sound.mp3') }}" preload="auto"></audio>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        let flashEnabled = false;
        let track; // Untuk menyimpan track video
        let scanning = false; // Tambahkan flag untuk mencegah spam
        let lastScannedCode = ""; // Simpan kode terakhir yang dideteksi

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

        function hapusPenggunaan(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        document.getElementById("scanButton").addEventListener("click", function() {
            let scannerModal = document.getElementById("scannerModal");
            scannerModal.classList.remove("hidden");

            // Cek apakah Quagga sudah berjalan sebelumnya
            if (Quagga.initialized) {
                Quagga.stop();
            }

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
                            torch: flashEnabled
                        }] // Tambahkan torch jika didukung
                    }
                })
                .then(stream => {
                    let videoElement = document.getElementById("preview");
                    videoElement.srcObject = stream;
                    videoElement.play();

                    // Simpan track video untuk kontrol flash
                    track = stream.getVideoTracks()[0];

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
                        facingMode: "environment"
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

            Quagga.onDetected(function(result) {
                let kodeBarang = result.codeResult.code;

                if (!scanning && kodeBarang !== lastScannedCode) {
                    scanning = true;
                    lastScannedCode = kodeBarang; // Simpan kode terakhir agar tidak spam

                    // Putar audio saat barcode berhasil ditemukan
                    document.getElementById("barcodeSound").play();

                    // Cek apakah barang ada di database sebelum redirect
                    fetch(`/cek-barang/${kodeBarang}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                // Jika barang ditemukan, redirect ke halaman create
                                window.location.href = "{{ route('penggunaan_barang.create') }}?kode_barang=" +
                                    kodeBarang;

                                // Hentikan Quagga setelah redirect
                                Quagga.stop();
                                let videoElement = document.getElementById("preview");
                                if (videoElement.srcObject) {
                                    let tracks = videoElement.srcObject.getTracks();
                                    tracks.forEach(track => track.stop());
                                }
                            } else {
                                // Jika barang tidak ditemukan, tampilkan SweetAlert2
                                Swal.fire({
                                    title: "Kode tidak ditemukan!",
                                    text: "Barang dengan kode ini tidak ada di database.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });

                                // Reset scanning setelah 2 detik agar bisa scan lagi
                                setTimeout(() => {
                                    scanning = false;
                                    lastScannedCode = ""; // Reset kode agar bisa scan ulang
                                }, 2000);
                            }
                        })
                        .catch(error => {
                            scanning = false;
                            lastScannedCode = ""; // Reset agar tetap bisa scan ulang
                        });
                }
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
