@extends('layouts.dashboard')

@section('title', 'Data Alat')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Data Alat</h5>
            <a href="{{ route('admin.alat.create') }}" class="btn btn-primary">
                + Tambah Alat
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th width="15%">Gambar</th>
                        <th width="18%">Nama Alat</th>
                        <th width="30%">Deskripsi</th>
                        <th width="12%">Kategori</th>
                        <th width="8%">Stok</th>
                        <th width="12%">Harga</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($alats as $alat)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>

                            {{-- Gambar --}}
                            <td class="text-center">
                                @if ($alat->gambar_alat)
                                    <img src="{{ asset('storage/' . $alat->gambar_alat) }}"
                                        style="width:100px;height:100px;object-fit:cover" class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>

                            {{-- Nama Alat --}}
                            <td
                                style="
                            max-width: 220px;
                            white-space: normal;
                            word-break: break-word;
                            overflow-wrap: break-word;
                        ">
                                {{ $alat->nama_alat }}
                            </td>

                            {{-- Deskripsi (DIBESARIN & LEGIBLE) --}}
                            <td
                                style="
                            max-width: 650px;
                            white-space: normal;
                            word-break: break-word;
                            overflow-wrap: break-word;
                            line-height: 1.6;
                        ">
                                {{ $alat->deskripsi }}
                            </td>

                            <td>
                                @forelse ($alat->kategoris as $kat)
                                    <span class="badge bg-secondary">{{ $kat->nama }}</span>
                                @empty
                                    -
                                @endforelse
                            </td>

                            <td class="text-center">{{ $alat->stok }}</td>

                            <td>
                                Rp {{ number_format($alat->harga, 0, ',', '.') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <a href="{{ route('admin.alat.edit', $alat->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus data alat ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Data alat belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
