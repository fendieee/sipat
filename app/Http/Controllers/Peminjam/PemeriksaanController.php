<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanController extends Controller
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
            'alasan_denda' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        $peminjaman->update([
            'denda' => $request->denda,
            'alasan_denda' => $request->alasan_denda,
            'status' => 'dikembalikan',
        ]);

        // KEMBALIKAN STOK ALAT
        $peminjaman->alat->increment('stok');

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => "Pemeriksaan selesai untuk alat: {$peminjaman->alat->nama_alat}. Denda: Rp {$request->denda}",
        ]);

        return back()->with('success', 'Pengembalian selesai diproses.');
    }
}
