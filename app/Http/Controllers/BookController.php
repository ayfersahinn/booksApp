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
        $sort = $req->input('sort');
        $booksQuery = Book::with([
            'category',
            'publisher',
            'users'
        ]);
        if ($query) {
            $booksQuery->whereHas('category', function ($slugQuery) use ($query) {
                $slugQuery->where('slug', $query);
            });
        }
        $books = $booksQuery->get();
        $filters = $this->filters($books);
        if ($sort === 'popular') {
            $books = $filters['popularBooks'];
        } elseif ($sort === 'rating') {
            $books = $filters['highPointBooks'];
        } elseif ($sort === 'last') {
            $books = $books->sortByDesc('created_at');
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
        $categories = Category::all();
        return view('mainpage', compact('query', 'books', 'categories'));
    }
    public function filters($books)
    {
        foreach ($books as $book) {
            $reviewCount = $book->users->whereNotNull('pivot.review')->count();
            $ratingCount = $book->users->whereNotNull('pivot.rating')->count();
            $book->popularity = $reviewCount + $ratingCount;
            $ratings = $book->users->whereNotNull('pivot.rating')->pluck('pivot.rating');
            $book->average_rating = $ratings->avg() ?? 0;
        }

        return [
            'popularBooks' => $books->sortByDesc('popularity'),
            'highPointBooks' => $books->sortByDesc('average_rating')
        ];
    }
    public function show($slug)
    {
        $book = Book::with(['category', 'publisher', 'users'])->where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        $userBook = null;
        $isFavorite = false;

        if ($user) {
            $userBook = $user->books()
                ->where('books.id', $book->id)
                ->first();
            $isFavorite = $user->books()->where('books.id', $book->id)->wherePivot('is_favorite', true)->exists();
        }

        $status = $userBook?->pivot->status;

        $userReview = $book->users()->wherePivotNotNull('review')->get();

        $ratingCounts = $book->users
            ->whereNotNull('pivot.rating')
            ->groupBy('pivot.rating')
            ->map->count();

        $totalRatings = $ratingCounts->sum();
        $ratings = $book->users->pluck('pivot.rating')->filter();
        $avgRatings = $ratings->avg();
        $ratingPercentages = [];

        for ($i = 5; $i >= 1; $i--) {
            $count = $ratingCounts->get($i, 0);

            $ratingPercentages[$i] = $totalRatings > 0
                ? round(($count / $totalRatings) * 100)
                : 0;
        }
        return view('book-detail', compact('book', 'userBook', 'isFavorite', 'status', 'userReview', 'ratingPercentages', 'avgRatings'));
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
