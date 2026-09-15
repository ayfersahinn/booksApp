<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_books'
        )->withPivot([
            'status',
            'is_favorite',
            'rating',
            'review'
        ])->withTimestamps();
    }
}
