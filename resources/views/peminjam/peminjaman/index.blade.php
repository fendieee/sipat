@extends('layouts.dashboard')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Alat</th>
                        <th>Deskripsi Alat</th>
                        <th>Foto Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>

                            <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                            <td>{{ $p->alat->nama_alat }}</td>

                            {{-- DESKRIPSI DENGAN SEE MORE --}}
                            <td>
                                @php
                                    $full = $p->alat->deskripsi;
                                    $short = Str::limit($full, 50);
                                @endphp

                                <p class="mb-1" id="short-{{ $p->id }}">
                                    {{ $short }}
                                    @if (strlen($full) > 80)
                                        <a href="javascript:void(0)" class="text-primary ms-1"
                                            onclick="toggleDesc({{ $p->id }})">
                                            See more
                                        </a>
                                    @endif
                                </p>

                                @if (strlen($full) > 80)
                                    <p class="mb-0 d-none" id="full-{{ $p->id }}">
                                        {{ $full }}
                                        <a href="javascript:void(0)" class="text-danger ms-1"
                                            onclick="toggleDesc({{ $p->id }})">
                                            See less
                                        </a>
                                    </p>
                                @endif
                            </td>

                            {{-- FOTO --}}
                            <td class="text-center">
                                @if ($p->foto_peminjam)
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ asset('storage/' . $p->foto_peminjam) }}" width="100"
                                            height="100" style="object-fit: cover;" class="img-thumbnail">
                                    </div>
                                @else
                                    <span class="text-muted">Belum ada foto</span>
                                @endif
                            </td>

                            <td class="text-center">{{ $p->tanggal_pinjam }}</td>
                            <td class="text-center">{{ $p->tanggal_jatuh_tempo }}</td>

                            {{-- STATUS --}}
                            <td class="text-center">
                                @php
                                    $badge = match ($p->status) {
                                        'pending' => 'bg-secondary',
                                        'dipinjam' => 'bg-warning text-dark',
                                        'dikembalikan' => 'bg-success',
                                        'ditolak' => 'bg-danger',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp

                                <span class="badge {{ $badge }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>

                            {{-- ✅ DENDA (SUDAH BENAR, AMBIL DARI DB) --}}
                            <td class="text-center">
                                @if ($p->denda > 0)
                                    <span class="badge bg-danger">
                                        Rp {{ number_format($p->denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                @if ($p->status === 'dipinjam')
                                    <form method="POST" action="{{ route('peminjam.kembalikan', $p->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success" onclick="return confirm('Kembalikan alat?')">
                                            Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                Belum ada riwayat peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SCRIPT SEE MORE --}}
    <script>
        function toggleDesc(id) {
            const shortText = document.getElementById('short-' + id);
            const fullText = document.getElementById('full-' + id);

            shortText.classList.toggle('d-none');
            fullText.classList.toggle('d-none');
        }
    </script>

@endsection
