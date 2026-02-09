@extends('layouts.dashboard')

@section('title', 'Ajukan Peminjaman')

@section('content')
    <div class="container-fluid">

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

                <form action="{{ route('peminjam.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilih Kategori --}}
                    <div class="mb-3">
                        <label class="form-label">Pilih Kategori</label>
                        <select id="kategoriSelect" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Alat --}}
                    <div class="mb-3">
                        <label class="form-label">Pilih Alat</label>
                        <select name="alat_id" id="alatSelect" class="form-select" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach ($alats as $alat)
                                <option value="{{ $alat->id }}" data-kategori="{{ $alat->kategori_id }}"
                                    data-gambar="{{ $alat->gambar_alat ? asset('storage/' . $alat->gambar_alat) : '' }}"
                                    data-deskripsi="{{ $alat->deskripsi }}" data-harga="{{ $alat->harga }}"
                                    {{ request('alat') == $alat->id ? 'selected' : '' }}>
                                    {{ $alat->nama_alat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Pinjam --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah yang Dipinjam</label>
                        <input type="number" name="jumlah" id="jumlahPinjam" class="form-control" min="1"
                            value="1" required>
                    </div>

                    {{-- Preview Gambar --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar Alat</label>
                        <div class="border p-2 rounded text-center">
                            <img id="previewGambar" src="" width="150" class="img-thumbnail d-none">
                            <p id="noGambar" class="text-muted">
                                Pilih alat untuk melihat gambar
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Alat</label>
                        <textarea id="deskripsiAlat" class="form-control" rows="3" readonly></textarea>
                    </div>

                    {{-- Harga Per Hari --}}
                    <div class="mb-3">
                        <label class="form-label">Harga Per Hari</label>
                        <input type="text" id="hargaPerHari" class="form-control" readonly>
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggalPinjam" class="form-control" required>
                    </div>

                    {{-- Tanggal Jatuh Tempo --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" id="tanggalJatuhTempo" class="form-control"
                            required>
                    </div>

                    {{-- Total Harga --}}
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <input type="text" id="totalHarga" class="form-control" readonly>
                    </div>

                    {{-- Upload Foto Peminjam --}}
                    <div class="mb-3">
                        <label class="form-label">Upload Foto Anda (Identitas)</label>
                        <input type="file" name="foto_peminjam" class="form-control" required>
                        <img id="previewFotoPeminjam" class="img-thumbnail mt-2 d-none" width="150">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Ajukan Peminjaman
                        </button>
                        <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggalPinjam').min = today;
        document.getElementById('tanggalJatuhTempo').min = today;

        // Saat halaman dimuat, jika ada alat dari dashboard -> langsung tampilkan preview
        window.addEventListener('load', function() {
            const alatSelect = document.getElementById('alatSelect');
            if (alatSelect.value) {
                triggerPreview();
            }
        });

        // Filter kategori -> alat
        document.getElementById('kategoriSelect').addEventListener('change', function() {
            const kategoriId = this.value;
            const alatSelect = document.getElementById('alatSelect');

            Array.from(alatSelect.options).forEach(opt => {
                if (opt.value === "") return;
                opt.style.display =
                    opt.getAttribute('data-kategori') === kategoriId ? "block" : "none";
            });

            hitungTotal();
        });

        // Saat alat dipilih
        document.getElementById('alatSelect').addEventListener('change', triggerPreview);

        function triggerPreview() {
            const alatSelect = document.getElementById('alatSelect');
            const selected = alatSelect.options[alatSelect.selectedIndex];

            const gambar = selected.getAttribute('data-gambar');
            const deskripsi = selected.getAttribute('data-deskripsi');
            const harga = selected.getAttribute('data-harga');

            const img = document.getElementById('previewGambar');
            const noGambar = document.getElementById('noGambar');
            const descBox = document.getElementById('deskripsiAlat');
            const hargaBox = document.getElementById('hargaPerHari');

            descBox.value = deskripsi || '';
            hargaBox.value = harga ? "Rp " + Number(harga).toLocaleString('id-ID') : "";

            if (gambar) {
                img.src = gambar;
                img.classList.remove('d-none');
                noGambar.classList.add('d-none');
            } else {
                img.classList.add('d-none');
                noGambar.classList.remove('d-none');
            }

            hitungTotal();
        }

        // Validasi tanggal
        document.getElementById('tanggalJatuhTempo').addEventListener('change', function() {
            const pinjam = document.getElementById('tanggalPinjam').value;

            if (!pinjam) {
                alert('Pilih tanggal pinjam terlebih dahulu!');
                this.value = "";
                return;
            }

            if (this.value <= pinjam) {
                alert('Tanggal jatuh tempo harus lebih besar dari tanggal pinjam!');
                this.value = "";
            }

            hitungTotal();
        });

        document.getElementById('tanggalPinjam').addEventListener('change', function() {
            document.getElementById('tanggalJatuhTempo').value = "";
            hitungTotal();
        });

        document.getElementById('jumlahPinjam').addEventListener('input', hitungTotal);

        // Preview foto peminjam
        document.querySelector('input[name="foto_peminjam"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewFotoPeminjam');

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            }
        });

        // Hitung total harga
        function hitungTotal() {
            const alatSelect = document.getElementById('alatSelect');
            const selected = alatSelect.options[alatSelect.selectedIndex];

            const harga = selected ? parseInt(selected.getAttribute('data-harga') || 0) : 0;
            const pinjam = document.getElementById('tanggalPinjam').value;
            const jatuhTempo = document.getElementById('tanggalJatuhTempo').value;
            const jumlah = parseInt(document.getElementById('jumlahPinjam').value || 1);

            if (!pinjam || !jatuhTempo || harga === 0 || jumlah <= 0) {
                document.getElementById('totalHarga').value = "";
                return;
            }

            const t1 = new Date(pinjam);
            const t2 = new Date(jatuhTempo);
            let diffTime = t2 - t1;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            diffDays = diffDays < 1 ? 1 : diffDays;

            const total = diffDays * harga * jumlah;

            document.getElementById('totalHarga').value =
                jumlah + " × " + diffDays + " hari × Rp " +
                harga.toLocaleString('id-ID') +
                " = Rp " + total.toLocaleString('id-ID');
        }
    </script>

@endsection
