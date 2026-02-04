<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }

        .navbar-custom {
            background-color: #020617;
        }

        footer {
            background-color: #020617;
            color: #cbd5e1;
            font-size: 14px;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom px-4 px-lg-5">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">
            Peminjaman Alat
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm ms-lg-2"
                       href="{{ route('register') }}">
                        Register
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- CONTENT --}}
<main class="container py-5" style="min-height: 80vh;">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="py-3 text-center">
    <p class="mb-0">
        © {{ date('Y') }} Sistem Peminjaman Alat
    </p>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
