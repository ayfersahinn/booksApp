@extends('admin.layouts.main')
@section('content')
<!-- Üst Aksiyon Barı: Yeni Ekle -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <div class="row g-3 align-items-center justify-content-between">
        <!-- Arama -->
        <div class="col-12 col-md-6">
            <form action="{{route('weekly-book-index')}}" id="weeklyBookFilter" method="GET" data-ajax-filter
                data-target="#adminWeeklyBookList" class="row g-2">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control bg-light border-start-0" placeholder="Kitap adı...">
                        <button type="submit" class="btn btn-primary">Ara</button>

                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-2">
            <button type="button" class="btn btn-outline-secondary w-100"
                data-clear-filter
                data-form="#weeklyBookFilter">
                <i class="bi bi-x-circle me-1"></i>
                Temizle
            </button>
        </div>
        <div class="col-12 col-md-4 text-md-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecommendationModal">
                <i class="bi bi-plus-lg me-1"></i> Yeni Seçki Ekle
            </button>
        </div>
    </div>
</div>

<!-- Seçkiler Tablosu -->
@include('admin.weekly-book.weekly-book-list')



<!-- ================= MODALLAR ================= -->

<!-- 1. YENİ SEÇKİ EKLE MODAL -->
<div class="modal fade" id="addRecommendationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Yeni Seçki Ekle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>

            <form action="{{route('weekly-book-store')}}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Kitap</label>
                            <select class="form-select" name="book_id" required>
                                <option value="">Kitap Seçin</option>
                                @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} - {{ $book->author }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Başlık</label>
                            <input type="text" class="form-control" name="title" required maxlength="255"
                                value="Editörün Seçimi" placeholder="Örn: Bu Haftanın Kitabı">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Başlangıç Tarihi</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bitiş Tarihi</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Editör Notu / Açıklama</label>
                            <textarea class="form-control" name="description" rows="4"
                                placeholder="Bu kitabı neden öneriyorsunuz?"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Kaydet
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- 2. SEÇKİ SİLME ONAY MODALI -->
<div class="modal fade" id="deleteRecommendationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Seçki Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash text-danger display-4 d-block mb-3"></i>
                <p class="mb-1 fw-bold fs-5">Bu seçkiyi silmek istediğinize emin misiniz?</p>
                <p class="fw-semibold text-danger" id="deleteRecommendationTitle"></p>
                <p class="text-muted small">Bu işlem geri alınamaz. Kitabın kendisi silinmez, sadece seçki kaydı kaldırılır.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Vazgeç</button>
                <form id="deleteRecommendationForm" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const deleteRecommendationModal = document.getElementById('deleteRecommendationModal');

    deleteRecommendationModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('deleteRecommendationForm').action = button.getAttribute('data-action');
        document.getElementById('deleteRecommendationTitle').textContent = button.getAttribute('data-title');
    });
</script>
@endsection