<div id="adminWeeklyBookList" data-ajax-list>
    <div class="card border-0 shadow-sm bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 70px;">Kapak</th>
                        <th>Kitap</th>
                        <th>Başlık</th>
                        <th>Tarih Aralığı</th>
                        <th>Durum</th>
                        <th class="text-end" style="width: 140px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td><small class="text-muted">{{ $items->firstItem() + $loop->index }}</small></td>

                        <td>
                            <img src="{{asset('storage/' . $item->book->cover_image)}}"
                                class="book-cover-thumb shadow-sm" alt="Kitap Kapak">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{$item->book->title}}</div>
                            <small class="text-muted">{{$item->book->author}}</small>
                        </td>
                        <td>Bu Haftanın Kitabı</td>
                        <td>
                            <small class="text-muted">{{$item->start_date->format('d.m.Y')}} - {{$item->end_date->format('d.m.Y')}}</small>
                        </td>
                        <td>
                            @if($item->start_date->isFuture())
                            <span class="badge bg-info-subtle text-info border">Planlandı</span>
                            @elseif($item->end_date->isPast())
                            <span class="badge bg-secondary-subtle text-secondary border">Yayın sona erdi</span>
                            @else
                            <span class="badge bg-success-subtle text-success border">Yayında</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{route('weekly-book-edit', $item->id)}}" class="btn btn-sm btn-outline-primary me-1" title="Düzenle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteRecommendationModal"
                                data-action="{{route('weekly-book-destroy', $item->id)}}"
                                data-title="{{$item->title}}" title="Sil">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @if($items->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Henüz seçki eklenmemiş.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Sayfalama -->
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">

            <small class="text-muted">
                Toplam {{ $items->total() }} seçkiden
                {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} arası gösteriliyor
            </small>
            {{ $items->links() }}
        </div>
    </div>
</div>