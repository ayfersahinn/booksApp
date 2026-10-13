@extends('admin.layouts.main')
@section('content')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<!-- Üst alan: Arama ve filtre -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="#" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control bg-light border-start-0" placeholder="Yorum, kullanıcı veya kitap ara...">
                <button type="submit" class="btn btn-primary">Ara</button>
            </div>
        </div>

        <div class="col-6 col-md-2">
            <select name="rating" class="form-select bg-light" onchange="this.form.submit()">
                <option value="">Tüm Puanlar</option>
                <option value="5">5 Yıldız</option>
                <option value="4">4 Yıldız</option>
                <option value="3">3 Yıldız</option>
                <option value="2">2 Yıldız</option>
                <option value="1">1 Yıldız</option>
            </select>
        </div>

        <div class="col-6 col-md-3">
            <select name="has_spoiler" class="form-select bg-light" onchange="this.form.submit()">
                <option value="">Spoiler (Tümü)</option>
                <option value="1">Sadece spoiler içerenler</option>
            </select>
        </div>

        <div class="col-12 col-md-2">
            <a href="{{ route('review-index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-x-circle me-1"></i> Filtreleri Temizle
            </a>
        </div>
    </form>
</div>

<!-- Yorumlar tablosu -->
<div class="card border-0 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kullanıcı</th>
                    <th>Kitap</th>
                    <th>Puan</th>
                    <th style="min-width: 260px;">Yorum</th>
                    <th class="text-center">Etkileşim</th>
                    <th>Tarih</th>
                    <th class="text-end" style="width: 120px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{$item->user->name}}</div>
                        <small class="text-muted">{{$item->user->email}}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('storage/' . $item->book->cover_image)}}" class="book-cover-thumb shadow-sm" alt="Kitap Kapak">
                            <a href="#" class="text-decoration-none text-dark fw-semibold">{{$item->book->name}}</a>
                        </div>
                    </td>
                    <td>


                        <span class="text-warning">
                            {{$item->rating}} <i class="bi bi-star-fill"></i>
                        </span>
                    </td>
                    <td>
                        @if($item->has_spoiler)
                        <span class="badge bg-warning-subtle text-warning border mb-1">
                            <i class="bi bi-exclamation-triangle me-1"></i>Spoiler
                        </span>
                        @endif
                        <div class="line-clamp-2 small text-secondary">
                            {{$item->review}}
                        </div>
                        <button type="button" class="btn btn-link btn-sm p-0"
                            data-bs-toggle="modal" data-bs-target="#viewReviewModal"
                            data-user="{{$item->user->name}}"
                            data-book="{{$item->book->title}}"
                            data-review="{{$item->review}}">
                            Tamamını oku
                        </button>
                    </td>
                    <td class="text-center">
                        <small class="text-muted d-block"><i class="bi bi-hand-thumbs-up me-1"></i>{{$item->helpfuls_count}}</small>
                        <small class="text-muted d-block"><i class="bi bi-chat me-1"></i>{{$item->comments_count}}</small>
                    </td>
                    <td><small class="text-muted">{{$item->review_updated_at ?? $item->created_at}}</small></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteReviewModal"
                            data-action=""
                            data-title="" title="Yorumu sil">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
                @if($items->isEmpty())
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Yorum bulunamadı.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Sayfalama -->
    <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Toplam {{ $items->total() }} yorumdan
            {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
        </small>
        {{ $items->links() }}
    </div>
</div>


<!-- ================= MODALLAR ================= -->

<!-- 1. YORUMUN TAMAMINI GÖRME MODALI -->
<div class="modal fade" id="viewReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-chat-quote me-2 text-primary"></i>Yorum
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-1"><strong id="viewReviewUser"></strong> · <span class="text-muted" id="viewReviewBook"></span></p>
                <hr>
                <p id="viewReviewText" style="white-space: pre-line;"></p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. YORUM SİLME ONAY MODALI -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Yorum Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash text-danger display-4 d-block mb-3"></i>
                <p class="mb-1 fw-bold fs-5">Bu yorumu silmek istediğinize emin misiniz?</p>
                <p class="fw-semibold text-danger" id="deleteReviewTitle"></p>
                <p class="text-muted small">Yorum, spoiler bilgisi, beğeniler ve yoruma gelen cevaplar silinir. Kullanıcının puanı ve kitap listesi etkilenmez.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Vazgeç</button>
                <form id="deleteReviewForm" action="#" method="POST">
                    <button type="submit" class="btn btn-danger px-4">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Yorumun tamamını göster
    document.getElementById('viewReviewModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('viewReviewUser').textContent = button.getAttribute('data-user');
        document.getElementById('viewReviewBook').textContent = button.getAttribute('data-book');
        document.getElementById('viewReviewText').textContent = button.getAttribute('data-review');
    });

    // Silme modalı
    document.getElementById('deleteReviewModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('deleteReviewForm').action = button.getAttribute('data-action');
        document.getElementById('deleteReviewTitle').textContent = button.getAttribute('data-title');
    });
</script>
@endsection