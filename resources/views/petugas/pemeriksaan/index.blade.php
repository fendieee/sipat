@extends('layouts.dashboard')

@section('title', 'Pemeriksaan Pengembalian')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-semibold">Pemeriksaan Pengembalian</h5>
        </div>

        <div class="card-body">

            {{-- ALERT --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($peminjamans->isEmpty())
                <div class="alert alert-warning">
                    Tidak ada pengembalian yang menunggu pemeriksaan.
                </div>
            @else

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Kategori</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Denda Saat Ini</th>
                            <th>Foto Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($peminjamans as $p)

                            @php
                                $tglPinjam = \Carbon\Carbon::parse($p->tanggal_pinjam);
                                $tglJatuhTempo = \Carbon\Carbon::parse($p->tanggal_jatuh_tempo);

                                $statusBadge = match ($p->status) {
                                    'menunggu_pemeriksaan' => 'info',
                                    'hilang' => 'danger',
                                    default => 'secondary',
                                };

                                $kondisiBadge = match ($p->kondisi ?? '') {
                                    'baik' => 'success',
                                    'rusak' => 'warning',
                                    'hilang' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp

                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>{{ $p->user->name }}</td>

                                <td>{{ $p->alat->kategori->nama ?? '-' }}</td>

                                <td class="fw-semibold">
                                    {{ $p->alat->nama_alat }}
                                </td>

                                <td class="text-center">
                                    {{ $p->jumlah }}
                                </td>

                                <td class="text-center">
                                    {{ $tglPinjam->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    {{ $tglJatuhTempo->format('d M Y') }}
                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">
                                    <span class="badge bg-{{ $statusBadge }}">
                                        {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                    </span>
                                </td>

                                {{-- KONDISI --}}
                                <td class="text-center">
                                    @if($p->kondisi)
                                        <span class="badge bg-{{ $kondisiBadge }}">
                                            {{ ucfirst($p->kondisi) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- DENDA --}}
                                <td class="text-center">
                                    @if($p->denda > 0)
                                        <span class="badge bg-danger">
                                            Rp {{ number_format($p->denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Rp 0</span>
                                    @endif
                                </td>

                                {{-- FOTO KONDISI --}}
                                <td class="text-center">
                                    @if ($p->foto_kondisi)
                                        <img src="{{ asset('storage/' . $p->foto_kondisi) }}"
                                             class="rounded shadow-sm"
                                             style="width:80px;height:80px;object-fit:cover;">
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>

                                {{-- FORM PEMERIKSAAN --}}
                                <td style="min-width:200px">

                                    <form action="{{ route('petugas.pemeriksaan.selesai', $p->id) }}"
                                          method="POST">
                                        @csrf

                                        <div class="mb-2">
                                            <label class="form-label small">
                                                Tambah / Ubah Denda (Rp)
                                            </label>
                                            <input type="number"
                                                   name="denda"
                                                   class="form-control form-control-sm"
                                                   value="{{ $p->denda ?? 0 }}"
                                                   min="0"
                                                   required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small">
                                                Catatan Petugas
                                            </label>
                                            <textarea name="catatan_petugas"
                                                      class="form-control form-control-sm"
                                                      rows="2"
                                                      placeholder="Contoh: Barang ada goresan...">{{ $p->catatan_petugas }}</textarea>
                                        </div>

                                        <button type="submit"
                                                class="btn btn-primary btn-sm w-100"
                                                onclick="return confirm('Selesaikan pengembalian?')">
                                            Selesaikan
                                        </button>
                                    </form>

                                </td>

                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            @endif

        </div>
    </div>

</div>
@endsection
