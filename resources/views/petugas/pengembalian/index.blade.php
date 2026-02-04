@extends('layouts.dashboard')

@section('title', 'Memantau Pengembalian')

@section('content')

@if($peminjamans->isEmpty())
    <div class="alert alert-warning">Tidak ada alat yang sedang dipinjam.</div>
@else
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $p)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td class="text-start">{{ $p->user->name }}</td>
                <td class="text-start">{{ $p->alat->nama_alat }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ $p->tanggal_jatuh_tempo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
