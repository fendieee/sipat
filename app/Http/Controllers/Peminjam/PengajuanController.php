<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function create()
    {
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->get();

        $kategoris = Kategori::all();

        return view('peminjam.peminjaman.create', compact('alats', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
            'foto_peminjam' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        if ($alat->stok < 1) {
            return back()->withErrors('Stok alat habis');
        }

        $sudahPinjam = Peminjaman::where('user_id', Auth::id())
            ->where('alat_id', $alat->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors('Anda sudah mengajukan / masih meminjam alat ini');
        }

        // 👉 PROSES UPLOAD FOTO PEMINJAM (INI YANG BARU)
        $pathFoto = $request->file('foto_peminjam')
            ->store('foto_peminjam', 'public');

        // Buat peminjaman
        Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_id' => $alat->id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'foto_peminjam' => $pathFoto,
            'status' => 'pending',
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengajukan peminjaman alat',
        ]);

        return redirect()
            ->route('peminjam.riwayat')
            ->with('success', 'Peminjaman berhasil diajukan (menunggu persetujuan)');
    }
}
