<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CommunityController;
use App\Http\Controllers\Web\EditorRecommendationController;
use App\Http\Controllers\Web\ProfileController;
use App\Models\EditorRecommendation;
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
    Route::post('topluluk/{user_book_id}/yorum-ekle', [CommunityController::class, 'replyToReview'])->name('replyToReview');
    Route::post('topluluk/{user_book_id}/faydali', [CommunityController::class, 'toggleHelpful'])->name('toggleHelpful');
    Route::delete('/topluluk/yorum/{id}', [CommunityController::class, 'deleteComment'])->name('delete-comment');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::post('/sifre-degistir', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/profil-guncelle', [ProfileController::class, 'updateProfile'])->name('update-profile');
});



Route::get('/topluluk/{user_book_id?}', [CommunityController::class, 'index'])->name('community');
Route::get('/haftanin-kitabi', [EditorRecommendationController::class, 'index'])->name('weekly-book');

Route::get('/kayit-ol', function () {
    return view('web.auth.register');
})->name('register');
Route::post('/kayit-ol', [AuthController::class, 'register']);

Route::get('/giris-yap', function () {
    return view('web.auth.login');
})->name('login');
Route::post('giris-yap', [AuthController::class, 'login']);
Route::post('/cikis-yap', [AuthController::class, 'logout'])->name('cikis');


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    // Route::get('/kitap-listesi', function () {
    //     return view('panel.books');
    // })->name('book_list');


    Route::group(['prefix' => 'books'], function () {
        Route::get('', [AdminBookController::class, 'index'])->name('book-index');
        //     Route::get('create', [BookController::class, 'create'])->name('book-add');
        //     Route::post('create/{id}', [BookController::class, 'store'])->name('book-add-post');


    });
});
