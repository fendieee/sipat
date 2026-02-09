@extends('layouts.dashboard')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Riwayat Peminjaman</h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Alat</th>
                                <th>Jumlah</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Kondisi</th>
                                <th>Denda</th>
                                <th>Foto Kondisi</th>
                                <th>Foto Peminjam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($peminjamans as $p)
                                @php
                                    $tglPinjam = \Carbon\Carbon::parse($p->tanggal_pinjam);
                                    $tglJatuhTempo = \Carbon\Carbon::parse($p->tanggal_jatuh_tempo);
                                    $jumlahHari = max($tglPinjam->diffInDays($tglJatuhTempo), 1);
                                    $hargaPerHari = $p->alat->harga ?? 0;
                                    $jumlahPinjam = $p->jumlah ?? 1;
                                    $totalHarga = $jumlahHari * $hargaPerHari * $jumlahPinjam;

                                    $statusBadge = match ($p->status) {
                                        'pending' => 'secondary',
                                        'dipinjam' => 'warning',
                                        'menunggu_pemeriksaan' => 'info',
                                        'dikembalikan' => 'success',
                                        'ditolak' => 'danger',
                                        'hilang' => 'danger',
                                        default => 'light',
                                    };

                                    $kondisiBadge = match ($p->kondisi ?? '') {
                                        'baik' => 'success',
                                        'rusak' => 'warning',
                                        'hilang' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $p->alat->kategori->nama ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $p->alat->nama_alat ?? '-' }}</td>
                                    <td class="text-center">{{ $jumlahPinjam }}</td>
                                    <td class="text-center">{{ $tglPinjam->format('d M Y') }}</td>
                                    <td class="text-center">{{ $tglJatuhTempo->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="fw-bold text-dark">
                                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $jumlahPinjam }} × {{ $jumlahHari }} hari ×
                                            Rp {{ number_format($hargaPerHari, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $statusBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($p->kondisi)
                                            <span class="badge bg-{{ $kondisiBadge }}">
                                                {{ ucfirst($p->kondisi) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($p->denda > 0)
                                            <span class="badge bg-danger">
                                                Rp {{ number_format($p->denda, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($p->foto_kondisi))
                                            <img src="{{ asset('storage/' . $p->foto_kondisi) }}" class="rounded shadow-sm"
                                                style="width:70px;height:70px;object-fit:cover;">
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($p->foto_peminjam))
                                            <img src="{{ asset('storage/' . $p->foto_peminjam) }}"
                                                class="rounded shadow-sm" style="width:70px;height:70px;object-fit:cover;">
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="min-width:150px">
                                        @if ($p->status === 'dipinjam')
                                            <button class="btn btn-success btn-sm w-100 mb-1"
                                                onclick="showForm({{ $p->id }})">
                                                Kembalikan
                                            </button>

                                            <form id="form-{{ $p->id }}" method="POST"
                                                action="{{ route('peminjam.kembalikan', $p->id) }}"
                                                enctype="multipart/form-data" class="d-none">
                                                @csrf
                                                <select name="kondisi" class="form-select form-select-sm mb-2" required>
                                                    <option value="">-- Pilih Kondisi --</option>
                                                    <option value="baik">Baik</option>
                                                    <option value="rusak">Rusak</option>
                                                    <option value="hilang">Hilang</option>
                                                </select>

                                                <input type="file" name="foto_kondisi" accept="image/*"
                                                    class="form-control form-control-sm mb-2">

                                                <div class="form-text small mb-2">
                                                    *Wajib upload foto untuk kondisi Baik/Rusak
                                                </div>

                                                <button type="button" class="btn btn-primary btn-sm w-100"
                                                    onclick="validateForm({{ $p->id }})">
                                                    Kirim
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted py-4">
                                        Belum ada riwayat peminjaman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showForm(id) {
                let form = document.getElementById('form-' + id);
                if (form) {
                    form.classList.remove('d-none');
                }
            }

            function validateForm(id) {
                let form = document.getElementById('form-' + id);
                let kondisi = form.querySelector('select[name="kondisi"]').value;
                let foto = form.querySelector('input[name="foto_kondisi"]').value;

                if (!kondisi) {
                    alert('Silakan pilih kondisi barang!');
                    return;
                }

                if ((kondisi === 'baik' || kondisi === 'rusak') && !foto) {
                    alert('Foto kondisi wajib diupload untuk barang Baik atau Rusak!');
                    return;
                }

                if (confirm('Ajukan pengembalian?')) {
                    form.submit();
                }
            }
        </script>
    @endpush
@endsection
