<?php

namespace App\Http\Controllers\Web;

use App\Models\EditorRecommendation;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorRecommendationController
{
    public function index()
    {
        $recommendedBook = EditorRecommendation::with(['book.category', 'book.publisher', 'book.users'])->first();
        $reviewCount = UserBook::where('book_id', $recommendedBook->book_id)
            ->whereNotNull('review')
            ->count();
        $averageRating = $recommendedBook->book->users->avg('pivot.rating') ?? 0;
        $user = Auth::user();

        $status = null;

        if ($user) {
            $userBook = $user->books()
                ->where('books.id', $recommendedBook->book_id)
                ->first();

            $status = $userBook?->pivot->status;
        }
        return view('web.weekly-book', compact('recommendedBook', 'reviewCount', 'averageRating', 'status'));
    }
}
