@extends('layouts.dashboard')

@section('title', 'Edit Alat')

@section('content')
<div class="container-fluid">


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text"
                           name="nama_alat"
                           class="form-control"
                           value="{{ old('nama_alat', $alat->nama_alat) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ $alat->kategori_id == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number"
                           name="stok"
                           class="form-control"
                           value="{{ old('stok', $alat->stok) }}">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                    <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
