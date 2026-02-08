<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class CetakLaporanController extends Controller
{
    // =========================
    // CETAK SEMUA LAPORAN
    // route: petugas.laporan.cetak
    // =========================
    public function cetak()
    {
        $peminjamans = Peminjaman::with(['user', 'alat', 'alat.kategori'])
            ->latest()
            ->get();

        return view('petugas.laporan.cetak', compact('peminjamans'));
    }

    // =========================
    // CETAK PER USER
    // route: petugas.laporan.user.cetak
    // =========================
    public function cetakPerUser($userId)
    {
        $peminjamans = Peminjaman::with(['user', 'alat', 'alat.kategori'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('petugas.laporan.cetak_per_user', compact('peminjamans'));
    }

    // =========================
    // CETAK PER BULAN
    // (opsional kalau mau dipakai)
    // =========================
    public function cetakPerBulan($bulan, $tahun)
    {
        $peminjamans = Peminjaman::with(['user', 'alat', 'alat.kategori'])
            ->whereYear('tanggal_pinjam', $tahun)
            ->whereMonth('tanggal_pinjam', $bulan)
            ->latest()
            ->get();

        return view('petugas.laporan.cetak_per_bulan', compact('peminjamans', 'bulan', 'tahun'));
    }
}
