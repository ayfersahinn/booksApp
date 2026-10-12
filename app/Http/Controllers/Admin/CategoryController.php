<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends BaseController
{
    protected $model = Category::class;
    protected $page = 'categories';
    public function index(Request $request)
    {
        $query = Category::withCount('books');
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('name', 'like', '%' . $search . '%');
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.categories.index', ['items' => $items] + $this->extraData());
    }
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255|unique:categories,name'
            ],
            [
                'name.required' => 'Kategori adı zorunludur.',
                'name.string'   => 'Kategori adı metin olmalıdır.',
                'name.max'      => 'Kategori adı en fazla 255 karakter olabilir.',
                'name.unique'   => 'Bu kategori zaten kayıtlı.',
            ]
        );
        $data['slug'] = Str::slug($data['name']);
        return $this->saveItem($data);
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            ],
            [
                'name.required' => 'Kategori adı zorunludur.',
                'name.string'   => 'Kategori adı metin olmalıdır.',
                'name.max'      => 'Kategori adı en fazla 255 karakter olabilir.',
                'name.unique'   => 'Bu kategori zaten kayıtlı.',
            ]
        );
        $data['slug'] = Str::slug($data['name']);
        return $this->updateItem($category, $data);
    }
    public function destroy($id)
    {
        $category = Category::withCount('books')->findOrFail($id);
        if ($category->books_count > 0) {
            return back()->with('error', 'Bu kategoriye bağlı kitaplar var, silinemez.');
        }
        $category->delete();
        return back()->with('success', 'Kategori silindi.');
    }
}
