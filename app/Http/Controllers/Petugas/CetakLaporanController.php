<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class CetakLaporanController extends Controller
{
    public function __invoke()
    {
        $peminjamans = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->get();

        return view('petugas.laporan.cetak', compact('peminjamans'));
    }
}
