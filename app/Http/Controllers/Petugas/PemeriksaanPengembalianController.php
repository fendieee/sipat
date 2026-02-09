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

        $kondisi = $peminjaman->alasan_denda; // kondisi dari peminjam
        $status = 'dikembalikan';

        // ==============================
        // LOGIKA KONDISI
        // ==============================

        if ($kondisi === 'hilang') {

            // ❌ stok tidak kembali
            $status = 'hilang';

        } else {

            // ✅ stok kembali
            $peminjaman->alat->increment('stok', $peminjaman->jumlah);
        }

        $peminjaman->update([
            'status' => $status,
            'denda' => $request->denda,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' =>
                "Petugas menyelesaikan pengembalian alat: "
                . $peminjaman->alat->nama_alat
                . " | Kondisi: " . $kondisi
                . " | Denda: Rp " . number_format($request->denda, 0, ',', '.'),
        ]);

        return back()->with('success', 'Pengembalian selesai.');
    }
}
