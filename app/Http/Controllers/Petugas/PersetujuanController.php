<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'pending')
            ->get();

        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    public function setujui($id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if ($peminjaman->alat->stok < 1) {
            return back()->with('error', 'Stok alat habis');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'alasan_tolak' => null,
        ]);

        $peminjaman->alat->decrement('stok');

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Petugas menyetujui peminjaman alat: ' . $peminjaman->alat->nama_alat,
        ]);

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function tolak(Request $request, $id)
    {
        // VALIDASI (biar tidak gagal diam-diam)
        $request->validate([
            'alasan_tolak' => 'required|string|min:5',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Petugas menolak peminjaman alat: '
                . $peminjaman->alat->nama_alat
                . ' | Alasan: ' . $request->alasan_tolak,
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak');
    }
}
