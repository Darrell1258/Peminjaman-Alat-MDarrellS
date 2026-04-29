<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ── DASHBOARD ──────────────────────────────────────────
    public function dashboard()
    {
        // Statistik Utama
        $totalAlat       = Alat::count();
        $totalKategori   = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        $menunggu        = Peminjaman::where('status', 'menunggu')->count();

        // Statistik tambahan untuk Chart & Info
        $disetujui = Peminjaman::where('status', 'disetujui')->count();
        $ditolak   = Peminjaman::where('status', 'ditolak')->count();
        $selesai   = Peminjaman::where('status', 'selesai')->count();

        // Data Bulanan & Stok
        $peminjamanBulanIni = Peminjaman::whereMonth('tanggal_pinjam', now()->month)->count();
        $alatTersedia       = Alat::sum('stok');

        // 🔥 RIWAYAT (Data yang sudah difinalisasi)
        $riwayat = Peminjaman::with(['alat','user'])
            ->whereIn('status', ['selesai','ditolak'])
            ->orderBy('updated_at', 'desc')
            ->take(10) // Ambil 10 terakhir saja agar tidak berat
            ->get();

        // Data untuk Grafik (6 Bulan Terakhir)
        $labels = [];
        $data   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M');
            $data[]   = Peminjaman::whereMonth('tanggal_pinjam', $month->month)
                                   ->whereYear('tanggal_pinjam', $month->year)
                                   ->count();
        }

        return view('admin.dashboard', compact(
            'totalAlat', 'totalKategori', 'totalPeminjaman', 'menunggu',
            'disetujui', 'ditolak', 'selesai', 'peminjamanBulanIni', 
            'alatTersedia', 'riwayat', 'labels', 'data'
        ));
    }

    // ── KATEGORI ───────────────────────────────────────────
    public function kategori() {
        $kategoris = Kategori::all();
        return view('admin.kategori', compact('kategoris'));
    }

    public function storeKategori(Request $request) {
        $request->validate(['nama_kategori' => 'required|unique:kategori,nama_kategori']);
        Kategori::create(['nama_kategori' => $request->nama_kategori]);
        return redirect()->back()->with('success', 'Kategori Berhasil Ditambah');
    }

    public function editKategori($id) {
        $kategori = Kategori::findOrFail($id);
        return view('admin.edit_kategori', compact('kategori'));
    }

    public function updateKategori(Request $request, $id) {
        $request->validate(['nama_kategori' => 'required']);
        $kategori = Kategori::findOrFail($id);
        $kategori->update(['nama_kategori' => $request->nama_kategori]);
        return redirect()->route('admin.kategori')->with('success', 'Kategori Berhasil Diupdate');
    }

    public function destroyKategori($id) {
        Kategori::destroy($id);
        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus');
    }

    // ── ALAT ───────────────────────────────────────────────
    public function alat() {
        $alats = Alat::with('kategori')->paginate(10); 
        $kategoris = Kategori::all();
        return view('admin.alat', compact('alats', 'kategoris'));
    }

    public function storeAlat(Request $request) {
        $request->validate([
            'nama_alat'   => 'required',
            'kategori_id' => 'required',
            'stok'        => 'required|integer|min:1',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $data = $request->all();
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }
        Alat::create($data);
        return redirect()->back()->with('success', 'Alat Berhasil Ditambah');
    }

    public function updateAlat(Request $request, $id) {
        $alat = Alat::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('gambar')) {
            if ($alat->gambar) Storage::disk('public')->delete($alat->gambar);
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }
        $alat->update($data);
        return redirect()->route('admin.alat')->with('success', 'Alat Berhasil Diupdate');
    }

    public function destroyAlat($id) {
        $alat = Alat::findOrFail($id);
        if ($alat->gambar) Storage::disk('public')->delete($alat->gambar);
        $alat->delete();
        return redirect()->back()->with('success', 'Alat Dihapus');
    }

    // ── USER ───────────────────────────────────────────────
    public function user() {
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    public function storeUser(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:peminjam,petugas,admin',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect()->back()->with('success', 'User Ditambah');
    }

    // ── LOGIKA PEMINJAMAN ──────────────────────────────────
    public function setujui($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        $alat = Alat::find($peminjaman->alat_id);

        if ($alat && $alat->stok >= $peminjaman->jumlah) {
            $peminjaman->update(['status' => 'disetujui']);
            $alat->decrement('stok', $peminjaman->jumlah);
            return redirect()->back()->with('success', 'Peminjaman disetujui, stok berkurang.');
        }
        return redirect()->back()->with('error', 'Stok alat tidak mencukupi!');
    }

    public function tolak($id) {
        Peminjaman::where('id', $id)->update(['status' => 'ditolak']);
        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function selesai($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        $alat = Alat::find($peminjaman->alat_id);

        if ($peminjaman->status == 'disetujui') {
            $peminjaman->update(['status' => 'selesai']);
            $alat->increment('stok', $peminjaman->jumlah); // Stok kembali
            return redirect()->back()->with('success', 'Alat telah dikembalikan, stok bertambah.');
        }
        return redirect()->back();
    }

    // ── LAPORAN ────────────────────────────────────────────
    public function laporan(Request $request) {
        $query = Peminjaman::with(['alat', 'user']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->bulan) {
            $query->whereMonth('tanggal_pinjam', date('m', strtotime($request->bulan)))
                  ->whereYear('tanggal_pinjam', date('Y', strtotime($request->bulan)));
        }
        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();
        return view('admin.laporan', compact('peminjaman'));
    }
}