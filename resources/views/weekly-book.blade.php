@extends('layouts.app')
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
        <span class="text-muted small d-none d-md-inline">14 - 20 Eylül 2026 Haftası</span>
    </div>

    <!-- Haftanın Kitabı Öne Çıkan Kartı (Hero Section) -->
    <div class="featured-hero p-4 p-md-5 mb-5 shadow-lg position-relative overflow-hidden">
        <div class="row align-items-center g-4">

            <!-- Kitap Kapak Görseli -->
            <div class="col-md-4 text-center">
                <img src="https://via.placeholder.com/240x350" class="hero-book-cover img-fluid" alt="Haftanın Kitabı">
            </div>

            <!-- Kitap Detayları & Açıklama -->
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary">Bilim Kurgu / Felsefe</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Haftanın En Çok Okunanı</span>
                </div>

                <h2 class="display-6 fw-bold mb-2">Mirasın İzinde</h2>
                <h3 class="h5 text-white-50 mb-3">Yazar: <strong>Ahmet Yılmaz</strong> | Yayınevi: <strong>Diyar Yayınları</strong></h3>

                <!-- Derecelendirme & İstatistikler -->
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rating-stars me-2 fs-5">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <span class="fw-bold fs-5">4.8</span>
                        <span class="text-white-50 ms-1">(342 Değerlendirme)</span>
                    </div>
                </div>

                <!-- Kitap Özeti -->
                <p class="lead text-light mb-4" style="font-size: 1rem; line-height: 1.7;">
                    Geleceğin dünyasında geçen bu sürükleyici yapıt; insan bilinci, zaman döngüleri ve kaybolmuş uygarlıkların izini süren bir mühendisin maceralarını konu alıyor. Bu hafta editörlerimiz tarafından derin kurgusu ve toplumsal eleştirileri nedeniyle haftanın kitabı seçilmiştir.
                </p>

                <!-- Etkileşim Butonları -->
                <div class="d-flex flex-wrap gap-3">
                    <button class="btn btn-warning btn-lg fw-bold px-4" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="bi bi-star me-2"></i>Değerlendir & Yorum Yap
                    </button>
                    <button class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-bookmark-plus me-2"></i>Okuyacaklarıma Ekle
                    </button>
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
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

            <!-- Kart 1 -->
            <div class="col">
                <div class="card border-0 shadow-sm book-card">
                    <div class="position-relative text-center p-3 bg-light">
                        <img src="https://via.placeholder.com/180x240" class="book-cover shadow-sm" alt="Kitap Kapak">
                        <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" title="Listeme Kaydet">
                            <i class="bi bi-bookmark-plus text-primary fs-6"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary-subtle text-primary category-badge w-auto mb-2 align-self-start">Bilim Kurgu</span>
                        <h5 class="card-title h6 fw-bold mb-1 text-truncate">Karanlık Madde</h5>
                        <p class="card-subtitle text-muted small mb-2">Yazar: Mehmet Kaya</p>

                        <div class="d-flex align-items-center mb-2">
                            <div class="rating-stars me-2 small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <small class="text-muted fw-bold">4.2 (110)</small>
                        </div>

                        <p class="card-text small text-secondary flex-grow-1">
                            Paralel evrenler arasında sıkışıp kalan bir bilim insanının eve dönüş mücadelesi...
                        </p>

                        <div class="pt-2 border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm w-100">İncele</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kart 2 -->
            <div class="col">
                <div class="card border-0 shadow-sm book-card">
                    <div class="position-relative text-center p-3 bg-light">
                        <img src="https://via.placeholder.com/180x240" class="book-cover shadow-sm" alt="Kitap Kapak">
                        <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" title="Listeme Kaydet">
                            <i class="bi bi-bookmark-plus text-primary fs-6"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary-subtle text-primary category-badge w-auto mb-2 align-self-start">Bilim Kurgu</span>
                        <h5 class="card-title h6 fw-bold mb-1 text-truncate">Yapay Zeka Çağı</h5>
                        <p class="card-subtitle text-muted small mb-2">Yazar: Selin Demir</p>

                        <div class="d-flex align-items-center mb-2">
                            <div class="rating-stars me-2 small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <small class="text-muted fw-bold">4.7 (205)</small>
                        </div>

                        <p class="card-text small text-secondary flex-grow-1">
                            Otonom sistemlerin yönettiği bir şehirde insan olmanın anlamını sorgulayan bir distopya.
                        </p>

                        <div class="pt-2 border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm w-100">İncele</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kart 3 -->
            <div class="col">
                <div class="card border-0 shadow-sm book-card">
                    <div class="position-relative text-center p-3 bg-light">
                        <img src="https://via.placeholder.com/180x240" class="book-cover shadow-sm" alt="Kitap Kapak">
                        <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" title="Listeme Kaydet">
                            <i class="bi bi-bookmark-plus text-primary fs-6"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary-subtle text-secondary category-badge w-auto mb-2 align-self-start">Felsefe</span>
                        <h5 class="card-title h6 fw-bold mb-1 text-truncate">Zaman Mimarisi</h5>
                        <p class="card-subtitle text-muted small mb-2">Yazar: Can Arslan</p>

                        <div class="d-flex align-items-center mb-2">
                            <div class="rating-stars me-2 small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <small class="text-muted fw-bold">3.9 (64)</small>
                        </div>

                        <p class="card-text small text-secondary flex-grow-1">
                            Algılanan zaman ile gerçeklik arasındaki farkı felsefi açıdan inceleyen sürükleyici bir deneme.
                        </p>

                        <div class="pt-2 border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm w-100">İncele</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kart 4 -->
            <div class="col">
                <div class="card border-0 shadow-sm book-card">
                    <div class="position-relative text-center p-3 bg-light">
                        <img src="https://via.placeholder.com/180x240" class="book-cover shadow-sm" alt="Kitap Kapak">
                        <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" title="Listeme Kaydet">
                            <i class="bi bi-bookmark-plus text-primary fs-6"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary-subtle text-primary category-badge w-auto mb-2 align-self-start">Bilim Kurgu</span>
                        <h5 class="card-title h6 fw-bold mb-1 text-truncate">Kayıp Galaksi</h5>
                        <p class="card-subtitle text-muted small mb-2">Yazar: Deniz Eren</p>

                        <div class="d-flex align-items-center mb-2">
                            <div class="rating-stars me-2 small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <small class="text-muted fw-bold">4.4 (150)</small>
                        </div>

                        <p class="card-text small text-secondary flex-grow-1">
                            Derin uzay keşiflerinde bulunan gizemli bir sinyalin peşinden giden mürettebatın öyküsü.
                        </p>

                        <div class="pt-2 border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm w-100">İncele</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</main>

<!-- Değerlendirme Modalı -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">"Mirasın İzinde" Kitabını Değerlendir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Puanınız</label>
                        <select class="form-select">
                            <option value="5">⭐⭐⭐⭐⭐ (5/5) - Mükemmel</option>
                            <option value="4">⭐⭐⭐⭐ (4/5) - Çok İyi</option>
                            <option value="3">⭐⭐⭐ (3/5) - Orta</option>
                            <option value="2">⭐⭐ (2/5) - Zayıf</option>
                            <option value="1">⭐ (1/5) - Kötü</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Yorumunuz</label>
                        <textarea class="form-control" rows="4" placeholder="Kitap hakkındaki düşüncelerinizi yazın..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning fw-bold w-100">Gönder</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection