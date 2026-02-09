<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1e3a8a;
            --secondary: #2563eb;
            --accent: #38bdf8;
            --bg: #f1f5f9;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .navbar-custom {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff;
            font-weight: 500;
        }

        /* CONTENT WRAPPER */
        .page-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 16px;
        }

        /* HERO */
        .hero {
            min-height: calc(100vh - 140px);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background:
                radial-gradient(circle at top, #60a5fa, transparent),
                linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            padding: 40px 20px;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.25rem;
            opacity: .95;
            max-width: 600px;
            margin: 20px auto 30px;
        }

        /* BUTTON */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            border: none;
            color: #fff;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: .3s;
            box-shadow: 0 10px 25px rgba(37, 99, 235, .35);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, .45);
        }

        /* AUTH CARD */
        .card-auth {
            background: #fff;
            border-radius: 20px;
            padding: 45px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 60px rgba(30, 58, 138, .15);
        }

        .card-auth h2 {
            font-weight: 700;
            color: var(--primary);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .auth-footer a {
            color: var(--secondary);
            font-weight: 600;
            text-decoration: none;
        }

        /* FOOTER */
        footer {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: #fff;
            text-align: center;
            padding: 16px 0;
            font-size: 14px;
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom px-4 px-lg-5">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/">Peminjaman Alat</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav gap-3 align-items-center">
                    <li><a class="nav-link" href="/">Home</a></li>
                    <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li>
                        <a class="btn btn-outline-light btn-sm" href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer>
        © {{ date('Y') }} Sistem Peminjaman Alat
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
