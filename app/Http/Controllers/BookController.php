<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $req)
    {
        $categories = Category::withCount('books')->get();
        $query = $req->input('category');
        $booksQuery = Book::with([
            'category',
            'publisher',
            'users' => function ($query) {
                $query->where('users.id', Auth::id());
            }
        ]);
        if ($query) {
            $booksQuery->whereHas('category', function ($slugQuery) use ($query) {
                $slugQuery->where('slug', $query);
            });
        }
        $books = $booksQuery->get();
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
        $categories = Category::all();
        return view('mainpage', compact('query', 'books', 'categories'));
    }
    public function show($id)
    {
        $book = Book::findOrFail($id);
        $isFavorite = Auth::user()->books()->where('books.id', $book->id)->wherePivot('is_favorite', true)->exists();
        $userBook = Auth::user()
            ->books()
            ->where('books.id', $book->id)
            ->first();

        $status = $userBook?->pivot->status;
        return view('book-detail', compact('book', 'isFavorite', 'status'));
    }
    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $userBook = $user->books()->where('books.id', $book->id)->first();


        if (!$userBook) {
            $user->books()->attach($book->id, [
                'status' => null,
                'is_favorite' => true
            ]);
        } else {
            if ($userBook->pivot->is_favorite == true) {
                $user->books()->updateExistingPivot(
                    $book->id,
                    ['is_favorite' => false]
                );
            } else {
                $user->books()->updateExistingPivot($book->id, ['is_favorite' => true]);
            }
        }
        return back();
    }
    public function bookStatus(Request $req, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $userBook = $user->books()->where('books.id', $book->id)->first();
        if (!$userBook) {
            $user->books()->attach(
                $book->id,
                ['status' => $req->input('status'), 'is_favorite' => false]
            );
        } else {
            $user->books()->updateExistingPivot(
                $book->id,
                ['status' => $req->input('status')]
            );
        }

        return back();
    }
}
