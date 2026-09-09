<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'discount', 'type', 'max_uses',
        'used_count', 'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
        'discount'   => 'decimal:2',
    ];

    public function orders() { return $this->hasMany(Order::class); }

    public function isValid(): bool
    {
        if (!$this->is_active)                              return false;
        if ($this->starts_at  && now()->lt($this->starts_at))  return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->max_uses   && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    public function markUsed(): void { $this->increment('used_count'); }

    public function getLabelAttribute(): string
    {
        return $this->type === 'percentage'
            ? '-' . $this->discount . '%'
            : '-' . number_format($this->discount, 0, ',', ' ') . ' XOF';
    }
}
