<div id="adminCategoryList" data-ajax-list>
    <div class="card border-0 shadow-sm bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Kategori Adı</th>
                        <th class="text-center">Kitap Sayısı</th>
                        <th>Ekleme Tarihi</th>
                        <th class="text-end" style="width: 140px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $category)
                    <tr>
                        <td><small class="text-muted">{{ $items->firstItem() + $loop->index }}</small></td>
                        <td>
                            <div class="fw-bold text-dark">{{$category->name}}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary-subtle text-secondary border">{{$category->books_count}}</span>
                        </td>
                        <td><small class="text-muted">{{$category->created_at}}</small></td>
                        <td class="text-end">

                            <a href="{{route('category-edit', $category->id)}}" class="btn btn-sm btn-outline-primary me-1" title="Düzenle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                data-action="{{route('category-destroy', $category->id)}}"
                                data-title="{{$category->name}}" title="Sil">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @if($items->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Kategori bulunamadı.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Sayfalama -->
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Toplam {{ $items->total() }} kategoriden
                {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
            </small>
            {{ $items->links() }}
        </div>
    </div>
</div>