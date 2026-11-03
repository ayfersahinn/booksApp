@extends('admin.layouts.main')
@section('content')
<!-- Üst Aksiyon Barı: Arama ve Yeni Ekle -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <div class="row g-3 align-items-center justify-content-between">

        <!-- Arama -->
        <div class="col-12 col-md-6">
            <form action="{{route('publisher-index')}}" id="publisherFilter" method="GET" data-ajax-filter
                data-target="#adminPublisherList" class="row g-2">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0" placeholder="Yayınevi adı...">
                        <button type="submit" class="btn btn-primary">Ara</button>

                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-2">
            <button type="button" class="btn btn-outline-secondary w-100"
                data-clear-filter
                data-form="#publisherFilter">
                <i class="bi bi-x-circle me-1"></i>
                Temizle
            </button>
        </div>
        <!-- Yeni Yayınevi Ekle Butonu -->
        <div class="col-12 col-md-4 text-md-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPublisherModal">
                <i class="bi bi-plus-lg me-1"></i> Yeni Yayınevi Ekle
            </button>
        </div>
    </div>
</div>

<!-- Yayınevleri Tablosu -->
@include('admin.publishers.publisher-list')



<!-- ================= MODALLAR ================= -->

<!-- 1. YENİ YAYINEVİ EKLE MODAL -->
<div class="modal fade" id="addPublisherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Yeni Yayınevi Ekle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>

            <form action="{{route('publisher-store')}}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Yayınevi Adı</label>
                            <input type="text" class="form-control" name="name" required maxlength="255" placeholder="Örn: Can Yayınları">
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


<!-- 2. YAYINEVİ SİLME ONAY MODALI -->
<div class="modal fade" id="deletePublisherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Yayınevi Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash text-danger display-4 d-block mb-3"></i>
                <p class="mb-1 fw-bold fs-5">Bu yayınevini silmek istediğinize emin misiniz?</p>
                <p class="fw-semibold text-danger" id="deletePublisherTitle"></p>
                <p class="text-muted small">Bu işlem geri alınamaz. Yayınevine bağlı kitaplar etkilenebilir.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Vazgeç</button>
                <form id="deletePublisherForm" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const deletePublisherModal = document.getElementById('deletePublisherModal');

    deletePublisherModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('deletePublisherForm').action = button.getAttribute('data-action');
        document.getElementById('deletePublisherTitle').textContent = button.getAttribute('data-title');
    });
</script>

@endsection