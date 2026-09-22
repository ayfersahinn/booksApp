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

        $userReview = $book->users()->wherePivotNotNull('review')->get();

        return view('book-detail', compact('book', 'userBook', 'isFavorite', 'status', 'userReview'));
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
    public function removeFromList($id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $user->books()->detach($book->id);
        return back();
    }
    public function addReview(Request $req, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $userBook = $user->books()->where('books.id', $book->id)->first();
        if ($req->boolean('has_spoiler') && !$req->filled('review')) {
            return back()->withErrors([
                'review' => 'Spoiler işaretlemek için yorum yazmalısınız.'
            ]);
        }
        if (!$userBook) {
            $user->books()->attach(
                $book->id,
                [
                    'review' => $req->input('review'),
                    'rating' => $req->input('rating'),
                    'has_spoiler' => $req->boolean('has_spoiler')
                ]
            );
        } else {
            $data = [
                'rating' => $req->input('rating'),
                'has_spoiler' => $req->boolean('has_spoiler')
            ];

            if ($req->filled('review')) {
                $data['review'] = $req->input('review');
            }

            $user->books()->updateExistingPivot(
                $book->id,
                $data
            );
        }
        return back();
    }
    public function updateReview(Request $req, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $validated = $req->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        if ($req->boolean('has_spoiler') && !$req->filled('review')) {
            return back()->withErrors([
                'review' => 'Spoiler işaretlemek için yorum yazmalısınız.'
            ]);
        }
        $user->books()->updateExistingPivot(
            $book->id,
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'],
                'has_spoiler' => $req->boolean('has_spoiler'),
            ]
        );

        return back()->with('success', 'Yorumunuz güncellendi.');
    }
}
