<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{

protected $fillable = [''];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function helpfuls()
    {
        return $this->hasMany(ReviewHelpful::class);
    }
    public function comments()
    {
        return $this->hasMany(ReviewComment::class);
    }
}
