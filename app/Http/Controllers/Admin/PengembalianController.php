<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    // Tampilkan daftar alat yang sedang dipinjam
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'dipinjam')
            ->get();

        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    // Proses konfirmasi pengembalian oleh admin
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Alat sudah dikembalikan');
        }

        // Update status dan tanggal kembali
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => Carbon::now(),
        ]);

        // Kembalikan stok alat
        $peminjaman->alat->increment('stok');

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Admin mengonfirmasi pengembalian alat',
        ]);

        return back()->with('success', 'Pengembalian berhasil');
    }
}
