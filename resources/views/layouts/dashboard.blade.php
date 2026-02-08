<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #020617, #0f172a);
            color: #fff;
            padding: 30px 20px;
        }

        .sidebar h2 {
            font-size: 14px;
            margin-bottom: 30px;
            text-transform: uppercase;
            color: #93c5fd;
            text-align: center;
            letter-spacing: 1px;
        }

        .sidebar a,
        .sidebar .dropdown-toggle {
            display: block;
            color: #e5e7eb;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 8px;
            font-size: 14px;
            transition: all .2s ease;
            width: 100%;
            text-align: left;
        }

        .sidebar a:hover,
        .sidebar .dropdown-toggle:hover {
            background: #1e293b;
            padding-left: 22px;
        }

        .sidebar .dropdown-menu {
            background: #1e293b;
            border: none;
            padding: 0;
        }

        .sidebar .dropdown-menu a {
            padding: 10px 20px;
            color: #e5e7eb;
        }

        .sidebar .dropdown-menu a:hover {
            background: #334155;
            padding-left: 25px;
        }

        /* CONTENT */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        header {
            background: #0f172a;
            padding: 16px 30px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        main {
            padding: 30px;
            flex: 1;
        }

        .card-custom {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        }

        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <h2>{{ ucfirst(auth()->user()->role) }}</h2>
        <a href="/{{ auth()->user()->role }}/dashboard">Dashboard</a>

        @if (auth()->user()->role === 'admin')
            <a href="/admin/users">Manajemen User</a>
            <a href="/admin/kategori">Kategori Alat</a>
            <a href="/admin/alat">Data Alat</a>
            <a href="/admin/peminjaman">Peminjaman</a>
            <a href="/admin/pengembalian">Pengembalian</a>
            <a href="{{ route('admin.pengembalian.rekap') }}">Rekap Pengembalian</a>
            <a href="/admin/log-aktivitas">Log Aktivitas</a>
        @endif

        @if (auth()->user()->role === 'petugas')
            <a href="{{ route('petugas.persetujuan') }}">Persetujuan Peminjaman</a>
            <a href="{{ route('petugas.pemantauan') }}">Monitoring Pengembalian</a>
            <a href="{{ route('petugas.pemeriksaan') }}">Pemeriksaan Pengembalian</a>

            {{-- DROPDOWN LAPORAN --}}
            <div class="dropdown">
                <button class="dropdown-toggle btn btn-dark w-100" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Laporan Peminjaman
                </button>
                <ul class="dropdown-menu w-100">
                    <li><a class="dropdown-item" href="{{ route('petugas.laporan.user') }}"> Per User</a></li>
                    <li><a class="dropdown-item" href="{{ route('petugas.laporan.bulan') }}"> Per Bulan</a></li>
                </ul>
            </div>
        @endif

        @if (auth()->user()->role === 'peminjam')
            <a href="{{ route('peminjam.pengajuan.create') }}">Ajukan Peminjaman</a>
            <a href="{{ route('peminjam.riwayat') }}">Riwayat Peminjaman</a>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="content">
        <header>
            <h5 class="mb-0">@yield('title')</h5>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-danger">Logout</button>
            </form>
        </header>
        <main>
            <div class="card-custom">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>