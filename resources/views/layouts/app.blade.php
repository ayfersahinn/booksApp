<!doctype html>
<html lang="tr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KitapDiyarı - Kitap Keşfet, Değerlendir, Kaydet</title>
    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        rel="stylesheet" />
    <style>
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 80px 0;
        }

        .book-card {
            transition:
                transform 0.2s ease,
                shadow 0.2s ease;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .book-cover {
            height: 260px;
            object-fit: cover;
            border-radius: 4px;
        }

        .category-badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rating-stars {
            color: #ffc107;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    @include('partials.navbar')

    @yield('content')
    <!-- Footer -->
    @include('partials.footer')


    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>