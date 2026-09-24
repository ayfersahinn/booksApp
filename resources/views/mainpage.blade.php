 @extends('layouts.app')
 @section('content')
 <!-- Hero Section -->
 <section class="hero-section text-center">
     <div class="container">
         <h1 class="display-4 fw-bold mb-3">
             Binlerce Kitabı Keşfet, İncele ve Kaydet
         </h1>
         <p class="lead mb-4 text-white-50">
             Okuduğun kitapları değerlendir, incelemelerini paylaş ve
             kişisel kitaplığını oluştur.
         </p>

         <!-- Arama Barı -->
         <div class="row justify-content-center">
             <div class="col-md-8 col-lg-6">
                 <div class="input-group input-group-lg shadow-sm">
                     <form action="{{route('books-search')}}" method="get" class="d-flex w-100 gap-2">
                         <input
                             type="text"
                             name="q"
                             class="form-control border-0"
                             placeholder="Kitap adı, yazar veya ISBN arayın..." />
                         <button class="btn btn-warning px-4 btn-sm" type="submit">
                             <i class="bi bi-search"></i> Ara
                         </button>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </section>

 <!-- İstatistikler / Hızlı Bilgi -->
 <div class="bg-white py-3 shadow-sm border-bottom">
     <div class="container">
         <div class="row text-center g-3">
             <div class="col-4">
                 <div class="fw-bold fs-5 text-primary">50.000+</div>
                 <small class="text-muted">Kitap</small>
             </div>
             <div class="col-4">
                 <div class="fw-bold fs-5 text-primary">120.000+</div>
                 <small class="text-muted">Yorum & Değerlendirme</small>
             </div>
             <div class="col-4">
                 <div class="fw-bold fs-5 text-primary">15.000+</div>
                 <small class="text-muted">Aktif Okur</small>
             </div>
         </div>
     </div>
 </div>

 <!-- Main Content -->
 <main class="container my-5">
     <div class="row g-4">
         <!-- Sol Taraf: Kategoriler & Filtreler -->
         <aside class="col-lg-3">
             <div class="card border-0 shadow-sm mb-4">
                 <div class="card-header bg-white fw-bold">
                     <i class="bi bi-grid me-2"></i>Kategoriler
                 </div>
                 <div class="list-group list-group-flush">
                     <a
                         href="{{route('mainpage')}}"
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-center active">
                         Tüm Kategoriler
                         <span
                             class="badge bg-light text-dark rounded-pill">{{$categories->count()}}</span>
                     </a>
                     @foreach($categories as $category)
                     <a
                         href="{{route('mainpage', ['category'=>$category->slug])}}"
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                         {{$category->name}}
                         <span class="badge bg-secondary rounded-pill">{{$category->books_count}}</span>
                     </a>
                     @endforeach
                 </div>
             </div>

             <!-- Filtreleme Kartı -->
             <form action="{{ route('mainpage') }}" method="get">
                 <div class="card border-0 shadow-sm">
                     <div class="card-header bg-white fw-bold">
                         <i class="bi bi-sliders me-2"></i>Sıralama
                     </div>
                     <div class="card-body">
                         <div class="form-check mb-2">
                             <input
                                 class="form-check-input"
                                 type="radio"
                                 name="sort"
                                 value="popular"
                                 id="sort1"
                                 onchange="this.form.submit()"
                                 {{ request('sort') === 'popular' ? 'checked' : '' }} />
                             <label class="form-check-label" for="sort1">En Popülerler</label>
                         </div>
                         <div class="form-check mb-2">
                             <input
                                 class="form-check-input"
                                 type="radio"
                                 name="sort"
                                 value="rating"
                                 onchange="this.form.submit()"
                                 {{ request('sort') === 'rating' ? 'checked' : '' }}
                                 id="sort2" />
                             <label class="form-check-label" for="sort2">En Yüksek Puanlılar</label>
                         </div>
                         <div class="form-check">
                             <input
                                 class="form-check-input"
                                 type="radio"
                                 value="last"
                                 onchange="this.form.submit()"
                                 name="sort"
                                 {{ request('sort') === 'last' ? 'checked' : '' }}
                                 id="sort3" />
                             <label class="form-check-label" for="sort3">Yeni Eklenenler</label>
                         </div>
                     </div>
                 </div>
             </form>
         </aside>

         <!-- Sağ Taraf: Kitap Kartları Listesi -->
         <section class="col-lg-9">
             <div
                 class="d-flex justify-content-between align-items-center mb-4">
                 <h3 class="h4 mb-0 fw-bold">Öne Çıkan Kitaplar</h3>
             </div>

             <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                 <!-- Kitap Kartı 1 -->
                 @foreach($books as $book)
                 <div class="col">
                     <div class="card border-0 shadow-sm book-card h-100">
                         <div class="position-relative text-center p-3 bg-light">
                             <img src="https://via.placeholder.com/180x260" class="book-cover shadow-sm" alt="Kitap Kapak" />
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
                                         <i class="bi bi-star-fill"></i>
                                         @endfor

                                         @if ($hasHalfStar)
                                         <i class="bi bi-star-half"></i>
                                         @endif
                                 </div>

                                 <span class="fw-bold fs-5">
                                     {{ number_format($averageRating ?? 0, 1) }}
                                 </span>
                             </div>

                             <p class="card-text small text-secondary flex-grow-1">
                                 {{$book->description}}
                             </p>

                             <div class="pt-2 border-top d-flex gap-2">
                                 @if($book->users->isEmpty() || ($book->users->first()->pivot->rating===null && $book->users->first()->pivot->review===null))

                                 <button class="btn btn-outline-primary btn-sm w-100 position-relative z-2" type="button"
                                     data-bs-toggle="modal"
                                     data-bs-target="#reviewModal{{ $book->id }}">
                                     <i class="bi bi-chat-left-text me-1"></i>
                                     Yorum Yap
                                 </button>
                                 @endif

                                 <a href="{{route('book-detail', $book->slug)}}" class="btn btn-primary btn-sm w-100 stretched-link">
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

             <!-- Sayfalandırma (Pagination) -->
             <nav class="mt-5">
                 <ul class="pagination justify-content-center">
                     <li class="page-item disabled">
                         <a class="page-link" href="#">Önceki</a>
                     </li>
                     <li class="page-item active">
                         <a class="page-link" href="#">1</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link" href="#">2</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link" href="#">3</a>
                     </li>
                     <li class="page-item">
                         <a class="page-link" href="#">Sonraki</a>
                     </li>
                 </ul>
             </nav>
         </section>
     </div>
 </main>

 <!-- Son Değerlendirmeler / Topluluk Bölümü -->
 <section class="bg-white py-5 border-top">
     <div class="container">
         <h3 class="h4 fw-bold mb-4">Son Kullanıcı Yorumları</h3>
         <div class="row g-4">
             <div class="col-md-6">
                 <div class="p-3 border rounded bg-light">
                     <div
                         class="d-flex justify-content-between align-items-center mb-2">
                         <span class="fw-bold">@okur_can</span>
                         <div class="rating-stars small">
                             <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                         </div>
                     </div>
                     <p class="small text-muted mb-1">
                         <strong>Mirasın İzinde</strong> kitabı için:
                     </p>
                     <p class="mb-0 text-secondary">
                         "Kurgusu harikaydı, özellikle son bölümlerdeki
                         ters köşeleri hiç beklemiyordum. Kesinlikle
                         tavsiye ederim."
                     </p>
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="p-3 border rounded bg-light">
                     <div
                         class="d-flex justify-content-between align-items-center mb-2">
                         <span class="fw-bold">@ayse_reads</span>
                         <div class="rating-stars small">
                             <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i>
                         </div>
                     </div>
                     <p class="small text-muted mb-1">
                         <strong>Modern Web Mimarisi</strong> kitabı
                         için:
                     </p>
                     <p class="mb-0 text-secondary">
                         "Teknik detaylar ve mimari örnekler çok temiz
                         açıklanmış. Başlangıç ve orta seviye için çok
                         faydalı."
                     </p>
                 </div>
             </div>
         </div>
     </div>
 </section>


 @endsection