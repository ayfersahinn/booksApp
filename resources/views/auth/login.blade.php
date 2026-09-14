@extends('layouts.app')
@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-sm login-card p-4 p-md-5 bg-white">

                <!-- Başlık ve Logo Alanı -->
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-box-arrow-in-right fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Hoş Geldiniz</h3>
                    <p class="text-muted small">Devam etmek için hesabınıza giriş yapın</p>
                </div>

                <!-- Giriş Formu -->
                <form action="" method="POST">


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
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label small fw-bold">Şifre</label>

                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control bg-light border-start-0 ps-0" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Beni Hatırla -->
                    <div class="mb-4 form-check d-flex justify-content-between">
                        <div>
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small text-muted" for="remember">
                                Beni Hatırla
                            </label>
                        </div>

                        <a href="#" class="text-primary small text-decoration-none mb-2">Şifremi Unuttum?</a>
                    </div>

                    <!-- Giriş Yap Butonu -->
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                        Giriş Yap
                    </button>

                    <!-- Kayıt Ol Yönlendirmesi -->
                    <div class="text-center small text-muted">
                        Hesabınız yok mu?
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Hemen Kayıt Ol</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


@endsection