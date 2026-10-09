<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BookController extends BaseController
{
    protected $model = Book::class;
    protected $page = 'books';

    protected function extraData(): array
    {
        return [
            'categories' => Category::all(),
            'publishers' => Publisher::all(),
        ];
    }

    private function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'isbn'           => 'nullable|string|max:17',
            'author'         => 'required|string|max:255',
            'pages'          => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id'    => 'required|exists:categories,id',
            'publisher_id'   => 'required|exists:publishers,id',
            'cover_image'    => 'nullable|image|max:2048',
            'description'    => 'nullable|string',
        ];
    }
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        return $this->saveItem($data);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate($this->rules());
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        return $this->updateItem($book, $data);
    }
}
