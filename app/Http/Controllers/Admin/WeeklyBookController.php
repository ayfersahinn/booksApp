<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\EditorRecommendation;
use Illuminate\Http\Request;

class WeeklyBookController extends BaseController
{
    protected $model = EditorRecommendation::class;
    protected $page = 'weekly-book';
    public function index(Request $request)
    {
        $query = EditorRecommendation::with('book');
        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('book', function ($b) use ($search) {
                $b->where('title', 'like', '%' . $search . '%');
            });
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.weekly-book.index', ['items' => $items] + $this->extraData());
    }
}
