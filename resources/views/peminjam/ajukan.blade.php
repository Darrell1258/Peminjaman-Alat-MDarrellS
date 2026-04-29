@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Ajukan Peminjaman</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('peminjam.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Alat</label>
                <select name="alat_id" required class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">-- Pilih Alat --</option>
                    @foreach($alats as $a)
                        <option value="{{ $a->id }}" {{ old('alat_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->nama_alat }} (Stok: {{ $a->stok }})
                        </option>
                    @endforeach
                </select>
                @error('alat_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                    class="w-full border rounded px-3 py-2 text-sm">
                @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" required
                    min="{{ date('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2 text-sm">
                @error('tanggal_pinjam')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}" required
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="w-full border rounded px-3 py-2 text-sm">
                @error('tanggal_kembali')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded text-sm font-semibold">
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection