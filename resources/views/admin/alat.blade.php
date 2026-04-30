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
        {{-- TAMBAHKAN enctype UNTUK UPLOAD FILE --}}
        <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex gap-3 flex-wrap items-end">
                <div class="flex-1 min-w-40">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama Alat</label>
                    <input type="text" name="nama_alat" placeholder="Nama Alat" required
                        class="border rounded px-3 py-2 text-sm w-full">
                </div>

                <div class="flex-1 min-w-40">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                    <select name="kategori_id" required
                        class="border rounded px-3 py-2 text-sm w-full">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-28">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Stok</label>
                    <input type="number" name="stok" placeholder="Stok" min="1" required
                        class="border rounded px-3 py-2 text-sm w-full">
                </div>

                {{-- INPUT GAMBAR BARU --}}
                <div class="flex-1 min-w-40">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Gambar</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="border rounded px-3 py-1.5 text-sm w-full file:mr-2 file:py-1 file:px-2 file:border-0 file:text-xs file:bg-gray-100">
                </div>

                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-semibold h-[38px]">
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
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($alats as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">
                        @if($a->gambar)
                            <img src="{{ asset('storage/' . $a->gambar) }}" class="w-12 h-12 object-cover rounded shadow-sm">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-[10px] text-gray-400">No Image</div>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800 font-medium">{{ $a->nama_alat }}</td>
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
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-400">Belum ada data alat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection