@extends('admin.layouts.main')
@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Kitap Düzenle
        </h2>

        <a href="{{ route('book-index') }}" class="btn btn-outline-secondary">
            İptal
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form id="editBookFrom" action="{{route('book-update', $item->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Kitap Adı</label>
                            <input type="text" class="form-control"
                                name="title"
                                value="{{old('title', $item->title)}}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" class="form-control"
                                name="isbn"
                                value="{{old('isbn', $item->isbn)}}"
                                maxlength="17">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Yazar</label>
                            <input type="text" class="form-control"
                                name="author"
                                value="{{old('author', $item->author)}}"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sayfa Sayısı</label>
                            <input type="number" class="form-control" value="{{old('pages', $item->pages)}}"
                                name="pages" min="1  ">

                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Yayın Tarihi</label>
                            <input type="number" class="form-control"
                                name="published_year"
                                value="{{old('published_year', $item->published_year)}}"
                                min="1000"
                                max="{{ date('Y') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="category_id">
                                @foreach($categories as $category)
                                <option value="{{$category->id}}" {{old('category_id', $item->category_id)== $category->id ? 'selected':''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Yayınevi</label>
                            <select class="form-select" name="publisher_id">
                                @foreach($publishers as $publisher)
                                <option value="{{$publisher->id}}" {{old('publisher_id', $item->publisher_id)==$publisher->id ? 'selected' :''}}>{{$publisher->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Kapak Görselini Değiştir
                            </label>
                            @if($item->cover_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $item->cover_image) }}"
                                    alt="{{ $item->title }}"
                                    width="100" class="rounded border">
                                <small class="text-muted ">Mevcut kapak. Değiştirmek için yeni bir dosya seç.</small>
                            </div>
                            @endif

                            <input type="file" class="form-control"
                                name="cover_image" accept="image/*">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Açıklama / Özet</label>
                            <textarea class="form-control" name="description"
                                rows="3">{{old('description', $item->description)}}</textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light">İptal</button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Güncelle
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection