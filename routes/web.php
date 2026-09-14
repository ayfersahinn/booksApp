<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mainpage');
})->name('mainpage');
Route::get('/topluluk', function () {
    return view('community');
})->name('community');
Route::get('/haftanin-kitabi', function () {
    return view('weekly-book');
})->name('weekly-book');
Route::get('/kitap/{id}', function ($id) {
    return view('book-detail', ['id' => $id]);
})->name('book-detail');
