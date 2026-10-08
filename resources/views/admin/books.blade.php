@extends('admin.layouts.main')
@section('content')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        color: #334155;
    }

    /* Sidebar Stilleri */
    #sidebar {
        min-width: 260px;
        max-width: 260px;
        min-height: 100vh;
        background-color: #ffffff;
        border-right: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    #sidebar .sidebar-header {
        padding: 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    #sidebar ul.nav-pills .nav-link {
        color: #64748b;
        font-weight: 500;
        padding: 0.65rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    #sidebar ul.nav-pills .nav-link:hover {
        color: #0d6efd;
        background-color: #f1f5f9;
    }

    #sidebar ul.nav-pills .nav-link.active {
        color: #0d6efd;
        background-color: #e0e7ff;
        font-weight: 600;
    }

    #sidebar ul.nav-pills .nav-link i {
        font-size: 1.1rem;
        margin-right: 0.75rem;
    }

    .navbar-custom {
        background-color: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }

    .avatar-img {
        width: 38px;
        height: 38px;
        object-fit: cover;
    }

    .book-cover-thumb {
        width: 45px;
        height: 65px;
        object-fit: cover;
        border-radius: 4px;
    }

    td {
        font-size: 14px;
    }
</style>


<!-- Üst Aksiyon Batanı: Başlık, Arama ve Yeni Ekle -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <div class="row g-3 align-items-center justify-content-between">
        <!-- Arama ve Filtreler -->
        <div class="col-12 col-md-8">
            <form class="row g-2">
                <div class="col-12 col-sm-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control bg-light border-start-0" placeholder="Kitap adı, ISBN veya Yazar...">
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <select class="form-select bg-light">
                        <option value="">Tüm Kategoriler</option>
                        <option value="1">Roman</option>
                        <option value="2">Bilim Kurgu</option>
                        <option value="3">Tarih</option>
                    </select>
                </div>
                <div class="col-6 col-sm-3">
                    <select class="form-select bg-light">
                        <option value="">Tüm Yayınevleri</option>
                        <option value="1">Can Yayınları</option>
                        <option value="2">İş Bankası Yayınları</option>
                        <option value="3">YKY</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Yeni Kitap Ekle Butonu -->
        <div class="col-12 col-md-4 text-md-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
                <i class="bi bi-plus-lg me-1"></i> Yeni Kitap Ekle
            </button>
        </div>
    </div>
</div>

<!-- Kitaplar Tablosu -->
<div class="card border-0 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">Kapak</th>
                    <th>Kitap Adı</th>
                    <th>ISBN</th>
                    <th>Kategori</th>
                    <th>Yayınevi</th>
                    <th>Ekleme Tarihi</th>
                    <th class="text-end" style="width: 140px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <!--kitaplar -->
                @foreach($items as $book)
                <tr>
                    <td>
                        <img src="https://via.placeholder.com/45x65?text=Kitap" class="book-cover-thumb shadow-sm" alt="Şeker Portakalı">
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{$book->title}}</div>
                        <small class="text-muted"> {{$book->author}} </small>
                    </td>
                    <td><code> {{$book->isbn}} </code></td>
                    <td><span class="badge bg-secondary-subtle text-secondary border"> {{$book->category->name}} </span></td>
                    <td> {{$book->publisher->name}} </td>
                    <td><small class="text-muted">{{$book->created_at}}</small></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editBookModal" title="Düzenle">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal" title="Sil">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- Sayfalama (Pagination) -->
    <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">Toplam 1,248 kitaptan 1 - 10 arası gösteriliyor</small>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Önceki</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Sonraki</a></li>
            </ul>
        </nav>
    </div>
</div>


<!-- ================= MODALLAR ================= -->

<!-- 1. YENİ KİTAP EKLE MODAL -->
<div class="modal fade" id="addBookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Yeni Kitap Ekle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Kitap Adı</label>
                            <input type="text" class="form-control"
                                name="title" required placeholder="Örn: 1984">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">ISBN (17 Karakter)</label>
                            <input type="text" class="form-control"
                                name="isbn" maxlength="17"
                                placeholder="978-x-xxx-xxxxx-x">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Yazar</label>
                            <input type="text" class="form-control"
                                name="author" required
                                placeholder="Örn: George Orwell">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sayfa Sayısı</label>
                            <input type="number" class="form-control"
                                name="page_count" min="1"
                                placeholder="Örn: 328">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Yayın Tarihi</label>
                            <input type="date" class="form-control"
                                name="published_at">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Kategori Seçin</option>
                                <option value="1">Roman</option>
                                <option value="2">Bilim Kurgu</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Yayınevi</label>
                            <select class="form-select" name="publisher_id" required>
                                <option value="">Yayınevi Seçin</option>
                                <option value="1">Can Yayınları</option>
                                <option value="2">YKY</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Kitap Kapak Görseli</label>
                            <input type="file" class="form-control"
                                name="cover_image" accept="image/*">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Açıklama / Özet</label>
                            <textarea class="form-control" name="description"
                                rows="3"
                                placeholder="Kitap hakkında kısa açıklama..."></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">İptal</button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Kaydet
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



<!-- 2. KİTAP DÜZENLE MODAL -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Kitap Düzenle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Kitap Adı</label>
                            <input type="text" class="form-control"
                                name="title"
                                value="Şeker Portakalı" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" class="form-control"
                                name="isbn"
                                value="978-975-07-3860-9"
                                maxlength="17">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Yazar</label>
                            <input type="text" class="form-control"
                                name="author"
                                value="José Mauro de Vasconcelos"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sayfa Sayısı</label>
                            <input type="number" class="form-control"
                                name="page_count"
                                value="182"
                                min="1">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Yayın Tarihi</label>
                            <input type="date" class="form-control"
                                name="published_at"
                                value="1968-01-01">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="category_id">
                                <option value="1" selected>Roman</option>
                                <option value="2">Bilim Kurgu</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Yayınevi</label>
                            <select class="form-select" name="publisher_id">
                                <option value="1" selected>Can Yayınları</option>
                                <option value="2">YKY</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Kapak Görselini Değiştir
                            </label>
                            <input type="file" class="form-control"
                                name="cover_image" accept="image/*">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Açıklama / Özet</label>
                            <textarea class="form-control" name="description"
                                rows="3"
                                placeholder="Kitap hakkında kısa açıklama..."></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">İptal</button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Güncelle
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



<!-- 3. KİTAP SİLME ONAY MODALI -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Kitap Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash text-danger display-4 d-block mb-3"></i>
                <p class="mb-1 fw-bold fs-5">Bu kitabı silmek istediğinize emin misiniz?</p>
                <p class="text-muted small">Bu işlem geri alınamaz ve kitaba bağlı tüm pivot verileri (favoriler, listeler) etkilenebilir.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Vazgeç</button>
                <form action="#" method="POST">
                    <button type="submit" class="btn btn-danger px-4">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection