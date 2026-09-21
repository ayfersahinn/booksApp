<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('mainpage');
Route::get('/arama', [BookController::class, 'search'])->name('books-search');
Route::get('/kitaplar/{id}', [BookController::class, 'show'])->name('book-detail');

Route::post('/kitap/{id}/favori', [BookController::class, 'toggleFavorite'])->middleware('auth')->name('book-favorite');
Route::post('/kitap/{id}/status', [BookController::class, 'bookStatus'])->middleware('auth')->name('book-status');
Route::post('/kitap/{id}/listeden-cikar', [BookController::class, 'removeFromList'])->middleware('auth')->name('removeFromList');

Route::get('/topluluk', function () {
    return view('community');
})->name('community');
Route::get('/haftanin-kitabi', function () {
    return view('weekly-book');
})->name('weekly-book');

Route::get('/kayit-ol', function () {
    return view('auth.register');
})->name('register');
Route::post('/kayit-ol', [AuthController::class, 'register']);

Route::get('/giris-yap', function () {
    return view('auth.login');
})->name('login');
Route::post('giris-yap', [AuthController::class, 'login']);
Route::post('/cikis-yap', [AuthController::class, 'logout'])->name('cikis');

Route::get('/profil', [ProfileController::class, 'index'])->middleware('auth')->name('profile');
Route::post('/sifre-degistir', [ProfileController::class, 'changePassword'])->middleware('auth')->name('change-password');
Route::post('/profil-guncelle', [ProfileController::class, 'updateProfile'])->middleware('auth')->name('update-profile');
