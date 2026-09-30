<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    protected $fillable = [
        'user_id',
        'user_book_id',
        'content',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userBook()
    {
        return $this->belongsTo(UserBook::class);
    }
}
