@extends('layouts.landing')

@section('title', 'Login')

@section('content')
    <div class="page-wrapper">
        <div class="card-auth">
            <h2 class="text-center mb-4">Login</h2>

            @if ($errors->any())
                <div class="error-message">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary-custom w-100">Login</button>
            </form>

            <div class="auth-footer text-center mt-4">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </div>
        </div>
    </div>
@endsection
