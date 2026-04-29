@extends('layouts.app')

@section('content')
{{-- Tambahkan pb-20 agar konten tidak terpotong di bagian bawah --}}
<div class="max-w-7xl mx-auto p-6 pb-20">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Statistik Sistem</h1>
        <div class="text-sm font-medium text-gray-500 bg-gray-100 px-4 py-2 rounded-full">
            <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d M Y') }}
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-r-lg mb-6 shadow-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        {{-- Card Total Alat --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider">Total Alat</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalAlat }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-toolbox text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Kategori --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wider">Total Kategori</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalKategori }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Card Total Peminjaman --}}
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs font-semibold uppercase tracking-wider">Total Peminjaman</p>
                    <p class="text-4xl font-bold mt-1">{{ $totalPeminjaman }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-hand-holding text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Card Menunggu Validasi --}}
        <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-xs font-semibold uppercase tracking-wider">Menunggu</p>
                    <p class="text-4xl font-bold mt-1">{{ $menunggu }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-pie text-indigo-500"></i> Ringkasan Peminjaman
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-green-50 rounded-2xl transition-hover hover:bg-green-100">
                    <span class="text-green-700 font-bold text-sm uppercase tracking-wide">Disetujui</span>
                    <span class="text-2xl font-black text-green-600">{{ $disetujui ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-red-50 rounded-2xl transition-hover hover:bg-red-100">
                    <span class="text-red-700 font-bold text-sm uppercase tracking-wide">Ditolak</span>
                    <span class="text-2xl font-black text-red-600">{{ $ditolak ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-2xl border-l-8 border-blue-500 transition-hover hover:bg-blue-100">
                    <span class="text-blue-700 font-bold text-sm uppercase tracking-wide">Selesai/Kembali</span>
                    <span class="text-2xl font-black text-blue-600">{{ $selesai ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-calendar-check text-emerald-500"></i> Kondisi Saat Ini
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-6 bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-3xl shadow-inner">
                    <p class="text-xs opacity-80 font-bold uppercase tracking-widest mb-1">Bulan Ini</p>
                    <p class="text-3xl font-black">{{ $peminjamanBulanIni ?? 0 }}</p>
                    <p class="text-[10px] mt-2 opacity-70 italic">*Data peminjaman baru</p>
                </div>
                <div class="p-6 bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-3xl shadow-inner">
                    <p class="text-xs opacity-80 font-bold uppercase tracking-widest mb-1">Total Unit Alat</p>
                    <p class="text-3xl font-black">{{ $alatTersedia ?? 0 }}</p>
                    <p class="text-[10px] mt-2 opacity-70 italic">*Stok keseluruhan fisik</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <a href="{{ route('admin.kategori') }}" class="group bg-white border border-gray-100 rounded-3xl p-6 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-blue-200 text-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white flex items-center justify-center transition-colors">
                <i class="fas fa-list-ul text-xl"></i>
            </div>
            <p class="font-bold text-sm">Kategori</p>
        </a>
        <a href="{{ route('admin.alat') }}" class="group bg-white border border-gray-100 rounded-3xl p-6 hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-emerald-200 text-center">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white flex items-center justify-center transition-colors">
                <i class="fas fa-box-open text-xl"></i>
            </div>
            <p class="font-bold text-sm">Data Alat</p>
        </a>
        <a href="{{ url('admin.peminjaman') }}" class="group bg-white border border-gray-100 rounded-3xl p-6 hover:bg-purple-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-purple-200 text-center">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white flex items-center justify-center transition-colors">
                <i class="fas fa-exchange-alt text-xl"></i>
            </div>
            <p class="font-bold text-sm">Peminjaman</p>
        </a>
        <a href="{{ route('admin.laporan') }}" class="group bg-white border border-gray-100 rounded-3xl p-6 hover:bg-orange-600 hover:text-white transition-all duration-300 shadow-md hover:shadow-orange-200 text-center">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white flex items-center justify-center transition-colors">
                <i class="fas fa-file-invoice text-xl"></i>
            </div>
            <p class="font-bold text-sm">Laporan</p>
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800 uppercase tracking-tighter">Riwayat Aktivitas Terakhir</h3>
            <span class="bg-indigo-100 text-indigo-700 text-[10px] px-3 py-1 rounded-full font-black uppercase">Finalized Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest">
                        <th class="px-8 py-4 text-left font-bold">Peminjam</th>
                        <th class="px-8 py-4 text-left font-bold">Alat</th>
                        <th class="px-8 py-4 text-center font-bold">Qty</th>
                        <th class="px-8 py-4 text-left font-bold">Waktu</th>
                        <th class="px-8 py-4 text-center font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $p)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xs">
                                    {{ substr($p->user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $p->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-600 font-medium">{{ $p->alat->nama_alat }}</td>
                        <td class="px-8 py-5 text-center text-sm font-bold text-gray-800">{{ $p->jumlah }}</td>
                        <td class="px-8 py-5">
                            <div class="text-[11px] text-gray-500 font-medium">
                                <p><i class="far fa-calendar-alt mr-1"></i> {{ $p->tanggal_pinjam }}</p>
                                @if($p->tgl_dikembalikan)
                                    <p class="text-green-600 mt-1"><i class="fas fa-undo-alt mr-1"></i> {{ $p->tgl_dikembalikan }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($p->status == 'selesai')
                                <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1.5 rounded-full uppercase">Selesai</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-[10px] font-black px-3 py-1.5 rounded-full uppercase">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-20">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="fas fa-inbox text-5xl mb-4"></i>
                                <p class="text-sm font-bold uppercase tracking-widest">Belum Ada Riwayat Aktivitas</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection