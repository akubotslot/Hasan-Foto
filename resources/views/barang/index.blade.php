@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Daftar Barang</h2>
        <a href="{{ route('barang.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded inline-block hover:bg-blue-600 transition duration-200">
            Tambah Barang
        </a>
 
        </div>
        <div class="mb-4">
            <div class="relative">
                <input type="text" id="search" placeholder="Cari kode barang atau nama barang..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <div class="absolute right-3 top-2.5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Wrapper untuk membuat tabel bisa di-scroll secara horizontal di mobile -->
        <div class="overflow-x-auto">
            <table class="w-full mt-4 min-w-max">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="px-4 py-2 text-sm md:text-base">No</th>
                        <th class="px-4 py-2 text-sm md:text-base">Kode Barang</th>
                        <th class="px-4 py-2 text-sm md:text-base">Nama Barang</th>
                        <th class="px-4 py-2 text-sm md:text-base">Satuan</th>
                        <th class="px-4 py-2 text-sm md:text-base">Stok Barang</th>
                        <th class="px-4 py-2 text-sm md:text-base">Harga Satuan</th>
                        <th class="px-4 py-2 text-sm md:text-base">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barang as $item)
                        <tr class="border-b hover:bg-gray-100 transition duration-150">
                            <td class="px-4 py-2 text-sm md:text-base">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">{{ $item->kode_barang }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">
                                {{ optional($item->satuan)->jenis_satuan ?? '-' }}
                            </td>

                            <td class="px-4 py-2 text-sm md:text-base">{{ $item->stok_barang }}</td>
                            <td class="px-4 py-2 text-sm md:text-base">Rp
                                {{ number_format($item->harga_barang, 0, ',', '.') }}</td>

                            <td class="px-4 py-2 flex space-x-2">
                                <a href="{{ route('barang.edit', $item->id) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 text-sm rounded hover:bg-yellow-600">
                                    Edit
                                </a>
                                <button onclick="hapusBarang({{ $item->id }})"
                                    class="bg-red-500 text-white px-3 py-1 text-sm rounded hover:bg-red-600">
                                    Hapus
                                </button>
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('barang.destroy', $item->id) }}" method="POST" class="hidden">
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
            {{ $barang->links('pagination::tailwind') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("search").addEventListener("input", function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const kodeBarang = row.cells[1].textContent.toLowerCase(); // Kode Barang
            const namaBarang = row.cells[2].textContent.toLowerCase(); // Nama Barang

            if (kodeBarang.includes(query) || namaBarang.includes(query)) {
                row.style.display = ""; // Tampilkan baris
            } else {
                row.style.display = "none"; // Sembunyikan baris
            }
        });
    });
        function hapusBarang(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
