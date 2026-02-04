@extends('layouts.dashboard')

@section('title', 'Kategori')

@section('content')
<div class="container-fluid">


    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary mb-3">
        + Tambah Kategori
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
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($kategoris as $kategori)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $kategori->nama }}</td>
                    <td>{{ $kategori->deskripsi }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus kategori?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Data kategori belum tersedia
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
