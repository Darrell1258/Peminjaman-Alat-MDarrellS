@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 mt-10">

    {{-- Header --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-serif font-bold text-zinc-900 tracking-tight">Edit <span class="text-amber-600">Alat</span></h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-400 mt-2 font-bold">Update Inventaris Premium</p>
    </div>

    {{-- Luxury Card --}}
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
        {{-- Header Bar --}}
        <div class="h-2 bg-gradient-to-r from-amber-600 to-amber-400"></div>
        
        <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

            {{-- Nama Alat --}}
            <div class="mb-6">
                <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-2">Nama Alat</label>
                <input type="text" name="nama_alat" value="{{ $alat->nama_alat }}" required
                    class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-zinc-800 focus:ring-2 focus:ring-amber-500 transition-all">
            </div>

            {{-- Kategori & Stok Grid --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-2">Kategori</label>
                    <select name="kategori_id" required class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-zinc-800 focus:ring-2 focus:ring-amber-500">
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ $alat->kategori_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-2">Stok</label>
                    <input type="number" name="stok" value="{{ $alat->stok }}" min="1" required
                        class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-zinc-800 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            {{-- Gambar --}}
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-3">Gambar Alat</label>
                
                @if($alat->gambar)
                    <div class="mb-4 p-2 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $alat->gambar) }}" class="w-16 h-16 object-cover rounded-lg shadow-inner">
                        <p class="text-[10px] text-zinc-400 italic">Menggunakan gambar tersimpan</p>
                    </div>
                @endif

                <input type="file" name="gambar" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-zinc-900 file:text-amber-500 hover:file:bg-black transition-all cursor-pointer">
                <p class="text-[9px] text-gray-400 mt-2 uppercase tracking-widest">Kosongkan jika tidak ingin mengubah</p>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="flex-1 bg-zinc-900 hover:bg-black text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-zinc-900/20 transition-all hover:-translate-y-0.5">
                    Update Alat
                </button>
                <a href="{{ route('admin.alat') }}" class="px-6 text-[10px] font-bold text-zinc-400 hover:text-red-500 uppercase tracking-widest transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection