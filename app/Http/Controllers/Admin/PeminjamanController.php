<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Tampilkan semua peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->get();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    // Form tambah peminjaman (admin)
    public function create()
    {
        $users = User::where('role', 'peminjam')->get();
        $alats = Alat::where('stok', '>', 0)->get();

        return view('admin.peminjaman.create', compact('users', 'alats'));
    }

    // Simpan peminjaman (admin langsung dipinjam)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'alat_id' => 'required|exists:alats,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        // Cek duplikasi peminjaman
        $exists = Peminjaman::where('user_id', $validated['user_id'])
            ->where('alat_id', $validated['alat_id'])
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'User masih meminjam atau mengajukan alat ini');
        }

        $alat = Alat::findOrFail($validated['alat_id']);

        if ($alat->stok <= 0) {
            return back()->with('error', 'Stok alat habis');
        }

        // Kurangi stok alat
        $alat->decrement('stok');

        // Admin: langsung set status dipinjam
        Peminjaman::create([
            'user_id' => $validated['user_id'],
            'alat_id' => $validated['alat_id'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
            'status' => 'dipinjam',
        ]);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan data peminjaman alat',
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan');
    }
}
