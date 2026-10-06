<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftVoucher extends Model
{
    protected $fillable = [
        'code', 'initial_amount', 'balance',
        'buyer_name', 'buyer_phone', 'buyer_email',
        'recipient_name', 'recipient_phone',
        'payment_transaction_id', 'payment_status',
        'status', 'paid_at', 'expires_at',
    ];

    protected $casts = [
        'initial_amount' => 'decimal:0',
        'balance'        => 'decimal:0',
        'paid_at'        => 'datetime',
        'expires_at'     => 'datetime',
    ];

    /* ============================================================
       RELATIONS
    ============================================================ */
    public function usages(): HasMany
    {
        return $this->hasMany(GiftVoucherUsage::class);
    }

    /* ============================================================
       MÉTHODES MÉTIER
    ============================================================ */

    /*
    | Générer un code unique : KW-GIFT-XXXXX
    */
    public static function generateCode(): string
    {
        do {
            $code = 'KW-GIFT-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /*
    | Le bon est-il utilisable ?
    | - Payé, actif, solde > 0, pas expiré
    */
    public function isUsable(): bool
    {
        if ($this->payment_status !== 'paid')  return false;
        if (!in_array($this->status, ['active'])) return false;
        if ((float)$this->balance <= 0)        return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        return true;
    }

    /*
    | Utiliser le bon sur une commande
    | Déduit le montant, enregistre l'usage, met à jour le solde
    | Retourne le montant réellement déduit
    */
    public function use(float $amountRequested, ?int $orderId = null): float
    {
        /* On ne peut déduire que ce qui reste dans le solde */
        $amountToDeduct = min($amountRequested, (float)$this->balance);

        $balanceBefore = (float)$this->balance;
        $balanceAfter  = $balanceBefore - $amountToDeduct;

        /* Enregistrer l'usage */
        GiftVoucherUsage::create([
            'gift_voucher_id' => $this->id,
            'order_id'        => $orderId,
            'amount_used'     => $amountToDeduct,
            'balance_before'  => $balanceBefore,
            'balance_after'   => $balanceAfter,
        ]);

        /* Mettre à jour le solde */
        $this->update([
            'balance' => $balanceAfter,
            'status'  => $balanceAfter <= 0 ? 'used' : 'active',
        ]);

        return $amountToDeduct;
    }

    /*
    | Accesseurs lisibles
    */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format((float)$this->balance, 0, ',', ' ') . ' XOF';
    }

    public function getFormattedInitialAmountAttribute(): string
    {
        return number_format((float)$this->initial_amount, 0, ',', ' ') . ' XOF';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'    => 'Actif',
            'used'      => 'Épuisé',
            'expired'   => 'Expiré',
            'cancelled' => 'Annulé',
            'pending'   => 'En attente de paiement',
            default     => ucfirst($this->status),
        };
    }

    public function getUsedAmountAttribute(): float
    {
        return (float)$this->initial_amount - (float)$this->balance;
    }
}
