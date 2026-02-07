@extends('layouts.dashboard')

@section('title', 'Laporan Peminjaman')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('petugas.laporan.cetak') }}" target="_blank" class="btn btn-primary">
                Cetak Laporan
            </a>

        </div>

        @if ($peminjamans->isEmpty())
            <div class="alert alert-warning">
                Data peminjaman tidak tersedia.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjamans as $p)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $p->user->name }}</td>
                                <td class="text-start">{{ $p->alat->nama_alat }}</td>
                                <td>{{ $p->tanggal_pinjam }}</td>
                                <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                                <td>{{ ucfirst($p->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
