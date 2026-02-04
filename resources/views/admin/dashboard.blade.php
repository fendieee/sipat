@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-body">

            <h2 class="mb-3">Selamat Datang, Admin</h2>

            <p class="text-muted">
                Anda memiliki akses penuh terhadap sistem peminjaman alat.
            </p>

            <ul class="list-group list-group-flush mt-3">
                <li class="list-group-item">Kelola data alat</li>
                <li class="list-group-item">Kelola petugas</li>
                <li class="list-group-item">Kelola peminjam</li>
                <li class="list-group-item">Lihat laporan peminjaman</li>
            </ul>

        </div>
    </div>

</div>
@endsection
