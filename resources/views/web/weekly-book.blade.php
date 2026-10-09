@extends('web.layouts.app')
@section('content')

<!-- Main Content -->
<main class="container my-5">

    <!-- Header / Banner Badge -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">
                <i class="bi bi-trophy-fill me-1"></i> Editörün Seçimi
            </span>
            <h1 class="h2 fw-bold mb-0">Haftanın Öne Çıkan Kitabı</h1>
        </div>
        <span class="text-muted small d-none d-md-inline"> {{ $recommendedBook->start_date->format('d-m-Y') }}
            /
            {{ $recommendedBook->end_date->format('d-m-Y') }} Haftası</span>
    </div>

    <!-- Haftanın Kitabı Öne Çıkan Kartı (Hero Section) -->
    <div class="featured-hero p-4 p-md-5 mb-5 shadow-lg position-relative overflow-hidden">
        <div class="row align-items-center g-4">

            <!-- Kitap Kapak Görseli -->
            <div class="col-md-4 text-center">
                <!-- <img src="https://via.placeholder.com/240x350" alt="Haftanın Kitabı"> -->
                <img src="{{ asset('storage/' . $recommendedBook->book->cover_image) }}"
                    class="hero-book-cover img-fluid"
                    alt="{{ $recommendedBook->book->title }}">
            </div>

            <!-- Kitap Detayları & Açıklama -->
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary">{{$recommendedBook->book->category->name}}</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Haftanın En Çok Okunanı</span>
                </div>

                <h2 class="display-6 fw-bold mb-2"><a href="{{route('book-detail',$recommendedBook->book->slug)}}" class="text-decoration-none text-white">{{$recommendedBook->book->title}}</a></h2>
                <h3 class="h5 text-white-50 mb-3">Yazar: <strong>{{$recommendedBook->book->author}}</strong> | Yayınevi: <strong>{{$recommendedBook->book->publisher->name}}</strong></h3>

                <!-- Derecelendirme & İstatistikler -->
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rating-stars me-2 fs-5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($averageRating>= $i)
                                <i class="bi bi-star-fill"></i>
                                @elseif($averageRating >= $i - 0.5)
                                <i class="bi bi-star-half"></i>
                                @else
                                <i class="bi bi-star"></i>
                                @endif
                                @endfor
                        </div>
                        <span class="fw-bold fs-5">
                            {{ number_format($averageRating, 1) }}
                        </span>
                        <span class="text-white-50 ms-1"> ({{ $reviewCount }} Değerlendirme)</span>
                    </div>
                </div>

                <!-- Kitap Özeti -->
                <p class="lead text-light mb-4" style="font-size: 1rem; line-height: 1.7;">
                    {{$recommendedBook->description}}
                </p>

                <!-- Etkileşim Butonları -->
                <div class="d-flex flex-wrap gap-3">
                    @if($userReview)
                    <button class="btn btn-warning btn-lg  px-4" type="button" disabled>
                        <i class="bi bi-check-circle me-1"></i>
                        Yorum Yapıldı
                    </button>
                    @else
                    <button class="btn btn-warning btn-lg  px-4"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#reviewModal{{ $recommendedBook->book->id }}">
                        <i class="bi bi-chat-left-text me-1"></i>
                        Yorum Yap
                    </button>
                    @endif

                    @if($status)
                    <form action="{{ route('removeFromList', $recommendedBook->book->id) }}" method="post">
                        @csrf

                        <button type="submit" class="btn btn-warning btn-lg px-4 ">
                            <i class="bi bi-check-circle me-2"></i>
                            Listende
                        </button>
                    </form>
                    @else
                    <form action="{{ route('book-status', $recommendedBook->book->id) }}" method="post">
                        @csrf

                        <input type="hidden" name="status" value="want-to-read">

                        <button type="submit" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-bookmark-plus me-2"></i>
                            Okuyacaklarıma Ekle
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Değerlendirme Modalı -->
    <div class="modal fade" id="reviewModal{{ $recommendedBook->book->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">"Mirasın İzinde" Kitabını Değerlendir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('add-review', $recommendedBook->book->id)}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Puanınız</label>
                            <select class="form-select" name="rating">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5) - Mükemmel</option>
                                <option value="4">⭐⭐⭐⭐ (4/5) - Çok İyi</option>
                                <option value="3">⭐⭐⭐ (3/5) - Orta</option>
                                <option value="2">⭐⭐ (2/5) - Zayıf</option>
                                <option value="1">⭐ (1/5) - Kötü</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Yorumunuz</label>
                            <textarea name="review" class="form-control" rows="4" placeholder="Kitap hakkındaki düşüncelerinizi yazın..."></textarea>
                            <div class="form-check mb-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="has_spoiler"
                                    id="has_spoiler" />
                                <label
                                    class="form-check-label small"
                                    for="has_spoiler">
                                    Yorumum spoiler içeriyor.
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning fw-bold w-100">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Benzer / Önerilen Kitaplar Bölümü -->
    <section class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <div>
                <h3 class="h4 fw-bold mb-0">Benzer Türdeki Kitaplar</h3>
                <p class="text-muted small mb-0">Bu kitabı sevenlerin tercih ettiği diğer eserler</p>
            </div>
            <a href="index.html" class="btn btn-outline-primary btn-sm">Tüm Kataloğu Gör &rarr;</a>
        </div>

        <!-- Benzer Kitaplar Grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">

            @foreach($similarBooks as $similarBook)
            <div class="col">
                <div class="card border-0 shadow-sm book-card h-100">
                    <div class="position-relative text-center p-3 bg-light">
                        <!-- <img src="https://via.placeholder.com/180x260" class="book-cover shadow-sm" alt="Kitap Kapak" /> -->
                        <img src="{{ asset('storage/' . $similarBook->cover_image) }}"
                            class="book-cover shadow-sm"
                            alt="{{ $similarBook->title }}">
                        <form action="{{route('book-favorite', $similarBook->id)}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm position-relative z-2" title="Listeme Kaydet">
                                @if ($similarBook->users->where('id', Auth::id())->first()?->pivot->is_favorite)
                                <i class="bi bi-heart-fill text-danger fs-6"></i>
                                @else
                                <i class="bi bi-heart text-primary fs-6"></i>
                                @endif
                            </button>
                        </form>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary-subtle text-primary category-badge w-auto mb-2 align-self-start">{{$similarBook->category->name}}</span>
                        <h5 class="card-title h6 fw-bold mb-1 text-truncate">
                            {{$similarBook->title}}
                        </h5>
                        <p class="card-subtitle text-muted small mb-2">
                            Yazar: {{$similarBook->author}}
                        </p>

                        <!-- Rating -->
                        <div class="d-flex align-items-center mb-2">
                            @php
                            $ratings = $similarBook->users
                            ->pluck('pivot.rating')
                            ->filter();

                            $averageRating = $ratings->avg() ?? 0;
                            $fullStars = floor($averageRating);
                            $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                            @endphp
                            <div class="rating-stars me-2 fs-5">
                                @for ($i = 1; $i <= $fullStars; $i++)
                                    <i class="bi bi-star-fill fs-6"></i>
                                    @endfor

                                    @if ($hasHalfStar)
                                    <i class="bi bi-star-half fs-6"></i>
                                    @endif
                            </div>

                            <span class="fw-bold fs-6">
                                {{ number_format($averageRating ?? 0, 1) }}
                            </span>
                        </div>

                        <p class="card-text small text-secondary flex-grow-1 line-clamp-3">
                            {{$similarBook->description}}
                        </p>
                        @php
                        $userBook = Auth::check()
                        ? $similarBook->users->firstWhere('id', Auth::id())
                        : null;
                        @endphp
                        <div class="pt-2 border-top  gap-2">
                            @if(!$userBook || ($userBook->pivot->rating === null && $userBook->pivot->review === null))
                            <button class="btn btn-outline-primary mb-2 btn-sm w-100 position-relative z-2" type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#reviewModal{{ $similarBook->id }}">
                                <i class="bi bi-chat-left-text me-1"></i>
                                Yorum Yap
                            </button>
                            @endif

                            <a href="{{route('book-detail', $similarBook->slug)}}" class="btn btn-primary btn-sm w-100 stretched-link">
                                İncele
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Değerlendirme / Yorum Yapma Modalı -->
            <div
                class="modal fade"
                id="reviewModal{{ $similarBook->id }}"
                tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Kitabı Değerlendir ve Yorum Yap
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('add-review', $similarBook->id)}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Puanınız</label>
                                    <select class="form-select" name="rating">
                                        <option value="5">
                                            ⭐⭐⭐⭐⭐ (5/5) - Mükemmel
                                        </option>
                                        <option value="4">
                                            ⭐⭐⭐⭐ (4/5) - Çok İyi
                                        </option>
                                        <option value="3">
                                            ⭐⭐⭐ (3/5) - Orta
                                        </option>
                                        <option value="2">
                                            ⭐⭐ (2/5) - Zayıf
                                        </option>
                                        <option value="1">⭐ (1/5) - Kötü</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Değerlendirme Notunuz</label>
                                    <textarea
                                        class="form-control"
                                        rows="4"
                                        name="review"
                                        placeholder="Kitap hakkında ne düşünüyorsunuz? Spoiler vermemeye özen gösteriniz..."></textarea>
                                </div>
                                <div class="form-check mb-3">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="has_spoiler"
                                        id="has_spoiler" />
                                    <label
                                        class="form-check-label small"
                                        for="has_spoiler">
                                        Yorumum spoiler içeriyor.
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    Değerlendirmeyi Gönder
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </section>

</main>


@endsection