<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'isbn',
        'slug',
        'author',
        'pages',
        'published_year',
        'category_id',
        'publisher_id',
        'cover_image',
        'description',
    ];
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
            'review',
            'has_spoiler',
            'review_updated_at'
        ])->withTimestamps();
    }
    public function  editorRecommendation()
    {
        return $this->hasOne(EditorRecommendation::class);
    }
}
