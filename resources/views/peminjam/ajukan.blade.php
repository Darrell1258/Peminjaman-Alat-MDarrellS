@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Ajukan Peminjaman</h1>
            <p class="text-gray-500 mt-1">Pilih alat yang Anda butuhkan untuk proyek Anda.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- SISI KIRI: FORM ENTRI --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('peminjam.store') }}" method="POST" id="formPinjam">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Pilih Alat</label>
                    <select name="alat_id" id="alat_select" required 
                        class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">-- Cari Alat Tersedia --</option>
                        @foreach($alats as $a)
                            <option value="{{ $a->id }}" 
                                data-gambar="{{ $a->gambar ? asset('storage/' . $a->gambar) : '' }}"
                                data-stok="{{ $a->stok }}"
                                {{ old('alat_id') == $a->id ? 'selected' : '' }}>
                                {{ $a->nama_alat }} (Tersedia: {{ $a->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('alat_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Jumlah</label>
                        <input type="number" name="jumlah" id="input_jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                            class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        <p id="stok_info" class="text-[10px] text-gray-400 mt-1 hidden italic">Maksimal stok tercapai</p>
                        @error('jumlah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tgl_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required
                            min="{{ date('Y-m-d') }}" 
                            class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
                        @error('tanggal_pinjam')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" id="tgl_kembali" value="{{ old('tanggal_kembali') }}" required
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
                    @error('tanggal_kembali')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-50">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                        Konfirmasi Peminjaman
                    </button>
                </div>
            </form>
        </div>

        {{-- SISI KANAN: PREVIEW VISUAL --}}
        <div class="flex flex-col gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center min-h-[300px]">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Visual Preview</h3>
                
                <div id="image_wrapper" class="w-full relative group">
                    <img id="preview_img" src="" alt="Preview" 
                        class="hidden w-full h-56 object-contain rounded-xl transition-opacity duration-300">
                    
                    <div id="placeholder_view" class="flex flex-col items-center text-gray-300">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs italic">Belum ada alat terpilih</span>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-900 rounded-2xl p-6 text-white">
                <h4 class="text-xs font-bold uppercase tracking-widest text-indigo-300 mb-2">Informasi</h4>
                <p class="text-sm leading-relaxed opacity-80">
                    Pastikan alat dikembalikan tepat waktu untuk menghindari denda atau sanksi administrasi.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAlat = document.getElementById('alat_select');
        const previewImg = document.getElementById('preview_img');
        const placeholder = document.getElementById('placeholder_view');
        const inputJumlah = document.getElementById('input_jumlah');
        const stokInfo = document.getElementById('stok_info');
        const tglPinjam = document.getElementById('tgl_pinjam');
        const tglKembali = document.getElementById('tgl_kembali');

        function updateUI() {
            const selectedOption = selectAlat.options[selectAlat.selectedIndex];
            const gambarUrl = selectedOption.getAttribute('data-gambar');
            const stokTersedia = selectedOption.getAttribute('data-stok');

            // Update Image
            if (gambarUrl) {
                previewImg.src = gambarUrl;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                previewImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Update Stok Constraint
            if (stokTersedia) {
                inputJumlah.max = stokTersedia;
                stokInfo.classList.remove('hidden');
                stokInfo.innerText = `Stok maksimal: ${stokTersedia}`;
            }
        }

        // Sinkronisasi Tanggal
        tglPinjam.addEventListener('change', function() {
            if (this.value) {
                let nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                tglKembali.min = nextDay.toISOString().split("T")[0];
                
                if(tglKembali.value && tglKembali.value <= this.value) {
                    tglKembali.value = tglKembali.min;
                }
            }
        });

        selectAlat.addEventListener('change', updateUI);
        
        // Init jika ada old value (saat validasi error)
        if (selectAlat.value !== "") {
            updateUI();
        }
    });
</script>
@endsection