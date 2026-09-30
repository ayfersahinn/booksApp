<?php

namespace App\Http\Controllers;

use App\Models\UserBook;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {

        $reviews = UserBook::with([
            'user',
            'book.category'
        ])
            ->whereNotNull('review')
            ->latest()
            ->get();
        return view('community', compact('reviews'));
    }
}
