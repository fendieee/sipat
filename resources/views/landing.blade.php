@extends('layouts.landing')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <div>
            <h1>Sistem Peminjaman Alat</h1>

            <h2>Apakah Sudah punya Akun?</h2>

            <a href="{{ route('login') }}" class="btn btn-lg btn-primary-custom m-3">
                Login
            </a>

            <a href="{{ route('register') }}" class="btn btn-lg btn-primary-custom">
                Register
            </a>
        </div>
    </section>
@endsection
