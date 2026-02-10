<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Halaman utama, redirect ke perUser
    public function index()
    {
        return redirect()->route('petugas.laporan.user');
    }

    // Laporan Per User
    public function perUser(Request $request)
    {
        $users = User::orderBy('name')->get();

        $selectedUserId = $request->query('user_id');
        $peminjamans = null;

        if ($selectedUserId) {
            $peminjamans = Peminjaman::with(['user', 'alat', 'alat.kategori'])
                ->where('user_id', $selectedUserId)
                ->latest()
                ->get();
        }

        return view('petugas.laporan.per_user', compact('users', 'peminjamans', 'selectedUserId'));
    }

    // Laporan Per Bulan
    public function perBulan(Request $request)
    {
        if ($request->filled('bulan')) {
            [$tahun, $bulan] = explode('-', $request->bulan);
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }

        $query = Peminjaman::with(['user', 'alat', 'alat.kategori'])
            ->whereYear('tanggal_pinjam', $tahun)
            ->whereMonth('tanggal_pinjam', $bulan);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
        }

        $peminjamans = $query->latest()->get();

        return view('petugas.laporan.per_bulan', compact('peminjamans', 'bulan', 'tahun'));
    }
}
