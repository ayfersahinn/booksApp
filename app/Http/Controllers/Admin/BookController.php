<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BookController extends BaseController
{
    protected $model = Book::class;
    protected $page = 'books';

    public function index(array $extraData = [])
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        return parent::index([
            'categories' => $categories,
            'publishers' => $publishers,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:17',
            'author' => 'required|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'cover_image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        return $this->save($validated);
    }
}
