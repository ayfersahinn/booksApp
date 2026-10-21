@extends('web.layouts.app')
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
                <img src="{{ asset('storage/' . $book->cover_image) }}"
                    class="main-book-cover mb-3"
                    alt="{{ $book->title }}">

                <!-- Okuma Durumu Ekleme Dropdown -->
                <div class="d-grid gap-2">
                    <div class="btn-group">
                        <button class="btn btn-primary fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" id="bookStatusButton">
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

                        <ul class="dropdown-menu dropdown-menu-end w-100">
                            <li>
                                <form action="{{route('book-status', $book->id)}}" method="post" class="book-status-form">
                                    @csrf
                                    <input type="hidden" name="status" value="read">
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-check-circle text-success me-2"></i>Okudum
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('book-status', $book->id)}}" method="post" class="book-status-form">
                                    @csrf

                                    <input type="hidden" name="status" value="reading">
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-clock text-warning me-2"></i>Okuyorum
                                    </button>
                                </form>

                            </li>
                            <li>
                                <form action="{{route('book-status', $book->id)}}" method="post" class="book-status-form">
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
                                <form action="{{route('removeFromList',$book->id)}}" method="post" class="remove-from-list-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-x-circle me-2"></i>Listeden Çıkar</button>

                                </form>
                            </li>
                        </ul>
                    </div>
                    <form action="{{ route('book-favorite', $book->id) }}"
                        method="POST"
                        id="favoriteForm">
                        @csrf
                        <button type="submit"
                            id="favoriteButton"
                            class="btn {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }} btn-sm w-100">
                            <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                            <span id="favoriteText">
                                {{ $isFavorite ? 'Favorilerden Çıkar' : 'Favorilere Ekle' }}
                            </span>
                        </button>
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
                        @php
                        $fullStars = floor($avgRatings);
                        $hasHalfStar = ($avgRatings - $fullStars) >= 0.5;
                        @endphp

                        <div class="rating-stars me-2 fs-5">
                            @for ($i = 1; $i <= $fullStars; $i++)
                                <i class="bi bi-star-fill"></i>
                                @endfor

                                @if ($hasHalfStar)
                                <i class="bi bi-star-half"></i>
                                @endif
                        </div>

                        <span class="fw-bold fs-5">
                            {{ number_format($avgRatings ?? 0, 1) }}
                        </span>

                    </div>
                    <span class="text-muted">|</span>
                    <span class="text-muted"><i class="bi bi-chat-text me-1"></i> {{$reviewCount}} Değerlendirme</span>
                    <span class="text-muted">|</span>
                    <span class="text-muted"><i class="bi bi-bookmark me-1"></i> {{$statusCount}} Okuma Listesinde</span>
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
    <div class="row g-4" id="bookReviews">

        <!-- Sol: Yorum Formu ve Yorum Listesi -->
        <div class="col-lg-8">
            <!-- Yorum Ekleme Formu -->
            @auth
            @if(!Auth::user()->is_active)
            <div class="alert alert-danger mb-4">
                <i class="bi bi-slash-circle me-2"></i>
                Hesabınız pasif durumda olduğu için yorum yazamazsınız.
            </div>
            @elseif(!$userBook || ($userBook->pivot->rating === null && $userBook->pivot->review === null))
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square text-primary me-2"></i>Bu Kitabı Değerlendir</h5>
                <form action="{{route('add-review', $book->id)}}" method="POST" class="add-review-form">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Puanınız</label>
                            <select class="form-select" name="rating">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5) - Mükemmel</option>
                                <option value="4">⭐⭐⭐⭐ (4/5) - Çok İyi</option>
                                <option value="3">⭐⭐⭐ (3/5) - Orta</option>
                                <option value="2">⭐⭐ (2/5) - Zayıf</option>
                                <option value="1">⭐ (1/5) - Kötü</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="has_spoiler" name="has_spoiler" value="1">
                                <label class="form-check-label small text-muted" for="has_spoiler">
                                    Yorumum spoiler içeriyor.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="review" class="form-control" rows="3" placeholder="Kitap hakkındaki düşüncelerinizi paylaşın..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Yorumu Gönder</button>
                </form>
            </div>
            @else
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                Bu kitabı zaten değerlendirdiniz. Mevcut yorumunuzu yorum kartından düzenleyebilirsiniz.
            </div>
            @endif
            @endauth

            <!-- Yorumlar Akışı -->
            <h5 class="fw-bold mb-3">Okur Yorumları {{$userReview->count()}}</h5>
            <div class="d-flex flex-column gap-3">

                @foreach($userReview as $review)
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex gap-3">
                        <img src="https://via.placeholder.com/45"
                            class="rounded-circle avatar"
                            alt="User">

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

                                {{-- Sadece kendi yorumunda göster --}}
                                @if(Auth::id() === $review->pivot->user_id)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary ms-auto me-2"
                                    onclick="document.getElementById('edit-review-{{ $review->id }}').classList.toggle('d-none')"
                                    title="Yorumu Düzenle">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                {{-- Modalı açan buton --}}
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Yorumu Sil"
                                    data-bs-toggle="modal"
                                    data-bs-target="#delete-review-{{ $review->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                                {{-- Silme onay modalı --}}
                                <div class="modal fade"
                                    id="delete-review-{{ $review->id }}"
                                    tabindex="-1"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Yorumu Sil</h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                Bu yorumu silmek istediğinize emin misiniz?
                                            </div>

                                            <div class="modal-footer">
                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    İptal
                                                </button>

                                                {{-- Silme işlemini yapan tek form --}}
                                                <form action="{{ route('delete-review', $book->id) }}" class="delete-review-form"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">
                                                        Yorumu Sil
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            {{-- DÜZENLEME FORMU --}}
                            @if(Auth::id() === $review->pivot->user_id)
                            <div id="edit-review-{{ $review->id }}" class="d-none mt-3 border-top pt-3">

                                <form action="{{ route('update-review', $book->id) }}" method="POST" class="edit-review-form">
                                    @csrf
                                    @method('PUT')


                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">
                                            Puanınız
                                        </label>

                                        <select name="rating" class="form-select">
                                            @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}"
                                                {{ $review->pivot->rating == $i ? 'selected' : '' }}>
                                                {{ str_repeat('⭐', $i) }} ({{ $i }}/5)
                                            </option>
                                            @endfor
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">
                                            Yorumunuz
                                        </label>

                                        <textarea
                                            name="review"
                                            class="form-control"
                                            rows="4">{{ $review->pivot->review }}</textarea>
                                    </div>


                                    <div class="form-check mb-3">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="has_spoiler"
                                            value="1"
                                            id="edit-spoiler-{{ $review->id }}"
                                            {{ $review->pivot->has_spoiler ? 'checked' : '' }}>

                                        <label
                                            class="form-check-label small"
                                            for="edit-spoiler-{{ $review->id }}">
                                            Yorumum spoiler içeriyor.
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-check-lg me-1"></i>
                                        Değişiklikleri Kaydet
                                    </button>

                                </form>

                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach


            </div>
        </div>

        <!-- Sağ: Puan Dağılım İstatistikleri -->
        <aside class="col-lg-4">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Puan Dağılımı</h6>

                @for ($rating = 5; $rating >= 1; $rating--)
                <div class="d-flex align-items-center gap-2 mb-2">

                    <small class="text-muted" style="width: 25px;">
                        {{ $rating }}★
                    </small>

                    <div class="progress flex-grow-1 progress-bar-star">
                        <div
                            class="progress-bar bg-warning"
                            style="width: {{ $ratingPercentages[$rating] }}%">
                        </div>
                    </div>

                    <small class="text-muted" style="width: 35px;">
                        %{{ $ratingPercentages[$rating] }}
                    </small>

                </div>
                @endfor

            </div>
        </aside>

    </div>
</main>
@push('scripts')
<script>
    $(document).on('submit', '#favoriteForm', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = $('#favoriteButton');

        button.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (!response.success) return;
                const isFavorite = response.isFavorite;
                button
                    .toggleClass('btn-danger', isFavorite)
                    .toggleClass('btn-outline-danger', !isFavorite);

                button.find('i')
                    .toggleClass('bi-heart-fill', isFavorite)
                    .toggleClass('bi-heart', !isFavorite);

                $('#favoriteText').text(
                    isFavorite ? 'Favorilerden Çıkar' : 'Favorilere Ekle'
                );
            },

            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                console.error('Favori işlemi başarısız:', xhr.status);
            },

            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
    $(document).on('submit', '.book-status-form', function(e) {
        e.preventDefault();

        const form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function(response) {
                if (!response.success) return;

                const statusButton = $('#bookStatusButton');
                let icon = '';
                let text = '';

                if (response.status === 'read') {
                    icon = 'bi-check-circle';
                    text = 'Okudum';
                } else if (response.status === 'reading') {
                    icon = 'bi-clock';
                    text = 'Okuyorum';
                } else if (response.status === 'want-to-read') {
                    icon = 'bi-bookmark-plus';
                    text = 'Okuyacağım';
                }

                statusButton.html(
                    `<i class="bi ${icon} me-1"></i> ${text}`
                );

                const dropdown = bootstrap.Dropdown.getInstance(
                    statusButton[0]
                );

                if (dropdown) {
                    dropdown.hide();
                }
            },

            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                console.error(
                    'Durum değiştirme başarısız:',
                    xhr.status
                );
            }
        });
    });
    $(document).on('submit', '.remove-from-list-form', function(e) {
        e.preventDefault();

        const form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function(response) {
                if (!response.success) return;

                $('#bookStatusButton').html(
                    '<i class="bi bi-bookmark-plus me-1"></i> Listeye Ekle'
                );
                const dropdownButton = document.getElementById('bookStatusButton');
                const dropdown = bootstrap.Dropdown.getInstance(dropdownButton);

                if (dropdown) {
                    dropdown.hide();
                }
            },

            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                console.error('Listeden çıkarma başarısız:', xhr.status);
            }
        });
    });

    function loadBookReviews(url) {
        const $reviewSection = $('#bookReviews');

        if (!$reviewSection.length) {
            return;
        }
        $reviewSection.css('opacity', '0.5');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',

            success: function(html) {
                const $newReviewSection = $(html).find('#bookReviews');

                if (!$newReviewSection.length) {
                    console.error('Yorum bölümü yüklenemedi.');
                    $reviewSection.css('opacity', '1');
                    return;
                }

                $reviewSection.replaceWith($newReviewSection);
                $('#bookReviews').css('opacity', '1');
            },

            error: function(xhr) {
                console.error('Yorumlar yüklenemedi:', xhr.status);
                $reviewSection.css('opacity', '1');
            }
        });
    }

    function handleBookReviewError(xhr) {
        if (xhr.status === 401) {
            window.location.href = "{{ route('login') }}";
            return;
        }

        if (xhr.status === 422) {
            const response = xhr.responseJSON;
            alert(response?.message || 'Lütfen form alanlarını kontrol edin.');
            return;
        }

        console.error('Yorum işlemi başarısız:', xhr.status);
    }

    // Yorum ekle
    $(document).on('submit', '.add-review-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        button.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'Accept': 'application/json'
            },

            success: function(response) {
                if (response.success) {
                    loadBookReviews(window.location.href);
                }
            },

            error: function(xhr) {
                handleBookReviewError(xhr);
            },

            complete: function() {
                button.prop('disabled', false);
            }
        });
    });


    // Yorum güncelle
    $(document).on('submit', '.edit-review-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        button.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'Accept': 'application/json'
            },

            success: function(response) {
                if (response.success) {
                    loadBookReviews(window.location.href);
                }
            },

            error: function(xhr) {
                handleBookReviewError(xhr);
            },

            complete: function() {
                button.prop('disabled', false);
            }
        });
    });


    // Yorum sil
    $(document).on('submit', '.delete-review-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        button.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'Accept': 'application/json'
            },

            success: function(response) {
                if (response.success) {
                    const modalElement = form.closest('.modal')[0];
                    if (modalElement) {
                        bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                    }
                    loadBookReviews(window.location.href);
                }
            },

            error: function(xhr) {
                handleBookReviewError(xhr);
            },

            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
</script>
@endpush
@endsection