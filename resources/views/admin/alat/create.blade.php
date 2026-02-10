@extends('layouts.dashboard')

@section('title', 'Tambah Alat')

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
        <div class="card-header bg-white fw-semibold">
            Form Tambah Alat
        </div>

        <div class="card-body">
            <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Alat -->
                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-control"
                        value="{{ old('nama_alat') }}"
                        maxlength="100"
                        placeholder="Maksimal 100 karakter"
                        required>
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi Alat</label>
                    <textarea name="deskripsi" class="form-control"
                        rows="3"
                        maxlength="255"
                        placeholder="Maksimal 255 karakter"
                        required>{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id[]" class="form-select" multiple required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stok -->
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control"
                        value="{{ old('stok') }}"
                        min="0"
                        required>
                </div>

                <!-- Harga -->
                <div class="mb-3">
                    <label class="form-label">Harga per Item (Rp)</label>
                    <input type="number" name="harga" class="form-control"
                        value="{{ old('harga') }}"
                        >
                </div>

                <!-- Gambar -->
                <div class="mb-3">
                    <label class="form-label">Gambar Alat</label>
                    <input type="file" name="gambar_alat" class="form-control"
                        accept="image/*"
                        required>
                    <small class="text-muted">
                        Rekomendasi ukuran: 500x500 px
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan
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
