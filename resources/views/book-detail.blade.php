@extends('layouts.app')
@section('content')
<!-- Breadcrumb -->
<div class="bg-white border-bottom py-2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('mainpage') }}" class="text-decoration-none">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">{{$book->category->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{$book->title}} </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<main class="container my-5">

    <!-- Kitap Üst Bilgi Kartı -->
    <div class="card border-0 shadow-sm p-4 mb-5">
        <div class="row g-4">

            <!-- Sol: Kapak Görseli ve Durum Butonları -->
            <div class="col-md-4 col-lg-3 text-center">
                <img src="https://via.placeholder.com/260x380" class="main-book-cover mb-3" alt="Kitap Kapak">

                <!-- Okuma Durumu Ekleme Dropdown -->
                <div class="d-grid gap-2">
                    <div class="btn-group">
                        <button class="btn btn-primary fw-bold" type="button">
                            @if($status === 'read')
                            <i class="bi bi-check-circle me-1"></i> Okudum
                            @elseif($status === 'reading')
                            <i class="bi bi-clock me-1"></i> Okuyorum
                            @elseif($status === 'want-to-read')
                            <i class="bi bi-bookmark-plus me-1"></i> Okuyacağım
                            @else
                            <i class="bi bi-bookmark-plus me-1"></i> Listeye Ekle
                            @endif
                        </button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Listeyi Değiştir</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end w-100">
                            <li>
                                <form action="{{route('book-status', $book->id)}}" method="post">
                                    @csrf
                                    <input type="hidden" name="status" value="read">
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-check-circle text-success me-2"></i>Okudum
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('book-status', $book->id)}}" method="post">
                                    @csrf

                                    <input type="hidden" name="status" value="reading">
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-clock text-warning me-2"></i>Okuyorum
                                    </button>
                                </form>

                            </li>
                            <li>
                                <form action="{{route('book-status', $book->id)}}" method="post">
                                    @csrf

                                    <input type="hidden" name="status" value="want-to-read">
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-bookmark text-primary me-2"></i>Okuyacağım
                                    </button>
                                </form>

                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{route('removeFromList',$book->id)}}" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-x-circle me-2"></i>Listeden Çıkar</button>

                                </form>
                            </li>
                        </ul>
                    </div>
                    <form action="{{route('book-favorite', $book->id)}}" method="post">
                        @csrf
                        @if($isFavorite)
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-heart-fill me-1"></i> Favorilerden Çıkar
                        </button>
                        @else
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-heart me-1"></i> Favorilere Ekle
                        </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Sağ: Kitap Detayları -->
            <div class="col-md-8 col-lg-9">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle"> {{$book->category->name}} </span>

                </div>

                <h1 class="fw-bold mb-1"> {{$book->title}}</h1>
                <p class="fs-5 text-muted mb-3">Yazar: <a href="#" class="text-decoration-none fw-bold"> {{$book->author}} </a></p>

                <!-- Derecelendirme Özeti -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="rating-stars me-2 fs-5">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <span class="fw-bold fs-5">4.6</span>
                    </div>
                    <span class="text-muted">|</span>
                    <span class="text-muted"><i class="bi bi-chat-text me-1"></i> 128 Değerlendirme</span>
                    <span class="text-muted">|</span>
                    <span class="text-muted"><i class="bi bi-bookmark me-1"></i> 450 Okuma Listesinde</span>
                </div>

                <!-- Yayın / Metadatas -->
                <div class="row g-3 bg-light p-3 rounded mb-4 text-center text-sm-start">
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Yayınevi</small>
                        <span class="fw-bold small"> {{$book->publisher->name}} </span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Yayın Tarihi</small>
                        <span class="fw-bold small"> {{$book->published_year}} </span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Sayfa Sayısı</small>
                        <span class="fw-bold small">{{$book->pages}}</span>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">ISBN</small>
                        <span class="fw-bold small">{{$book->isbn}}</span>
                    </div>
                </div>

                <!-- Kitap Özeti -->
                <h5 class="fw-bold mb-2">Kitap Hakkında</h5>
                <p class="text-secondary leading-relaxed mb-4">
                    {{$book->description}}
                </p>
            </div>
        </div>
    </div>

    <!-- Değerlendirmeler & Yorumlar Bölümü -->
    <div class="row g-4">

        <!-- Sol: Yorum Formu ve Yorum Listesi -->
        <div class="col-lg-8">

            <!-- Yorum Ekleme Formu -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square text-primary me-2"></i>Bu Kitabı Değerlendir</h5>
                <form action="#" method="POST">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Puanınız</label>
                            <select class="form-select">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5) - Mükemmel</option>
                                <option value="4">⭐⭐⭐⭐ (4/5) - Çok İyi</option>
                                <option value="3">⭐⭐⭐ (3/5) - Orta</option>
                                <option value="2">⭐⭐ (2/5) - Zayıf</option>
                                <option value="1">⭐ (1/5) - Kötü</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="hasSpoiler">
                                <label class="form-check-label small text-muted" for="hasSpoiler">
                                    Yorumum spoiler (sürpriz bozan) içeriyor.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" placeholder="Kitap hakkındaki düşüncelerinizi paylaşın..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Yorumu Gönder</button>
                </form>
            </div>

            <!-- Yorumlar Akışı -->
            <h5 class="fw-bold mb-3">Okur Yorumları (128)</h5>
            <div class="d-flex flex-column gap-3">

                <!-- Yorum 1 -->
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex gap-3">
                        <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="User">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0">Ayşe Yılmaz</h6>
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mb-2">2 gün önce inceledi</small>
                            <p class="text-secondary small mb-2">
                                Kurgusu harikaydı, özellikle son bölümlerdeki ters köşeleri hiç beklemiyordum. Bilim kurgu severlerin kesinlikle kaçırmaması gereken bir kitap.
                            </p>
                            <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">
                                <i class="bi bi-hand-thumbs-up me-1"></i> Faydalı (12)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Yorum 2 -->
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex gap-3">
                        <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="User">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0">Caner Şahin</h6>
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mb-2">1 hafta önce inceledi</small>
                            <p class="text-secondary small mb-2">
                                Başlangıcı biraz yavaş olsa da ikinci yarıdan itibaren temposu çok yükseldi. Yazarın dünyası gayet tutarlı kurgulanmış.
                            </p>
                            <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">
                                <i class="bi bi-hand-thumbs-up me-1"></i> Faydalı (5)
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sağ: Puan Dağılım İstatistikleri -->
        <aside class="col-lg-4">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Puan Dağılımı</h6>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <small class="text-muted" style="width: 25px;">5★</small>
                    <div class="progress flex-grow-1 progress-bar-star">
                        <div class="progress-bar bg-warning" style="width: 75%"></div>
                    </div>
                    <small class="text-muted" style="width: 35px;">%75</small>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <small class="text-muted" style="width: 25px;">4★</small>
                    <div class="progress flex-grow-1 progress-bar-star">
                        <div class="progress-bar bg-warning" style="width: 15%"></div>
                    </div>
                    <small class="text-muted" style="width: 35px;">%15</small>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <small class="text-muted" style="width: 25px;">3★</small>
                    <div class="progress flex-grow-1 progress-bar-star">
                        <div class="progress-bar bg-warning" style="width: 6%"></div>
                    </div>
                    <small class="text-muted" style="width: 35px;">%6</small>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <small class="text-muted" style="width: 25px;">2★</small>
                    <div class="progress flex-grow-1 progress-bar-star">
                        <div class="progress-bar bg-warning" style="width: 3%"></div>
                    </div>
                    <small class="text-muted" style="width: 35px;">%3</small>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <small class="text-muted" style="width: 25px;">1★</small>
                    <div class="progress flex-grow-1 progress-bar-star">
                        <div class="progress-bar bg-warning" style="width: 1%"></div>
                    </div>
                    <small class="text-muted" style="width: 35px;">%1</small>
                </div>
            </div>
        </aside>

    </div>
</main>
@endsection