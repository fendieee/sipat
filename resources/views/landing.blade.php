@extends('layouts.landing')

@section('title', 'Beranda')

@section('content')

<style>
.beranda {
    min-height: 75vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f1f5f9;
}

.beranda-card {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.08);
}

.beranda-card h2 {
    font-size: 2rem;
    font-weight: 600;
    color: #1e3a8a;
}

.beranda-card p {
    color: #64748b;
    margin: 20px 0 30px;
}

.btn-login {
    background: #1e40af;
    color: #fff;
    padding: 10px 32px;
    border-radius: 8px;
    text-decoration: none;
    transition: .2s;
}

.btn-login:hover {
    background: #1e3a8a;
    color: #fff;
}

</style>

<section class="beranda">
    <div class="beranda-card">
        <h2>Sistem Peminjaman Alat</h2>

        <p>
            Aplikasi pengelolaan peminjaman alat yang cepat dan terstruktur.
        </p>

        <a href="{{ route('login') }}" class="btn-login">
            Login
        </a>
    </div>
</section>

@endsection
