@extends('layouts.app')

@section('title', 'Pencatatan Pengeluaran')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Riwayat Pengeluaran</h2>
    <a href="{{ route('pengeluaran.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded inline-block hover:bg-blue-600 transition duration-200">
        Tambah Pengeluaran
    </a>
    </div>  

    <div class="overflow-x-auto">
    <table class="w-full mt-4">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama Pengeluaran</th>
                <th class="px-4 py-2">Total Pengeluaran</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Keterangan</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengeluaran as $item)
            <tr class="border-b hover:bg-gray-100 transition duration-150">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $item->nama_pengeluaran }}</td>
                <td class="px-4 py-2">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td class="px-4 py-2">{{ $item->tanggal }}</td>
                <td class="px-4 py-2">{{ $item->keterangan }}</td>
                <td class="px-4 py-2 flex space-x-2">
                    <a href="{{ route('pengeluaran.edit', $item->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-sm">
                        Edit
                    </a>
                    <button onclick="hapusPengeluaran({{ $item->id }})" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm">
                        Hapus
                    </button>
                    <form id="delete-form-{{ $item->id }}" action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" style="display: none;">
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
    {{ $pengeluaran->links('pagination::tailwind') }}
</div>
</div>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusPengeluaran(id) {
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