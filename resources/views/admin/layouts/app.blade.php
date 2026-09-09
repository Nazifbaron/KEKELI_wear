<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>KEKELI Admin — @yield('title','Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    @stack('styles')
</head>
<body class="admin-body">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="admin-sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <img src="{{ asset('images/icon.png') }}"
                 alt="K" style="height:32px;width:auto" />
            <span>Kekeli <em>Admin</em></span>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="s-icon">📊</span> Dashboard
            </a>

            <div class="sidebar-sep">Catalogue</div>

            {{-- Catégories --}}
            <a href="{{ route('admin.categories') }}"
               class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <span class="s-icon">🗂</span> Catégories
            </a>

            {{-- Produits --}}
            <a href="{{ route('admin.products') }}"
               class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <span class="s-icon">👗</span> Produits
            </a>

            {{-- Hero slides --}}
            <a href="{{ route('admin.hero-slides') }}"
               class="sidebar-link {{ request()->routeIs('admin.hero-slides*') ? 'active' : '' }}">
                <span class="s-icon">🖼</span> Slides Hero
            </a>

            <div class="sidebar-sep">Commerce</div>

            {{-- Commandes --}}
            <a href="{{ route('admin.orders') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <span class="s-icon">🛍</span> Commandes
                @php
                    $pendingOrders = \App\Models\Order::where('status','pending')->count();
                @endphp
                @if($pendingOrders > 0)
                    <span class="sidebar-badge">{{ $pendingOrders }}</span>
                @endif
            </a>

            {{-- Codes promo --}}
            <a href="{{ route('admin.promo-codes') }}"
               class="sidebar-link {{ request()->routeIs('admin.promo-codes*') ? 'active' : '' }}">
                <span class="s-icon">🎁</span> Codes Promo
            </a>

            <div class="sidebar-sep">Clients</div>

            {{-- Mensurations --}}
            <a href="{{ route('admin.measurements') }}"
               class="sidebar-link {{ request()->routeIs('admin.measurements*') ? 'active' : '' }}">
                <span class="s-icon">📐</span> Mensurations
                @php
                    $pendingMeas = \App\Models\Measurement::where('status','received')->count();
                @endphp
                @if($pendingMeas > 0)
                    <span class="sidebar-badge">{{ $pendingMeas }}</span>
                @endif
            </a>

            {{-- Avis --}}
            <a href="{{ route('admin.reviews') }}"
               class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                <span class="s-icon">⭐</span> Avis Clients
                @php
                    $pendingReviews = \App\Models\Review::where('is_approved',false)->count();
                @endphp
                @if($pendingReviews > 0)
                    <span class="sidebar-badge">{{ $pendingReviews }}</span>
                @endif
            </a>

        </nav>

        {{-- Lien vers le site --}}
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <span class="s-icon">🌐</span> Voir le site
            </a>
            {{-- Déconnexion --}}
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="sidebar-link"
                        style="width:100%;background:none;border:none;cursor:pointer;
                               color:rgba(228,40,41,.7);text-align:left">
                    <span class="s-icon">🚪</span> Déconnexion
                </button>
            </form>
        </div>

    </aside>

    {{-- ===== CONTENU PRINCIPAL ===== --}}
    <main class="admin-main">

        {{-- Topbar --}}
        <div class="admin-topbar">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <h1 class="admin-page-title">@yield('title','Dashboard')</h1>
            <div class="topbar-right">
                <span style="font-size:12px;color:#888">
                    {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif

        {{-- Contenu de la page --}}
        <div class="admin-content">
            @yield('content')
        </div>

    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
    </script>
    @stack('scripts')
</body>
</html>
