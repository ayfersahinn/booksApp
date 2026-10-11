<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    private function rules(?int $ignoreId = null): array
    {
        return [
            'title'          => 'required|string|max:255',
            'isbn'           => ['nullable', 'string', 'max:17', Rule::unique('books', 'isbn')->ignore($ignoreId)],
            'author'         => 'required|string|max:255',
            'pages'          => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id'    => 'required|exists:categories,id',
            'publisher_id'   => 'required|exists:publishers,id',
            'cover_image'    => 'nullable|image|max:2048',
            'description'    => 'nullable|string',
        ];
    }
    private function messages(): array
    {
        return [
            // Kitap adı
            'title.required' => 'Kitap adı zorunludur.',
            'title.string'   => 'Kitap adı metin olmalıdır.',
            'title.max'      => 'Kitap adı en fazla 255 karakter olabilir.',

            // ISBN
            'isbn.string' => 'ISBN metin olmalıdır.',
            'isbn.max'    => 'ISBN en fazla 17 karakter olabilir.',
            'isbn.unique' => 'Bu ISBN başka bir kitapta zaten kayıtlı.',

            // Yazar
            'author.required' => 'Yazar adı zorunludur.',
            'author.string'   => 'Yazar adı metin olmalıdır.',
            'author.max'      => 'Yazar adı en fazla 255 karakter olabilir.',

            // Sayfa sayısı
            'pages.integer' => 'Sayfa sayısı tam sayı olmalıdır.',
            'pages.min'     => 'Sayfa sayısı en az 1 olmalıdır.',

            // Yayın yılı
            'published_year.integer' => 'Yayın yılı tam sayı olmalıdır.',
            'published_year.min'     => 'Yayın yılı en az 1000 olmalıdır.',
            'published_year.max'     => 'Yayın yılı ' . date('Y') . ' değerinden büyük olamaz.',

            // Kategori
            'category_id.required' => 'Lütfen bir kategori seçin.',
            'category_id.exists'   => 'Seçilen kategori bulunamadı.',

            // Yayınevi
            'publisher_id.required' => 'Lütfen bir yayınevi seçin.',
            'publisher_id.exists'   => 'Seçilen yayınevi bulunamadı.',

            // Kapak görseli
            'cover_image.image'  => 'Kapak dosyası bir resim olmalıdır (jpg, png, gif, webp...).',
            'cover_image.max'    => 'Kapak görseli en fazla 2 MB olabilir.',
            'cover_image.uploaded' => 'Kapak görseli yüklenemedi. Dosya çok büyük olabilir.',

            // Açıklama
            'description.string' => 'Açıklama metin olmalıdır.',
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
        $data = $request->validate($this->rules(), $this->messages());
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        return $this->saveItem($data);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate($this->rules($book->id), $this->messages());
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
