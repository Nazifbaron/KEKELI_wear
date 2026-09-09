<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- CSRF token lu par kekeli.js pour toutes les requêtes POST --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>KEKELI WEAR — Une lumière pour la mode au féminin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/kekeli.css') }}" />
    @stack('styles')
</head>
<body>

    {{-- ===== NAVIGATION FIXE ===== --}}
    @include('components.navbar')

    {{-- ===== BARRE STATS (sous la nav) ===== --}}
    @include('components.stats-bar')

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    @include('components.footer')

    {{-- ===== PANIER FLOTTANT ===== --}}
    @include('components.cart')

    {{-- ===== TOAST NOTIFICATION ===== --}}
    <div class="toast" id="toast"></div>

    {{-- ===== JS PRINCIPAL ===== --}}
    <script src="{{ asset('js/kekeli.js') }}"></script>
    @stack('scripts')

</body>
</html>
