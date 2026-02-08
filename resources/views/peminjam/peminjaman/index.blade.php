@extends('layouts.dashboard')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Riwayat Peminjaman</h5>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th width="10%">Kategori</th>
                        <th width="12%">Alat</th>
                        <th width="25%">Deskripsi</th>
                        <th width="10%">Foto</th>
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
                            if ($jumlahHari < 1) {
                                $jumlahHari = 1;
                            }

                            $hargaPerHari = $p->alat->harga ?? 0;
                            $totalHarga = $jumlahHari * $hargaPerHari;
                        @endphp

                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                            <td>{{ $p->alat->nama_alat }}</td>

                            {{-- DESKRIPSI --}}
                            <td>
                                @php
                                    $full = $p->alat->deskripsi;
                                    $short = Str::limit($full, 80);
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

                            {{-- FOTO PEMINJAM --}}
                            <td class="text-center">
                                @if ($p->foto_peminjam)
                                    <img src="{{ asset('storage/' . $p->foto_peminjam) }}"
                                        style="width:80px;height:80px;object-fit:cover" class="img-thumbnail">
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y') }}
                            </td>

                            {{-- HARGA TOTAL --}}
                            <td class="text-center">
                                <span class="fw-bold">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </span>
                                <br>
                                <small class="text-muted">
                                    ({{ $jumlahHari }} hari × Rp {{ number_format($hargaPerHari, 0, ',', '.') }})
                                </small>
                            </td>

                            {{-- STATUS --}}
                            @php
                                $badge = match ($p->status) {
                                    'pending' => 'bg-secondary',
                                    'dipinjam' => 'bg-warning text-dark',
                                    'menunggu_pemeriksaan' => 'bg-info text-dark',
                                    'dikembalikan' => 'bg-success',
                                    'ditolak' => 'bg-danger',
                                    default => 'bg-light text-dark',
                                };
                            @endphp

                            <td class="text-center">
                                <span class="badge {{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </td>

                            {{-- DENDA --}}
                            <td class="text-center">
                                @if ($p->denda > 0)
                                    <span class="badge bg-danger">
                                        Rp {{ number_format($p->denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- AKSI (SATU TOMBOL, FORM MUNCUL SETELAH DIKLIK) --}}
                            <td class="text-center">
                                @if ($p->status === 'dipinjam')
                                    <button class="btn btn-success btn-sm w-100" onclick="showForm({{ $p->id }})">
                                        Kembalikan
                                    </button>

                                    <form id="form-{{ $p->id }}" method="POST"
                                        action="{{ route('peminjam.kembalikan', $p->id) }}" enctype="multipart/form-data"
                                        class="mt-2 d-none">

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
                            <td colspan="11" class="text-center text-muted">
                                Belum ada riwayat peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleDesc(id) {
            document.getElementById('short-' + id).classList.toggle('d-none');
            document.getElementById('full-' + id).classList.toggle('d-none');
        }

        function showForm(id) {
            document.getElementById('form-' + id).classList.remove('d-none');
        }
    </script>
@endsection
