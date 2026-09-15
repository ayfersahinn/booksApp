<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['category', 'publisher'])->get();
        return view('mainpage', compact('books'));
    }
    public function search(Request $req)
    {
        $query = $req->input('q');
        if ($query == '') {
            $books = Book::with(['category', 'publisher'])->get();
        } else {

            $books = Book::with(['category', 'publisher'])
                ->where('title', 'like', '%' . $query . '%')
                ->orWhere('author', 'like', '%' . $query . '%')
                ->orWhereHas('category', function ($cat) use ($query) {
                    $cat->where('name', 'like', '%' . $query . '%');
                })
                ->orWhereHas('publisher', function ($pub) use ($query) {
                    $pub->where('name', 'like', '%' . $query . '%');
                })
                ->get();
        }
        return view('mainpage', compact('query', 'books'));
    }
}
