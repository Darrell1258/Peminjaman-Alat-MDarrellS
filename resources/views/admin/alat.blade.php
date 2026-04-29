@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Kelola Alat</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Tambah Alat</h2>
        <form action="{{ route('admin.alat.store') }}" method="POST">
            @csrf
            <div class="flex gap-3 flex-wrap">
                <input type="text" name="nama_alat" placeholder="Nama Alat" required
                    class="border rounded px-3 py-2 text-sm flex-1 min-w-40">

                <select name="kategori_id" required
                    class="border rounded px-3 py-2 text-sm flex-1 min-w-40">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>

                <input type="number" name="stok" placeholder="Stok" min="1" required
                    class="border rounded px-3 py-2 text-sm w-28">

                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-semibold">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($alats as $a)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $a->nama_alat }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $a->kategori->nama_kategori }}</td>
                    <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $a->stok }}</td>
                    <td class="px-4 py-2 text-sm text-right space-x-2">
                        <a href="{{ route('admin.alat.edit', $a->id) }}"
                            class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                        <form action="{{ route('admin.alat.destroy', $a->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus alat ini?')"
                                class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-400">Belum ada data alat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection