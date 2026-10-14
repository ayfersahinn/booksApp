<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PublisherController as AdminPublisherController;
use App\Http\Controllers\Admin\ReviewCommentController as AdminReviewCommentController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WeeklyBookController as AdminWeeklyBookController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CommunityController;
use App\Http\Controllers\Web\EditorRecommendationController;
use App\Http\Controllers\Web\ProfileController;
use App\Models\EditorRecommendation;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('mainpage');
Route::get('/kitaplar/{slug}', [BookController::class, 'show'])->name('book-detail');
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


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard-index');
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::group(['prefix' => 'books'], function () {
        Route::get('/', [AdminBookController::class, 'index'])->name('book-index');
        Route::post('', [AdminBookController::class, 'store'])->name('book-store');
        Route::get('/{id}/edit', [AdminBookController::class, 'edit'])->name('book-edit');
        Route::put('/{id}', [AdminBookController::class, 'update'])->name('book-update');
        Route::delete('/{id}', [AdminBookController::class, 'destroy'])->name('book-destroy');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('user-index');
        Route::patch('/{id}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('user-toggle-active');
        Route::patch('/{id}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('user-toggle-admin');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('user-destroy');
        Route::post('/logout', [AdminUserController::class, 'logout'])->name('user-logout');
    });
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('category-index');
        Route::post('', [AdminCategoryController::class, 'store'])->name('category-store');
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy'])->name(('category-destroy'));
        Route::put('/{id}', [AdminCategoryController::class, 'update'])->name('category-update');
        Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])->name('category-edit');
    });
    Route::group(['prefix' => 'publishers'], function () {
        Route::get('/', [AdminPublisherController::class, 'index'])->name('publisher-index');
        Route::post('', [AdminPublisherController::class, 'store'])->name('publisher-store');
        Route::delete('/{id}', [AdminPublisherController::class, 'destroy'])->name(('publisher-destroy'));
        Route::put('/{id}', [AdminPublisherController::class, 'update'])->name('publisher-update');
        Route::get('/{id}/edit', [AdminPublisherController::class, 'edit'])->name('publisher-edit');
    });
    Route::group(['prefix' => 'weekly-book'], function () {
        Route::get('/', [AdminWeeklyBookController::class, 'index'])->name('weekly-book-index');
        Route::post('', [AdminWeeklyBookController::class, 'store'])->name('weekly-book-store');
        Route::delete('/{id}', [AdminWeeklyBookController::class, 'destroy'])->name(('weekly-book-destroy'));
        Route::get('/{id}/edit', [AdminWeeklyBookController::class, 'edit'])->name('weekly-book-edit');
        Route::put('/{id}', [AdminWeeklyBookController::class, 'update'])->name('weekly-book-update');
    });
    Route::group(['prefix' => 'reviews'], function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('review-index');
        Route::delete('/{id}', [AdminReviewController::class, 'destroy'])->name('review-destroy');
    });
    Route::group(['prefix' => 'review-comments'], function () {
        Route::get('/', [AdminReviewCommentController::class, 'index'])->name('review-comment-index');
        Route::delete('/{id}', [AdminReviewCommentController::class, 'destroy'])->name('review-comment-destroy');
    });
});
