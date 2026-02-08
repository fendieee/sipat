@extends('layouts.dashboard')

@section('title', 'Rekap Pengembalian')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Rekap Pengembalian</h5>
            <div>
                <strong>Total Denda:</strong>
                <span class="badge bg-danger">
                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                </span>
            </div>
        </div>

        @if ($peminjamans->isEmpty())
            <div class="alert alert-secondary">
                Belum ada pengembalian selesai.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Foto Kondisi</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Telat (hari)</th>
                            <th>Denda</th>
                            <th>Catatan Petugas</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($peminjamans as $p)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $p->user->name }}</td>
                                <td>{{ $p->alat->nama_alat }}</td>

                                <td class="text-center">
                                    @if ($p->foto_kondisi)
                                        <img src="{{ asset('storage/' . $p->foto_kondisi) }}"
                                            style="width:110px;height:110px;object-fit:cover" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">Tidak ada foto</span>
                                    @endif
                                </td>


                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y H:i') }}
                                </td>

                                <td class="text-center">
                                    {{ $p->hari_telat ?? 0 }} hari
                                </td>

                                <td class="text-center">
                                    @if ($p->denda > 0)
                                        <span class="badge bg-danger">
                                            Rp {{ number_format($p->denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Rp 0</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $p->catatan_petugas ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
