<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* ============================================================
   CART ITEM — Panier temporaire par session
   Converti en OrderItems lors du checkout
============================================================ */
class CartItem extends Model
{
    protected $fillable = [
        'session_id', 'product_id', 'quantity',
        'promo_code_id', 'measurement_id',
    ];

    public function product()     { return $this->belongsTo(Product::class); }
    public function promoCode()   { return $this->belongsTo(PromoCode::class); }
    public function measurement() { return $this->belongsTo(Measurement::class); }

    /**
     * Sous-total d'un item (prix × quantité, nul si sur devis)
     */
    public function getSubtotalAttribute(): ?float
    {
        if (is_null($this->product->price)) return null;
        return $this->product->price * $this->quantity;
    }
}
