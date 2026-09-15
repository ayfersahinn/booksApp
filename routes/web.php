<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('mainpage');
Route::get('/arama', [BookController::class, 'search'])->name('books.search');

Route::get('/topluluk', function () {
    return view('community');
})->name('community');
Route::get('/haftanin-kitabi', function () {
    return view('weekly-book');
})->name('weekly-book');
Route::get('/kitap/{id}', function ($id) {
    return view('book-detail', ['id' => $id]);
})->name('book-detail');
Route::get('/kayit-ol', function () {
    return view('auth.register');
})->name('register');

Route::get('/giris-yap', function () {
    return view('auth.login');
})->name('login');
