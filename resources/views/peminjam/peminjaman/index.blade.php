@extends('layouts.dashboard')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Riwayat Peminjaman</h5>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kategori</th>
                        <th width="12%">Alat</th>
                        <th width="25%">Deskripsi</th>
                        <th width="10%">Foto</th>
                        <th width="5%">Jumlah</th>
                        <th width="7%">Tgl Pinjam</th>
                        <th width="7%">Jatuh Tempo</th>
                        <th width="10%">Harga Total</th>
                        <th width="7%">Status</th>
                        <th width="7%">Denda</th>
                        <th width="7%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($peminjamans as $p)
                        @php
                            $tglPinjam = \Carbon\Carbon::parse($p->tanggal_pinjam);
                            $tglJatuhTempo = \Carbon\Carbon::parse($p->tanggal_jatuh_tempo);

                            $jumlahHari = $tglPinjam->diffInDays($tglJatuhTempo);
                            $jumlahHari = $jumlahHari < 1 ? 1 : $jumlahHari;

                            $hargaPerHari = $p->alat->harga ?? 0;
                            $jumlahPinjam = $p->jumlah ?? 1; // jumlah pinjam
                            $totalHarga = $jumlahHari * $hargaPerHari * $jumlahPinjam;

                            $fullDesc = $p->alat->deskripsi ?? '-';
                            $shortDesc = \Illuminate\Support\Str::limit($fullDesc, 80);

                            $statusBadge = match ($p->status) {
                                'pending' => 'bg-secondary',
                                'dipinjam' => 'bg-warning text-dark',
                                'menunggu_pemeriksaan' => 'bg-info text-dark',
                                'dikembalikan' => 'bg-success',
                                'ditolak' => 'bg-danger',
                                default => 'bg-light text-dark',
                            };
                        @endphp

                        <tr>
                            {{-- No --}}
                            <td class="text-center">{{ $loop->iteration }}</td>

                            {{-- Kategori --}}
                            <td>{{ $p->alat->kategori->nama ?? '-' }}</td>

                            {{-- Alat --}}
                            <td>{{ $p->alat->nama_alat ?? '-' }}</td>

                            {{-- Deskripsi --}}
                            <td>
                                <p class="mb-1" id="short-{{ $p->id }}">
                                    {{ $shortDesc }}
                                    @if (strlen($fullDesc) > 80)
                                        <a href="javascript:void(0)" class="text-primary ms-1"
                                            onclick="toggleDesc({{ $p->id }})">
                                            See more
                                        </a>
                                    @endif
                                </p>
                                @if (strlen($fullDesc) > 80)
                                    <p class="mb-0 d-none" id="full-{{ $p->id }}">
                                        {{ $fullDesc }}
                                        <a href="javascript:void(0)" class="text-danger ms-1"
                                            onclick="toggleDesc({{ $p->id }})">
                                            See less
                                        </a>
                                    </p>
                                @endif
                            </td>

                            {{-- Foto --}}
                            <td class="text-center">
                                @if ($p->foto_peminjam)
                                    <img src="{{ asset('storage/' . $p->foto_peminjam) }}" class="img-thumbnail"
                                        style="width:80px; height:80px; object-fit:cover;">
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>

                            {{-- Jumlah Pinjam --}}
                            <td class="text-center">{{ $jumlahPinjam }}</td>

                            {{-- Tanggal Pinjam --}}
                            <td class="text-center">{{ $tglPinjam->format('d M Y') }}</td>

                            {{-- Tanggal Jatuh Tempo --}}
                            <td class="text-center">{{ $tglJatuhTempo->format('d M Y') }}</td>

                            {{-- Harga Total --}}
                            <td class="text-center">
                                <span class="fw-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                                <br>
                                <small class="text-muted">({{ $jumlahPinjam }} × {{ $jumlahHari }} hari × Rp
                                    {{ number_format($hargaPerHari, 0, ',', '.') }})</small>
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                <span class="badge {{ $statusBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </td>

                            {{-- Denda --}}
                            <td class="text-center">
                                @if ($p->denda > 0)
                                    <span class="badge bg-danger">Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                @if ($p->status === 'dipinjam')
                                    <button class="btn btn-success btn-sm w-100 mb-1"
                                        onclick="showForm({{ $p->id }})">
                                        Kembalikan
                                    </button>

                                    <form id="form-{{ $p->id }}" method="POST"
                                        action="{{ route('peminjam.kembalikan', $p->id) }}" enctype="multipart/form-data"
                                        class="d-none">
                                        @csrf
                                        <input type="file" name="foto_kondisi" accept="image/*"
                                            class="form-control form-control-sm mb-2" required>
                                        <button class="btn btn-primary btn-sm w-100"
                                            onclick="return confirm('Ajukan pengembalian?')">
                                            Kirim Foto
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">Belum ada riwayat peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script --}}
    @push('scripts')
        <script>
            function toggleDesc(id) {
                document.getElementById('short-' + id).classList.toggle('d-none');
                document.getElementById('full-' + id).classList.toggle('d-none');
            }

            function showForm(id) {
                document.getElementById('form-' + id).classList.remove('d-none');
            }
        </script>
    @endpush
@endsection
