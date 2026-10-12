<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $model = User::class;
    protected $page = 'users';
    public function index(Request $request)
    {
        $query = User::withCount(['books as reviews_count' => function ($q) {
            $q->whereNotNull('user_books.review')->where('user_books.review', '!=', '');
        }]);
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($request->role === 'admin') {
            $query->where('is_admin', true);
        } elseif ($request->role === 'user') {
            $query->where('is_admin', false);
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.users.index', ['items' => $items] + $this->extraData());
    }
}
