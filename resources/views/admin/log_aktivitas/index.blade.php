@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')

@section('content')
  <div class="container-fluid">

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr class="text-center">
            <th>No</th>
            <th>Pelaku</th>
            <th>Detail Aktivitas</th>
            <th>Waktu</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($logs as $log)
            @php
              $aksi = strtolower($log->aktivitas);
              $pelaku = $log->user->name ?? 'Sistem';

              // CEK DULU ADA ATAU TIDAK PEMINJAMAN (AMAN DARI ERROR)
              $peminjaman = $log->peminjaman ?? null;

              $peminjam = $peminjaman?->user->name ?? 'User tidak ditemukan';
              $alat = $peminjaman?->alat->nama_alat ?? 'Alat tidak ditemukan';
              $kode = $peminjaman?->id ?? '-';
              $status = $peminjaman?->status ?? '-';

              $tanggalPinjam = $peminjaman?->tanggal_pinjam
                  ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y')
                  : '-';
            @endphp

            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $pelaku }}</td>
              <td>
                @if ($peminjaman)
                  @if (str_contains($aksi, 'setujui'))
                    <strong>Petugas {{ $pelaku }}</strong>
                    telah <strong>MENYETUJUI</strong> permintaan peminjaman
                    <strong>Alat: {{ $alat }}</strong>
                    (ID: <strong>{{ $kode }}</strong>)
                    <br>
                    yang diajukan oleh
                    <strong>Peminjam: {{ $peminjam }}</strong>
                    pada <strong>{{ $tanggalPinjam }}</strong>.
                    <br>
                    <small class="text-muted">
                      Status saat ini: <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                    </small>
                  @elseif (str_contains($aksi, 'tolak'))
                    <strong>Petugas {{ $pelaku }}</strong>
                    telah <strong>MENOLAK</strong> permintaan peminjaman
                    <strong>Alat: {{ $alat }}</strong>
                    (ID: <strong>{{ $kode }}</strong>)<br>
                    yang diajukan oleh
                    <strong>Peminjam: {{ $peminjam }}</strong>.
                    <br>
                    <small class="text-muted">
                      Status saat ini: <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                    </small>
                  @elseif (str_contains($aksi, 'kembalikan'))
                    <strong>Peminjam {{ $peminjam }}</strong>
                    mengajukan <strong>PENGEMBALIAN</strong> alat
                    <strong>{{ $alat }}</strong>
                    (ID: <strong>{{ $kode }}</strong>)<br>
                    dan saat ini <strong>menunggu pemeriksaan petugas</strong>.
                    <br>
                    <small class="text-muted">
                      Status saat ini: <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                    </small>
                  @elseif (str_contains($aksi, 'periksa'))
                    <strong>Petugas {{ $pelaku }}</strong>
                    sedang <strong>MEMERIKSA</strong> kondisi alat
                    <strong>{{ $alat }}</strong>
                    (ID: <strong>{{ $kode }}</strong>)<br>
                    milik <strong>{{ $peminjam }}</strong>.
                    <br>
                    <small class="text-muted">
                      Status saat ini: <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                    </small>
                  @elseif (str_contains($aksi, 'selesai'))
                    <strong>Petugas {{ $pelaku }}</strong>
                    menyatakan peminjaman alat
                    <strong>{{ $alat }}</strong>
                    (ID: <strong>{{ $kode }}</strong>)<br>
                    oleh <strong>{{ $peminjam }}</strong>
                    telah <strong>SELESAI dan DITUTUP</strong>.
                    <br>
                    <small class="text-muted">
                      Status akhir: <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                    </small>
                  @else
                    {{ $log->aktivitas }}
                  @endif
                @else
                  {{-- Jika log TIDAK terkait peminjaman --}}
                  {{ $log->aktivitas }}
                @endif
              </td>

              <td class="text-center">
                {{ $log->created_at->format('d-m-Y H:i') }}
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="4" class="text-center text-muted">
                Log aktivitas belum tersedia
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
@endsection
