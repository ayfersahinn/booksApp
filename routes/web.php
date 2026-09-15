<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('mainpage');
Route::get('/arama', [BookController::class, 'search'])->name('books.search');
Route::get('/kitaplar/{id}', [BookController::class, 'show'])->name('book-detail');

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
