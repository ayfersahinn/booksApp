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



        .review-card {
            transition: border-color 0.2s ease;
        }

        .review-card:hover {
            border-color: #0d6efd !important;
        }

        .book-thumb {
            width: 70px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        .rating-stars {
            color: #ffc107;
        }

        .featured-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            border-radius: 16px;
        }

        .hero-book-cover {
            max-width: 240px;
            height: 350px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .book-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
        }

        .book-cover {
            height: 240px;
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

        .main-book-cover {
            max-width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .progress-bar-star {
            height: 8px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
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