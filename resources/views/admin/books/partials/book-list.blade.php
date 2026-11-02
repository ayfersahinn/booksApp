<div id="adminBookList">
    <div class="card border-0 shadow-sm bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
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
                        <td><small class="text-muted">{{ $items->firstItem() + $loop->index }}</small></td>

                        <td>
                            <img src="{{ asset('storage/' . $book->cover_image) }}"
                                class="book-cover-thumb shadow-sm"
                                alt="{{ $book->title }}">
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
                            <a href="{{ route('book-edit', $book->id) }}"
                                class="btn btn-sm btn-outline-primary me-1"
                                title="Düzenle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal" data-action="{{ route('book-destroy', $book->id) }}"
                                data-title="{{ $book->title }}" title="Sil">
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
            <small class="text-muted">
                Toplam {{ $items->total() }} kitaptan
                {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
            </small>
            {{ $items->links() }}
        </div>
    </div>
</div>