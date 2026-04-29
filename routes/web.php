<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ROUTE UTAMA
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;

        if ($role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($role == 'petugas') {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// USER
Route::get('/dashboard', [PeminjamController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::post('/admin/setujui/{id}', [AdminController::class, 'setujui'])->name('admin.setujui');
        
    // KATEGORI
    Route::get('/admin/kategori', [AdminController::class, 'kategori'])
        ->name('admin.kategori'); // ← ini yang hilang!
    Route::post('/admin/kategori', [AdminController::class, 'storeKategori'])
        ->name('admin.kategori.store');
    Route::delete('/admin/kategori/{id}', [AdminController::class, 'destroyKategori'])
        ->name('admin.kategori.destroy');
    Route::get('/admin/kategori/{id}/edit', [AdminController::class, 'editKategori'])
        ->name('admin.kategori.edit');
    Route::put('/admin/kategori/{id}', [AdminController::class, 'updateKategori'])
        ->name('admin.kategori.update');
        
        // LAPORAN
    Route::get('/admin/laporan', [AdminController::class, 'laporan'])
        ->name('admin.laporan');

    // ➕ TAMBAHKAN INI
    Route::post('/admin/kategori/store', [AdminController::class, 'storeKategori'])
        ->name('admin.kategori.store');

            // ALAT
    Route::get('/admin/alat', [AdminController::class, 'alat'])
        ->name('admin.alat');
    Route::post('/admin/alat', [AdminController::class, 'storeAlat'])
        ->name('admin.alat.store');
    Route::get('/admin/alat/{id}/edit', [AdminController::class, 'editAlat'])
        ->name('admin.alat.edit');
    Route::put('/admin/alat/{id}', [AdminController::class, 'updateAlat'])
        ->name('admin.alat.update');
    Route::delete('/admin/alat/{id}', [AdminController::class, 'destroyAlat'])
        ->name('admin.alat.destroy');

            // USER
    Route::get('/admin/user', [AdminController::class, 'user'])
        ->name('admin.user');
    Route::post('/admin/user', [AdminController::class, 'storeUser'])
        ->name('admin.user.store');
    Route::get('/admin/user/{id}/edit', [AdminController::class, 'editUser'])
        ->name('admin.user.edit');
    Route::put('/admin/user/{id}', [AdminController::class, 'updateUser'])
        ->name('admin.user.update');
    Route::delete('/admin/user/{id}', [AdminController::class, 'destroyUser'])
        ->name('admin.user.destroy');

});

        // PETUGAS
    Route::middleware(['auth', 'role:petugas'])->group(function () {

        Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])
            ->name('petugas.dashboard');

        Route::post('/petugas/validasi/{id}', [PetugasController::class, 'validasi'])
            ->name('petugas.validasi');

        Route::post('/petugas/setujui/{id}', [PetugasController::class, 'setujui'])
            ->name('petugas.setujui');

        Route::post('/petugas/kembali/{id}', [PetugasController::class, 'kembali'])
            ->name('petugas.kembali');
    });

    // PROFILE
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // PEMINJAM
    Route::middleware(['auth', 'role:peminjam'])->group(function () {
        Route::get('/peminjam/ajukan', [PeminjamController::class, 'ajukan'])
            ->name('peminjam.ajukan');
        Route::post('/peminjam/ajukan', [PeminjamController::class, 'store'])
            ->name('peminjam.store');
});

require __DIR__.'/auth.php';
