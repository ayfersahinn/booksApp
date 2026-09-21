@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row g-4">

        <!-- Sol Kolon: Profil Özet Kartı ve Yan Menü -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm profile-card p-4 bg-white text-center mb-4">
                <div class="avatar-wrapper bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-person-fill display-4"></i>
                </div>
                <!-- Kullanıcı Adı & E-posta -->
                <h5 class="fw-bold mb-1">{{Auth::user()->name}}</h5>
                <p class="text-muted small mb-3">{{Auth::user()->email}}</p>
                <span class="badge bg-primary-subtle text-primary pill px-3 py-2 rounded-pill align-self-center">Kitap Kurdu</span>
            </div>

            <!-- Sekme Navigasyonu -->
            <div class="card shadow-sm profile-card p-3 bg-white">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                    <button class="nav-link active text-start d-flex align-items-center mb-1" id="tab-profile-link" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
                        <i class="bi bi-person-gear me-2 fs-5"></i> Profil Bilgileri
                    </button>
                    <button class="nav-link text-start d-flex align-items-center mb-1" id="tab-favorites-link" data-bs-toggle="pill" data-bs-target="#tab-favorites" type="button" role="tab">
                        <i class="bi bi-heart me-2 fs-5"></i> Favori Kitaplarım
                    </button>
                    <button class="nav-link text-start d-flex align-items-center mb-1" id="tab-reading-link" data-bs-toggle="pill" data-bs-target="#tab-reading" type="button" role="tab">
                        <i class="bi bi-bookmark-check me-2 fs-5"></i> Okuma Listem
                    </button>
                    <hr class="my-2">
                    <form action="{{route('cikis')}}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger text-start d-flex align-items-center w-100 border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-2 fs-5"></i> Çıkış Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ Kolon: Sekme İçerikleri -->
        <div class="col-12 col-lg-8">
            <div class="tab-content" id="v-pills-tabContent">

                <!-- 1. SEKME: Profil Bilgileri Güncelleme -->
                <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                    <div class="card shadow-sm content-card p-4 bg-white">
                        <h4 class="fw-bold mb-4">Profil Bilgilerini Güncelle</h4>

                        <form action="{{route('update-profile')}}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label small fw-bold">Ad Soyad</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0 ps-0" id="name" name="name" value="{{Auth::user()->name}}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-bold">E-posta Adresi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" value="{{Auth::user()->email}}" required>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                        <hr class="my-4">
                        <form action="{{route('change-password')}}" method="post">
                            @csrf
                            <h6 class="fw-bold mb-3">Şifre Değiştir </h6>

                            <div class="mb-3">
                                <label for="current_password" class="form-label small fw-bold">Mevcut Şifre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control bg-light border-start-0 ps-0" id="current_password" name="current_password" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label small fw-bold">Yeni Şifre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control bg-light border-start-0 ps-0" id="new_password" name="new_password" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                            @if (session('success'))
                            <div class="alert alert-success my-2">
                                {{ session('success') }}
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="my-2">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- 2. SEKME: Favori Kitaplarım -->
                <div class="tab-pane fade" id="tab-favorites" role="tabpanel">
                    <div class="card shadow-sm content-card p-4 bg-white">
                        <h4 class="fw-bold mb-4">Favori Kitaplarım</h4>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                            <!-- Örnek Kitap Kartı 1 -->
                            <div class="col">
                                <div class="card h-100 border shadow-sm">
                                    <img src="https://via.placeholder.com/150x200" class="card-img-top" alt="Kitap Kapak" style="height: 160px; object-fit: cover;">
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-1 text-truncate">Simyacı</h6>
                                        <p class="card-text small text-muted mb-2">Paulo Coelho</p>
                                        <a href="" class="btn btn-sm btn-outline-primary w-100">İncele</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Örnek Kitap Kartı 2 -->
                            <div class="col">
                                <div class="card h-100 border shadow-sm">
                                    <img src="https://via.placeholder.com/150x200" class="card-img-top" alt="Kitap Kapak" style="height: 160px; object-fit: cover;">
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-1 text-truncate">1984</h6>
                                        <p class="card-text small text-muted mb-2">George Orwell</p>
                                        <a href="" class="btn btn-sm btn-outline-primary w-100">İncele</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 3. SEKME: Okuma Listesi -->
                <div class="tab-pane fade" id="tab-reading" role="tabpanel">
                    <div class="card shadow-sm content-card p-4 bg-white">
                        <h4 class="fw-bold mb-4">Okuma Listem</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="mb-0 fw-bold">Suç ve Ceza</h6>
                                    <small class="text-muted">Fyodor Dostoyevski</small>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill">Okunuyor</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="mb-0 fw-bold">Dönüşüm</h6>
                                    <small class="text-muted">Franz Kafka</small>
                                </div>
                                <span class="badge bg-success rounded-pill">Tamamlandı</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection