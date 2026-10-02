<?php

use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CommunityController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('mainpage');
Route::get('/kitaplar/{slug}', [BookController::class, 'show'])->name('book-detail');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/kitap/{id}/favori', [BookController::class, 'toggleFavorite'])->name('book-favorite');
    Route::post('/kitap/{id}/status', [BookController::class, 'bookStatus'])->name('book-status');
    Route::post('/kitap/{id}/listeden-cikar', [BookController::class, 'removeFromList'])->name('removeFromList');

    Route::post('/kitap/{id}/yorum-ekle', [BookController::class, 'addReview'])->name('add-review');
    Route::put('/kitap/{id}/yorum', [BookController::class, 'updateReview'])->name('update-review');
    Route::delete('/kitap/{id}/yorum-sil', [BookController::class, 'deleteReview'])->name('delete-review');
});



Route::get('/topluluk', [CommunityController::class, 'index'])->name('community');
Route::post('topluluk/{user_book_id}/yorum-ekle', [CommunityController::class, 'replyToReview'])->middleware('auth')->name('replyToReview');
Route::post('topluluk/{user_book_id}/faydali', [CommunityController::class, 'toggleHelpful'])->middleware('auth')->name('toggleHelpful');
Route::delete('/topluluk/yorum/{id}', [CommunityController::class, 'deleteComment'])
    ->middleware('auth')
    ->name('delete-comment');

Route::get('/haftanin-kitabi', function () {
    return view('web.weekly-book');
})->name('weekly-book');

Route::get('/kayit-ol', function () {
    return view('web.auth.register');
})->name('register');
Route::post('/kayit-ol', [AuthController::class, 'register']);

Route::get('/giris-yap', function () {
    return view('web.auth.login');
})->name('login');
Route::post('giris-yap', [AuthController::class, 'login']);
Route::post('/cikis-yap', [AuthController::class, 'logout'])->name('cikis');

Route::get('/profil', [ProfileController::class, 'index'])->middleware('auth')->name('profile');
Route::post('/sifre-degistir', [ProfileController::class, 'changePassword'])->middleware('auth')->name('change-password');
Route::post('/profil-guncelle', [ProfileController::class, 'updateProfile'])->middleware('auth')->name('update-profile');
