@extends('layouts.dashboard')

@section('title', 'Laporan Peminjaman Per Bulan')

@section('content')
    <form action="{{ route('petugas.laporan.bulan') }}" method="GET" class="mb-3 d-flex gap-2 flex-wrap align-items-end">

        <div>
            <label class="form-label">Bulan</label>
            <input type="month" name="bulan" value="{{ $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) }}"
                class="form-control">
        </div>

        <div>
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>

        <div>
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>

        <button class="btn btn-primary">Tampilkan</button>

        <a href="{{ route('petugas.laporan.bulan.cetak', [
            $bulan,
            $tahun,
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
        ]) }}"
            target="_blank" class="btn btn-success">
            Cetak
        </a>
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
                        <th>Harga/Hari</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Hari Telat</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Harga Total</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $totalDenda = 0;
                        $totalHarga = 0;
                    @endphp

                    @foreach ($peminjamans as $p)
                        @php
                            $lamaPinjam =
                                \Carbon\Carbon::parse($p->tanggal_pinjam)->diffInDays($p->tanggal_kembali ?? now()) + 1;

                            $subtotal = ($p->alat->harga ?? 0) * $lamaPinjam;
                            $hargaTotal = $subtotal + ($p->denda ?? 0);

                            $totalDenda += $p->denda ?? 0;
                            $totalHarga += $hargaTotal;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ $p->alat->nama_alat }}</td>
                            <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($p->alat->harga ?? 0, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}
                            </td>
                            <td>{{ $p->hari_telat ?? 0 }} hari</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $p->status)) }}</td>
                            <td>{{ $p->denda ? 'Rp ' . number_format($p->denda, 0, ',', '.') : '-' }}</td>
                            <td><strong>Rp {{ number_format($hargaTotal, 0, ',', '.') }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="9" class="text-end">Total Denda:</th>
                        <th colspan="2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-end">TOTAL BAYAR:</th>
                        <th colspan="2"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
@endsection
