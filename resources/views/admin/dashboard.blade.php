@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="card border-0 shadow-sm rounded-4 bg-light p-4 d-flex flex-md-row flex-column align-items-md-center align-items-start justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-1 text-dark">Selamat Datang, Admin</h2>
                        <p class="text-muted mb-0">Sistem Informasi Peminjaman Alat Terpadu</p>
                    </div>
                    <div class="d-flex align-items-center mt-3 mt-md-0">
                        <i class="bi bi-speedometer2 fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dashboard Cards Simple --}}
        <div class="row g-4">

            @php
                $cards = [
                    [
                        'title' => 'Manajemen Alat',
                        'desc' => 'Kelola data alat & inventaris',
                        'icon' => 'bi-tools',
                        'color' => 'primary',
                        'route' => '#',
                    ],
                    [
                        'title' => 'Peminjaman',
                        'desc' => 'Data peminjaman alat',
                        'icon' => 'bi-box-arrow-up',
                        'color' => 'secondary',
                        'route' => '#',
                    ],
                    [
                        'title' => 'Pengembalian',
                        'desc' => 'Data pengembalian alat',
                        'icon' => 'bi-box-arrow-in-down',
                        'color' => 'success',
                        'route' => '#',
                    ],
                    [
                        'title' => 'Laporan',
                        'desc' => 'Ringkasan laporan peminjaman',
                        'icon' => 'bi-file-earmark-text',
                        'color' => 'dark',
                        'route' => '#',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 text-center hover-card p-3">
                        <div class="icon-box mb-3 text-white bg-{{ $card['color'] }}">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </div>
                        <h6 class="fw-semibold">{{ $card['title'] }}</h6>
                        <p class="text-muted small">{{ $card['desc'] }}</p>
                        <a href="{{ $card['route'] }}" class="btn btn-sm btn-outline-{{ $card['color'] }}">Kelola</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto;
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }
    </style>
@endsection
