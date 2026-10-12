<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;

use App\Http\Controllers\BaseController;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends BaseController
{
    protected $model = Publisher::class;
    protected $page = 'publishers';

    public function index(Request $request)
    {
        $query = Publisher::withCount('books');
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('name', 'like', '%' . $search . '%');
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.publishers.index', ['items' => $items] + $this->extraData());
    }
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255|unique:publishers,name'
            ],
            [
                'name.required' => 'Yayınevi adı zorunludur.',
                'name.string'   => 'Yayınevi adı metin olmalıdır.',
                'name.max'      => 'Yayınevi adı en fazla 255 karakter olabilir.',
                'name.unique'   => 'Bu yayınevi zaten kayıtlı.',
            ]
        );
        $data['slug'] = Str::slug($data['name']);
        return $this->saveItem($data);
    }
    public function destroy($id)
    {
        $publisher = Publisher::withCount('books')->findOrFail($id);
        if ($publisher->books_count > 0) {
            return back()->with('error', 'Bu yayınevine bağlı kitaplar var, silinemez.');
        }
        $publisher->delete();
        return back()->with('success', 'Yayınevi silindi.');
    }
}
