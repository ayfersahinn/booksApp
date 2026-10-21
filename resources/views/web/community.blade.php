@extends('web.layouts.app')
@section('content')

<style>
    .book-cover-thumb {
        width: 50px;
        height: 75px;
        object-fit: cover;
        flex-shrink: 0;
    }
</style>
<!-- Header Section -->
<section class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="bi bi-chat-square-quote me-2 text-primary"></i>Son Değerlendirmeler ve Yorumlar</h1>
                <p class="text-muted small mb-0">Okurların paylaştığı en güncel kitap incelemelerini keşfedin.</p>
            </div>
            <!-- Arama ve Sıralama -->
            <div class="d-flex gap-2">
                <form id="communitySortForm" action="{{ route('community') }}">
                    <select name="sort" class="form-select form-select-sm" style="width: 180px;">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>
                            En Yeniler
                        </option>

                        <option value="highest" {{ request('sort') === 'highest' ? 'selected' : '' }}>
                            En Yüksek Puanlılar
                        </option>

                        <option value="lowest" {{ request('sort') === 'lowest' ? 'selected' : '' }}>
                            En Düşük Puanlılar
                        </option>

                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                            En Çok Beğenilenler
                        </option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="container mb-5">
    <div class="row g-4">

        <!-- Sol Taraf: Yorumlar Akışı -->
        <section class="col-lg-8">
            <div class="d-flex flex-column gap-3">

                @include('web.partials.community-reviews')

            </div>


        </section>

        <!-- Sağ Taraf: Yan Panel (İstatistikler & Popüler Eleştirmenler) -->
        <aside class="col-lg-4">

            <!-- Yorum Yazma Alanı Çağrısı -->
            <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-pencil-square display-5 mb-2 d-block"></i>
                    <h5 class="fw-bold">Bir Kitap İncele</h5>
                    <p class="small text-white-50">Okuduğun son kitabı değerlendir, düşüncelerini toplulukla paylaş.</p>

                    <a href="{{ route('profile') }}#tab-reading" class="btn btn-light btn-sm fw-bold w-100">
                        İnceleme Ekle
                    </a>

                </div>
            </div>

            <!-- Öne Çıkan Eleştirmenler / Okurlar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-trophy text-warning me-2"></i>Ayın Eleştirmenleri
                </div>
                <div class="list-group list-group-flush">
                    @foreach($reviewers as $reviewer)
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://via.placeholder.com/35" class="rounded-circle" alt="User">
                            <div>
                                <h6 class="mb-0 small fw-bold">{{$reviewer->user->name}}</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">{{$reviewer->review_count}} İnceleme</small>
                            </div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning fw-bold">#{{ $loop->iteration }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </aside>
    </div>
</main>
@push('scripts')
<script>
    function loadCommunityReviews(url, pushState = true) {
        const $reviewList = $('#communityReviews');
        if (!$reviewList.length) {
            return;
        }
        $reviewList.css('opacity', '0.5');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',

            success: function(html) {
                const $newReviewList = $(html).filter('#communityReviews');

                if (!$newReviewList.length) {
                    console.error('Sunucudan geçerli yorum listesi gelmedi.');
                    $reviewList.css('opacity', '1');
                    return;
                }

                // Hangi yorumun yanıt bölümü açık?
                const openReview = $reviewList
                    .find('.reply-section:not(.d-none)')
                    .closest('.review-card')
                    .attr('id');

                $reviewList.replaceWith($newReviewList);

                // Aynı yorumun yanıt bölümünü tekrar aç
                if (openReview) {
                    const $section = $('#' + openReview).find('.reply-section');

                    $section.removeClass('d-none');
                    $section.find('.comments-list').removeClass('d-none');
                }

                $('#communityReviews').css('opacity', '1');

                if (pushState) {
                    history.pushState(null, '', url);
                }
            },
            error: function(error) {
                console.error('Yorumlar yüklenemedi:', error.status);
                $reviewList.css('opacity', '1');
            }
        })
    }
    $('#communitySortForm').on('change', 'select[name="sort"]', function() {
        const form = $('#communitySortForm')[0];
        const url = new URL(form.action, window.location.origin);
        url.search = new URLSearchParams($(form).serialize()).toString();
        loadCommunityReviews(url.href)
    })

    $(document).on('click', '#communityReviews .pagination a', function(e) {
        e.preventDefault();
        loadCommunityReviews(this.href);
    });

    $(window).on('popstate', function() {
        const url = new URL(window.location.href);

        $('#communitySortForm select[name="sort"]')
            .val(url.searchParams.get('sort') || 'latest');
        loadCommunityReviews(url.href, false);
    });
    $(document).on('submit', '.helpful-form', function(e) {
        e.preventDefault();

        const form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function() {
                loadCommunityReviews(window.location.href, false);
            },

            error: function(error) {
                if (error.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }
                console.error('İşlem başarısız:', error.status);
            }
        });
    });
    $(document).on('submit', '.reply-form-submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const button = form.find('button[type="submit"]');

        button.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function(response) {
                if (response.success) {
                    loadCommunityReviews(window.location.href, false);
                }
            },

            error: function(error) {
                if (error.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                console.error('Yanıt gönderilemedi:', error.status);

            },

            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
    $(document).on('click', '.reply-toggle', function() {

        if ($(this).data('auth') === false) {
            window.location.href = "{{ route('login') }}";
            return;
        }

        const container = this.closest('.w-100');
        const replySection = container.querySelector('.reply-section');
        const commentsList = container.querySelector('.comments-list');

        replySection.classList.toggle('d-none');

        if (commentsList) {
            commentsList.classList.toggle('d-none');
        }
    });
    $(document).on('submit', '.delete-comment-form', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function(response) {
                if (response.success) {
                    loadCommunityReviews(window.location.href, false);
                }
            },

            error: function(xhr) {
                console.error('Silme hatası:', xhr.status, xhr.responseText);
            }
        });
    });
</script>
@endpush
@endsection