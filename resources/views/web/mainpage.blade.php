 @extends('web.layouts.app')
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
                     <form action="{{route('mainpage')}}" id="searchForm" method="get" class="d-flex w-100 gap-2">
                         <input
                             type="text"
                             name="q"
                             value="{{ request('q') }}"
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
                 <div class="fw-bold fs-5 text-primary">{{$bookCount}}</div>
                 <small class="text-muted">Kitap</small>
             </div>
             <div class="col-4">
                 <div class="fw-bold fs-5 text-primary">{{$reviewCount}}</div>
                 <small class="text-muted">Yorum & Değerlendirme</small>
             </div>
             <div class="col-4">
                 <div class="fw-bold fs-5 text-primary">{{$userCount}}</div>
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
                         href="{{ request()->fullUrlWithQuery(['category' => $category->slug]) }}"
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                         {{$category->name}}
                         <span class="badge bg-secondary rounded-pill">{{$category->books_count}}</span>
                     </a>
                     @endforeach
                 </div>
             </div>
             <div class="card border-0 shadow-sm mb-4">
                 <div class="card-header bg-white fw-bold">
                     <i class="bi bi-grid me-2"></i>Yayınevi
                 </div>
                 <form method="GET" action="{{ route('mainpage') }}">
                     @if(request('category'))
                     <input type="hidden" name="category" value="{{ request('category') }}">
                     @endif
                     <div class="list-group list-group-flush">

                         @foreach($publishers as $publisher)
                         <label class="list-group-item d-flex justify-content-between align-items-center">

                             <div>
                                 <input
                                     type="checkbox"
                                     name="publishers[]"
                                     value="{{ $publisher->slug }}"
                                     class="form-check-input me-2">

                                 {{ $publisher->name }}
                             </div>

                             <span class="badge bg-secondary rounded-pill">
                                 {{ $publisher->books_count }}
                             </span>

                         </label>
                         @endforeach
                     </div>
                     <div class="p-3">
                         <button type="submit" class="btn btn-primary w-100">
                             Filtrele
                         </button>
                     </div>
                 </form>
             </div>
             <!-- Filtreleme Kartı -->
             <form action="{{ route('mainpage') }}" method="get">
                 {{-- Seçili kategori --}}
                 @if(request('category'))
                 <input
                     type="hidden"
                     name="category"
                     value="{{ request('category') }}">
                 @endif

                 {{-- Seçili yayınevleri --}}
                 @foreach(request('publishers', []) as $publisher)
                 <input
                     type="hidden"
                     name="publishers[]"
                     value="{{ $publisher }}">
                 @endforeach
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

             @include('web.partials.book-list')
         </section>
     </div>
 </main>

 <!-- Son Değerlendirmeler / Topluluk Bölümü -->
 <section class="bg-white py-5 border-top">
     <div class="container">
         <h3 class="h4 fw-bold mb-4">Son Kullanıcı Yorumları</h3>
         <div class="row g-4">
             @foreach($lastReviews as $review)
             @if($review->book && $review->user)
             <div class="col-md-6">
                 <div class="p-3 border rounded bg-light">
                     <div
                         class="d-flex justify-content-between align-items-center mb-2">
                         <div>
                             <span class="fw-bold">{{ $review->book?->name }}</span>

                             <small class="text-muted d-block mb-2">
                                 {{ \Carbon\Carbon::parse($review->review_updated_at)->diffForHumans() }}
                             </small>
                         </div>
                         <div class="rating-stars small">
                             @for($i = 1; $i <= 5; $i++)
                                 <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                 @endfor
                         </div>
                     </div>
                     <p class="small text-muted mb-1"> <a href="{{ route('book-detail', $review->book?->slug) }}" class="text-decoration-none"> <strong>{{ $review->book?->title }}</strong> </a> kitabı için: </p>
                     <p class="mb-0 text-secondary review-preview">
                         {{$review->review}}
                     </p>
                 </div>
             </div>
             @endif
             @endforeach
         </div>
     </div>
 </section>

 @push('scripts')
 <script>
     function loadBooks(url, pushState = true) {
         const $bookList = $('#bookList');

         if (!$bookList.length) {
             return;
         }

         $bookList.css('opacity', '0.5');

         $.ajax({
             url: url,
             type: 'GET',
             dataType: 'html',

             success: function(html) {
                 const $newBookList = $(html).filter('#bookList');

                 if (!$newBookList.length) {
                     console.error('Sunucudan geçerli kitap listesi gelmedi.');
                     $bookList.css('opacity', '1');
                     return;
                 }

                 $bookList.replaceWith($newBookList);

                 if (pushState) {
                     history.pushState(null, '', url);
                 }
             },

             error: function(xhr) {
                 console.error('Kitaplar yüklenemedi:', xhr.status);
                 $bookList.css('opacity', '1');
             }
         });
     }

     // Arama formu
     $('#searchForm').on('submit', function(e) {
         e.preventDefault();

         const url = new URL(this.action, window.location.origin);
         const params = new URLSearchParams($(this).serialize());

         url.search = params.toString();

         loadBooks(url.href);
     });

     // Sayfalama bağlantıları
     $(document).on('click', '#bookList .pagination a', function(e) {
         e.preventDefault();

         loadBooks(this.href);
     });

     // Tarayıcının geri / ileri düğmeleri
     $(window).on('popstate', function() {
         loadBooks(window.location.href, false);
     });
 </script>
 @endpush
 @endsection