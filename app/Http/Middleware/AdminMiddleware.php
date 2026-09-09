<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /*
    |----------------------------------------------------------
    | Vérifie que l'utilisateur est connecté ET admin.
    | Si non connecté → redirige vers /admin/login
    | Si connecté mais pas admin → erreur 403
    |----------------------------------------------------------
    */
    public function handle(Request $request, Closure $next): Response
    {
        // Non connecté → page de login admin
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // Connecté mais pas admin → accès refusé
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
