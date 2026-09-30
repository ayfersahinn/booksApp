<?php

namespace App\Http\Controllers;

use App\Models\ReviewComment;
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
            'comments.user'
        ])
            ->whereNotNull('review')
            ->latest()
            ->get();
        return view('community', compact('reviews'));
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
}
