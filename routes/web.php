<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mainpage');
})->name('mainpage');
Route::get('/community', function () {
    return view('community');
})->name('community');
