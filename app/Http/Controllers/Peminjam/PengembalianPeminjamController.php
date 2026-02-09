<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianPeminjamController extends Controller
{
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->firstOrFail();

        // ==============================
        // VALIDASI DASAR
        // ==============================
        $rules = [
            'kondisi' => 'required|in:baik,rusak,hilang',
            'foto_kondisi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // LOGIKA: jika baik atau rusak, foto wajib
        if (in_array($request->kondisi, ['baik', 'rusak'])) {
            $rules['foto_kondisi'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules, [
            'foto_kondisi.required' => 'Foto kondisi wajib diupload untuk barang Baik atau Rusak.',
        ]);

        // ==============================
        // UPLOAD FOTO
        // ==============================
        $path = null;
        if ($request->hasFile('foto_kondisi')) {
            $path = $request->file('foto_kondisi')
                ->store('foto_kondisi', 'public');
        }

        // ==============================
        // UPDATE PEMINJAMAN
        // ==============================
        $peminjaman->update([
            'tanggal_kembali' => now(),
            'status' => 'menunggu_pemeriksaan',
            'foto_kondisi' => $path,
            'kondisi' => $request->kondisi,
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }
}
