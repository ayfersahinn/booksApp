<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\UserBook;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    protected $model = UserBook::class;
    protected $page = 'reviews';
    public function index(Request $request)
    {
        $query = UserBook::with(['user:id,name,email', 'book:id,title,slug,cover_image'])
            ->withCount(['helpfuls', 'comments'])
            ->whereNotNull('review')
            ->where('review', '!=', '');
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('review', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('book', function ($b) use ($search) {
                        $b->where('title', 'like', '%' . $search . '%');
                    });
            });
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->has_spoiler === '1') {
            $query->where('has_spoiler', true);
        }
        $items = $query->latest('review_updated_at')->paginate(10)->withQueryString();
        return view('admin.reviews.index', ['items' => $items] + $this->extraData());
    }
}
