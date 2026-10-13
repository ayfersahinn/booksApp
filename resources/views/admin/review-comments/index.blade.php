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
<!-- Üst alan: Arama -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form action="{{route('review-comment-index')}}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control bg-light border-start-0" placeholder="Yanıt, kullanıcı veya kitap ara...">
                <button type="submit" class="btn btn-primary">Ara</button>
            </div>
        </div>

        <div class="col-12 col-md-2">
            <a href="{{route('review-comment-index')}}" class="btn btn-outline-secondary w-100" title="Filtreleri temizle">
                <i class="bi bi-x-circle me-1"></i>Filtre Temizle
            </a>
        </div>
    </form>
</div>

<!-- Yanıtlar tablosu -->
<div class="card border-0 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Yanıtlayan</th>
                    <th style="min-width: 260px;">Yanıt</th>
                    <th>Yanıtlanan Yorum</th>
                    <th>Kitap</th>
                    <th>Tarih</th>
                    <th class="text-end" style="width: 100px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->user?->name }}</div>
                        <small class="text-muted">{{ $item->user?->email }}</small>
                    </td>
                    <td>
                        <div class="line-clamp-2 small text-secondary">{{ $item->content }}</div>

                        @if(mb_strlen($item->content) > 120)
                        <button type="button" class="btn btn-link btn-sm p-0"
                            data-bs-toggle="modal" data-bs-target="#viewCommentModal"
                            data-user="{{ $item->user?->name }}"
                            data-book="{{ $item->userBook?->book?->title }}"
                            data-comment="{{ $item->content }}">
                            Tamamını oku
                        </button>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">Yorum sahibi:</small>
                        <div class="fw-semibold">{{ $item->userBook?->user?->name }}</div>
                    </td>
                    <td>
                        @if($item->userBook?->book)
                        <a href="{{ route('book-detail', $item->userBook->book->slug) }}" class="text-decoration-none text-dark fw-semibold">
                            {{ $item->userBook->book->title }}
                        </a>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $item->created_at->format('d.m.Y H:i') }}</small></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteCommentModal"
                            data-action="{{ route('review-comment-destroy', $item->id) }}"
                            data-title="{{ $item->user?->name }} - {{ $item->userBook?->book?->title }}" title="Yanıtı sil">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Yanıt bulunamadı.</td>
                </tr>
                @endforelse
                @if($items->isEmpty())
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Yanıt bulunamadı.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Sayfalama -->
    <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Toplam {{ $items->total() }} yanıttan
            {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
        </small>
        {{ $items->links() }}
    </div>
</div>


<!-- ================= MODALLAR ================= -->

<!-- 1. YANITIN TAMAMINI GÖRME MODALI -->
<div class="modal fade" id="viewCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-chat-dots me-2 text-primary"></i>Yanıt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-1"><strong id="viewCommentUser"></strong> · <span class="text-muted" id="viewCommentBook"></span></p>
                <hr>
                <p id="viewCommentText" style="white-space: pre-line;"></p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. YANIT SİLME ONAY MODALI -->
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Yanıt Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash text-danger display-4 d-block mb-3"></i>
                <p class="mb-1 fw-bold fs-5">Bu yanıtı silmek istediğinize emin misiniz?</p>
                <p class="fw-semibold text-danger" id="deleteCommentTitle"></p>
                <p class="text-muted small">Bu işlem geri alınamaz. Yanıtın bağlı olduğu yorum silinmez.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Vazgeç</button>
                <form id="deleteCommentForm" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Yanıtın tamamını göster
    document.getElementById('viewCommentModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('viewCommentUser').textContent = button.getAttribute('data-user');
        document.getElementById('viewCommentBook').textContent = button.getAttribute('data-book');
        document.getElementById('viewCommentText').textContent = button.getAttribute('data-comment');
    });

    // Silme modalı
    document.getElementById('deleteCommentModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('deleteCommentForm').action = button.getAttribute('data-action');
        document.getElementById('deleteCommentTitle').textContent = button.getAttribute('data-title');
    });
</script>
@endsection