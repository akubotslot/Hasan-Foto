@extends('layouts.app')

@section('title', 'Pencatatan Penghasilan')

@section('content')

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Riwayat Penghasilan</h2>
    <a href="{{ route('penghasilan.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block hover:bg-blue-600 transition duration-200">
        Tambah Penghasilan
    </a>

    <div class="overflow-x-auto">
    <table class="w-full mt-4">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2 text-sm md:text-base">No</th>
                <th class="px-4 py-2 text-sm md:text-base">Tanggal</th>
                <th class="px-4 py-2 text-sm md:text-base">Total Penghasilan</th>
                <th class="px-4 py-2 text-sm md:text-base">Keterangan</th>
                <th class="px-4 py-2 text-sm md:text-base">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penghasilan as $item)
            <tr class="border-b hover:bg-gray-100 transition duration-150">
                <td class="px-4 py-2 text-sm md:text-base">{{ $loop->iteration }}</td>
                <td class="px-4 py-2 text-sm md:text-base">{{ $item->tanggal }}</td>
                <td class="px-4 py-2 text-sm md:text-base">Rp {{ number_format($item->total_penghasilan, 0, ',', '.') }}</td>
                <td class="px-4 py-2 text-sm md:text-base">{{ $item->catatan }}</td>

                <td class="px-4 py-2 flex space-x-2">
                    <a href="{{ route('penghasilan.edit', $item->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-sm">
                        Edit
                    </a>
                    <button onclick="hapusPenghasilan({{ $item->id }})" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm">
                        Hapus
                    </button>
                    <form id="delete-form-{{ $item->id }}" action="{{ route('penghasilan.destroy', $item->id) }}" method="POST" style="display: none;">
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
    {{ $penghasilan->links('pagination::tailwind') }}
</div>
</div>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusPenghasilan(id) {
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
