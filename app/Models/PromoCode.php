<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'category_id', 'discount', 'type',
        'max_uses', 'used_count', 'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'is_active'  => 'boolean',
        'discount'   => 'decimal:2',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];

    /* ============================================================
       RELATION — catégorie ciblée (null = tous les produits)
    ============================================================ */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /* ============================================================
       MÉTHODES
    ============================================================ */

    /*
    | Vérifier validité globale du code (dates, limite, actif)
    */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at  && now()->lt($this->starts_at))  return false;
        if ($this->expires_at && now()->gt($this->expires_at))  return false;
        if ($this->max_uses   && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    /*
    | Vérifier si le code s'applique à un produit donné
    | - Si category_id null → s'applique à tous
    | - Sinon → uniquement aux produits de cette catégorie
    */
    public function appliesToProduct(Product $product): bool
    {
        if (!$this->category_id) return true;
        return $product->category_id === $this->category_id;
    }

    /*
    | Vérifier si le code s'applique à une catégorie donnée
    */
    public function appliesToCategory(int $categoryId): bool
    {
        if (!$this->category_id) return true;
        return $this->category_id === $categoryId;
    }

    /*
    | Calculer la remise sur un montant donné
    */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            return round($amount * ($this->discount / 100));
        }
        return min((float)$this->discount, $amount);
    }

    /*
    | Incrémenter le compteur d'utilisations
    */
    public function markUsed(): void
    {
        $this->increment('used_count');
    }

    /*
    | Label lisible : "-10%" ou "-5 000 XOF"
    */
    public function getLabelAttribute(): string
    {
        return $this->type === 'percentage'
            ? '-' . rtrim(rtrim(number_format((float)$this->discount, 2, '.', ''), '0'), '.') . '%'
            : '-' . number_format((float)$this->discount, 0, ',', ' ') . ' XOF';
    }

    /*
    | Portée lisible pour l'affichage admin
    */
    public function getScopeLabelAttribute(): string
    {
        return $this->category
            ? $this->category->icon . ' ' . $this->category->name . ' uniquement'
            : '🌐 Tous les produits';
    }
}
