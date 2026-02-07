<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
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
        ]);

        $peminjaman->alat->decrement('stok');

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Petugas menyetujui peminjaman alat: ' . $peminjaman->alat->nama_alat,
        ]);

        return back()->with('success', 'Peminjaman disetujui');
    }
    
    public function tolak($id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Petugas menolak peminjaman alat: ' . $peminjaman->alat->nama_alat,
        ]);

        return back()->with('success', 'Peminjaman ditolak');
    }
}
