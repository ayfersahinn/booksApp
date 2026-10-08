<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index()
    {
        $items = $this->model::all();
        return view("admin.{$this->page}", compact('items'));
    }
}
