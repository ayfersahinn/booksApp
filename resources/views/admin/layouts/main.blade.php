<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Kitap Yönetim Sistemi</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #334155;
        }

        /* Sidebar Stilleri */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        #sidebar .sidebar-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }

        #sidebar ul.nav-pills .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        #sidebar ul.nav-pills .nav-link:hover {
            color: #0d6efd;
            background-color: #f1f5f9;
        }

        #sidebar ul.nav-pills .nav-link.active {
            color: #0d6efd;
            background-color: #e0e7ff;
            font-weight: 600;
        }

        #sidebar ul.nav-pills .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        /* Dashboard Kartları & Bileşenler */
        .stat-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            background-color: #ffffff;
            transition: transform 0.2s ease, shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .icon-shape {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .avatar-img {
            width: 38px;
            height: 38px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="d-flex flex-column justify-content-between p-3">
            <div>
                <!-- Brand Logo / Title -->
                <div class="sidebar-header d-flex align-items-center gap-2 mb-3 px-2">
                    <i class="bi bi-book-half text-primary fs-3"></i>
                    <span class="fs-5 fw-bold text-dark">BookAdmin</span>
                </div>

                <!-- Menü Linkleri -->
                <ul class="nav nav-pills flex-column gap-1">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('book-index')}}" class="nav-link">
                            <i class="bi bi-journal-bookmark"></i> Kitaplar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-people"></i> Kullanıcılar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-tags"></i> Kategoriler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-building"></i> Yayınevleri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-star"></i> Haftanın Kitabı
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-chat-left-text"></i> Yorumlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-reply-all"></i> Yanıtlar
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="bi bi-gear"></i> Ayarlar
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Sidebar Alt Alanı (Çıkış Yap / Profil Özeti) -->
            <div class="border-top pt-3">
                <a href="#" class="d-flex align-items-center text-decoration-none text-danger fw-semibold px-2">
                    <i class="bi bi-box-arrow-right me-2 fs-5"></i> Çıkış Yap
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="w-100">
            <!-- TOP NAVBAR -->
            <nav class="navbar navbar-expand navbar-custom px-4 py-2 justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">Dashboard</h5>

                <!-- Sağ Üst Profil Menüsü -->
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light position-relative rounded-circle border-0 p-2">
                        <i class="bi bi-bell text-secondary fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">Yeni Bildirim</span>
                        </span>
                    </button>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D6EFD&color=fff" alt="Admin Avatar" class="rounded-circle avatar-img me-2">
                            <div class="d-none d-md-block text-start">
                                <div class="fw-bold fs-7 lh-1">Yönetici</div>
                                <small class="text-muted fs-8">admin@site.com</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profilim</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Hesap Ayarları</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- DASHBOARD İÇERİK -->
            <main class="p-4">

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>