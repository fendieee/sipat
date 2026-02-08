@extends('layouts.dashboard')

@section('title', 'Laporan Peminjaman Per Bulan')

@section('content')
    <form action="{{ route('petugas.laporan.bulan') }}" method="GET" class="mb-3 d-flex gap-2 flex-wrap">
        <input type="month" name="bulan" class="form-control" value="{{ $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) }}">
        <button class="btn btn-primary">Tampilkan</button>
        <a href="{{ route('petugas.laporan.bulan.cetak', [$bulan, $tahun]) }}" target="_blank"
            class="btn btn-success">Cetak</a>
    </form>

    @if ($peminjamans->isEmpty())
        <div class="alert alert-warning">Data peminjaman tidak tersedia untuk bulan ini.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Alat</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalDenda=0; @endphp
                    @foreach ($peminjamans as $p)
                        @php $totalDenda += $p->denda ?? 0; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ $p->alat->nama_alat }}</td>
                            <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($p->alat->deskripsi, 50) }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}
                            </td>
                            <td>{{ ucfirst($p->status) }}</td>
                            <td>{{ $p->denda ? 'Rp ' . number_format($p->denda, 0, ',', '.') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8" class="text-end">Total Denda:</th>
                        <th>Rp {{ number_format($totalDenda, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
@endsection
