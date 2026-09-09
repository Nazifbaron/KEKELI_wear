<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'tag', 'title', 'title_highlight', 'subtitle',
        'btn_primary_label', 'btn_primary_url',
        'btn_secondary_label', 'btn_secondary_url',
        'image', 'overlay_color', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    /*
    |----------------------------------------------------------
    | Retourne la couleur CSS de l'overlay selon le choix admin
    |----------------------------------------------------------
    */
    public function getOverlayCssAttribute(): string
    {
        return match($this->overlay_color) {
            'blue'   => 'linear-gradient(135deg,rgba(0,0,0,.75),rgba(24,65,131,.35) 60%,transparent)',
            'purple' => 'linear-gradient(135deg,rgba(0,0,0,.75),rgba(58,38,101,.35) 60%,transparent)',
            default  => 'linear-gradient(135deg,rgba(0,0,0,.75),rgba(119,22,14,.35) 60%,transparent)',
        };
    }
}
