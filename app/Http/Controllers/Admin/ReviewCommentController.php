<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\ReviewComment;
use Illuminate\Http\Request;

class ReviewCommentController extends BaseController
{
    protected $model = ReviewComment::class;
    protected $page = 'review-comments';
    public function index(Request $request)
    {
        $query = ReviewComment::with([
            'user:id,name,email',
            'userBook.user:id,name',
            'userBook.book:id,title,slug',
        ]);
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('userBook.book', function ($b) use ($search) {
                        $b->where('title', 'like', '%' . $search . '%');
                    });
            });
        }
        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.review-comments.index', ['items' => $items] + $this->extraData());
    }
    public function destroy($id)
    {
        ReviewComment::findOrFail($id)->delete();

        return back()->with('success', 'Yanıt silindi.');
    }
}
