<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
    |----------------------------------------------------------
    | Page de connexion admin
    |----------------------------------------------------------
    */
    public function showLogin()
    {
        // Déjà connecté en tant qu'admin → dashboard
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /*
    |----------------------------------------------------------
    | Traitement du formulaire de connexion
    |----------------------------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Vérifier que c'est bien un admin
            if (!auth()->user()->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Ce compte n\'a pas les droits administrateur.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    /*
    |----------------------------------------------------------
    | Déconnexion
    |----------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
                         ->with('success', 'Vous êtes déconnecté.');
    }
}
