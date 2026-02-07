<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianPeminjamController extends Controller
{
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('alat')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->firstOrFail();

        $dendaPerHari = 5000;

        // 🔹 PAKAI FORMAT TANGGAL SAJA (HILANGKAN JAM)
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo)->toDateString();
        $tanggalKembali = Carbon::now()->toDateString();

        $hariTelat = 0;
        $totalDenda = 0;

        if ($tanggalKembali > $jatuhTempo) {
            // ✅ HITUNG SELISIH HARI (PASTI ANGKA BULAT)
            $hariTelat = Carbon::parse($jatuhTempo)
                ->diffInDays(Carbon::parse($tanggalKembali));

            if ($hariTelat < 1) {
                $hariTelat = 1;
            }

            $totalDenda = $hariTelat * $dendaPerHari;
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => Carbon::now(),
            'hari_telat' => $hariTelat,
            'denda' => $totalDenda,
        ]);

        $peminjaman->alat->increment('stok');

        return back()->with(
            'success',
            $hariTelat > 0
                ? "Alat dikembalikan. Telat {$hariTelat} hari. Denda: Rp "
                . number_format($totalDenda, 0, ',', '.')
                : "Alat berhasil dikembalikan."
        );
    }
}
