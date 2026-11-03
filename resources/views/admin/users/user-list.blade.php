<div id="adminUserList" data-ajax-list>
    <div class="card border-0 shadow-sm bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
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
                        <td><small class="text-muted">{{ $items->firstItem() + $loop->index }}</small></td>

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
</div>