<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamController extends Controller
{
    public function dashboard()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with('alat')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('peminjaman'));
    }

    public function ajukan()
    {
        $alats = Alat::where('stok', '>', 0)->get();
        return view('peminjam.ajukan', compact('alats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id'          => 'required|exists:alat,id',
            'jumlah'           => 'required|integer|min:1',
            'tanggal_pinjam'   => 'required|date|after_or_equal:today',
            'tanggal_kembali'  => 'required|date|after:tanggal_pinjam',
        ]);

        // cek stok cukup
        $alat = Alat::findOrFail($request->alat_id);
        if ($request->jumlah > $alat->stok) {
            return redirect()->back()
                ->with('error', 'Stok alat tidak mencukupi. Stok tersedia: ' . $alat->stok)
                ->withInput();
        }

        Peminjaman::create([
            'user_id'         => Auth::id(),
            'alat_id'         => $request->alat_id,
            'jumlah'          => $request->jumlah,
            'tanggal_pinjam'  => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'menunggu',
        ]);

        return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan, menunggu validasi petugas.');
    }
}