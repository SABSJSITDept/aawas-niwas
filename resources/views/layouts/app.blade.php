<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Custom CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS (Agar required ho) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #FFFBF0;
            margin: 0;
            padding-top: 0;
            overflow-x: hidden;
        }

        .navbar-border-wrapper {
            border-top: 6px double #C28E3E;
            border-bottom: 6px double #C28E3E;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .navbar {
            padding: 0 !important;
            background: linear-gradient(rgba(255, 251, 240, 0.95), rgba(255, 251, 240, 0.95)), url("{{ asset('images/Header01.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: black;
            text-shadow: 1px 1px 2px #000;
        }

        .brand-logo {
            max-height: 80px;
            height: auto;
        }

        .brand-text {
            font-weight: 800;
            color: rgba(174, 34, 32, 0.85);
            text-shadow: 1px 1px 2px #000;
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-line-1 {
            font-size: 26px;
        }

        .brand-line-2 {
            font-size: 22px;
        }

        .navbar-nav .nav-link {
            font-size: 16px;
            font-weight: 600;
            background-color: #4a3f2d;
            color: #fff !important;
            border-color: #3e2f1c;
            transition: 0.3s ease-in-out;
            text-align: center;
            padding: 8px 16px;
            min-width: 130px;
            border-radius: 10px !important;
            display: inline-block;
        }

        .navbar-nav .nav-link:hover {
            background-color: #2f2415 !important;
            color: #fff !important;
            transform: translateY(-1px);
        }

        @media (max-width: 767px) {
            .brand-logo {
                max-height: 70px;
            }

            .brand-text {
                font-size: 16px;
            }

            .brand-line-1 {
                font-size: 18px;
            }

            .brand-line-2 {
                font-size: 14px;
            }

            .navbar-nav {
                text-align: center;
            }

            .navbar-nav .nav-link {
                margin: 5px 0;
                border-radius: 50rem !important;
                padding: 8px 16px;
            }

            .top-header-wrapper {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .logo-and-title {
                display: flex;
                align-items: center;
                gap: 10px;
            }
        }

        @media (min-width: 768px) {
            .brand-text {
                flex-direction: row;
                gap: 6px;
                align-items: center;
            }

            .brand-line {
                display: inline;
            }

            .navbar-nav .nav-link {
                min-width: 120px;
                padding: 8px 16px;
                border-radius: 8px !important;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Main Content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


