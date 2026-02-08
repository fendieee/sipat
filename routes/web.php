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

use App\Http\Controllers\Petugas\PersetujuanController;
use App\Http\Controllers\Petugas\PemantauanController;
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Petugas\CetakLaporanController;

use App\Http\Controllers\Peminjam\RiwayatController;
use App\Http\Controllers\Peminjam\PengajuanController;
use App\Http\Controllers\Peminjam\PengembalianPeminjamController;

// ==========================
// LANDING PAGE
// ==========================
Route::get('/', fn() => view('landing'));

// ==========================
// AUTH
// ==========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==========================
// DASHBOARD
// ==========================
Route::middleware(['auth', 'role:admin'])
    ->get('/admin/dashboard', [AdminDashboard::class, 'index'])
    ->name('admin.dashboard');

Route::middleware(['auth', 'role:petugas'])
    ->get('/petugas/dashboard', [PetugasDashboard::class, 'index'])
    ->name('petugas.dashboard');

Route::middleware(['auth', 'role:peminjam'])
    ->get('/peminjam/dashboard', [PeminjamDashboard::class, 'index'])
    ->name('peminjam.dashboard');

// ==========================
// PEMINJAM ROUTES
// ==========================
Route::middleware(['auth', 'role:peminjam'])
    ->prefix('peminjam')
    ->name('peminjam.')
    ->group(function () {

        Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

        Route::get('/pengajuan', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

        Route::post('/kembalikan/{id}', [PengembalianPeminjamController::class, 'kembalikan'])
            ->name('kembalikan');
    });

// ==========================
// PETUGAS ROUTES
// ==========================
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

        // Persetujuan
        Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan');
        Route::post('/setujui/{id}', [PersetujuanController::class, 'setujui'])->name('setujui');
        Route::post('/tolak/{id}', [PersetujuanController::class, 'tolak'])->name('tolak');

        // Pemantauan
        Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('pemantauan');

        // =========================
        // LAPORAN (FINAL FIX)
        // =========================
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

        Route::get('/laporan/user', [LaporanController::class, 'perUser'])
            ->name('laporan.user');

        Route::get('/laporan/bulan', [LaporanController::class, 'perBulan'])
            ->name('laporan.bulan');

        Route::get('/laporan/user/cetak/{id}', [CetakLaporanController::class, 'cetakPerUser'])
            ->name('laporan.user.cetak');

        Route::get('/laporan/cetak', [CetakLaporanController::class, 'cetak'])
            ->name('laporan.cetak');
    });

// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('users', UserController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('alat', AlatController::class);
        Route::resource('peminjaman', AdminPeminjaman::class);

        Route::get('/get-alat/{kategori}', [AdminPeminjaman::class, 'getAlatByKategori']);

        Route::get('/pengembalian', [PengembalianController::class, 'index'])
            ->name('pengembalian.index');

        Route::post('/pengembalian/{id}', [PengembalianController::class, 'kembalikan'])
            ->name('pengembalian.kembalikan');

        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])
            ->name('log-aktivitas.index');
    });
