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
        $peminjamans = Peminjaman::with(['user', 'alat.kategori'])
            ->where('status', 'menunggu_pemeriksaan')
            ->orderBy('created_at', 'desc')
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

        // Ambil kondisi yang diisi peminjam
        $kondisi = $peminjaman->kondisi;
        $jumlahPinjam = (int) $peminjaman->jumlah;

        // Default status
        $status = 'dikembalikan';

        // Logika kondisi
        if ($kondisi === 'hilang') {
            // Jika hilang → stok TIDAK kembali
            $status = 'hilang';
        } else {
            // Jika baik / rusak → stok kembali
            if ($peminjaman->alat && $jumlahPinjam > 0) {
                $peminjaman->alat->increment('stok', $jumlahPinjam);
            }
        }

        // Update peminjaman
        $peminjaman->update([
            'status' => $status,
            'denda' => $request->denda,
            'catatan_petugas' => $request->catatan_petugas,
            'tanggal_kembali' => now(), // Tambahkan tanggal kembali
        ]);

        // Simpan log aktivitas petugas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => "Petugas menyelesaikan pengembalian alat: " .
                $peminjaman->alat->nama_alat .
                " | Kondisi: " . ($kondisi ?? 'belum ada') .
                " | Jumlah: " . $jumlahPinjam .
                " | Denda: Rp " . number_format($request->denda, 0, ',', '.'),
        ]);

        return back()->with('success', 'Pengembalian berhasil diselesaikan.');
    }
}
