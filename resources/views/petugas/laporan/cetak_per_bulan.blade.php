<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman Per Bulan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }

        body {
            font-size: 13px;
        }

        table th,
        table td {
            font-size: 12px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container my-4">
        <h3 class="text-center mb-0">LAPORAN PEMINJAMAN PER BULAN</h3>
        <p class="text-center mb-4">Bulan: {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }}
            {{ $tahun }}</p>
        <table class="table table-bordered table-striped align-middle text-center">
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
                <tr class="text-center">
                    <th colspan="8" class="text-end">Total Denda:</th>
                    <th>Rp {{ number_format($totalDenda, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
