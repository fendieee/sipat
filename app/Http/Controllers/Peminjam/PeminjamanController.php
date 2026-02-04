<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Tampilkan riwayat peminjaman milik user login
    public function index()
    {
        $peminjamans = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('peminjam.peminjaman.index', compact('peminjamans'));
    }

    // Tampilkan form peminjaman dengan alat yang tersedia
    public function create()
    {
        $alats = Alat::where('stok', '>', 0)->get();
        return view('peminjam.peminjaman.create', compact('alats'));
    }

    // Simpan pengajuan peminjaman (status: pending)
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        // Cek stok sebelum mengajukan
        if ($alat->stok < 1) {
            return back()->withErrors('Stok alat habis');
        }

        // Cegah pengajuan ganda (pending atau masih dipinjam)
        $sudahPinjam = Peminjaman::where('user_id', Auth::id())
            ->where('alat_id', $alat->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors('Anda sudah mengajukan / masih meminjam alat ini');
        }

        // Buat peminjaman dengan status menunggu persetujuan
        Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_id' => $alat->id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => 'pending',
        ]);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengajukan peminjaman alat',
        ]);

        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan (menunggu persetujuan)');
    }

    // Proses pengembalian alat oleh peminjam
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->firstOrFail();

        // Ubah status dan simpan tanggal kembali
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
        ]);

        // Kembalikan stok alat
        $peminjaman->alat->increment('stok');

        return back()->with('success', 'Alat berhasil dikembalikan');
    }
}
