<?php

namespace App\Http\Controllers\Web;

use App\Models\ReviewComment;
use App\Models\ReviewHelpful;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {

        $reviews = UserBook::with([
            'user',
            'book.category',
            'comments.user',
            'helpfuls'
        ])
            ->whereNotNull('review')
            ->latest()
            ->get();
        return view('web.community', compact('reviews'));
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
