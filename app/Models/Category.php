<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'image',
        'color', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    /* ============================================================
       RELATIONS
    ============================================================ */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /* ============================================================
       SCOPES
    ============================================================ */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /* ============================================================
       ACCESSORS
    ============================================================ */

    /*
    | Nombre de produits actifs — via withCount() dans les queries
    | ou calcul direct (plus lent, éviter en liste)
    */
    public function getProductCountAttribute(): int
    {
        /* Si withCount() a été appelé → utiliser la valeur eagerly loadée */
        if (isset($this->attributes['product_count'])) {
            return (int)$this->attributes['product_count'];
        }
        return $this->products()->where('is_active', true)->count();
    }

    /*
    | Libellé de l'unité selon la catégorie
    */
    public function unitLabel(): string
    {
        return match($this->slug) {
            'accessoires' => 'pièces',
            'surmesure'   => 'modèles',
            default       => 'créations',
        };
    }

    /*
    | CSS de la couleur pour les cartes front
    */
    public function getAccentColorAttribute(): string
    {
        return $this->color ?? '#e42829';
    }
}
