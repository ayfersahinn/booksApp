@extends('layouts.app')
@section('content')


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
                <select class="form-select form-select-sm" style="width: 180px;">
                    <option value="latest" selected>En Yeniler</option>
                    <option value="highest">En Yüksek Puanlılar</option>
                    <option value="lowest">En Düşük Puanlılar</option>
                    <option value="popular">En Çok Beğenilenler</option>
                </select>
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

                <!-- Yorum Kartı 1 -->
                <div class="card border-0 shadow-sm review-card p-3">
                    <div class="d-flex gap-3">
                        <!-- Kullanıcı Avatarı -->
                        <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="Kullanıcı">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="fw-bold mb-0">Ahmet Yılmaz <span class="text-muted fw-normal small">@ahmetyilmaz</span></h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">2 saat önce inceledi</small>
                                </div>
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <span class="fw-bold text-dark ms-1">5.0</span>
                                </div>
                            </div>

                            <!-- İncelenen Kitap Bilgisi -->
                            <div class="bg-light p-2 rounded d-flex align-items-center gap-3 my-2">
                                <img src="https://via.placeholder.com/70x100" class="book-thumb shadow-sm" alt="Kitap">
                                <div>
                                    <a href="#" class="fw-bold text-dark text-decoration-none d-block">Mirasın İzinde</a>
                                    <small class="text-muted d-block">Yazar: Caner Şahin</small>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">Bilim Kurgu</span>
                                </div>
                            </div>

                            <!-- Yorum Metni -->
                            <p class="card-text text-secondary mb-3">
                                Kurgusu harikaydı, özellikle son bölümlerdeki ters köşeleri hiç beklemiyordum. Yazarın dünya yaratımı ve karakter derinlikleri çok başarılı işlenmiş. Kesinlikle okunması gereken bir başyapıt!
                            </p>

                            <!-- Etkileşim Butonları -->
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-hand-thumbs-up me-1"></i> Faydalı (24)
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-chat me-1"></i> Yanıtla (3)
                                    </button>
                                </div>
                                <button class="btn btn-sm text-muted p-0" title="Bildir">
                                    <i class="bi bi-flag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yorum Kartı 2 (Spoiler İçeren Örnek) -->
                <div class="card border-0 shadow-sm review-card p-3">
                    <div class="d-flex gap-3">
                        <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="Kullanıcı">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="fw-bold mb-0">Zeynep Kaya <span class="text-muted fw-normal small">@zeynepk</span></h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">5 saat önce inceledi</small>
                                </div>
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                    <i class="bi bi-star"></i>
                                    <span class="fw-bold text-dark ms-1">3.5</span>
                                </div>
                            </div>

                            <div class="bg-light p-2 rounded d-flex align-items-center gap-3 my-2">
                                <img src="https://via.placeholder.com/70x100" class="book-thumb shadow-sm" alt="Kitap">
                                <div>
                                    <a href="#" class="fw-bold text-dark text-decoration-none d-block">Zamanın Ötesinde</a>
                                    <small class="text-muted d-block">Yazar: Elif Şahin</small>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">Klasik</span>
                                </div>
                            </div>

                            <!-- Spoiler Uyarısı -->
                            <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Bu yorum <strong>spoiler</strong> içermektedir.</span>
                            </div>

                            <p class="card-text text-secondary mb-3">
                                Kitabın ilk yarısı biraz yavaş ilerlese de ana karakterin 150. sayfadaki kararı tüm hikayenin seyrini değiştirdi. Yine de beklentimin biraz altında kaldı.
                            </p>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-hand-thumbs-up me-1"></i> Faydalı (8)
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-chat me-1"></i> Yanıtla (1)
                                    </button>
                                </div>
                                <button class="btn btn-sm text-muted p-0" title="Bildir">
                                    <i class="bi bi-flag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yorum Kartı 3 -->
                <div class="card border-0 shadow-sm review-card p-3">
                    <div class="d-flex gap-3">
                        <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="Kullanıcı">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="fw-bold mb-0">Mehmet Demir <span class="text-muted fw-normal small">@mdemir</span></h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">1 gün önce inceledi</small>
                                </div>
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                    <span class="fw-bold text-dark ms-1">4.0</span>
                                </div>
                            </div>

                            <div class="bg-light p-2 rounded d-flex align-items-center gap-3 my-2">
                                <img src="https://via.placeholder.com/70x100" class="book-thumb shadow-sm" alt="Kitap">
                                <div>
                                    <a href="#" class="fw-bold text-dark text-decoration-none d-block">Modern Web Mimarisi</a>
                                    <small class="text-muted d-block">Yazar: Mehmet Demir</small>
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle mt-1">Yazılım</span>
                                </div>
                            </div>

                            <p class="card-text text-secondary mb-3">
                                Teknik detaylar, mimari kalıplar ve kod örnekleri son derece anlaşılır anlatılmış. Laravel ve REST API geliştiren her yazılımcının kütüphanesinde bulunmalı.
                            </p>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-hand-thumbs-up me-1"></i> Faydalı (42)
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-chat me-1"></i> Yanıtla (5)
                                    </button>
                                </div>
                                <button class="btn btn-sm text-muted p-0" title="Bildir">
                                    <i class="bi bi-flag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sayfalandırma (Pagination) -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Önceki</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sonraki</a></li>
                </ul>
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
                    <button class="btn btn-light btn-sm fw-bold w-100" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        İnceleme Ekle
                    </button>
                </div>
            </div>

            <!-- Öne Çıkan Eleştirmenler / Okurlar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-trophy text-warning me-2"></i>Ayın Eleştirmenleri
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://via.placeholder.com/35" class="rounded-circle" alt="User">
                            <div>
                                <h6 class="mb-0 small fw-bold">Ayşe Yılmaz</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">42 İnceleme</small>
                            </div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning fw-bold">#1</span>
                    </div>
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://via.placeholder.com/35" class="rounded-circle" alt="User">
                            <div>
                                <h6 class="mb-0 small fw-bold">Can Tekin</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">35 İnceleme</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary fw-bold">#2</span>
                    </div>
                    <div class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://via.placeholder.com/35" class="rounded-circle" alt="User">
                            <div>
                                <h6 class="mb-0 small fw-bold">Merve K.</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">28 İnceleme</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary fw-bold">#3</span>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</main>


@endsection