<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Web\Controller;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $model;
    protected $page;
    protected function extraData(): array
    {
        return [];
    }
    public function index()
    {
        $items = $this->model::all();
        return view("admin.{$this->page}.index", ['items' => $items] + $this->extraData());
    }

    protected function saveItem(array $data)
    {
        $this->model::create($data);

        return back()->with('success', 'Kaydedildi.');
    }
    protected function updateItem($item, array $data)
    {
        $item->update($data);
        return back()->with('success', 'Güncellendi.');
    }
    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        return view("admin.{$this->page}.edit", ['item' => $item] + $this->extraData());
    }
    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return back()->with('success', 'Silindi.');
    }
}
