<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = [
        'full_name', 'whatsapp', 'morphology',
        'back_size', 'chest', 'waist', 'hips', 'height',
        'dress_length', 'top_length', 'skirt_length',
        'notes', 'product_id', 'status',
    ];

    public function product() { return $this->belongsTo(Product::class); }
    public function orders()  { return $this->hasMany(Order::class); }

    /**
     * Détecte la morphologie depuis les mensurations.
     * Logique identique au JS front — une seule source de vérité côté back.
     */
    public function detectMorphology(): string
    {
        $chest = $this->chest ?? 0;
        $waist = $this->waist ?? 0;
        $hips  = $this->hips  ?? 0;

        if (!$chest || !$waist || !$hips) return 'undetermined';

        $diff  = abs($chest - $hips);
        $ratio = $hips > 0 ? $waist / $hips : 0;

        if ($diff <= 5 && $ratio <= 0.75)  return 'hourglass';
        if ($chest > $hips + 5)            return 'inverted-triangle';
        if ($hips  > $chest + 5)           return 'pear';
        if ($ratio > 0.85)                 return 'round';
        return 'rectangle';
    }

    public static function morphologyLabel(string $key): string
    {
        return match($key) {
            'hourglass'          => 'Sablier',
            'inverted-triangle'  => 'Triangle inversé',
            'pear'               => 'Poire',
            'round'              => 'Ronde / Pomme',
            'rectangle'          => 'Rectangle',
            default              => 'Indéterminée',
        };
    }
}
