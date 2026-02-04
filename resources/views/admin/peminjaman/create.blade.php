@extends('layouts.dashboard')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="container-fluid">


    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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

            <form action="{{ route('admin.peminjaman.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Peminjam</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih Peminjam --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alat</label>
                    <select name="alat_id" class="form-select" required>
                        <option value="">-- Pilih Alat --</option>
                        @foreach ($alats as $alat)
                            <option value="{{ $alat->id }}">
                                {{ $alat->nama_alat }} (Stok: {{ $alat->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date"
                           name="tanggal_pinjam"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Jatuh Tempo</label>
                    <input type="date"
                           name="tanggal_jatuh_tempo"
                           class="form-control"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
