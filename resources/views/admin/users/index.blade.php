@extends('admin.layouts.main')
@section('content')

<style>
    .btn-badge {
        cursor: pointer;
        border: 0;
        padding: 0.4em 0.65em;
    }

    .btn-badge:hover {
        opacity: 0.8;
    }
</style>
<!-- Üst alan: Arama ve filtre -->
<div class="card border-0 shadow-sm p-3 bg-white mb-4">
    <form data-ajax-filter id="userFilter"
        action="{{ route('user-index') }}"
        method="GET"
        data-target="#adminUserList" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" value="{{request('q')}}" class="form-control bg-light border-start-0" placeholder="Ad, soyad veya e-posta...">
                <button type="submit" class="btn btn-primary">Ara</button>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <select name="role" class="form-select bg-light">
                <option value="">Tüm Roller</option>
                <option value="admin" {{request('role') == 'admin' ? 'selected' : ''}}>Admin</option>
                <option value="user" {{request('role') == 'user' ? 'selected' : ''}}>Üye</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="button"
                data-clear-filter
                data-form="#userFilter"
                class="btn btn-outline-secondary w-100">
                <i class="bi bi-x-circle me-1"></i> Temizle
            </button>
        </div>

    </form>
</div>

<!-- Kullanıcılar tablosu -->
<div data-ajax-list>
    @include('admin.users.user-list')
</div>

<!-- Kullanıcı silme onay modalı -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Kullanıcı Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-person-x text-danger display-4 d-block mb-3"></i>
                <p class="mb-1 fw-bold fs-5">Bu kullanıcıyı silmek istediğinize emin misiniz?</p>
                <p class="fw-semibold text-danger" id="deleteUserName"></p>
                <p class="text-muted small">Bu işlem geri alınamaz. Kullanıcının yorumları ve favorileri de etkilenebilir.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Vazgeç</button>
                <form id="deleteUserForm" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    const deleteUserModal = document.getElementById('deleteUserModal');

    deleteUserModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('deleteUserForm').action = button.getAttribute('data-action');
        document.getElementById('deleteUserName').textContent = button.getAttribute('data-title');
    });
</script>
@push('scripts')
<script>
    loadAdminList(
        window.location.href,
        '/admin/users',
        '#adminUserList'
    );
</script>
@endpush
@endsection