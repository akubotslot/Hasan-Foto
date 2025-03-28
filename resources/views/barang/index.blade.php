@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Daftar Barang</h2>
        <a href="{{ route('barang.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block hover:bg-blue-600 transition duration-200">
            Tambah Barang
        </a>

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
