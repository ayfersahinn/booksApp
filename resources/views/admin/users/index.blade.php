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
    <form action="{{route('user-index')}}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" value="{{request('q')}}" class="form-control bg-light border-start-0" placeholder="Ad, soyad veya e-posta...">
                <button type="submit" class="btn btn-primary">Ara</button>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <select name="role" class="form-select bg-light" onchange="this.form.submit()">
                <option value="">Tüm Roller</option>
                <option value="admin" {{request('role') == 'admin' ? 'selected' : ''}}>Admin</option>
                <option value="user" {{request('role') == 'user' ? 'selected' : ''}}>Üye</option>
            </select>
        </div>


    </form>
</div>

<!-- Kullanıcılar tablosu -->
<div class="card border-0 shadow-sm bg-white">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th class="text-center">Yorum</th>
                    <th>Kayıt Tarihi</th>
                    <th>Email Doğrulama</th>
                    <th>Durum</th>
                    <th class="text-end" style="width: 140px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $user)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{$user->name}}</div>

                    </td>
                    <td>{{$user->email}}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary-subtle text-secondary border">{{$user->reviews_count}}</span>
                    </td>
                    <td><small class="text-muted">{{$user->created_at}}</small></td>
                    <td>
                        @if($user->email_verified_at==true)
                        <span class="badge bg-success-subtle text-success border">Doğrulanmış</span>
                        @else
                        <span class="badge bg-warning-subtle text-warning border">Doğrulanmamış</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('user-toggle-active', $user->id) }}" method="POST" onsubmit="return confirm('Bu kullanıcının durumunu değiştirmek istiyor musun?')">
                            @csrf
                            @method('PATCH')
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    {{ $user->is_active ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <label class="form-check-label small">
                                    {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                                </label>
                            </div>
                        </form>
                    </td>
                    <td class="text-end">
                        @if(Auth::id()!=$user->id)

                        <form action="{{route('user-toggle-admin', $user->id)}}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            @if($user->is_admin)
                            <button type="submit" class="btn btn-sm btn-outline-warning me-1" title="Admin yetkisini kaldır">
                                <i class="bi bi-shield-minus"></i>
                            </button>
                            @else
                            <button type="submit" class="btn btn-sm btn-outline-success me-1" title="Admin yap">
                                <i class="bi bi-shield-plus"></i>
                            </button>
                            @endif
                        </form>

                        <!-- Sil -->
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                            data-action="{{route('user-destroy',$user->id)}}"
                            data-title="{{$user->name}}" title="Sil">
                            <i class="bi bi-trash"></i>
                        </button>
                        @else
                        @if($user->is_super_admin)
                        <span class="badge bg-primary-subtle text-primary border">Süper Admin</span>
                        @else
                        <span class="badge bg-primary-subtle text-primary border">Admin</span>
                        <small class="text-muted">Sen</small>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($items->isEmpty())
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Kullanıcı bulunamadı.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Sayfalama -->
    <!-- Sayfalama (Pagination) -->
    <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Toplam {{ $items->total() }} kullanıcıdan
            {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
        </small>
        {{ $items->links() }}
    </div>

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

@endsection