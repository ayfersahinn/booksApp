<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewHelpful extends Model
{
    protected $table = 'review_helpful';
    protected $fillable = [
        'user_id',
        'user_book_id',
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
