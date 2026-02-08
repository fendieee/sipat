<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class RekapPengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->where('status', 'dikembalikan')
            ->latest()
            ->get();

        // Total denda untuk rekap
        $totalDenda = $peminjamans->sum('denda');

        return view('admin.pengembalian.rekap', compact('peminjamans', 'totalDenda'));
    }
}
