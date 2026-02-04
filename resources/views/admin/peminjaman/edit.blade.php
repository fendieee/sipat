@extends('layouts.dashboard')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="container-fluid">


    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Peminjam</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $peminjaman->user->name }}"
                           disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alat</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $peminjaman->alat->nama_alat }}"
                           disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date"
                           name="tanggal_pinjam"
                           class="form-control"
                           value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Jatuh Tempo</label>
                    <input type="date"
                           name="tanggal_jatuh_tempo"
                           class="form-control"
                           value="{{ old('tanggal_jatuh_tempo', $peminjaman->tanggal_jatuh_tempo) }}"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
