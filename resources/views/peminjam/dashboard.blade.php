@extends('layouts.dashboard')

@section('title', 'Dashboard Peminjam')

@section('content')
    <div class="container-fluid">

        {{-- ROW STAT --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Jumlah Alat Tersedia</h6>
                        <h2 class="fw-bold text-primary">{{ $alatTersedia }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Alat Sedang Dipinjam</h6>
                        <h2 class="fw-bold text-warning">{{ $dipinjam }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR ALAT (VERSI DASHBOARD) --}}
        <h5 class="mb-3">Daftar Alat (Ringkas)</h5>

        @if ($daftarAlat->isEmpty())
            <div class="alert alert-secondary">
                Belum ada alat.
            </div>
        @else
            <div class="row mb-4">
                @foreach ($daftarAlat as $alat)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">{{ $alat->nama_alat }}</h6>
                                <small class="text-muted">
                                    Kategori: {{ $alat->kategori->nama ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- RIWAYAT TERAKHIR --}}
        <h5 class="mb-3">Riwayat Terakhir</h5>

        @if ($riwayat->isEmpty())
            <div class="alert alert-info">
                Belum ada peminjaman.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $p)
                            <tr>
                                <td>{{ $p->alat->nama_alat }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge 
                                {{ $p->status === 'pending'
                                    ? 'bg-secondary'
                                    : ($p->status === 'dipinjam'
                                        ? 'bg-warning text-dark'
                                        : ($p->status === 'ditolak'
                                            ? 'bg-danger'
                                            : 'bg-success')) }}">
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
