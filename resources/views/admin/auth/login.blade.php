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
        /*
        | Page login centrée — fond sombre avec logo KEKELI
        */
        body.admin-body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }

        .login-card {
            background: #111; border: 1px solid rgba(255,255,255,.08);
            border-radius: 8px; padding: 40px 36px;
            width: 100%; max-width: 380px;
        }
        .login-logo {
            text-align: center; margin-bottom: 32px;
        }
        .login-logo img  { height: 44px; margin: 0 auto 12px; }
        .login-logo span {
            display: block; font-size: 11px; font-weight: 600;
            letter-spacing: .2em; text-transform: uppercase;
            color: rgba(255,255,255,.4);
        }
        .login-title {
            font-size: 18px; font-weight: 700; color: #fff;
            margin-bottom: 24px; text-align: center;
        }
        .login-error {
            background: rgba(228,40,41,.1); border: 1px solid rgba(228,40,41,.3);
            border-left: 3px solid #e42829; border-radius: 4px;
            padding: 10px 14px; font-size: 12px; color: #ef9a9a;
            margin-bottom: 16px;
        }
        .login-success {
            background: rgba(76,175,80,.1); border: 1px solid rgba(76,175,80,.3);
            border-left: 3px solid #4caf50; border-radius: 4px;
            padding: 10px 14px; font-size: 12px; color: #81c784;
            margin-bottom: 16px;
        }
        .login-footer {
            text-align: center; margin-top: 20px;
            font-size: 11px; color: rgba(255,255,255,.25);
        }
    </style>
</head>
<body class="admin-body">

    <div class="login-card">

        {{-- Logo --}}
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}"
                 alt="KEKELI WEAR"
                 style="filter:brightness(0) invert(1)" />
            <span>Espace Administration</span>
        </div>

        <div class="login-title">Connexion</div>

        {{-- Message succès (après logout) --}}
        @if(session('success'))
            <div class="login-success">{{ session('success') }}</div>
        @endif

        {{-- Erreurs --}}
        @if($errors->any())
            <div class="login-error">
                @foreach($errors->all() as $error)
                    <div>✗ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Formulaire de connexion --}}
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-admin-group">
                <label class="form-admin-label">Adresse email</label>
                <input class="form-admin-input"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="admin@kekeliwear.com"
                       autocomplete="email"
                       autofocus
                       required />
            </div>

            <div class="form-admin-group" style="margin-bottom:20px">
                <label class="form-admin-label">Mot de passe</label>
                <input class="form-admin-input"
                       type="password"
                       name="password"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       required />
            </div>

            {{-- Se souvenir de moi --}}
            <label class="check-label" style="margin-bottom:20px">
                <input type="checkbox" name="remember" value="1" />
                <span>Rester connecté</span>
            </label>

            <button type="submit" class="btn-admin-primary" style="width:100%;justify-content:center">
                Se connecter →
            </button>

        </form>

        <div class="login-footer">
            © {{ date('Y') }} KEKELI WEAR — Accès réservé
        </div>

    </div>

</body>
</html>
