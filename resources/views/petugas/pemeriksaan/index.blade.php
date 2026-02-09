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
                                    <th>Kondisi</th>
                                    <th>Foto Kondisi</th>
                                    <th>Denda Saat Ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($peminjamans as $p)
                                    @php
                                        $tglPinjam = \Carbon\Carbon::parse($p->tanggal_pinjam);
                                        $tglJatuhTempo = \Carbon\Carbon::parse($p->tanggal_jatuh_tempo);

                                        // Warna badge kondisi
                                        $kondisiBadge = match ($p->kondisi ?? '') {
                                            'baik' => 'success',
                                            'rusak' => 'warning',
                                            'hilang' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $p->user->name ?? '-' }}</td>
                                        <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                                        <td class="fw-semibold">{{ $p->alat->nama_alat ?? '-' }}</td>

                                        {{-- JUMLAH --}}
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $p->jumlah ?? 0 }}
                                            </span>
                                        </td>

                                        <td class="text-center">{{ $tglPinjam->format('d M Y') }}</td>
                                        <td class="text-center">{{ $tglJatuhTempo->format('d M Y') }}</td>

                                        {{-- KONDISI --}}
                                        <td class="text-center">
                                            @if ($p->kondisi)
                                                <span class="badge bg-{{ $kondisiBadge }}">
                                                    {{ ucfirst($p->kondisi) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Belum diisi</span>
                                            @endif
                                        </td>

                                        {{-- FOTO KONDISI --}}
                                        <td class="text-center">
                                            @if ($p->foto_kondisi)
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#fotoModal{{ $p->id }}">
                                                    Lihat Foto
                                                </button>
                                            @else
                                                <span class="text-muted small">Tidak ada</span>
                                            @endif
                                        </td>

                                        {{-- DENDA --}}
                                        <td class="text-center">
                                            <span class="badge bg-{{ $p->denda > 0 ? 'danger' : 'success' }}">
                                                Rp {{ number_format($p->denda, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        {{-- FORM PEMERIKSAAN --}}
                                        <td style="min-width:220px">
                                            <form action="{{ route('petugas.pemeriksaan.selesai', $p->id) }}"
                                                method="POST">
                                                @csrf

                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Jumlah Dipinjam</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $p->jumlah }}" readonly>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Kondisi Barang</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ ucfirst($p->kondisi ?? 'Belum diisi') }}" readonly>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Tambah/Ubah Denda (Rp)</label>
                                                    <input type="number" name="denda"
                                                        class="form-control form-control-sm" value="{{ $p->denda ?? 0 }}"
                                                        min="0" required>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Catatan Petugas</label>
                                                    <textarea name="catatan_petugas" class="form-control form-control-sm" rows="2"
                                                        placeholder="Contoh: Barang ada goresan, perlu perbaikan...">{{ $p->catatan_petugas }}</textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-sm w-100"
                                                    onclick="return confirm('Selesaikan pengembalian untuk alat: {{ $p->alat->nama_alat ?? '' }}?')">
                                                    <i class="fas fa-check me-1"></i> Selesaikan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Modal Foto --}}
                                    @if ($p->foto_kondisi)
                                        <div class="modal fade" id="fotoModal{{ $p->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Foto Kondisi Alat</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/' . $p->foto_kondisi) }}"
                                                            class="img-fluid rounded">
                                                        <p class="mt-2 text-muted small">
                                                            {{ $p->alat->nama_alat }} - {{ ucfirst($p->kondisi) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
