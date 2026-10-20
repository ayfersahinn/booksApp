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
            ->whereNotNull('review')->where('review', '!=', '')
            ->count();

        $userCount = User::count();
        $categories = Category::has('books')->withCount('books')->get();
        $publishers = Publisher::has('books')
            ->withCount('books')
            ->get();
        $lastReviews = UserBook::with(['user:id,name', 'book:id,title,slug,isbn'])->whereNotNull('user_books.review')->where('user_books.review', '!=', '')
            ->latest('review_updated_at')
            ->limit(2)
            ->get();
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
                $q->whereNotNull('user_books.review')->where('review', '!=', '');
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
                    ->orWhere('isbn', 'like', '%' . $search . '%')
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
            $booksQuery->orderByRaw('(review_count + rating_count) DESC')->latest('id');
        } elseif ($sort === 'rating') {
            $booksQuery->orderByDesc('average_rating')->latest('id');
        } else {
            $booksQuery->latest();
        }

        $books = $booksQuery
            ->paginate(8)
            ->withQueryString();
        if ($req->ajax()) {
            return view('web.partials.book-list', compact('books'))->render();
        }
        return view('web.mainpage', compact(['books', 'categories', 'search', 'publishers', 'lastReviews', 'bookCount', 'userCount', 'reviewCount']));
    }


    public function show($slug, $isbn)
    {
        $book = Book::with(['category', 'publisher', 'users'])->where('isbn', $isbn)->firstOrFail();
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
