@extends('layouts.landing')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <div>
            <h1>Sistem Peminjaman Alat</h1>
            <p>
                Platform digital untuk mengelola peminjaman alat secara efisien,
                transparan, dan terintegrasi.
            </p>
            <a href="{{ route('login') }}" class="btn btn-lg btn-primary-custom">
                Mulai Sekarang
            </a>
        </div>
    </section>
@endsection
