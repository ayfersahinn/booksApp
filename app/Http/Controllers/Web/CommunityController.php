<?php

namespace App\Http\Controllers\Web;

use App\Models\ReviewComment;
use App\Models\ReviewHelpful;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index(Request $req, $user_book_id = null)
    {

        $sort = $req->input('sort');
        $reviewQuery = UserBook::with([
            'user',
            'book.category',
            'comments.user',
            'helpfuls'
        ])
            ->withCount('helpfuls')->whereNotNull('review');
        if ($sort === 'highest') {
            $reviewQuery->orderByDesc('rating');
        } elseif ($sort === 'lowest') {
            $reviewQuery->orderBy('rating');
        } elseif ($sort === 'popular') {
            $reviewQuery->orderByDesc('helpfuls_count');
        } else {
            $reviewQuery->latest('updated_at');
        }
        $selectedPage =  $req->input('page', 1);;

        if ($user_book_id) {

            $allReviews = $reviewQuery->clone()->get();

            $position = $allReviews->search(function ($review) use ($user_book_id) {
                return $review->id == $user_book_id;
            });

            if ($position !== false) {
                $selectedPage = floor($position / 3) + 1;
            }
        }
        $reviews = $reviewQuery
            ->paginate(3, ['*'], 'page', $selectedPage)
            ->withQueryString();
        $reviewers = UserBook::with('user')
            ->whereNotNull('review')
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(review) as review_count')
            ->orderBy('review_count', 'desc')
            ->limit(3)
            ->get();
        $selectedReview = null;

        if ($user_book_id) {
            $selectedReview = UserBook::findOrFail($user_book_id);
        }
        return view('web.community', compact('reviews', 'reviewers', 'selectedReview', 'selectedPage'));
    }
    public function replyToReview(Request $req, $user_book_id)
    {
        $content = $req->validate([
            'content' => 'required|string|max:500'
        ]);
        $user = Auth::id();
        $userBook = UserBook::find($user_book_id);
        if ($userBook) {
            ReviewComment::create([
                'user_id' => $user,
                'user_book_id' => $user_book_id,
                'content' => $content['content']
            ]);
        }
        return back();
    }
    public function deleteComment($id)
    {
        $comment = ReviewComment::findOrFail($id);
        $comment->delete();
        return back();
    }
    public function toggleHelpful($user_book_id)
    {
        $user = Auth::id();
        $query = ReviewHelpful::where('user_book_id', $user_book_id)->where('user_id', $user)->first();
        $userBook = UserBook::findOrFail($user_book_id);
        if ($userBook->user_id === $user) {
            return back();
        }

        if ($query) {
            $query->delete();
        } else {
            ReviewHelpful::create([
                'user_book_id' => $user_book_id,
                'user_id' => $user
            ]);
        }
        return back();
    }
}
