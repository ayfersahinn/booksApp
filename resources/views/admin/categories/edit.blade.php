@extends('admin.layouts.main')
@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Kategori Düzenle
        </h2>

        <a href="{{ route('category-index') }}" class="btn btn-outline-secondary">
            İptal
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form id="editCategoryForm" action="{{ route('category-update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Kategori Adı</label>
                        <input type="text" class="form-control"
                            name="name"
                            value="{{ old('name', $item->name) }}"
                            maxlength="255" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('category-index') }}" class="btn btn-light">İptal</a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Güncelle
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection