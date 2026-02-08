@extends('layouts.dashboard')

@section('title', 'Menyetujui Peminjaman')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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
                            </td>

                            {{-- AKSI --}}
                            <td>
                                @if ($p->status === 'pending')
                                    <form action="{{ route('petugas.setujui', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Setujui peminjaman?')">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('petugas.tolak', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman?')">
                                            Tolak
                                        </button>
                                    </form>
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

