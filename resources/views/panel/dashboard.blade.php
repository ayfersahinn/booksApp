@extends('panel.layouts.main')
@section('content')

<!-- 1. İstatistik Kartları -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Toplam Kitap</span>
                    <h3 class="fw-bold mb-0 mt-1">1,248</h3>
                </div>
                <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Kullanıcılar</span>
                    <h3 class="fw-bold mb-0 mt-1">3,420</h3>
                </div>
                <div class="icon-shape bg-success bg-opacity-10 text-success">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Yorumlar</span>
                    <h3 class="fw-bold mb-0 mt-1">856</h3>
                </div>
                <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-chat-left-text"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium">Bekleyen Yanıtlar</span>
                    <h3 class="fw-bold mb-0 mt-1">12</h3>
                </div>
                <div class="icon-shape bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-reply-all"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Orta İçerik: Haftanın Kitabı Özet & Son İncelemeler Tablosu -->
<div class="row g-4">

    <!-- Son Yorumlar Tablosu -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Son Eklenen Yorumlar</h6>
                <a href="#" class="btn btn-sm btn-link text-decoration-none">Tümünü Gör</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover border-top">
                    <thead class="table-light">
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Kitap</th>
                            <th>Puan</th>
                            <th>Durum</th>
                            <th class="text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Ahmet Yılmaz</strong></td>
                            <td>Şeker Portakalı</td>
                            <td><span class="text-warning">⭐⭐⭐⭐⭐</span></td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle">Onaylandı</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Ayşe Kaya</strong></td>
                            <td>Simyacı</td>
                            <td><span class="text-warning">⭐⭐⭐⭐</span></td>
                            <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle">Bekliyor</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Haftanın Kitabı Kartı -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-1"></i> Haftanın Kitabı</h6>
                <button class="btn btn-sm btn-outline-primary">Değiştir</button>
            </div>
            <div class="d-flex gap-3 align-items-center p-2 border rounded bg-light">
                <img src="https://via.placeholder.com/80x120?text=Kitap" class="rounded shadow-sm" alt="Kitap Kapak" style="width: 70px; height: 100px; object-fit: cover;">
                <div>
                    <h6 class="fw-bold mb-1">Kürk Mantolu Madonna</h6>
                    <p class="text-muted small mb-1">Sabahattin Ali</p>
                    <span class="badge bg-primary">Yapı Kredi Yayınları</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection