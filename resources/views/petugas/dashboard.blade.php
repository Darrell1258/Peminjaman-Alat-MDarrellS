@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Dashboard Petugas</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- SECTION 1: Menunggu Validasi --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-3">
            Menunggu Validasi
            <span class="ml-2 bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">
                {{ $menunggu->count() }}
            </span>
        </h2>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($menunggu as $p)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $p->user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $p->alat->nama_alat }}</td>
                        <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $p->jumlah }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $p->tanggal_pinjam }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $p->tanggal_kembali }}</td>

                        <td class="px-4 py-2 text-sm text-center">

                            {{-- FIX: pakai admin.setujui --}}
                            <form action="{{ route('admin.setujui', $p->id) }}" method="POST" class="inline">
                                @csrf
                                <button onclick="return confirm('Setujui peminjaman ini?')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    Setujui
                                </button>
                            </form>

                            {{-- tetap pakai validasi untuk tolak --}}
                            <form action="{{ route('petugas.validasi', $p->id) }}" method="POST" class="inline ml-1">
                                @csrf
                                <input type="hidden" name="aksi" value="ditolak">
                                <button onclick="return confirm('Tolak peminjaman ini?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    Tolak
                                </button>
                            </form>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-400">
                            Tidak ada peminjaman yang menunggu validasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: Sedang Dipinjam --}}
    <div>
        <h2 class="text-lg font-semibold mb-3">
            Sedang Dipinjam
            <span class="ml-2 bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-medium">
                {{ $dipinjam->count() }}
            </span>
        </h2>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dipinjam as $p)
                    <tr class="{{ \Carbon\Carbon::parse($p->tanggal_kembali)->isPast() ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $p->user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $p->alat->nama_alat }}</td>
                        <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $p->jumlah }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="{{ \Carbon\Carbon::parse($p->tanggal_kembali)->isPast() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                {{ $p->tanggal_kembali }}
                                @if(\Carbon\Carbon::parse($p->tanggal_kembali)->isPast())
                                    <span class="text-xs">(terlambat)</span>
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            <form action="{{ route('petugas.kembali', $p->id) }}" method="POST" class="inline">
                                @csrf
                                <button onclick="return confirm('Proses pengembalian alat ini?')"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    Proses Kembali
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-400">
                            Tidak ada alat yang sedang dipinjam.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- SECTION 3: Riwayat --}}
<div class="mt-8">
    <h2 class="text-lg font-semibold mb-3">
        Riwayat Peminjaman
        <span class="ml-2 bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full font-medium">
            {{ $riwayat->count() }}
        </span>
    </h2>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dikembalikan</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($riwayat as $p)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $p->user->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $p->alat->nama_alat }}</td>
                    <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $p->jumlah }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $p->tanggal_pinjam }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">
                        {{ $p->tgl_dikembalikan ?? '-' }}
                    </td>
                    <td class="px-4 py-2 text-sm text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            {{ $p->status === 'selesai' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $p->status === 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-400">
                        Belum ada riwayat.
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
@endsection