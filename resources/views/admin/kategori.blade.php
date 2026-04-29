@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-8 min-h-screen">

    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-10 border-b border-amber-500/30 pb-4">
        <h1 class="text-3xl font-serif font-bold text-gray-900 tracking-tight">
            Manajemen <span class="text-amber-600">Kategori</span>
        </h1>
        <div class="text-xs uppercase tracking-widest text-gray-500 font-semibold">
            Premium Administration
        </div>
    </div>

    @if(session('success'))
        <div class="bg-black text-amber-400 border-l-4 border-amber-500 px-6 py-4 rounded-md mb-8 shadow-xl animate-fade-in">
            <span class="font-bold">Sukses:</span> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- Form Section --}}
        <div class="lg:col-span-1">
            <div class="bg-zinc-900 rounded-xl shadow-2xl overflow-hidden border border-amber-500/20">
                <div class="bg-gradient-to-r from-amber-600 to-amber-400 px-6 py-4">
                    <h2 class="text-white font-semibold uppercase tracking-wider text-sm">Tambah Kategori Baru</h2>
                </div>
                <div class="p-8">
                    <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-amber-500 uppercase tracking-widest mb-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" placeholder="Contoh: Kamera Mirrorless" required 
                                class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition duration-300 placeholder-gray-500">
                        </div>

                        <button type="submit" 
                            class="w-full bg-amber-500 hover:bg-amber-600 text-black font-bold py-3 rounded-lg shadow-lg shadow-amber-500/20 transition-all duration-300 transform hover:-translate-y-1 uppercase text-xs tracking-widest">
                            Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h2 class="text-lg font-bold text-zinc-800">Daftar Koleksi Kategori</h2>
                    <span class="bg-zinc-900 text-amber-400 text-[10px] px-3 py-1 rounded-full uppercase font-bold tracking-tighter">
                        Total: {{ $kategoris->count() }}
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-900 text-amber-500 uppercase text-[10px] tracking-widest">
                            <tr>
                                <th class="px-8 py-4 text-left">Nama Kategori</th>
                                <th class="px-8 py-4 text-center">Status</th>
                                <th class="px-8 py-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($kategoris as $k)
                            <tr class="hover:bg-amber-50/30 transition-colors group">
                                <td class="px-8 py-5 text-sm font-medium text-zinc-700 group-hover:text-amber-700 transition-colors italic font-serif">
                                    {{ $k->nama_kategori }}
                                </td>
                                <td class="px-8 py-5 text-center text-xs">
                                    <span class="text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-100">Aktif</span>
                                </td>
                                <td class="px-8 py-5 text-right space-x-3">
                                    <a href="{{ route('admin.kategori.edit', $k->id) }}" class="text-zinc-400 hover:text-amber-600 transition-colors text-xs font-bold uppercase tracking-tighter">Edit</a>
                                    
                                    <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Hapus kategori eksklusif ini?')" 
                                            class="text-zinc-300 hover:text-red-600 transition-colors text-xs font-bold uppercase tracking-tighter">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-10 text-center text-gray-400 italic">
                                    Belum ada kategori yang terdaftar di galeri.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }
</style>
@endsection