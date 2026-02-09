@extends('layouts.dashboard')

@section('title', 'Edit Peminjaman')

@section('content')
    <div class="container-fluid">

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- PEMINJAM --}}
                    <div class="mb-3">
                        <label class="form-label">Peminjam</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih Peminjam --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $peminjaman->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="mb-3">
                        <label class="form-label">Kategori Alat</label>
                        <select id="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ $peminjaman->alat->kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ALAT --}}
                    <div class="mb-3">
                        <label class="form-label">Alat</label>
                        <select name="alat_id" id="alat" class="form-select" required>
                            <option value="">Loading...</option>
                        </select>
                        <small id="infoAlat" class="text-muted"></small>
                    </div>

                    {{-- JUMLAH --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control"
                            value="{{ old('jumlah', $peminjaman->jumlah) }}" min="1" required>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggalPinjam" class="form-control"
                            value="{{ old('tanggal_pinjam', \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d')) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" id="tanggalJatuhTempo" class="form-control"
                            value="{{ old('tanggal_jatuh_tempo', \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('Y-m-d')) }}"
                            required>
                    </div>

                    {{-- TOTAL HARGA --}}
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <input type="text" id="totalHarga" class="form-control" readonly>
                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        const kategoriSelect = document.getElementById('kategori');
        const alatSelect = document.getElementById('alat');
        const infoAlat = document.getElementById('infoAlat');
        const jumlahInput = document.getElementById('jumlah');
        const tanggalPinjam = document.getElementById('tanggalPinjam');
        const tanggalJatuhTempo = document.getElementById('tanggalJatuhTempo');
        const totalHargaBox = document.getElementById('totalHarga');

        const selectedAlatId = "{{ $peminjaman->alat_id }}";

        // Load alat saat halaman dibuka
        function loadAlat(kategoriId, selected = null) {
            alatSelect.innerHTML = '<option value="">Loading...</option>';
            alatSelect.disabled = true;

            fetch(`/admin/get-alat/${kategoriId}`)
                .then(res => res.json())
                .then(data => {
                    alatSelect.innerHTML = '<option value="">-- Pilih Alat --</option>';
                    if (data.length === 0) {
                        alatSelect.innerHTML += '<option value="">Tidak ada alat</option>';
                    }
                    data.forEach(alat => {
                        let sel = alat.id == selected ? 'selected' : '';
                        alatSelect.innerHTML +=
                            `<option value="${alat.id}" data-harga="${alat.harga}" ${sel}>
                        ${alat.nama_alat} (Stok: ${alat.stok})
                    </option>`;
                    });
                    alatSelect.disabled = false;
                    infoAlat.innerText = "";
                    hitungTotal();
                });
        }

        // Auto-load alat
        window.addEventListener('load', function() {
            if (kategoriSelect.value) {
                loadAlat(kategoriSelect.value, selectedAlatId);
            }
        });

        // Saat kategori diganti
        kategoriSelect.addEventListener('change', function() {
            let kategoriId = this.value;
            if (kategoriId) {
                loadAlat(kategoriId);
            } else {
                alatSelect.innerHTML = '<option value="">-- Pilih Alat --</option>';
                alatSelect.disabled = true;
                infoAlat.innerText = "Pilih kategori terlebih dahulu";
                hitungTotal();
            }
        });

        // Hitung total otomatis
        function hitungTotal() {
            const selectedAlat = alatSelect.options[alatSelect.selectedIndex];
            const harga = selectedAlat ? parseInt(selectedAlat.getAttribute('data-harga') || 0) : 0;
            const jumlah = parseInt(jumlahInput.value || 1);
            const t1 = tanggalPinjam.value ? new Date(tanggalPinjam.value) : null;
            const t2 = tanggalJatuhTempo.value ? new Date(tanggalJatuhTempo.value) : null;

            if (!t1 || !t2 || harga === 0) {
                totalHargaBox.value = "";
                return;
            }

            const diffDays = Math.max(1, Math.ceil((t2 - t1) / (1000 * 60 * 60 * 24)));
            const total = diffDays * harga * jumlah;
            totalHargaBox.value =
                `${jumlah} × ${diffDays} hari × Rp ${harga.toLocaleString('id-ID')} = Rp ${total.toLocaleString('id-ID')}`;
        }

        alatSelect.addEventListener('change', hitungTotal);
        jumlahInput.addEventListener('input', hitungTotal);
        tanggalPinjam.addEventListener('change', function() {
            tanggalJatuhTempo.value = "";
            hitungTotal();
        });
        tanggalJatuhTempo.addEventListener('change', function() {
            if (tanggalJatuhTempo.value <= tanggalPinjam.value) {
                alert('Tanggal jatuh tempo harus lebih besar dari tanggal pinjam!');
                tanggalJatuhTempo.value = "";
            }
            hitungTotal();
        });
    </script>
@endsection
