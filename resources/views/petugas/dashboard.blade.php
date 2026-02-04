@extends('layouts.dashboard')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="card shadow-sm p-4">
    <h2 class="fw-bold text-center mb-3">Selamat Datang, Petugas</h2>
    <p class="text-center">
        Anda bertugas mengelola peminjaman dan pengembalian alat.
    </p>

    <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item">Verifikasi peminjaman</li>
        <li class="list-group-item">Kelola pengembalian alat</li>
        <li class="list-group-item">Cek status alat</li>
    </ul>
</div>
@endsection
