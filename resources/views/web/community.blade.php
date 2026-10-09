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
                <form action="{{ route('community') }}">
                    <select name="sort" class="form-select form-select-sm" style="width: 180px;" onchange="this.form.submit()">
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

                @foreach($reviews as $review)
                <!-- Yorum Kartı  -->
                <div id="review-{{ $review->id }}" class="card border-0 shadow-sm review-card p-3">
                    <div class="d-flex gap-3">
                        <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="Kullanıcı">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="fw-bold mb-0">{{$review->user->name}} </h6>
                                    <small class="text-muted d-block mb-2">
                                        {{ $review->review_updated_at?->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="rating-stars small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                </div>
                            </div>

                            <div class="bg-light p-2 rounded d-flex align-items-center gap-3 my-2">
                                <img src="{{ asset('storage/' . $review->book->cover_image) }}"
                                    class="book-cover-thumb shadow-sm"
                                    alt="{{ $review->book->title }}">
                                <div>
                                    <a href="{{route('book-detail' , $review->book->slug)}}" class="fw-bold text-dark text-decoration-none d-block">{{$review->book->title}}</a>
                                    <small class="text-muted d-block">Yazar: {{$review->book->author}}</small>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">{{$review->book->category->name}}</span>
                                </div>
                            </div>

                            <!-- Spoiler Uyarısı -->
                            @if($review->has_spoiler)
                            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Bu yorum <strong>spoiler</strong> içermektedir.</span>
                            </div>
                            @endif

                            <p class="card-text  mb-3">
                                {{$review->review}}
                            </p>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex gap-2">
                                    @if($review->user_id != Auth::id())

                                    @php
                                    $isHelpful = $review->helpfuls->contains('user_id', Auth::id());
                                    @endphp

                                    <form action="{{ route('toggleHelpful', $review->id) }}" method="post">
                                        @csrf

                                        <button type="submit"
                                            class="btn btn-sm {{ $isHelpful ? 'btn-success' : 'btn-outline-secondary' }}">
                                            <i class="bi bi-hand-thumbs-up me-1"></i>
                                            Faydalı {{ $review->helpfuls->count() }}
                                        </button>
                                    </form>

                                    @endif
                                    <button type="submit" class="btn btn-sm btn-outline-secondary reply-toggle">
                                        <i class="bi bi-chat me-1"></i> Yanıtla {{$review->comments->count()}}
                                    </button>
                                </div>
                                <button class="btn btn-sm text-muted p-0" title="Bildir">
                                    <i class="bi bi-flag"></i>
                                </button>
                            </div>
                            {{-- Yanıt Alanı --}}
                            <div class="reply-form mt-3 pt-3 border-top d-none">
                                @if($review->comments->count() > 0)

                                <div class="comments-list d-none mt-3">

                                    @foreach($review->comments as $comment)

                                    <div class="border-top pt-2 mb-2">
                                        <strong>{{ $comment->user->name }}</strong>
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0 text-secondary">
                                                {{ $comment->content }}
                                            </p>
                                            @if($comment->user_id==Auth::id())
                                            <form action="{{route('delete-comment', $comment->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Sil
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>

                                    @endforeach

                                </div>

                                @endif
                                <form action="{{ route('replyToReview', $review->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="content" class="form-control" rows="3" placeholder="Yanıtınızı yazın..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-sm "> <i class="bi bi-send me-1"></i> Yanıtla </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
            <script>
                document.querySelectorAll('.reply-toggle').forEach(button => {
                    button.addEventListener('click', function() {

                        const container = this.closest('.w-100');

                        const replyForm = container.querySelector('.reply-form');
                        const commentsList = container.querySelector('.comments-list');

                        replyForm.classList.toggle('d-none');

                        if (commentsList) {
                            commentsList.classList.toggle('d-none');
                        }
                    });
                });
            </script>
            <!-- Sayfalandırma (Pagination) -->
            <nav class="mt-4">
                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            </nav>
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


@endsection