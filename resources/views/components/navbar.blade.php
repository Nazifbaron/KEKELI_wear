{{-- ============================================================
     components/navbar.blade.php
     Top bar réseaux sociaux (disparaît au scroll)
     + Nav transparente qui devient blanche au scroll
============================================================ --}}

{{-- TOP BAR — réseaux sociaux, non fixe --}}
<div id="top-bar">
    <div class="top-bar-msg">
        Livraison dans tout le Bénin &amp; à l'international ✦
        <strong>Commandez sur WhatsApp</strong>
    </div>
    <div class="top-bar-social">
        <a href="https://facebook.com/kekeliwear229"
           target="_blank" rel="noopener" title="Facebook">f</a>
        <a href="https://wa.me/{{ config('kekeli.whatsapp') }}"
           target="_blank" rel="noopener" title="WhatsApp">W</a>
        <a href="https://tiktok.com/@kekeliwear229"
           target="_blank" rel="noopener" title="TikTok">T</a>
    </div>
</div>

{{-- NAV PRINCIPALE --}}
<nav id="main-nav">
    <div class="nav-inner">

        {{-- Logo --}}
        <a class="nav-logo" href="{{ url('/') }}">
            <img src="{{ asset('images/logo1.png') }}"
                 alt="KEKELI WEAR"
                 style="height:96px;width:auto" />
        </a>

        {{-- Liens desktop --}}
        <div class="nav-links">
            <a href="#hero"        class="nav-link active">Accueil</a>
            <a href="/about"       class="nav-link">À propos</a>
            <a href="#catalogue"   class="nav-link">Nos créations</a>
            <a href="#featured"    class="nav-link">Tendances</a>
            <a href="#shop"        class="nav-link">Boutique</a>
            <a href="#morphology"  class="nav-link">Espace client</a>
            <a href="#reviews"     class="nav-link">Avis</a>
            <a href="#contact"     class="nav-link">Contact</a>
            <a href="{{ route('bon') }}"
                class="nav-link nav-link-gift"
                style="display:inline-flex;align-items:center;gap:6px;
                        background:linear-gradient(135deg,#C9A84C,#a8893e);
                        color:#fff;padding:7px 14px;border-radius:3px;
                        font-size:10px;font-weight:700;letter-spacing:.1em;
                        text-transform:uppercase;text-decoration:none;
                        transition:all .2s">
                    🎁 <span>Offrir un bon</span>
            </a>
        </div>

        {{-- Bouton menu mobile --}}
        <button class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu">☰</button>

    </div>
</nav>

{{-- Menu mobile plein écran --}}
<div class="mobile-menu" id="mobile-menu">
    <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Fermer">✕</button>
    <a href="#hero"       onclick="closeMobileMenu()">Accueil</a>
    <a href="#advantages"  onclick="closeMobileMenu()">Avantages</a>
    <a href="#catalogue"  onclick="closeMobileMenu()">Nos créations</a>
    <a href="#featured"   onclick="closeMobileMenu()">Tendances</a>
    <a href="#shop"       onclick="closeMobileMenu()">Boutique</a>
    <a href="#morphology" onclick="closeMobileMenu()">Espace client</a>
    <a href="#reviews"    onclick="closeMobileMenu()">Avis</a>
    <a href="#contact"    onclick="closeMobileMenu()">Contact</a>
    <a href="#about"      onclick="closeMobileMenu()">À propos</a>
</div>
