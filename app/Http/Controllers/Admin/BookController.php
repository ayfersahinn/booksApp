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
    public function index(Request $request)
    {
        $query = Book::query();
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')->orWhere('author', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.books.index', ['items' => $items] + $this->extraData());
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
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();
        return back()->with('success', 'Kitap silindi.');
    }
}
