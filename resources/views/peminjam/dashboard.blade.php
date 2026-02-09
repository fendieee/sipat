@extends('layouts.dashboard')

@section('title', 'Dashboard Peminjam')

@section('content')
    <div class="container-fluid">

        {{-- ROW STAT --}}
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Jumlah Alat Tersedia</h6>
                        <h2 class="fw-bold text-primary mb-0">{{ $alatTersedia }}</h2>
                        <small class="text-muted">Total alat yang dapat dipinjam</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Alat Sedang Dipinjam</h6>
                        <h2 class="fw-bold text-warning mb-0">{{ $dipinjam }}</h2>
                        <small class="text-muted">Alat dalam proses peminjaman</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR ALAT --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="fw-bold mb-1">Daftar Alat</h5>
                <p class="text-muted mb-0 small">Pilih alat yang ingin dipinjam</p>
            </div>
            <div class="card-body">
                @if ($daftarAlat->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-tools fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Belum ada alat tersedia</h5>
                        <p class="text-muted small">Silahkan hubungi administrator untuk informasi lebih lanjut</p>
                    </div>
                @else
                    <div class="row">
                        @foreach ($daftarAlat as $alat)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <a href="{{ route('peminjam.pengajuan.create', ['alat' => $alat->id, 'kategori' => $alat->kategori_id]) }}"
                                    class="text-decoration-none text-dark">
                                    <div class="card h-100 shadow-sm border">
                                        <div class="position-relative" style="height: 180px; overflow: hidden;">
                                            @if ($alat->gambar_alat)
                                                <img src="{{ asset('storage/' . $alat->gambar_alat) }}"
                                                    class="card-img-top h-100 w-100" style="object-fit: cover;"
                                                    alt="{{ $alat->nama_alat }}">
                                            @else
                                                <div
                                                    class="h-100 w-100 bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-tools fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">
                                                    Stok: {{ $alat->stok }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <h6 class="fw-bold text-truncate mb-2" title="{{ $alat->nama_alat }}">
                                                {{ $alat->nama_alat }}
                                            </h6>

                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $alat->kategori->nama ?? 'Tidak Berkategori' }}
                                            </small>

                                            <p class="mb-3 text-muted small"
                                                style="display: -webkit-box;
                                                       -webkit-line-clamp: 2;
                                                       -webkit-box-orient: vertical;
                                                       overflow: hidden;
                                                       line-height: 1.4;">
                                                {{ $alat->deskripsi ?: 'Tidak ada deskripsi' }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-primary fs-6">
                                                    Rp {{ number_format($alat->harga, 0, ',', '.') }}
                                                </span>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-shopping-cart me-1"></i> Pinjam
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- PAGINATION --}}
                    @if ($daftarAlat->hasPages())
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Menampilkan {{ $daftarAlat->firstItem() ?? 0 }} - {{ $daftarAlat->lastItem() ?? 0 }}
                                    dari {{ $daftarAlat->total() }} alat
                                </div>

                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm justify-content-end mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($daftarAlat->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $daftarAlat->previousPageUrl() }}">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Pagination Numbers --}}
                                        @php
                                            $current = $daftarAlat->currentPage();
                                            $last = $daftarAlat->lastPage();
                                            $start = max(1, $current - 1);
                                            $end = min($last, $current + 1);
                                        @endphp

                                        @if ($start > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $daftarAlat->url(1) }}">1</a>
                                            </li>
                                            @if ($start > 2)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                        @endif

                                        @for ($i = $start; $i <= $end; $i++)
                                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                                @if ($i == $current)
                                                    <span class="page-link">{{ $i }}</span>
                                                @else
                                                    <a class="page-link"
                                                        href="{{ $daftarAlat->url($i) }}">{{ $i }}</a>
                                                @endif
                                            </li>
                                        @endfor

                                        @if ($end < $last)
                                            @if ($end < $last - 1)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $daftarAlat->url($last) }}">{{ $last }}</a>
                                            </li>
                                        @endif

                                        {{-- Next Page Link --}}
                                        @if ($daftarAlat->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $daftarAlat->nextPageUrl() }}">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- RIWAYAT TERAKHIR --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="fw-bold mb-1">Riwayat Peminjaman</h5>
                <p class="text-muted mb-0 small">Riwayat peminjaman terakhir Anda</p>
            </div>
            <div class="card-body">
                @if ($riwayat->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-history fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Belum ada riwayat peminjaman</h5>
                        <p class="text-muted small">Mulai lakukan peminjaman pertama Anda</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="table-light">
                                    <th class="ps-3">Alat</th>
                                    <th class="text-center">Tanggal Pinjam</th>
                                    <th class="text-center">Jatuh Tempo</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat as $p)
                                    <tr class="border-bottom">
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                @if ($p->alat->gambar_alat)
                                                    <div class="flex-shrink-0 me-3">
                                                        <img src="{{ asset('storage/' . $p->alat->gambar_alat) }}"
                                                            alt="{{ $p->alat->nama_alat }}"
                                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-medium">{{ $p->alat->nama_alat }}</div>
                                                    <small
                                                        class="text-muted">{{ $p->alat->kategori->nama ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</div>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div>{{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y') }}
                                            </div>
                                            <small class="text-muted">Sisa:
                                                {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->diffForHumans(['parts' => 1]) }}</small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['color' => 'bg-secondary', 'icon' => 'fas fa-clock'],
                                                    'dipinjam' => [
                                                        'color' => 'bg-warning text-dark',
                                                        'icon' => 'fas fa-hourglass-half',
                                                    ],
                                                    'ditolak' => [
                                                        'color' => 'bg-danger',
                                                        'icon' => 'fas fa-times-circle',
                                                    ],
                                                    'selesai' => [
                                                        'color' => 'bg-success',
                                                        'icon' => 'fas fa-check-circle',
                                                    ],
                                                ];
                                                $config = $statusConfig[$p->status] ?? $statusConfig['pending'];
                                            @endphp
                                            <span class="badge {{ $config['color'] }}">
                                                <i class="{{ $config['icon'] }} me-1"></i>
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('peminjam.riwayat') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-history me-1"></i> Lihat Semua Riwayat
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
