<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends BaseController
{
    protected $model = Book::class;
    protected $page = 'books';
}
