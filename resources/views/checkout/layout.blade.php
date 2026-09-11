<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>KEKELI WEAR — @yield('title', 'Commande')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/kekeli.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}" />
    @stack('styles')
</head>
<body class="checkout-body">

    {{-- ===== HEADER SIMPLIFIÉ ===== --}}
    <header class="checkout-header">
        <a href="{{ route('home') }}" class="checkout-logo">
            <img src="{{ asset('images/logo.png') }}"
                 alt="KEKELI WEAR"
                 style="height:34px;width:auto" />
        </a>
        {{-- Étapes --}}
        <div class="checkout-steps">
            <div class="step {{ request()->routeIs('checkout.summary') ? 'active' : (request()->routeIs('checkout.confirmation') ? 'done' : '') }}">
                <span class="step-num">1</span>
                <span class="step-label">Récapitulatif</span>
            </div>
            <div class="step-line"></div>
            <div class="step {{ request()->routeIs('checkout.payment.gateway') ? 'active' : (request()->routeIs('checkout.confirmation') ? 'done' : '') }}">
                <span class="step-num">2</span>
                <span class="step-label">Paiement</span>
            </div>
            <div class="step-line"></div>
            <div class="step {{ request()->routeIs('checkout.confirmation') ? 'active' : '' }}">
                <span class="step-num">3</span>
                <span class="step-label">Confirmation</span>
            </div>
        </div>
        {{-- Lien retour --}}
        <a href="{{ route('home') }}#shop" class="checkout-back">
            ← Continuer mes achats
        </a>
    </header>

    {{-- ===== CONTENU ===== --}}
    <main class="checkout-main">
        @yield('content')
    </main>

    {{-- ===== FOOTER MINIMAL ===== --}}
    <footer class="checkout-footer">
        <div>© {{ date('Y') }} KEKELI WEAR — Paiement sécurisé</div>
        <div class="checkout-secure">
            🔒 SSL · 💳 MTN MoMo · Moov Money · Carte bancaire
        </div>
    </footer>

    <div class="toast" id="toast"></div>

    <script>
        var CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

        function apiPost(url, data) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            }).then(r => r.json());
        }

        var toastTimer;
        function showToast(msg, type) {
            var t = document.getElementById('toast');
            if (!t) return;
            t.textContent = msg;
            t.className   = 'toast show' + (type === 'err' ? ' toast-err' : '');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
        }
    </script>
    @stack('scripts')
</body>
</html>
