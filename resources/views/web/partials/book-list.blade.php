<div id="bookList">
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-4">

        @forelse($books as $book)
        <div class="col">
            <div class="card border-0 shadow-sm book-card h-100">
                <div class="position-relative text-center p-3 bg-light">
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                        class="book-cover shadow-sm"
                        alt="{{ $book->title }}">

                    <form action="{{route('book-favorite', $book->id)}}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm position-relative z-2" title="Listeme Kaydet">
                            @if ($book->users->where('id', Auth::id())->first()?->pivot->is_favorite)
                            <i class="bi bi-heart-fill text-danger fs-6"></i>
                            @else
                            <i class="bi bi-heart text-primary fs-6"></i>
                            @endif
                        </button>
                    </form>
                </div>

                <div class="card-body d-flex flex-column">
                    <span class="badge bg-primary-subtle text-primary category-badge w-auto mb-2 align-self-start">{{$book->category->name}}</span>
                    <h5 class="card-title h6 fw-bold mb-1 text-truncate">
                        {{$book->title}}
                    </h5>
                    <p class="card-subtitle text-muted small mb-2">
                        Yazar: {{$book->author}}
                    </p>

                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-2">
                        @php
                        $ratings = $book->users
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
                        {{$book->description}}
                    </p>
                    @php
                    $userBook = Auth::check()
                    ? $book->users->firstWhere('id', Auth::id())
                    : null;
                    @endphp
                    <div class="pt-2 border-top ">
                        @if(!$userBook || ($userBook->pivot->rating === null && $userBook->pivot->review === null))
                        <button class="btn btn-outline-primary mb-2 btn-sm w-100 position-relative z-2" type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#reviewModal{{ $book->id }}">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Yorum Yap
                        </button>
                        @endif

                        <a href="{{route('book-detail', [$book->slug, $book->isbn])}}" class="btn btn-primary btn-sm w-100 stretched-link">
                            İncele
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Değerlendirme / Yorum Yapma Modalı -->
        <div
            class="modal fade"
            id="reviewModal{{ $book->id }}"
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
                        <form action="{{route('add-review', $book->id)}}" method="POST">
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
                                    id="has_spoiler{{ $book->id }}" />
                                <label
                                    class="form-check-label small"
                                    for="has_spoiler{{ $book->id }}">
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
        @empty
        <div class="col-12 text-center text-muted py-5">
            Aramanla eşleşen kitap bulunamadı.
        </div>
        @endforelse

    </div>

    <!-- Sayfalandırma (Pagination) -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $books->links() }}
    </div>
</div>