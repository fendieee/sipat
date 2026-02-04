<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    // Tampilkan daftar peminjaman yang menunggu persetujuan
    public function menyetujuiPeminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'pending')
            ->get();

        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    // Setujui peminjaman dan kurangi stok alat
    public function setujui($id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        // Cek stok sebelum disetujui
        if ($peminjaman->alat->stok < 1) {
            return back()->with('error', 'Stok alat habis');
        }

        // Ubah status menjadi dipinjam
        $peminjaman->update([
            'status' => 'dipinjam',
        ]);

        // Kurangi stok alat
        $peminjaman->alat->decrement('stok');

        // Catat log aktivitas petugas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Petugas menyetujui peminjaman alat: ' . $peminjaman->alat->nama_alat,
        ]);

        return back()->with('success', 'Peminjaman disetujui');
    }

    // Tampilkan daftar alat yang sedang dipinjam
    public function memantauPengembalian()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'dipinjam')
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans'));
    }

    // Tampilkan laporan peminjaman
    public function laporanPeminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->get();

        return view('petugas.laporan.index', compact('peminjamans'));
    }

    // Cetak laporan peminjaman
    public function cetakLaporan()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->get();

        return view('petugas.laporan.cetak', compact('peminjamans'));
    }
}
