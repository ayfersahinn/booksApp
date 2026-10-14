@extends('admin.layouts.main')
@section('content')

<style>
    .stat-card {
        border: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        transition: transform .15s, box-shadow .15s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, .10);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.4rem;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- 1. Hızlı işlemler -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('book-index', ['add' => 1]) }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Yeni Kitap</a>
    <a href="{{route('weekly-book-index')}}" class="btn btn-outline-primary"><i class="bi bi-star me-1"></i> Editörün Seçimi</a>
    <a href="{{route('review-index')}}" class="btn btn-outline-secondary"><i class="bi bi-chat-left-text me-1"></i> Yorumlar</a>
    <a href="{{route('user-index')}}" class="btn btn-outline-secondary"><i class="bi bi-people me-1"></i> Kullanıcılar</a>
</div>

<!-- 2. İstatistik kartları -->
<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Toplam Kitap</span>
                    <h3 class="fw-bold mb-0 mt-1">{{$books}}</h3>
                    <small class="text-success"><i class="bi bi-arrow-up-short"></i>Bu hafta +{{$weekBooks}}</small>
                </div>
                <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="bi bi-journal-bookmark"></i></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Kullanıcılar</span>
                    <h3 class="fw-bold mb-0 mt-1">{{$users}}</h3>
                    <small class="text-success"><i class="bi bi-arrow-up-short"></i>Bu hafta +{{$weekUsers}}</small>
                </div>
                <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Yorumlar</span>
                    <h3 class="fw-bold mb-0 mt-1">{{$reviews}}</h3>
                    <small class="text-success"><i class="bi bi-arrow-up-short"></i>Bu hafta +{{$weekReviews}}</small>
                </div>
                <div class="icon-shape bg-warning bg-opacity-10 text-warning"><i class="bi bi-chat-left-text"></i></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Yanıtlar</span>
                    <h3 class="fw-bold mb-0 mt-1">{{$answers}}</h3>
                    <small class="text-muted">Pasif kullanıcı: {{$passiveUsers}}</small>
                </div>
                <div class="icon-shape bg-info bg-opacity-10 text-info"><i class="bi bi-reply-all"></i></div>
            </div>
        </div>
    </div>

</div>

<!-- 3. Orta alan -->
<div class="row g-4 mb-4">

    <!-- Son yorumlar -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Son Yorumlar</h6>
                <a href="{{route('review-index')}}" class="btn btn-sm btn-link text-decoration-none">Tümünü Gör</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover border-top mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Kitap</th>
                            <th>Puan</th>
                            <th style="min-width: 200px;">Yorum</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lastReviews as $review)
                        <tr>
                            <td><strong>{{ $review->user?->name }}</strong></td>
                            <td>{{ $review->book?->title }}</td>
                            <td>
                                @if($review->rating)
                                <span class="text-warning">

                                    {{$review->rating }} <i class="bi bi-star-fill"></i>

                                </span>
                                @else
                                <small class="text-muted">Puan yok</small>
                                @endif
                            </td>
                            <td>
                                @if($review->has_spoiler)
                                <span class="badge bg-warning-subtle text-warning border">Spoiler</span>
                                @endif
                                <div class="line-clamp-2 small text-secondary">{{ $review->review }}</div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ ($review->review_updated_at ?? $review->created_at)->diffForHumans() }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Henüz yorum yok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Haftanın kitabı -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-1"></i> Editörün Seçimi</h6>
                <a href="{{route('weekly-book-index')}}" class="btn btn-sm btn-outline-primary">Yönet</a>
            </div>
            @if($recommendation)
            <div class="d-flex gap-3 align-items-center p-2 border rounded bg-light">
                <img src="{{asset('storage/' . $recommendation->book->cover_image)}}" class="rounded shadow-sm" alt="Kitap Kapak"
                    style="width: 70px; height: 100px; object-fit: cover;">
                <div>
                    <h6 class="fw-bold mb-1">{{$recommendation->book->title}}</h6>
                    <p class="text-muted small mb-1">{{$recommendation->book->author}}</p>
                    @if($recommendation->start_date->isFuture())
                    <span class="badge bg-info-subtle text-info border">Planlandı</span>
                    @elseif($recommendation->end_date->isPast())
                    <span class="badge bg-secondary-subtle text-secondary border">Yayın sona erdi</span>
                    @else
                    <span class="badge bg-success-subtle text-success border">Yayında</span>
                    @endif
                </div>
            </div>
            <small class="text-muted">{{$recommendation->start_date->format('d.m.Y')}} - {{$recommendation->end_date->format('d.m.Y')}}</small>

            @else
            <div class="alert alert-warning small mt-3 mb-0">
                Şu an yayında bir seçki yok. <a href="{{ route('weekly-book-index', ['add' => 1]) }}" class="alert-link">Yeni seçki ekle</a>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- 4. Alt alan: listeler -->
<div class="row g-4">

    <!-- En çok yorumlanan kitaplar -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm p-3 bg-white h-100">
            <h6 class="fw-bold mb-3">En Çok Yorumlanan Kitaplar</h6>
            <ul class="list-group list-group-flush">
                @foreach($popularBooks as $book)
                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{$book->title}}</div>
                        <small class=" text-muted">{{$book->author}}</small>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border">{{$book->reviews_count}} yorum</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Kategori dağılımı -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm p-3 bg-white h-100">
            <h6 class="fw-bold mb-3">Kategorilere Göre Kitaplar</h6>
            @foreach($categories as $category)
            <div class="mb-3">
                <div class="d-flex justify-content-between small mb-1">
                    <span>{{$category->name}}</span><span class="text-muted">{{$category->books_count}}</span>
                </div>
                <div class="progress" style="height: 8px;">

                    <div class="progress-bar" style="width: {{ round($category->books_count / $maxCategoryCount * 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Yeni üyeler -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Yeni Üyeler</h6>
                <a href="{{route('user-index')}}" class="btn btn-sm btn-link text-decoration-none">Tümü</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($newUsers as $user)
                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{$user->name}}</div>
                        <small class="text-muted">{{$user->email}}</small>
                    </div>
                    <small class="text-muted">Bugün</small>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>

@endsection