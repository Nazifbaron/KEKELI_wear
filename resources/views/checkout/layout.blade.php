{{-- checkout/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Commande') — KEKELI WEAR</title>
    <link rel="stylesheet" href="{{ asset('css/kekeli.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}" />
    @stack('styles')
</head>
<body class="ck-body">

    {{-- Barre de progression --}}
    <header class="ck-header">
        <a href="{{ route('home') }}" class="ck-logo">
            KEKELI <span>WEAR</span>
        </a>

        <div class="ck-steps">
            @php $step = View::yieldContent('step', 1); @endphp

            <div class="ck-step {{ $step >= 1 ? 'done' : '' }} {{ $step == 1 ? 'active' : '' }}">
                <div class="ck-step-dot">{{ $step > 1 ? '✓' : '1' }}</div>
                <div class="ck-step-label">Panier</div>
            </div>

            <div class="ck-step-line {{ $step >= 2 ? 'done' : '' }}"></div>

            <div class="ck-step {{ $step >= 2 ? 'done' : '' }} {{ $step == 2 ? 'active' : '' }}">
                <div class="ck-step-dot">{{ $step > 2 ? '✓' : '2' }}</div>
                <div class="ck-step-label">Paiement</div>
            </div>

            <div class="ck-step-line {{ $step >= 3 ? 'done' : '' }}"></div>

            <div class="ck-step {{ $step >= 3 ? 'done' : '' }} {{ $step == 3 ? 'active' : '' }}">
                <div class="ck-step-dot">{{ $step > 3 ? '✓' : '3' }}</div>
                <div class="ck-step-label">Confirmation</div>
            </div>
        </div>

        <a href="{{ route('boutique') }}" class="ck-back-link">← Boutique</a>
    </header>

    <main class="ck-main">
        @if(session('success'))
            <div class="ck-alert ck-alert-ok">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="ck-alert ck-alert-err">{{ session('error') }}</div>
        @endif

        @yield('checkout-content')
    </main>

    <footer class="ck-footer">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:center">
            <span>🔒 Paiement sécurisé KKiaPay</span>
            <span style="opacity:.3">·</span>
            <span>KEKELI WEAR · Sèmé-Kpodji, Cotonou</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
