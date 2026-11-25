<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'tag', 'title', 'title_highlight', 'subtitle',
        'media_type', 'video', 'image',
        'btn_primary_label', 'btn_primary_url',
        'btn_secondary_label', 'btn_secondary_url',
        'overlay_color', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeOrdered($q)  { return $q->orderBy('order')->orderBy('id'); }
    public function scopeVisible($q)  { return $q->where('is_active', true)->ordered(); }

    public function getIsVideoAttribute(): bool
    {
        return $this->media_type === 'video' && !empty($this->video);
    }
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
    public function getVideoUrlAttribute(): ?string
    {
        return $this->video ? asset('storage/' . $this->video) : null;
    }

    public function getOverlayCssAttribute(): string
    {
        return match ($this->overlay_color) {
            'blue'   => 'linear-gradient(135deg,rgba(10,30,90,.65),rgba(0,0,0,.35))',
            'purple' => 'linear-gradient(135deg,rgba(70,20,110,.65),rgba(0,0,0,.35))',
            default  => 'linear-gradient(135deg,rgba(160,20,20,.6),rgba(0,0,0,.35))',
        };
    }
}
