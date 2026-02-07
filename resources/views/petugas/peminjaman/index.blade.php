@extends('layouts.dashboard')

@section('title', 'Menyetujui Peminjaman')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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
                @forelse($peminjamans as $p)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">{{ $p->user->name }}</td>
                        <td class="text-start">{{ $p->alat->nama_alat }}</td>

                        <td>
                            @if ($p->alat->gambar_alat)
                                <img src="{{ asset('storage/' . $p->alat->gambar_alat) }}" width="80" class="img-thumbnail">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>

                        <td>{{ $p->tanggal_pinjam }}</td>

                        {{-- ✅ BAGIAN YANG KAMU TANYA (TARUH DI SINI) --}}
                        <td>
                            @if ($p->status === 'pending')
                                <span class="badge bg-secondary">Pending</span>
                            @elseif ($p->status === 'dipinjam')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif ($p->status === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <td>
                            @if ($p->status === 'pending')
                                <form action="{{ route('petugas.setujui', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman?')">
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
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Tidak ada peminjaman menunggu
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
