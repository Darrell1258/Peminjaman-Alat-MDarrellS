@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Dashboard Peminjam</h1>
        <a href="{{ route('peminjam.ajukan') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-semibold">
            + Ajukan Peminjaman
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($peminjaman as $p)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $p->alat->nama_alat }}</td>
                    <td class="px-4 py-2 text-sm text-center text-gray-800">{{ $p->jumlah }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $p->tanggal_pinjam }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $p->tanggal_kembali }}</td>
                    <td class="px-4 py-2 text-sm text-center">
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $p->status === 'menunggu'       ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $p->status === 'disetujui'      ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $p->status === 'selesai'        ? 'bg-green-100 text-green-700' : '' }}
                            {{ $p->status === 'ditolak'        ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-400">
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection