<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mainpage');
})->name('mainpage');
Route::get('/community', function () {
    return view('community');
})->name('community');
Route::get('/book-of-the-week', function () {
    return view('weekly-book');
})->name('weekly-book');
