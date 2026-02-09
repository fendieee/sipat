@extends('layouts.dashboard')

@section('title', 'Memantau Pengembalian')

@section('content')

    @if ($peminjamans->isEmpty())
        <div class="alert alert-warning">Tidak ada alat yang sedang dipinjam.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Gambar Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Kondisi</th> {{-- ✅ KOLUM BARU --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjamans as $p)
                        @php
                            $kondisiBadge = match ($p->kondisi ?? '') {
                                'baik' => 'success',
                                'rusak' => 'warning',
                                'hilang' => 'danger',
                                default => 'secondary',
                            };
                        @endphp

                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $p->user->name }}</td>
                            <td class="text-start">{{ $p->alat->nama_alat }}</td>

                            {{-- GAMBAR ALAT --}}
                            <td>
                                @if ($p->alat->gambar_alat)
                                    <img src="{{ asset('storage/' . $p->alat->gambar_alat) }}" width="80"
                                        class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>

                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y') }}</td>

                            {{-- ✅ KONDISI (BARU) --}}
                            <td>
                                @if ($p->kondisi)
                                    <span class="badge bg-{{ $kondisiBadge }}">
                                        {{ ucfirst($p->kondisi) }}
                                    </span>
                                @else
                                    <span class="text-muted">Belum dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
