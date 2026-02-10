@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="card border-0 shadow-sm rounded-4 bg-light p-4 d-flex flex-md-row flex-column align-items-md-center align-items-start justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-1 text-dark">Selamat Datang, Admin</h2>
                        <p class="text-muted mb-0">Sistem Informasi Peminjaman Alat Terpadu</p>
                    </div>
                    <div class="d-flex align-items-center mt-3 mt-md-0">
                        <i class="bi bi-speedometer2 fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                    <h6 class="text-muted">Total Alat</h6>
                    <h3 class="fw-bold">{{ $alats->count() }}</h3>
                    <a href="{{ route('admin.alat.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                    <h6 class="text-muted">Total Kategori</h6>
                    <h3 class="fw-bold">{{ $kategoris->count() }}</h3>
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                    <h6 class="text-muted">Total User</h6>
                    <h3 class="fw-bold">{{ $users->count() }}</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 p-3 text-center">
                    <h6 class="text-muted">Total Peminjaman</h6>
                    <h3 class="fw-bold">{{ $peminjamans->count() }}</h3>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat</a>
                </div>
            </div>

        </div>

        {{-- TABEL 4 DATA RINGKAS --}}
        <div class="row g-4">

            {{-- TABEL ALAT --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Alat Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($alats->take(5) as $alat)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $alat->nama_alat }}</td>
                                            <td class="text-center">{{ $alat->stok }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL KATEGORI --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Kategori</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse($kategoris->take(5) as $kategori)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $kategori->nama }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted text-center">Tidak ada data</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- TABEL USER --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">User Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users->take(5) as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TABEL PEMINJAMAN --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">Peminjaman Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Alat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamans->take(5) as $p)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $p->user->name }}</td>
                                        <td>{{ $p->alat->nama_alat }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge
                                            @if ($p->status == 'dipinjam') bg-primary
                                            @elseif($p->status == 'kembali') bg-success
                                            @elseif($p->status == 'pending') bg-warning
                                            @else bg-secondary @endif">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
