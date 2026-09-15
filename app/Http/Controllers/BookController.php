<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $req)
    {
        $categories = Category::withCount('books')->get();
        $query = $req->input('category');
        if ($query) {
            $books = Book::with(['category', 'publisher'])->whereHas('category', function ($slugQuery) use ($query) {
                $slugQuery->where('slug', $query);
            })->get();
        } else {
            $books = Book::with(['category', 'publisher'])->get();
        }
        return view('mainpage', compact(['books', 'categories']));
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
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('book-detail', compact('book'));
    }
}
