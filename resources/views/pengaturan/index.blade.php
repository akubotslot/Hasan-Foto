@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
    <div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold ">Pengaturan Aplikasi</h2>
            <form action="{{ route('backup.database') }}" method="GET">
                <button type="submit">Backup Database</button>
            </form>
        </div>

        <div class="space-y-4">
            @foreach ($pengaturan as $item)
                <div class="mb-4">
                    <label for="batas_minimum_stok" class="block text-gray-700 font-semibold">
                        Minimum Stok:
                    </label>
                    <input type="number" name="batas_minimum_stok" value="{{ $item->batas_minimum_stok }}"
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="updateMinimumStok(this.value, {{ $item->id }})">

                </div>
                <div class="flex mb-4">
                    <label for="batas_minimum_stok" class="block text-gray-700 font-semibold">
                        Notifikasi Minimum Stok
                    </label>
                    <div class="ml-4">
                        <label class="inline-flex relative items-center cursor-pointer">
                            <input type="checkbox" name="notifikasi_minimum_stok" value="1"
                                @if ($item->notifikasi_minimum_stok == 1) checked @endif class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Aktif</span>
                        </label>
                    </div>
                </div>

                

                <div class="mb-4">
                    <div class="flex justify-between">
                        <label for="satuan" class="block text-gray-700 font-semibold">
                            Satuan:
                        </label>
                        <div class="flex justify-center">
                            <button type="button" id="tombolsatuan"
                                class=" bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-sm">Tambah</button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">No</th>
                                    <th class="px-4 py-2 border border-gray-200">Jenis Satuan</th>
                                    <th class="px-4 py-2 border border-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($satuan as $item)
                                    <tr class="border-b hover:bg-gray-100 transition duration-150">
                                        <td class="text-center px-4 py-2 border border-gray-200">{{ $loop->iteration }}</td>
                                        <td class="text-center px-4 py-2 border border-gray-200">{{ $item->jenis_satuan }}
                                        </td>

                                        <td class="px-4 py-2 flex justify-center space-x-2">
                                            <a onclick="openEditModal({{ $item->id }}, '{{ $item->jenis_satuan }}')"
                                                class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-sm">
                                                Edit
                                            </a>
                                            <button onclick="hapussatuan({{ $item->id }})"
                                                class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm">
                                                Hapus
                                            </button>
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('satuan.destroy', $item->id) }}" method="POST"
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
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Scanner -->
    <div id="scannerModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-2">Tambah Satuan</h3>
            <form action="{{ route('satuan.store') }}" method="POST">
                @csrf
                <label class="block text-gray-700 font-semibold mb-1">Nama Satuan:</label>
                <input type="text" name="jenis_satuan" class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeScanner()"
                        class="px-3 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Batal</button>
                    <button type="submit"
                        class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Satuan -->
    <div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-2">Edit Satuan</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <label class="block text-gray-700 font-semibold mb-1">Nama Satuan:</label>
                <input type="text" name="jenis_satuan" id="editJenisSatuan"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-3 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Batal</button>
                    <button type="submit"
                        class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        
        function updateMinimumStok(value, id) {
            fetch("/update-minimum-stok/" + id, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({
                        batas_minimum_stok: value
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Minimum stok telah diperbarui!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan, silakan coba lagi!',
                    });
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("input[name='notifikasi_minimum_stok']").forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    let status = this.checked ? 1 :
                        0; // Jika checkbox dicentang, status = 1, jika tidak = 0
                    let id = 1; // ID data dari database

                    // Kirim update ke server
                    fetch("/update-notifikasi/" + id, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute("content"),
                            },
                            body: JSON.stringify({
                                notifikasi_minimum_stok: status
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {

                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });


        document.getElementById("tombolsatuan").addEventListener("click", function() {
            let scannerModal = document.getElementById("scannerModal");
            scannerModal.classList.remove("hidden");
            document.querySelector('[name="jenis_satuan"]').focus();
        });

        document.addEventListener("click", function(event) {
            let modal = document.getElementById("scannerModal");
            if (!modal.contains(event.target) && !event.target.closest("#tombolsatuan")) {
                closeScanner();
            }
        });

        function openEditModal(id, jenis_satuan) {
            document.getElementById("editId").value = id;
            document.getElementById("editJenisSatuan").value = jenis_satuan;
            document.getElementById("editForm").action = "/satuan/" + id;
            document.getElementById("editModal").classList.remove("hidden");
        }

        function closeEditModal() {
            document.getElementById("editModal").classList.add("hidden");
        }

        function closeScanner() {
            document.getElementById("scannerModal").classList.add("hidden");
        }

        function hapussatuan(id) {
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
    </script>
@endsection
