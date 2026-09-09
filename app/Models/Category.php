<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'image', 'color', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ---------- Relations ---------- */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    /* ---------- Scopes ---------- */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /* ---------- Accessors ---------- */
    public function getProductCountAttribute(): int
    {
        return $this->activeProducts()->count();
    }

    /* ---------- Helpers ---------- */
    public function unitLabel(): string
    {
        return match($this->slug) {
            'accessories' => 'pièces',
            'custom'      => 'modèles',
            default       => 'créations',
        };
    }
}