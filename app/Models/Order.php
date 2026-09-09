<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'reference', 'session_id',
        'customer_name', 'customer_phone', 'customer_email',
        'delivery_address', 'delivery_city', 'delivery_country',
        'subtotal', 'discount_amount', 'delivery_fee', 'total',
        'promo_code_id', 'payment_method', 'payment_status',
        'payment_reference', 'paid_at', 'status',
        'measurement_id', 'notes',
    ];

    protected $casts = [
        'paid_at'         => 'datetime',
        'subtotal'        => 'decimal:0',
        'discount_amount' => 'decimal:0',
        'delivery_fee'    => 'decimal:0',
        'total'           => 'decimal:0',
    ];

    /* ---------- Relations ---------- */
    public function items()       { return $this->hasMany(OrderItem::class); }
    public function promoCode()   { return $this->belongsTo(PromoCode::class); }
    public function measurement() { return $this->belongsTo(Measurement::class); }

    /* ---------- Helpers ---------- */

    /**
     * Génère une référence unique : KW-2026-00142
     */
    public static function generateReference(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->max('id') ?? 0;
        return 'KW-' . $year . '-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }

    public function isPaid(): bool     { return $this->payment_status === 'paid'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isCancelled(): bool{ return $this->status === 'cancelled'; }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'En attente',
            'paid'       => 'Payée',
            'processing' => 'En confection',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
            'refunded'   => 'Remboursée',
            default      => $this->status,
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending'  => 'En attente de paiement',
            'paid'     => 'Payée',
            'failed'   => 'Échec du paiement',
            'refunded' => 'Remboursée',
            default    => $this->payment_status,
        };
    }

    /**
     * Construit le message WhatsApp de récap commande.
     */
    public function toWhatsAppMessage(): string
    {
        $lines = $this->items->map(fn($i) =>
            "• {$i->product_name} × {$i->quantity} — " . number_format($i->subtotal, 0, ',', ' ') . ' XOF'
        )->join("\n");

        return "✦ Commande KEKELI WEAR\n"
            . "Réf : {$this->reference}\n\n"
            . "Client : {$this->customer_name}\n"
            . "Tél : {$this->customer_phone}\n\n"
            . "Articles :\n{$lines}\n\n"
            . "Total : " . number_format($this->total, 0, ',', ' ') . " XOF\n"
            . "Paiement : {$this->payment_method}";
    }
}
