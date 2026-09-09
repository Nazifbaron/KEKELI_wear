<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'is_admin'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_admin'          => 'boolean',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /*
    |----------------------------------------------------------
    | Vérifie que l'utilisateur est admin
    | Utilisé par AdminMiddleware
    |----------------------------------------------------------
    */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
}
