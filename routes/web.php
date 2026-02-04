<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Peminjam\DashboardController as PeminjamDashboard;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjaman;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\LogAktivitasController;

use App\Http\Controllers\Petugas\PetugasController;
use App\Http\Controllers\Peminjam\PeminjamanController as PeminjamPeminjaman;

Route::get('/', fn () => view('landing'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'role:petugas'])->get('/petugas/dashboard', [PetugasDashboard::class, 'index'])->name('petugas.dashboard');
Route::middleware(['auth', 'role:peminjam'])->get('/peminjam/dashboard', [PeminjamDashboard::class, 'index'])->name('peminjam.dashboard');

Route::middleware(['auth', 'role:peminjam'])
    ->prefix('peminjam')
    ->name('peminjam.')
    ->group(function () {
        Route::get('/peminjaman', [PeminjamPeminjaman::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create', [PeminjamPeminjaman::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamPeminjaman::class, 'store'])->name('peminjaman.store');
        Route::post('/peminjaman/{id}/kembalikan', [PeminjamPeminjaman::class, 'kembalikan'])->name('peminjaman.kembalikan');
    });

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/menyetujui-peminjaman', [PetugasController::class, 'menyetujuiPeminjaman'])->name('menyetujui-peminjaman');
        Route::post('/setujui/{id}', [PetugasController::class, 'setujui'])->name('setujui');
        Route::get('/memantau-pengembalian', [PetugasController::class, 'memantauPengembalian'])->name('memantau-pengembalian');
        Route::get('/laporan-peminjaman', [PetugasController::class, 'laporanPeminjaman'])->name('laporan-peminjaman');
        Route::get('/laporan-peminjaman/cetak', [PetugasController::class, 'cetakLaporan'])->name('laporan-peminjaman.cetak');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('alat', AlatController::class);
        Route::resource('peminjaman', AdminPeminjaman::class);

        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::post('/pengembalian/{id}', [PengembalianController::class, 'kembalikan'])->name('pengembalian.kembalikan');

        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
    });
