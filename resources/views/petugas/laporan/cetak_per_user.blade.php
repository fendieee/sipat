<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman Per User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        <h3 class="text-center mb-0">LAPORAN PEMINJAMAN PER USER</h3>
        <p class="text-center mb-4">Dicetak oleh Petugas</p>

        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
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
                    <th colspan="8" class="text-end">Total Denda:</th>
                    <th colspan="2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="8" class="text-end">TOTAL BAYAR:</th>
                    <th colspan="2"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
