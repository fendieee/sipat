@extends('layouts.dashboard')

@section('title', 'Pemeriksaan Pengembalian')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($peminjamans->isEmpty())
        <div class="alert alert-warning">
            Tidak ada pengembalian yang menunggu pemeriksaan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Foto Kondisi</th>
                        <th>Tgl Pinjam</th>
                        <th>Terlambat (hari)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($peminjamans as $p)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $p->user->name }}</td>
                            <td class="text-start">{{ $p->alat->nama_alat }}</td>

                            {{-- FOTO KONDISI (FIX SUPAYA MUNCUL) --}}
                            <td>
                                @if ($p->foto_kondisi)
                                    <img src="{{ asset('storage/' . $p->foto_kondisi) }}"
                                        style="width:110px;height:110px;object-fit:cover" class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                            </td>

                            <td>
                                {{ $p->hari_telat ?? 0 }} hari
                            </td>

                            {{-- FORM PEMERIKSAAN --}}
                            <td>
                                <form action="{{ route('petugas.pemeriksaan.selesai', $p->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-2">
                                        <label class="form-label">Denda (Rp)</label>
                                        <input type="number" name="denda" class="form-control form-control-sm"
                                            value="0" min="0" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Catatan Kondisi (opsional)</label>
                                        <textarea name="catatan_petugas" class="form-control form-control-sm" rows="2"
                                            placeholder="Misal: Ada goresan kecil..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm w-100"
                                        onclick="return confirm('Selesaikan pengembalian?')">
                                        Selesaikan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
