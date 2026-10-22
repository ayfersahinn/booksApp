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
                         href="{{route('mainpage')}}" data-category=""
                         class=" category-filter list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !request('category') ? 'active' : '' }}">
                         Tüm Kategoriler
                         <span
                             class="badge bg-light text-dark rounded-pill">{{$bookCount}}</span>
                     </a>
                     @foreach($categories as $category)
                     <a
                         href="{{ request()->fullUrlWithQuery(['category' => $category->slug]) }}"
                         data-category="{{ $category->slug }}"
                         class="category-filter list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') === $category->slug ? 'active' : '' }}">
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
                 <form id="publisherFilterForm" method="GET" action="{{ route('mainpage') }}">
                     @if(request('category'))
                     <input type="hidden" name="category" value="{{ request('category') }}">
                     @endif

                     @if(request('q'))
                     <input type="hidden" name="q" value="{{ request('q') }}">
                     @endif

                     @if(request('sort'))
                     <input type="hidden" name="sort" value="{{ request('sort') }}">
                     @endif
                     <div class="list-group list-group-flush">

                         @foreach($publishers as $publisher)
                         <label class="list-group-item d-flex justify-content-between align-items-center">

                             <div>
                                 <input
                                     type="checkbox"
                                     name="publishers[]"
                                     value="{{ $publisher->slug }}"

                                     class=" form-check-input me-2"
                                     {{ in_array($publisher->slug, request('publishers', [])) ? 'checked' : '' }}>

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
             <form id="sortForm" action="{{ route('mainpage') }}" method="get">
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
                 @if(request('q'))
                 <input type="hidden" name="q" value="{{ request('q') }}">
                 @endif
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

                                 {{ request('sort') === 'popular' ? 'checked' : '' }} />
                             <label class="form-check-label" for="sort1">En Popülerler</label>
                         </div>
                         <div class="form-check mb-2">
                             <input
                                 class="form-check-input"
                                 type="radio"
                                 name="sort"
                                 value="rating"

                                 {{ request('sort') === 'rating' ? 'checked' : '' }}
                                 id="sort2" />
                             <label class="form-check-label" for="sort2">En Yüksek Puanlılar</label>
                         </div>
                         <div class="form-check">
                             <input
                                 class="form-check-input"
                                 type="radio"
                                 value="last"

                                 name="sort"
                                 {{ request('sort') === 'last' ? 'checked' : '' }}
                                 id="sort3" />
                             <label class="form-check-label" for="sort3">Yeni Eklenenler</label>
                         </div>

                     </div>
                 </div>

             </form>
             <button type="button" id="clearFilters" class=" btn btn-primary w-100 mt-3">

                 Tüm filtreleri temizle
             </button>
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
                     <p class="small text-muted mb-1"> <a href="{{ route('book-detail',  [
    'slug' => $review->book->slug,
    'isbn' => $review->book->isbn
]) }}" class="text-decoration-none"> <strong>{{ $review->book?->title }}</strong> </a> kitabı için: </p>
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
         const pageUrl = new URL(url, window.location.origin);
         const ajaxUrl = new URL(
             "{{ route('book-list') }}",
             window.location.origin
         );
         ajaxUrl.search = pageUrl.search;

         $bookList.css('opacity', '0.5');

         $.ajax({
             url: ajaxUrl.toString(),
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
                     history.pushState(null, '', pageUrl.pathname + pageUrl.search);
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

     // Kategori filtresi
     $(document).on('click', '.category-filter', function(e) {
         e.preventDefault();

         const selectedCategory = this.dataset.category;
         const url = new URL(window.location.href);

         if (selectedCategory) {
             url.searchParams.set('category', selectedCategory);
         } else {
             url.searchParams.delete('category');
         }

         $('.category-filter').removeClass('active');
         $(this).addClass('active');

         loadBooks(url.href);
     });
     //yayınevi filtresi
     $('#publisherFilterForm').on('submit', function(e) {
         e.preventDefault();

         const url = new URL(window.location.href);
         const formParams = new URLSearchParams($(this).serialize());

         url.searchParams.delete('publishers[]');

         formParams.getAll('publishers[]').forEach(function(publisher) {
             url.searchParams.append('publishers[]', publisher);
         });

         loadBooks(url.href);
     });

     // Sıralama filtresi
     $('#sortForm').on('change', 'input[name="sort"]', function() {
         const url = new URL($('#sortForm').attr('action'), window.location.origin);
         const params = new URLSearchParams($('#sortForm').serialize());
         const currentParams = new URLSearchParams(window.location.search);

         ['category', 'q', 'sort'].forEach(function(key) {
             if (!params.has(key) && currentParams.has(key)) {
                 params.set(key, currentParams.get(key));
             }
         });

         if (!params.has('publishers[]')) {
             currentParams.getAll('publishers[]').forEach(function(publisher) {
                 params.append('publishers[]', publisher);
             });
         }
         url.search = params.toString();
         loadBooks(url.href);
     });
     //filtre temizleme
     $('#clearFilters').on('click', function() {
         $('#searchForm')[0]?.reset();
         $('.category-filter').removeClass('active');
         $('.category-filter[data-category=""]').addClass('active');
         $('input[name="publishers[]"]').prop('checked', false);
         $('input[name="sort"]').prop('checked', false);
         const url = new URL(window.location.origin + '/');
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