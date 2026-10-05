@extends('web.layouts.app')
@section('content')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
    }

    .nav-tabs .nav-link.active {
        font-weight: 600;
    }

    .nav-tabs .nav-link.read.active {
        color: #198754;
    }

    .nav-tabs .nav-link.reading.active {
        color: #ffc107;
    }

    .nav-tabs .nav-link.want-to-read.active {
        color: #0d6efd;
    }
</style>
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
                    <button class="nav-link text-start d-flex align-items-center mb-1"
                        id="tab-reviews-link"
                        data-bs-toggle="pill"
                        data-bs-target="#tab-reviews"
                        type="button"
                        role="tab">
                        <i class="bi bi-chat-left-text me-2 fs-5"></i>
                        Kitap Yorumlarım
                    </button>
                    <button class="nav-link text-start d-flex align-items-center mb-1"
                        id="tab-replies-link"
                        data-bs-toggle="pill"
                        data-bs-target="#tab-replies"
                        type="button"
                        role="tab">

                        <i class="bi bi-reply me-2 fs-5"></i>
                        Yanıtlarım

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
                            @foreach($favoriteBooks as $favoriteBook)
                            <div class="col">
                                <div class="card h-100 border shadow-sm">
                                    <img src="https://via.placeholder.com/150x200" class="card-img-top" alt="Kitap Kapak" style="height: 160px; object-fit: cover;">
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-1 text-truncate">
                                            {{$favoriteBook->title}}
                                        </h6>
                                        <p class="card-text small text-muted mb-2">{{$favoriteBook->author}}</p>
                                        <a href="{{route('book-detail',$favoriteBook->slug)}}" class="btn btn-sm btn-outline-primary w-100">İncele</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach


                        </div>

                    </div>
                </div>

                <!-- 3. SEKME: Okuma Listesi -->
                <!-- 3. SEKME: Okuma Listesi -->

                <div class="tab-pane fade" id="tab-reading" role="tabpanel">
                    <div class="card shadow-sm content-card p-4 bg-white">


                        <h4 class="fw-bold mb-3">Okuma Listem</h4>

                        <!-- Okuma durumu filtreleri -->
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a class="nav-link read active" href="#read" data-bs-toggle="tab">
                                    Okudum
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link reading" href="#reading" data-bs-toggle="tab">
                                    Okuyorum
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link want-to-read" href="#want-to-read" data-bs-toggle="tab">
                                    Okuyacağım
                                </a>
                            </li>
                        </ul>

                        <!-- Okuma durumlarının içerikleri -->
                        <div class="tab-content">

                            <!-- Okudum -->
                            <div class="tab-pane fade show active" id="read">
                                <ul class="list-group list-group-flush">
                                    @foreach($listItems->where('pivot.status', 'read') as $listItem)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <h6>
                                                <a href="{{ route('book-detail', $listItem->slug) }}"
                                                    class="mb-0 fw-bold text-dark text-decoration-none">
                                                    {{ $listItem->title }}
                                                </a>
                                            </h6>

                                            <small class="text-muted">{{ $listItem->author }}</small>
                                        </div>

                                        <span class="badge bg-success rounded-pill">
                                            Okudum
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Okuyorum -->
                            <div class="tab-pane fade" id="reading">
                                <ul class="list-group list-group-flush">
                                    @foreach($listItems->where('pivot.status', 'reading') as $listItem)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <h6>
                                                <a href="{{ route('book-detail', $listItem->slug) }}"
                                                    class="mb-0 fw-bold text-dark text-decoration-none">
                                                    {{ $listItem->title }}
                                                </a>
                                            </h6>

                                            <small class="text-muted">{{ $listItem->author }}</small>
                                        </div>

                                        <span class="badge bg-warning text-dark rounded-pill">
                                            Okuyorum
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Okuyacağım -->
                            <div class="tab-pane fade" id="want-to-read">
                                <ul class="list-group list-group-flush">
                                    @foreach($listItems->where('pivot.status', 'want-to-read') as $listItem)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <h6>
                                                <a href="{{ route('book-detail', $listItem->slug) }}"
                                                    class="mb-0 fw-bold text-dark text-decoration-none">
                                                    {{ $listItem->title }}
                                                </a>
                                            </h6>

                                            <small class="text-muted">{{ $listItem->author }}</small>
                                        </div>

                                        <span class="badge bg-primary rounded-pill">
                                            Okuyacağım
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>


                </div>

                <!-- 4. SEKME: Yorumlar -->
                <div class="tab-pane fade" id="tab-reviews" role="tabpanel">
                    <div class="card shadow-sm content-card p-4 bg-white">
                        <h4 class="fw-bold mb-4">Kitap Yorumlarım</h4>

                        @foreach($reviews as $review)
                        <div class="card border-0 shadow-sm p-3 m-3">
                            <div class="d-flex gap-3">
                                <img src="https://via.placeholder.com/45"
                                    class="rounded-circle "
                                    alt="book">

                                <div class="w-100">

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0">
                                            {{ $review->name }}
                                        </h6>

                                        <div class="d-flex align-items-center gap-2">

                                            <div class="rating-stars small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <=$review->pivot->rating)
                                                    <i class="bi bi-star-fill"></i>
                                                    @else
                                                    <i class="bi bi-star"></i>
                                                    @endif
                                                    @endfor
                                            </div>



                                        </div>
                                    </div>

                                    <small class="text-muted d-block mb-2">
                                        {{ $review->pivot->review_updated_at
        ? \Carbon\Carbon::parse($review->pivot->review_updated_at)->diffForHumans()
        : '' }} inceledi
                                    </small>

                                    <p class="text-secondary small mb-2">
                                        {{ $review->pivot->review }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if($review->pivot->has_spoiler)
                                        <span class="badge bg-warning text-dark mb-2">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Spoiler içerir
                                        </span>
                                        @endif
                                        <a href="{{route('book-detail',$review->slug)}}" class="btn btn-sm btn-primary ms-auto">Kitaba git</a>

                                    </div>


                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
                <!-- 5. SEKME: Yanıtlarım -->
                <!-- Yanıtlarım -->
                <div class="tab-pane fade" id="tab-replies" role="tabpanel">

                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Yanıtlarım</h5>
                        <p class="text-muted small mb-0">
                            Toplulukta yaptığın yanıtları burada görebilirsin.
                        </p>
                    </div>

                    <div class="d-flex flex-column gap-3">

                        <!-- Yanıtlar -->
                        @foreach($responses as $response)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <small class="text-muted">
                                            {{$response->userBook->user->name}}'nin incelemesine yanıt verdin
                                        </small>
                                        <h6 class="fw-bold mb-0 mt-1">
                                            {{$response->userBook->book->title}}
                                        </h6>
                                    </div>
                                    <small class="text-muted">
                                        {{ $response->created_at->diffForHumans()}}
                                    </small>
                                </div>

                                <!-- Orijinal yorum -->
                                <div class="bg-light rounded p-3 mb-3">
                                    <small class="text-muted d-block mb-1">
                                        {{$response->userBook->user->name}}'nin yorumu
                                    </small>

                                    <p class="mb-0 small">
                                        {{$response->userBook->review}}
                                    </p>
                                </div>

                                <!-- Kullanıcının cevabı -->
                                <div class="border-start border-primary border-3 ps-3">
                                    <small class="text-muted d-block mb-1">Senin yorumun
                                    </small>

                                    <p class="mb-0">
                                        {{$response->content}}

                                    </p>
                                </div>

                                <div class="text-end mt-3">
                                    <a href="{{route('community',$response->userBook->id)}}#review-{{ $response->userBook->id }}" class="btn btn-sm btn-outline-primary">
                                        İncelemeye Git
                                    </a>
                                </div>

                            </div>
                        </div>
                        @endforeach


                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;

        if (hash) {
            const tab = document.querySelector(`[data-bs-target="${hash}"]`);

            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        }
    });
</script>
@endsection