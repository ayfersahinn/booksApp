<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use App\Models\EditorRecommendation;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorRecommendationController extends Controller
{
    public function index()
    {
        $with = ['book.category', 'book.publisher', 'book.users'];

        $recommendedBook = EditorRecommendation::with($with)->active()->first()
            ?? EditorRecommendation::with($with)
            ->whereDate('end_date', '<', today())
            ->latest('end_date')
            ->first();
        if (!$recommendedBook) {
            return redirect()->route('mainpage');
        }
        $reviewCount = UserBook::where('book_id', $recommendedBook->book_id)
            ->whereNotNull('review')->where('review', '!=', '')
            ->count();
        $averageRating = UserBook::where('book_id', $recommendedBook->book_id)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;
        $user = Auth::user();

        $status = null;

        if ($user) {
            $userBook = $user->books()
                ->where('books.id', $recommendedBook->book_id)
                ->first();

            $status = $userBook?->pivot->status;
        }
        $userReview = null;

        if (Auth::check()) {
            $userReview = Auth::user()
                ->books()
                ->where('books.id', $recommendedBook->book_id)
                ->wherePivotNotNull('review')
                ->first();
        }
        $similarBooks = Book::with(['category', 'users'])->where('category_id', $recommendedBook->book->category_id)->where('id', '!=', $recommendedBook->book_id)->limit(4)->get();
        return view('web.weekly-book', compact('recommendedBook', 'reviewCount', 'averageRating', 'status', 'userReview', 'similarBooks'));
    }
}
