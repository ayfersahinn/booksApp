<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditorRecommendation extends Model
{
    protected $fillable = [
        'book_id',
        'title',
        'description',
        'start_date',
        'end_date',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeActive($query)
    {
        return $query->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today());
    }
}
