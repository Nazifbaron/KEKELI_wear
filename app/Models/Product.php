<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'is_on_sale', 'sale_price', 'sale_ends_at',
        'badge', 'badge_color', 'main_image',
        'is_custom', 'is_active', 'is_featured',
        'stock', 'likes', 'views', 'heart_score', 'orders_count',
        'fit_details',
    ];

    protected $casts = [
        'category_id'  => 'integer',
        'is_custom'    => 'boolean',
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'is_on_sale'   => 'boolean',
        'price'        => 'decimal:0',
        'sale_price'   => 'decimal:0',
        'heart_score'  => 'decimal:2',
        'sale_ends_at' => 'datetime',
    ];

    /* ============================================================
       RELATIONS
    ============================================================ */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productLikes(): HasMany
    {
        return $this->hasMany(ProductLike::class);
    }

    public function extraImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ============================================================
       SCOPES
    ============================================================ */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    /* ============================================================
       ACCESSORS PROMOTIONS
    ============================================================ */
    /*
    | Est-ce que la promo est active EN CE MOMENT ?
    | Vérifie is_on_sale + sale_price > 0 + date non expirée
    */
    public function getIsCurrentlyOnSaleAttribute(): bool
    {
        if (!$this->is_on_sale) return false;
        if (!$this->price || $this->price <= 0) return false;
        if (!$this->sale_price || $this->sale_price <= 0 || $this->sale_price >= $this->price) return false;
        if ($this->sale_ends_at && now()->gt($this->sale_ends_at)) return false;
        return true;
    }
    /*
    | Pourcentage de réduction affiché sur le badge
    | Ex: "-30%"
    */
    public function getDiscountPercentAttribute(): ?string
    {
        if (!$this->is_currently_on_sale || !$this->price || $this->price <= 0) return null;
        $pct = round((1 - $this->sale_price / $this->price) * 100);
        return '-' . $pct . '%';
    }

    /*
    | Prix effectif (promo si active, sinon prix normal)
    */
    public function getEffectivePriceAttribute(): ?float
    {
        return $this->is_currently_on_sale
            ? (float)$this->sale_price
            : ($this->price ? (float)$this->price : null);
    }

    /*
    | Prix formaté du prix effectif
    */
    public function getFormattedPriceAttribute(): string
    {
        $price = $this->effective_price;
        if (!$price || $price <= 0) return 'Sur devis';
        return number_format($price, 0, ',', ' ') . ' XOF';
    }
    /*
    | Prix original formaté (pour l'affichage barré si promo)
    */
    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if (!$this->is_currently_on_sale) return null;
        if (!$this->price || $this->price <= 0) return null;
        return number_format((float)$this->price, 0, ',', ' ') . ' XOF';
    }

    /*
    | Prix promo formaté
    */
    public function getFormattedSalePriceAttribute(): ?string
    {
        if (!$this->is_currently_on_sale) return null;
        return number_format((float)$this->sale_price, 0, ',', ' ') . ' XOF';
    }

    /*
    | URL image principale
    */
    public function getMainImageUrlAttribute(): ?string
    {
        return $this->main_image ? asset('storage/' . $this->main_image) : null;
    }

    /*
    | Toutes les images (principale + supplémentaires)
    */
    public function getAllImagesAttribute(): array
    {
        $all = [];
        if ($this->main_image) $all[] = asset('storage/' . $this->main_image);
        foreach ($this->extraImages as $img) $all[] = $img->url;
        return $all;
    }

    /* ============================================================
       MÉTHODES MÉTIER
    ============================================================ */
    public function incrementViews(): void
    {
        $this->increment('views');
        $this->recalculateScore();
    }

    public function recalculateScore(): void
    {
        $fresh     = $this->fresh();
        $score     = ($fresh->likes * 2) + ($fresh->views * 0.5);
        $threshold = config('kekeli.featured_threshold', 50);

        $this->update([
            'heart_score' => round($score, 2),
            'is_featured' => $score >= $threshold ? true : $fresh->is_featured,
        ]);
    }
}

