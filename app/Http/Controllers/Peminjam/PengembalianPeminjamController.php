<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianPeminjamController extends Controller
{
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'foto_kondisi' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->firstOrFail();

        // ✅ Simpan foto ke public (BENAR)
        $path = $request->file('foto_kondisi')
            ->store('foto_kondisi', 'public');

        // Hitung keterlambatan
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo);
        $tanggalKembali = Carbon::now();

        $hariTelat = 0;

        if ($tanggalKembali->greaterThan($jatuhTempo)) {
            $hariTelat = $jatuhTempo->diffInDays($tanggalKembali);
            if ($hariTelat < 1) {
                $hariTelat = 1;
            }
        }

        $peminjaman->update([
            'foto_kondisi' => $path,
            'tanggal_kembali' => Carbon::now(),
            'hari_telat' => $hariTelat,
            'status' => 'menunggu_pemeriksaan',
        ]);

        return back()->with('success', 
            'Pengembalian diajukan. Menunggu pemeriksaan petugas.'
        );
    }
}
