<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Alat</title>
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            .print-shadow { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-5xl mx-auto">

    {{-- Toolbar (tidak ikut tercetak) --}}
    <div class="no-print flex items-center justify-between mb-4">
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm text-gray-500 hover:text-gray-800">
            ← Kembali ke Dashboard
        </a>
        <button onclick="window.print()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-semibold">
            🖨️ Cetak Laporan
        </button>
    </div>

    {{-- Filter (tidak ikut tercetak) --}}
    <div class="no-print bg-white rounded-lg shadow p-4 mb-4">
        <form method="GET" action="{{ route('admin.laporan') }}" class="flex gap-3 flex-wrap items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Filter Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}"
                    class="border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Filter Status</label>
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="selesai"   {{ request('status') === 'selesai'   ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-semibold">
                Terapkan Filter
            </button>
            @if(request('bulan') || request('status'))
                <a href="{{ route('admin.laporan') }}"
                    class="text-sm text-gray-400 hover:text-gray-700 py-2">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Dokumen laporan (ini yang tercetak) --}}
    <div class="bg-white rounded-lg shadow print-shadow p-8">

        {{-- Header laporan --}}
        <div class="text-center mb-6 border-b pb-4">
            <h1 class="text-xl font-bold text-gray-800">LAPORAN PEMINJAMAN ALAT</h1>
            <p class="text-sm text-gray-500 mt-1">
                Dicetak pada: {{ now()->format('d F Y, H:i') }}
                @if(request('bulan'))
                    &nbsp;|&nbsp; Bulan: {{ \Carbon\Carbon::parse(request('bulan'))->format('F Y') }}
                @endif
                @if(request('status'))
                    &nbsp;|&nbsp; Status: {{ ucfirst(request('status')) }}
                @endif
            </p>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-4 gap-3 mb-6 no-print">
            <div class="bg-gray-50 rounded p-3 text-center">
                <p class="text-xs text-gray-500">Total</p>
                <p class="text-xl font-bold text-gray-800">{{ $peminjaman->count() }}</p>
            </div>
            <div class="bg-yellow-50 rounded p-3 text-center">
                <p class="text-xs text-gray-500">Menunggu</p>
                <p class="text-xl font-bold text-yellow-600">{{ $peminjaman->where('status', 'menunggu')->count() }}</p>
            </div>
            <div class="bg-blue-50 rounded p-3 text-center">
                <p class="text-xs text-gray-500">Disetujui</p>
                <p class="text-xl font-bold text-blue-600">{{ $peminjaman->where('status', 'disetujui')->count() }}</p>
            </div>
            <div class="bg-green-50 rounded p-3 text-center">
                <p class="text-xs text-gray-500">Selesai</p>
                <p class="text-xl font-bold text-green-600">{{ $peminjaman->where('status', 'selesai')->count() }}</p>
            </div>
        </div>

        {{-- Tabel --}}
        <table class="min-w-full text-sm border border-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border border-gray-200 px-3 py-2 text-left text-xs font-semibold text-gray-600">No</th>
                    <th class="border border-gray-200 px-3 py-2 text-left text-xs font-semibold text-gray-600">Peminjam</th>
                    <th class="border border-gray-200 px-3 py-2 text-left text-xs font-semibold text-gray-600">Alat</th>
                    <th class="border border-gray-200 px-3 py-2 text-center text-xs font-semibold text-gray-600">Jml</th>
                    <th class="border border-gray-200 px-3 py-2 text-left text-xs font-semibold text-gray-600">Tgl Pinjam</th>
                    <th class="border border-gray-200 px-3 py-2 text-left text-xs font-semibold text-gray-600">Tgl Kembali</th>
                    <th class="border border-gray-200 px-3 py-2 text-left text-xs font-semibold text-gray-600">Dikembalikan</th>
                    <th class="border border-gray-200 px-3 py-2 text-center text-xs font-semibold text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $p)
                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                    <td class="border border-gray-200 px-3 py-2 text-center text-gray-500">{{ $i + 1 }}</td>
                    <td class="border border-gray-200 px-3 py-2 text-gray-800">{{ $p->user->name }}</td>
                    <td class="border border-gray-200 px-3 py-2 text-gray-800">{{ $p->alat->nama_alat }}</td>
                    <td class="border border-gray-200 px-3 py-2 text-center text-gray-800">{{ $p->jumlah }}</td>
                    <td class="border border-gray-200 px-3 py-2 text-gray-500">{{ $p->tanggal_pinjam }}</td>
                    <td class="border border-gray-200 px-3 py-2 text-gray-500">{{ $p->tanggal_kembali }}</td>
                    <td class="border border-gray-200 px-3 py-2 text-gray-500">
                        {{ $p->tgl_dikembalikan ?? '-' }}
                    </td>
                    <td class="border border-gray-200 px-3 py-2 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            {{ $p->status === 'menunggu'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $p->status === 'disetujui' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $p->status === 'selesai'   ? 'bg-green-100 text-green-700' : '' }}
                            {{ $p->status === 'ditolak'   ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="border border-gray-200 px-3 py-6 text-center text-gray-400">
                        Tidak ada data peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer laporan --}}
        <div class="mt-8 flex justify-end">
            <div class="text-center">
                <p class="text-sm text-gray-600">Petugas,</p>
                <div class="mt-14 border-t border-gray-400 w-40"></div>
                <p class="text-sm text-gray-600">( ........................... )</p>
            </div>
        </div>

    </div>
</div>

</body>
</html>