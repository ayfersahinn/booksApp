<div id="adminPublisherList" data-ajax-list>
    <div class="card border-0 shadow-sm bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Yayınevi Adı</th>
                        <th class="text-center">Kitap Sayısı</th>
                        <th>Ekleme Tarihi</th>
                        <th class="text-end" style="width: 140px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $publisher)
                    <tr>
                        <td><small class="text-muted">{{ $items->firstItem() + $loop->index }}</small></td>
                        <td>
                            <div class="fw-bold text-dark">{{$publisher->name}}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary-subtle text-secondary border">{{$publisher->books_count}}</span>
                        </td>
                        <td><small class="text-muted">{{$publisher->created_at}}</small></td>
                        <td class="text-end">

                            <a href="{{route('publisher-edit',$publisher->id)}}" class="btn btn-sm btn-outline-primary me-1" title="Düzenle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deletePublisherModal"
                                data-action="{{route('publisher-destroy', $publisher->id)}}"
                                data-title="{{$publisher->name}}" title="Sil">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @if($items->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Yayınevi bulunamadı.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Sayfalama -->
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Toplam {{ $items->total() }} yayınevinden
                {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
            </small>
            {{ $items->links() }}
        </div>
    </div>
</div>