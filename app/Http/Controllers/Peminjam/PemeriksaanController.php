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
            'catatan_petugas' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        // Jika sebelumnya dilaporkan hilang
        if ($peminjaman->alasan_denda === 'Dilaporkan hilang oleh peminjam') {

            $peminjaman->update([
                'status' => 'hilang',
                'denda' => $peminjaman->alat->harga * $peminjaman->jumlah,
                'alasan_denda' => 'Barang hilang',
            ]);

            // ❌ STOK TIDAK DIKEMBALIKAN

        } else {

            $peminjaman->update([
                'status' => 'dikembalikan',
                'denda' => $request->denda,
                'catatan_petugas' => $request->catatan_petugas,
            ]);

            // ✅ STOK KEMBALI SESUAI JUMLAH
            $peminjaman->alat->increment('stok', $peminjaman->jumlah);
        }

        return back()->with('success', 'Pemeriksaan selesai.');
    }
}
