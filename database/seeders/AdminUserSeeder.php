<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /*
    |----------------------------------------------------------
    | Crée le compte administrateur par défaut.
    | IMPORTANT : changer le mot de passe après la première
    | connexion depuis /admin/login
    |
    | Email    : admin@kekeliwear.com
    | Password : Kekeli@2026
    |----------------------------------------------------------
    */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kekeliwear.com'],
            [
                'name'     => 'Admin KEKELI',
                'password' => Hash::make('Kekeli@2026'),
                'is_admin' => true,
            ]
        );

        $this->command->info('✦ Compte admin créé : admin@kekeliwear.com / Kekeli@2026');
        $this->command->warn('  ⚠ Changez le mot de passe après la première connexion !');
    }
}
