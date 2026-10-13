<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Book;
use App\Models\EditorRecommendation;
use Illuminate\Http\Request;

class WeeklyBookController extends BaseController
{
    protected $model = EditorRecommendation::class;
    protected $page = 'weekly-book';
    protected function extraData(): array
    {
        return [
            'books' => Book::orderBy('title')->get(['id', 'title', 'author']),
        ];
    }
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id'     => 'required|exists:books,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ], [
            'book_id.required' => 'Lütfen bir kitap seçin.',
            'book_id.exists'   => 'Seçilen kitap bulunamadı.',

            'title.required' => 'Başlık zorunludur.',
            'title.string'   => 'Başlık metin olmalıdır.',
            'title.max'      => 'Başlık en fazla 255 karakter olabilir.',

            'description.string' => 'Açıklama metin olmalıdır.',

            'start_date.required' => 'Başlangıç tarihi zorunludur.',
            'start_date.date'     => 'Başlangıç tarihi geçerli bir tarih olmalıdır.',

            'end_date.required'       => 'Bitiş tarihi zorunludur.',
            'end_date.date'           => 'Bitiş tarihi geçerli bir tarih olmalıdır.',
            'end_date.after_or_equal' => 'Bitiş tarihi, başlangıç tarihinden önce olamaz.',
        ]);
        return $this->saveItem($data);
    }
    public function destroy($id)
    {
        $reccomendationBook = EditorRecommendation::findOrFail($id);
        $reccomendationBook->delete();
        return back()->with('success', 'Seçki silindi.');
    }
}
