<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KEKELI Admin — Connexion</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    <style>
        body.admin-body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #FAF8F5;
        }
        .login-wrap {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 16px 56px rgba(0,0,0,.1);
            margin: 24px;
        }
        /* Panneau gauche — brand */
        .login-brand {
            flex: 1;
            background: #1A1A1A;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .login-brand-logo img {
            height: 40px;
            filter: brightness(0) invert(1);
        }
        .login-brand-quote {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }
        .login-brand-quote span { color: #e42829; }
        .login-brand-foot {
            font-size: 11px;
            color: rgba(255,255,255,.3);
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        /* Panneau droit — formulaire */
        .login-form-panel {
            width: 380px;
            background: #fff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-shrink: 0;
        }
        .login-form-title {
            font-size: 22px;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 6px;
        }
        .login-form-sub {
            font-size: 12px;
            color: #8A8A8A;
            margin-bottom: 32px;
        }
        @media (max-width: 640px) {
            .login-brand { display: none; }
            .login-form-panel { width: 100%; }
        }
    </style>
</head>
<body class="admin-body">

<div class="login-wrap">

    {{-- Panneau gauche — branding --}}
    <div class="login-brand">
        <div class="login-brand-logo">
            <img src="{{ asset('images/logo.png') }}" alt="KEKELI WEAR" />
        </div>
        <div>
            <div class="login-brand-quote">
                Une lumière pour<br><span>la mode au féminin.</span>
            </div>
            <p style="font-size:13px;color:rgba(255,255,255,.5);margin-top:16px;line-height:1.6">
                Espace d'administration sécurisé.<br>
                Gérez vos produits, commandes et clients.
            </p>
        </div>
        <div class="login-brand-foot">
            ACCES UNIVERSEL SARL — Cotonou, Bénin
        </div>
    </div>

    {{-- Panneau droit — formulaire --}}
    <div class="login-form-panel">

        <div class="login-form-title">Connexion</div>
        <div class="login-form-sub">Espace réservé aux administrateurs</div>

        {{-- Messages --}}
        @if(session('success'))
            <div class="login-success">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="login-error">
                @foreach($errors->all() as $err)
                    <div>✗ {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-admin-group">
                <label class="form-admin-label">Adresse email</label>
                <input class="form-admin-input"
                       type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="admin@kekeliwear.com"
                       autocomplete="email" autofocus required />
            </div>

            <div class="form-admin-group" style="margin-bottom:20px">
                <label class="form-admin-label">Mot de passe</label>
                <input class="form-admin-input"
                       type="password" name="password"
                       placeholder="••••••••"
                       autocomplete="current-password" required />
            </div>

            <label class="check-label" style="margin-bottom:24px">
                <input type="checkbox" name="remember" value="1" />
                <span>Rester connecté</span>
            </label>

            <button type="submit" class="btn-admin-primary"
                    style="width:100%;justify-content:center;padding:13px">
                Se connecter →
            </button>

        </form>

        <div style="margin-top:28px;font-size:11px;color:#8A8A8A;text-align:center">
            © {{ date('Y') }} KEKELI WEAR — Accès réservé
        </div>

    </div>

</div>

</body>
</html>
