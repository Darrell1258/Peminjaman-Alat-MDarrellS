<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Alat;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $menunggu = Peminjaman::where('status', 'menunggu')
            ->with(['alat', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $dipinjam = Peminjaman::where('status', 'disetujui')
            ->with(['alat', 'user'])
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // ✅ TAMBAHAN RIWAYAT
        $riwayat = Peminjaman::whereIn('status', ['selesai', 'ditolak'])
            ->with(['alat', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.dashboard', compact('menunggu', 'dipinjam', 'riwayat'));
    }

    public function validasi(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:disetujui,ditolak',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->aksi === 'disetujui') {
            $alat = Alat::findOrFail($peminjaman->alat_id);

            if ($alat->stok < $peminjaman->jumlah) {
                return back()->with('error', 'Stok tidak cukup!');
            }

            $alat->decrement('stok', $peminjaman->jumlah);
        }

        $peminjaman->update([
            'status' => $request->aksi
        ]);

        return back()->with('success', 'Data berhasil divalidasi');
    }

    public function setujui($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $alat = Alat::findOrFail($peminjaman->alat_id);

        if ($alat->stok < $peminjaman->jumlah) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        $alat->decrement('stok', $peminjaman->jumlah);

        $peminjaman->update([
            'status' => 'disetujui'
        ]);

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function kembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $alat = Alat::findOrFail($peminjaman->alat_id);
        $alat->increment('stok', $peminjaman->jumlah);

        $peminjaman->update([
            'status' => 'selesai',
            'tgl_dikembalikan' => now()
        ]);

        return back()->with('success', 'Pengembalian berhasil');
    }
}