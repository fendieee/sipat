@extends('layouts.dashboard')

@section('title', 'Menyetujui Peminjaman')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- TAMPILKAN ERROR VALIDASI (INI PENTING BIAR TIDAK HANYA REFRESH) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($peminjamans->isEmpty())
        <div class="alert alert-warning">Tidak ada peminjaman menunggu.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Gambar Alat</th>
                        <th>Tgl Pinjam</th>
                        <th>Harga / Hari</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjamans as $p)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $p->user->name }}</td>
                            <td class="text-start">{{ $p->alat->nama_alat }}</td>

                            {{-- GAMBAR ALAT --}}
                            <td>
                                @if ($p->alat->gambar_alat)
                                    <img src="{{ asset('storage/' . $p->alat->gambar_alat) }}" width="80"
                                        class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>

                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>

                            {{-- HARGA PER HARI --}}
                            <td class="fw-bold">
                                Rp {{ number_format($p->alat->harga, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @php
                                    $statusBadge = match ($p->status) {
                                        'pending' => 'bg-secondary',
                                        'dipinjam' => 'bg-success',
                                        'ditolak' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                    };

                                    $statusText = match ($p->status) {
                                        'pending' => 'Pending',
                                        'dipinjam' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        default => ucfirst($p->status),
                                    };
                                @endphp

                                <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>

                                @if ($p->status === 'ditolak' && $p->alasan_tolak)
                                    <div class="small text-danger mt-1">
                                        <strong>Alasan:</strong> {{ $p->alasan_tolak }}
                                    </div>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                @if ($p->status === 'pending')
                                    {{-- TOMBOL SETUJUI --}}
                                    <form action="{{ route('petugas.setujui', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Setujui peminjaman?')">
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- TOMBOL TOLAK (BUKA MODAL) --}}
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#tolakModal{{ $p->id }}">
                                        Tolak
                                    </button>

                                    <!-- MODAL ALASAN TOLAK -->
                                    <div class="modal fade" id="tolakModal{{ $p->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Alasan Menolak</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <form action="{{ route('petugas.tolak', $p->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Masukkan alasan penolakan</label>
                                                            <textarea name="alasan_tolak" class="form-control" rows="3" required
                                                                placeholder="Contoh: Alat sedang dalam perbaikan..."></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            Konfirmasi Tolak
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
