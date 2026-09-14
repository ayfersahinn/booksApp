<nav
    class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">
            <i class="bi bi-book-half me-2"></i>KitapDiyarı
        </a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('mainpage')}}">Ana Sayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('community')}}">Topluluk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('weekly-book')}}">Haftanın Kitabı</a>
                </li>

            </ul>
            <div class="d-flex gap-2">
                <a href=""
                    class="btn btn-outline-light btn-sm">
                    Giriş Yap
                </a>
                <a href="{{route('register')}}" class="btn btn-primary btn-sm">
                    Kayıt Ol
                </a>
            </div>
        </div>
    </div>
</nav>