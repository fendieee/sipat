<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\peminjamanRequest;

class PeminjamanController extends Controller
{
    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat.kategori'])
            ->latest()
            ->get();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    // ==============================
    // CREATE
    // ==============================
    public function create()
    {
        $users = User::where('role', 'peminjam')->get();
        $kategoris = Kategori::all();

        return view('admin.peminjaman.create', compact('users', 'kategoris'));
    }

    // ==============================
    // GET ALAT BY KATEGORI (AJAX)
    // ==============================
    public function getAlatByKategori($kategoriId)
    {
        $alat = Alat::where('kategori_id', $kategoriId)
            ->where('stok', '>', 0)
            ->get();

        return response()->json($alat);
    }

    // ==============================
    // STORE
    // ==============================
    public function PeminjamanRequest(peminjamanRequest $request)
    {
        $validated = $request->validated();

        $exists = Peminjaman::where('user_id', $validated['user_id'])
            ->where('alat_id', $validated['alat_id'])
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Peminjam masih memiliki peminjaman aktif untuk alat ini.');
        }

        $alat = Alat::findOrFail($validated['alat_id']);

        if ($alat->stok < (int) $validated['jumlah']) {
            return back()->with('error', 'Stok alat tidak cukup.');
        }

        // Kurangi stok
        $alat->decrement('stok', (int) $validated['jumlah']);

        // Simpan peminjaman
        Peminjaman::create([
            'user_id' => $validated['user_id'],
            'alat_id' => $validated['alat_id'],
            'jumlah' => (int) $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
            'status' => 'dipinjam',
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan data peminjaman alat',
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $users = User::where('role', 'peminjam')->get();
        $kategoris = Kategori::all();

        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'kategoris'));
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'alat_id' => 'required|exists:alats,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if (
            $peminjaman->alat_id != $validated['alat_id'] ||
            (int) $peminjaman->jumlah !== (int) $validated['jumlah']
        ) {
            // Kembalikan stok lama
            $alatLama = Alat::find($peminjaman->alat_id);
            if ($alatLama) {
                $alatLama->increment('stok', (int) $peminjaman->jumlah);
            }

            // Cek stok baru
            $alatBaru = Alat::findOrFail($validated['alat_id']);

            if ($alatBaru->stok < (int) $validated['jumlah']) {
                return back()->with('error', 'Stok alat tidak cukup.');
            }

            // Kurangi stok baru
            $alatBaru->decrement('stok', (int) $validated['jumlah']);
        }

        $peminjaman->update([
            'user_id' => $validated['user_id'],
            'alat_id' => $validated['alat_id'],
            'jumlah' => (int) $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah data peminjaman alat',
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    // ==============================
    // DESTROY
    // ==============================
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $alat = Alat::find($peminjaman->alat_id);
        if ($alat) {
            $alat->increment('stok', (int) $peminjaman->jumlah);
        }

        $peminjaman->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus data peminjaman alat',
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
