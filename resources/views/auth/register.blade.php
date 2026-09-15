@extends('layouts.app')
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-sm register-card p-4 p-md-5 bg-white">

                <!-- Başlık ve Logo Alanı -->
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Hesap Oluştur</h3>
                    <p class="text-muted small">Aramıza katılmak için bilgilerinizi girin</p>
                </div>

                <!-- Kayıt Formu -->
                <form action="{{route('register')}}" method="POST">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <!-- Ad Soyad -->
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold">Ad Soyad</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" class="form-control bg-light border-start-0 ps-0" id="name" name="name" placeholder="Ad Soyad" required>
                        </div>
                    </div>

                    <!-- E-posta -->
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">E-posta Adresi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" placeholder="ornek@mail.com" required>
                        </div>
                    </div>

                    <!-- Parola -->
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold">Şifre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control bg-light border-start-0 ps-0" id="password" name="password" required>
                        </div>
                    </div>

                    <!-- Parola Tekrarı -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label small fw-bold">Şifre Tekrarı</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <input type="password" class="form-control bg-light border-start-0 ps-0" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <!-- Kullanıcı Sözleşmesi Onayı -->
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label small text-muted" for="terms">
                            <a href="#" class="text-primary text-decoration-none">Kullanım Şartları</a>'nı ve <a href="#" class="text-primary text-decoration-none">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.
                        </label>
                    </div>

                    <!-- Kayıt Ol Butonu -->
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                        Kayıt Ol
                    </button>

                    <!-- Giriş Yap Yönlendirmesi -->
                    <div class="text-center small text-muted">
                        Zaten bir hesabınız var mı?
                        <a href="{{route('login')}}" class="text-primary fw-bold text-decoration-none">Giriş Yap</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection