@extends('layouts.dashboard')

@section('title', 'Data Alat')

@section('content')
<div class="container-fluid">


    <a href="{{ route('admin.alat.create') }}" class="btn btn-primary mb-3">
        + Tambah Alat
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
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($alats as $alat)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $alat->nama_alat }}</td>
                    <td>{{ $alat->kategori->nama ?? '-' }}</td>
                    <td class="text-center">{{ $alat->stok }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.alat.edit', $alat->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('admin.alat.destroy', $alat->id) }}"
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
                    <td colspan="5" class="text-center text-muted">
                        Data alat belum tersedia
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
