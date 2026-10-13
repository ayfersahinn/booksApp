@extends('admin.layouts.main')
@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Seçki Düzenle
        </h2>

        <a href="{{route('weekly-book-index')}}" class="btn btn-outline-secondary">İptal</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form id="editRecommendationForm" action="{{route('weekly-book-update', $item->id)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Kitap</label>
                        <select class="form-select" name="book_id" required>
                            <option value="">Kitap Seçin</option>
                            @foreach($books as $book)
                            <option value="{{$book->id}}" {{old('book_id', $item->book_id)== $book->id ? 'selected' : ''}}>{{$book->title}} - {{$book->author}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Başlık</label>
                        <input type="text" class="form-control" name="title"
                            value="{{old('title', $item->title)}}" maxlength="255" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Başlangıç Tarihi</label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{old('start_date', $item->start_date->format('Y-m-d'))}}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bitiş Tarihi</label>
                        <input type="date" class="form-control" name="end_date"
                            value="{{old('end_date', $item->end_date->format('Y-m-d'))}}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Editör Notu / Açıklama</label>
                        <textarea class="form-control" name="description" rows="5">{{old('description', $item->description)}}</textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="#" class="btn btn-light">İptal</a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Güncelle
                    </button>

                </div>
            </form>

        </div>
    </div>

</div>
@endsection