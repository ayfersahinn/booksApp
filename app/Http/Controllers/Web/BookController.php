<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $req)
    {
        $bookCount = Book::count();

        $reviewCount = DB::table('user_books')
            ->whereNotNull('review')
            ->count();

        $userCount = User::count();
        $categories = Category::withCount('books')->get();
        $publishers = Publisher::withCount('books')->get();
        $lastReviews = DB::table('user_books')
            ->join('users', 'users.id', '=', 'user_books.user_id')
            ->join('books', 'books.id', '=', 'user_books.book_id')
            ->whereNotNull('user_books.review')
            ->latest('user_books.review_updated_at')
            ->limit(2)
            ->get([
                'users.name',
                'books.title',
                'books.slug',
                'user_books.review',
                'user_books.rating',
                'user_books.review_updated_at',
            ]);
        $query = $req->input('category');
        $search = $req->input('q');
        $publisherQueries = $req->input('publishers', []);
        $sort = $req->input('sort');
        $booksQuery = Book::with([
            'category',
            'publisher',
            'users'
        ])->withCount([
            'users as review_count' => function ($q) {
                $q->whereNotNull('user_books.review');
            },
            'users as rating_count' => function ($q) {
                $q->whereNotNull('user_books.rating');
            }
        ])
            ->withAvg('users as average_rating', 'user_books.rating');
        if ($search) {
            $booksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($cat) use ($search) {
                        $cat->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('publisher', function ($pub) use ($search) {
                        $pub->where('name', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($query) {
            $booksQuery->whereHas('category', function ($slugQuery) use ($query) {
                $slugQuery->where('slug', $query);
            });
        }
        if ($publisherQueries) {
            $booksQuery->whereHas('publisher', function ($slugQuery) use ($publisherQueries) {
                $slugQuery->whereIn('slug', $publisherQueries);
            });
        }
        if ($sort === 'popular') {
            $booksQuery->orderByRaw('(review_count + rating_count) DESC');
        } elseif ($sort === 'rating') {
            $booksQuery->orderByDesc('average_rating');
        } elseif ($sort === 'last') {
            $booksQuery->latest();
        }

        $books = $booksQuery
            ->paginate(8)
            ->withQueryString();
        return view('web.mainpage', compact(['books', 'categories', 'search', 'publishers', 'lastReviews', 'bookCount', 'userCount', 'reviewCount']));
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
        return view('web.mainpage', compact('query', 'books', 'categories'));
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
        $reviewCount = $book->users()->wherePivotNotNull('review')->count();
        $statusCount = $book->users()->wherePivotNotNull('status')->count();
        return view('web.book-detail', compact('book', 'userBook', 'isFavorite', 'status', 'userReview', 'ratingPercentages', 'avgRatings', 'reviewCount', 'statusCount'));
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
                    'has_spoiler' => $req->boolean('has_spoiler'),
                    'review_updated_at' => now(),
                ]
            );
        } else {
            $data = [
                'rating' => $req->input('rating'),
                'has_spoiler' => $req->boolean('has_spoiler'),
                'review_updated_at' => now(),
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
                'review_updated_at' => now(),
            ]
        );

        return back()->with('success', 'Yorumunuz güncellendi.');
    }
    public function deleteReview($id)
    {
        $user = Auth::user();
        $user->books()->updateExistingPivot($id, [
            'rating' => null,
            'review' => null,
            'has_spoiler' => false,
            'review_updated_at' => null
        ]);
        return back();
    }
}
