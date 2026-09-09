<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['first_name', 'city', 'content', 'rating', 'is_approved'];

    protected $casts = ['is_approved' => 'boolean'];

    public function scopeApproved($query) { return $query->where('is_approved', true); }

    public static function averageRating(): float
    {
        return round(static::approved()->avg('rating') ?? 4.9, 1);
    }
}
