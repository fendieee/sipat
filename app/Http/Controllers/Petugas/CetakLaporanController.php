<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class CetakLaporanController extends Controller
{
    // Cetak per User
    public function perUser($userId)
    {
        $peminjamans = Peminjaman::with(['user', 'alat', 'alat.kategori'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('petugas.laporan.cetak_per_user', compact('peminjamans'));
    }

    // Cetak per Bulan
    public function perBulan($bulan, $tahun)
    {
        $peminjamans = Peminjaman::with(['user', 'alat', 'alat.kategori'])
            ->whereYear('tanggal_pinjam', $tahun)
            ->whereMonth('tanggal_pinjam', $bulan)
            ->latest()
            ->get();

        return view('petugas.laporan.cetak_per_bulan', compact('peminjamans', 'bulan', 'tahun'));
    }
}
