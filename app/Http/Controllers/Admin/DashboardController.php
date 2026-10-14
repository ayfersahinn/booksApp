<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Book;
use App\Models\Category;
use App\Models\EditorRecommendation;
use App\Models\ReviewComment;
use App\Models\User;
use App\Models\UserBook;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $weekStart = now()->startOfWeek();
        $books = Book::count();
        $weekBooks  = Book::where('created_at', '>=', $weekStart)->count();

        $users = User::count();
        $weekUsers  = User::where('created_at', '>=', $weekStart)->count();
        $passiveUsers = User::where('is_active', false)->count();

        $reviewQuery = UserBook::whereNotNull('review')->where('review', '!=', '');
        $reviews = (clone $reviewQuery)->count();
        $weekReviews  = (clone $reviewQuery)->where('review_updated_at', '>=', $weekStart)->count();
        $lastReviews = UserBook::with(['book', 'user'])
            ->whereNotNull('review')
            ->where('review', '!=', '')
            ->latest('review_updated_at')
            ->limit(3)
            ->get();

        $answers = ReviewComment::count();
        $popularBooks = Book::withCount(['users as reviews_count' => function ($q) {
            $q->whereNotNull('user_books.review')->where('user_books.review', '!=', '');
        }])
            ->orderByDesc('reviews_count')
            ->limit(5)
            ->get();
        $categories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->limit(5)
            ->get();
        $maxCategoryCount = $categories->max('books_count');

        if (!$maxCategoryCount) {
            $maxCategoryCount = 1;
        }

        $newUsers = User::latest()->limit(5)->get();

        $recommendation = EditorRecommendation::with('book')->active()->first();
        return view('admin.dashboard', compact('books', 'weekBooks', 'users', 'weekUsers', 'passiveUsers', 'reviews', 'weekReviews', 'lastReviews', 'answers', 'popularBooks', 'categories', 'maxCategoryCount', 'newUsers', 'recommendation'));
    }
}
