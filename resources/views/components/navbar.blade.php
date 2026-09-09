{{-- ============================================================
     components/navbar.blade.php
     Navigation fixe avec ancres, réseaux sociaux, menu mobile.
     La classe .active est gérée par le scroll spy dans kekeli.js
============================================================ --}}
<nav id="main-nav">
    <div class="nav-inner">

        {{-- Logo — image réelle depuis public/images/logo.png --}}
        <a class="nav-logo" href="#hero">
            <img src="{{ asset('images/logo.png') }}"
                 alt="KEKELI WEAR"
                 style="height:38px;width:auto;display:block" />
        </a>

        {{-- Liens de navigation — ancres vers les sections --}}
        <div class="nav-links">
            <a href="#hero"        class="nav-link active">Accueil</a>
            <a href="#about"       class="nav-link">À propos</a>
            <a href="#catalogue"   class="nav-link">Nos créations</a>
            <a href="#featured"    class="nav-link">Tendances</a>
            <a href="#shop"        class="nav-link">Boutique</a>
            <a href="#morphology"  class="nav-link">Espace Client</a>
            <a href="#reviews"     class="nav-link">Avis</a>
            <a href="#contact"     class="nav-link">Contact</a>
        </div>

        {{-- Réseaux sociaux --}}
        <div class="nav-social">
            <a href="https://facebook.com/kekeliwear229"
               target="_blank" rel="noopener" title="Facebook">f</a>
            <a href="https://wa.me/{{ config('kekeli.whatsapp') }}"
               target="_blank" rel="noopener" title="WhatsApp">W</a>
            <a href="https://tiktok.com/@kekeliwear229"
               target="_blank" rel="noopener" title="TikTok">T</a>
        </div>

        {{-- Bouton menu mobile --}}
        <button class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu">☰</button>
    </div>
</nav>

{{-- Menu déroulant mobile --}}
<div class="mobile-menu" id="mobile-menu">
    <a href="#hero">Accueil</a>
    <a href="#about">À propos</a>
    <a href="#catalogue">Collections</a>
    <a href="#featured">Tendances</a>
    <a href="#shop">Boutique</a>
    <a href="#morphology">Espace Client</a>
    <a href="#reviews">Avis</a>
    <a href="#contact">Contact</a>
</div>
