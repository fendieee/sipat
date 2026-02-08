@extends('layouts.dashboard')

@section('title', 'Laporan Peminjaman Per User')

@section('content')
    <form action="{{ route('petugas.laporan.user') }}" method="GET" class="mb-3 d-flex gap-2 flex-wrap">
        <select name="user_id" class="form-select" required>
            <option value="">-- Pilih User --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>{{ $user->name }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-primary">Tampilkan</button>
        @if ($selectedUserId)
            <a href="{{ route('petugas.laporan.user.cetak', $selectedUserId) }}" target="_blank"
                class="btn btn-success">Cetak</a>
        @endif
    </form>

    @if ($peminjamans && $peminjamans->isEmpty())
        <div class="alert alert-warning">Data peminjaman tidak tersedia untuk user ini.</div>
    @elseif($peminjamans)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
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
                        <th colspan="7" class="text-end">Total Denda:</th>
                        <th>Rp {{ number_format($totalDenda, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
@endsection
