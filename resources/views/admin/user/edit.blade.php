@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Password <span class="text-muted">(opsional)</span>
                        </label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>

                    @if ($user->role !== 'admin')
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>
                                    Petugas
                                </option>
                                <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>
                                    Peminjam
                                </option>
                            </select>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
