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
    private function getBooks(Request $req)
    {
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
        ])->withAvg('users as average_rating', 'user_books.rating');
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

        return $booksQuery
            ->paginate(8)
            ->withQueryString();
    }
    public function index(Request $req)
    {
        $data = [];
        $bookCount = Book::count();
        $userCount = User::count();
        $categories = Category::has('books')->withCount('books')->get();
        $publishers = Publisher::has('books')
            ->withCount('books')
            ->get();
        $books = $this->getBooks($req);
        $reviewCount = DB::table('user_books')
            ->whereNotNull('review')->where('review', '!=', '')
            ->count();

        $lastReviews = UserBook::with(['user:id,name', 'book:id,title,slug,isbn'])->whereNotNull('user_books.review')->where('user_books.review', '!=', '')
            ->latest('review_updated_at')
            ->limit(2)
            ->get();

        $data = [
            'books' => $books,
            'categories' => $categories,
            'search' => $req->input('q'),
            'publishers' => $publishers,
            'lastReviews' => $lastReviews,
            'bookCount' => $bookCount,
            'userCount' => $userCount,
            'reviewCount' => $reviewCount
        ];
        return view('web.mainpage', $data);
    }

    public function bookList(Request $req)
    {
        $books = $this->getBooks($req);
        return view('web.partials.book-list', compact('books'));
    }


    public function show($slug, $isbn)
    {
        $data = [];
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

        $data = [
            'book' => $book,
            'userBook' => $userBook,
            'isFavorite' => $isFavorite,
            'status' => $status,
            'userReview' => $userReview,
            'ratingPercentages' => $ratingPercentages,
            'avgRatings' => $avgRatings,
            'reviewCount' => $reviewCount,
            'statusCount' => $statusCount,
        ];
        return view('web.book-detail', $data);
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
        if (request()->ajax()) {
            $isFavorite = $user->books()
                ->where('books.id', $book->id)
                ->wherePivot('is_favorite', true)
                ->exists();

            return response()->json([
                'success' => true,
                'isFavorite' => $isFavorite
            ]);
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
        if ($req->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $req->input('status')
            ]);
        }

        return back();
    }
    public function removeFromList($id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);
        $user->books()->detach($book->id);
        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }
        return back();
    }
    public function addReview(Request $req, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);

        $validated = $req->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'has_spoiler' => 'nullable|boolean',
        ]);

        if ($req->boolean('has_spoiler') && !$req->filled('review')) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Spoiler işaretlemek için yorum yazmalısınız.'
                ], 422);
            }

            return back()->withErrors([
                'review' => 'Spoiler işaretlemek için yorum yazmalısınız.'
            ]);
        }

        $userBook = $user->books()
            ->where('books.id', $book->id)
            ->first();

        $data = [
            'rating' => $validated['rating'],
            'has_spoiler' => $req->boolean('has_spoiler'),
            'review_updated_at' => now(),
        ];

        if ($req->filled('review')) {
            $data['review'] = $validated['review'];
        }

        if (!$userBook) {
            $user->books()->attach($book->id, $data);
        } else {
            $user->books()->updateExistingPivot($book->id, $data);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Yorumunuz kaydedildi.'
            ]);
        }

        return back()->with('success', 'Yorumunuz kaydedildi.');
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

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Yorumunuz güncellendi.'
            ]);
        }

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

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Yorumunuz silindi.'
            ]);
        }

        return back()->with('success', 'Yorumunuz silindi.');
    }
}
