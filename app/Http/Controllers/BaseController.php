<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Web\Controller;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $model;
    protected $page;
    public function index(array $extraData = [])
    {

        $items = $this->model::all();

        return view(
            "admin.{$this->page}",
            compact('items') + $extraData
        );
    }

    public function save(array $data)
    {
        $this->model::create($data);

        return redirect()->back();
    }
}
