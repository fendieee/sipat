<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        return view('peminjam.dashboard', [
            // Jumlah alat yang masih tersedia
            'alatTersedia' => Alat::where('stok', '>', 0)->count(),

            // Jumlah alat yang sedang dipinjam user ini
            'dipinjam' => Peminjaman::where('user_id', $userId)
                ->where('status', 'dipinjam')
                ->count(),

            // 5 riwayat peminjaman terakhir user ini
            'riwayat' => Peminjaman::where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
            // list alat
            'daftarAlat' => Alat::select('id', 'nama_alat')->get(),
        ]);
    }
}
