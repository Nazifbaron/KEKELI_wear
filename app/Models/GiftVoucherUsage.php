<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftVoucherUsage extends Model
{
    protected $fillable = [
        'gift_voucher_id', 'order_id',
        'amount_used', 'balance_before', 'balance_after',
    ];

    protected $casts = [
        'amount_used'    => 'decimal:0',
        'balance_before' => 'decimal:0',
        'balance_after'  => 'decimal:0',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(GiftVoucher::class, 'gift_voucher_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
