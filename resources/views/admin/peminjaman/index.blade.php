@extends('layouts.dashboard')

@section('title', 'Data Peminjaman')

@section('content')
    <div class="container-fluid">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Data Peminjaman</h5>
                    <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">
                        + Tambah Peminjaman
                    </a>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Kategori</th>
                                <th>Alat</th>
                                <th>Jumlah</th>
                                <th>Harga/Hari</th>
                                <th>Tanggal Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjamans as $p)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $p->user->name }}</td>
                                    <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                                    <td>{{ $p->alat->nama_alat }}</td>
                                    <td class="text-center">{{ $p->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($p->alat->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d-m-Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d-m-Y') }}</td>

                                    {{-- Total Harga --}}
                                    @php
                                        $hari = max(
                                            1,
                                            \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->diffInDays(
                                                \Carbon\Carbon::parse($p->tanggal_pinjam),
                                            ),
                                        );
                                        $total = $p->jumlah * $p->alat->harga * $hari;
                                    @endphp
                                    <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        <span
                                            class="badge
                                    @if ($p->status == 'dipinjam') bg-primary
                                    @elseif($p->status == 'kembali') bg-success
                                    @elseif($p->status == 'pending') bg-warning
                                    @else bg-secondary @endif">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center">
                                        <a href="{{ route('admin.peminjaman.edit', $p->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form action="{{ route('admin.peminjaman.destroy', $p->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah yakin ingin menghapus peminjaman ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        Data peminjaman belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
