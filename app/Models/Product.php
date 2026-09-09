<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price',
        'main_image', 'images', 'badge', 'badge_color',
        'is_custom', 'is_active', 'is_featured',
        'views', 'likes', 'orders_count', 'heart_score', 'stock',
    ];

    protected $casts = [
        'images'      => 'array',
        'is_custom'   => 'boolean',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'price'       => 'decimal:0',
    ];

    /* ---------- Relations ---------- */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productLikes()
    {
        return $this->hasMany(ProductLike::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }

    /* ---------- Scopes ---------- */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $slug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    public function scopeFeatured($query, int $limit = 6)
    {
        return $query->active()
                     ->where('is_featured', true)
                     ->orderByDesc('heart_score')
                     ->limit($limit);
    }

    /* ---------- Business logic ---------- */

    /**
     * Incrémente les vues et recalcule le score.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
        $this->recalculateScore();
    }

    /**
     * Score = likes × 2 + views × 0.5
     * Marque automatiquement comme featured si score >= seuil.
     */
    public function recalculateScore(): void
    {
        $score     = ($this->likes * 2) + ($this->views * 0.5);
        $threshold = config('kekeli.featured_threshold', 50);

        $this->updateQuietly([
            'heart_score' => $score,
            // Ne pas écraser un is_featured = true posé manuellement par l'admin
            // On le passe à true auto, mais l'admin peut le remettre à false
            'is_featured' => $this->is_featured ?: ($score >= $threshold),
        ]);
    }

    /* ---------- Accessors ---------- */

    public function getFormattedPriceAttribute(): string
    {
        if (is_null($this->price)) return 'Sur devis';
        return number_format($this->price, 0, ',', ' ') . ' XOF';
    }

    /**
     * Prix après application d'un code promo.
     */
    public function getPriceAfterPromo(PromoCode $promo): ?float
    {
        if (is_null($this->price)) return null;
        return $promo->type === 'percentage'
            ? $this->price * (1 - $promo->discount / 100)
            : max(0, $this->price - $promo->discount);
    }
}
