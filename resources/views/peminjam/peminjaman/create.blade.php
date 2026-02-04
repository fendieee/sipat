@extends('layouts.dashboard')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="container-fluid">


    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('peminjam.peminjaman.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Alat</label>
                    <select name="alat_id" class="form-select" required>
                        <option value="">-- Pilih Alat --</option>
                        @foreach($alats as $alat)
                            <option value="{{ $alat->id }}">
                                {{ $alat->nama_alat }} (Stok: {{ $alat->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                </div>

                <button class="btn btn-primary">
                    Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
