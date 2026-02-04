@extends('layouts.dashboard')

@section('title', 'Pengembalian Alat')

@section('content')
<div class="container-fluid">
    

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
            @forelse ($peminjamans as $p)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->alat->nama_alat }}</td>
                    <td class="text-center">{{ $p->tanggal_pinjam }}</td>
                    <td class="text-center">{{ $p->tanggal_jatuh_tempo }}</td>
                    <td class="text-center">
                        <form method="POST"
                              action="{{ route('admin.pengembalian.kembalikan', $p->id) }}"
                              class="d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-success btn-sm"
                                    onclick="return confirm('Konfirmasi pengembalian?')">
                                Kembalikan
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
