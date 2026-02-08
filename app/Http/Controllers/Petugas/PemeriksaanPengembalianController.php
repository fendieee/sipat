<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanPengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'menunggu_pemeriksaan')
            ->get();

        return view('petugas.pemeriksaan.index', compact('peminjamans'));
    }

    public function selesaikan(Request $request, $id)
    {
        $request->validate([
            'denda' => 'required|integer|min:0',
            'catatan_petugas' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        $peminjaman->update([
            'status' => 'dikembalikan',
            'denda' => $request->denda,
            'catatan_petugas' => $request->catatan_petugas,
        ]);

        // Kembalikan stok alat
        $peminjaman->alat->increment('stok');

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' =>
            "Petugas menyelesaikan pengembalian alat: "
                . $peminjaman->alat->nama_alat
                . " | Denda: Rp " . number_format($request->denda, 0, ',', '.'),
        ]);

        return back()->with('success', 'Pengembalian selesai dan stok telah dikembalikan.');
    }
}
