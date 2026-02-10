<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            // Untuk card statistik
            'totalUser' => User::count(),
            'totalAlat' => Alat::count(),
            'totalKategori' => Kategori::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'dipinjam'  => Peminjaman::where('status', 'dipinjam')->count(),

            // Untuk tabel di dashboard
            'users' => User::latest()->get(),
            'alats' => Alat::with('kategori')->latest()->get(),
            'kategoris' => Kategori::latest()->get(),
            'peminjamans' => Peminjaman::with(['user', 'alat'])->latest()->get(),
        ]);
    }
}
