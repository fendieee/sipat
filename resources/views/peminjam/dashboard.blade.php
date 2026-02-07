@extends('layouts.dashboard')

@section('title', 'Dashboard Peminjam')

@section('content')
    <div class="container-fluid">


        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Jumlah Alat Tersedia</h6>
                        <h2 class="fw-bold">{{ $alatTersedia }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Alat Sedang Dipinjam</h6>
                        <h2 class="fw-bold">{{ $dipinjam }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="mb-3">Daftar Alat</h4>

        @if ($daftarAlat->isEmpty())
            <div class="alert alert-secondary">
                Belum ada alat.
            </div>
        @else
            <ul class="list-group mb-4">
                @foreach ($daftarAlat as $alat)
                    <li class="list-group-item">
                        {{ $alat->nama_alat }}
                    </li>
                @endforeach
            </ul>
        @endif


        <h4 class="mb-3">Riwayat Terakhir</h4>

        @if ($riwayat->isEmpty())
            <div class="alert alert-info">
                Belum ada peminjaman.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $p)
                            <tr>
                                <td>{{ $p->alat->nama_alat }}</td>
                                <td>{{ $p->tanggal_pinjam }}</td>
                                <td>
                                    <span
                                        class="badge 
                                    {{ 
                                        $p->status === 'pending' ? 'bg-secondary' :
                                        ($p->status === 'dipinjam' ? 'bg-warning text-dark' :
                                        ($p->status === 'ditolak' ? 'bg-danger' : 'bg-success')) 
                                    }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
