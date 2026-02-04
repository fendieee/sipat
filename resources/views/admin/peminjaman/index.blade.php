@extends('layouts.dashboard')

@section('title', 'Data Peminjaman')

@section('content')
<div class="container-fluid">


    <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary mb-3">
        + Tambah Peminjaman
    </a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($peminjamans as $peminjaman)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $peminjaman->user->name }}</td>
                    <td>{{ $peminjaman->alat->nama_alat }}</td>
                    <td class="text-center">{{ $peminjaman->tanggal_pinjam }}</td>
                    <td class="text-center">{{ $peminjaman->tanggal_jatuh_tempo }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.peminjaman.edit', $peminjaman->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Data peminjaman belum tersedia
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
