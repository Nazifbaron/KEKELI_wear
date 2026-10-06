<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- CSRF token lu par kekeli.js pour toutes les requêtes POST --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>KEKELI WEAR - À propos</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/kekeli.css') }}" />
    @stack('styles')
</head>

<body>
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
                <a href="{{ url('/') }}" class="nav-link active">Accueil</a>
                <a href="{{ url('/about') }}" class="nav-link">À propos</a>
            </div>

            {{-- Bouton menu mobile --}}
            <button class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu">☰</button>

        </div>
    </nav>

    {{-- Menu mobile plein écran --}}
    <div class="mobile-menu" id="mobile-menu">
        <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Fermer">✕</button>
        <a href="{{ url('/') }}" onclick="closeMobileMenu()">Accueil</a>
        <a href="{{ url('/about') }}" onclick="closeMobileMenu()">À propos</a>
    </div>


    <main>

        <section id="about">
            <div class="container">
                <div class="about-grid">

                    {{-- Images empilées des fondatrices --}}
                    <div class="about-img-stack">
                        <div class="about-img1">
                            {{-- Remplacer par la vraie photo de Mrs Dègnon --}}
                            <img src="{{ asset('images/about/dame1.jpg') }}"
                                alt="Mrs Dègnon, co-fondatrice KEKELI WEAR"
                                onerror="this.style.display='none'" />
                        </div>
                        <div class="about-img2">
                            {{-- Remplacer par la vraie photo de Mlle Eunice --}}
                            <img src="{{ asset('images/about/dame2.jpg') }}"
                                alt="Mlle Eunice, co-fondatrice KEKELI WEAR"
                                onerror="this.style.display='none'" />

                        </div>
                    </div>

                    {{-- Texte --}}
                    <div>
                        <div class="sec-label">✦ À propos de la marque</div>
                        <div class="sec-title">
                            Kekeli Wear - une lumière pour la mode au féminin
                        </div>
                        <div class="divider"></div>

                        <p class="about-text">
                            « Kekeli » signifie <span style="color:var(--kgold)">"Lumière"</span>. 
                            Notre maison est née d'un désir simple : révéler la lumière intérieure de chaque femme.
                            Fondée par <strong>Mrs Dègnon</strong> et co-gérée avec str <strong>Mlle Eunice</strong>, <em>KEKELI Wear</em> 
                            est une maison de mode afrofusion basée à Cotonou. Chaque pièce est unique, pensée pour sublimer votre morphologie, 
                            raconter une histoire et affirmer la puissance du féminin.
                            
                        </p>
                        <p class="about-text" style="margin-bottom:36px">
                            <strong>Notre engagement :</strong> nous défendons une mode durable et engagée : des créations écoresponsables et des conseils sincères 
                            pour valoriser le style de la femme béninoise, africaine et internationale... Sans jamais chercher 
                            la perfection, mais le rayonnement!
                            Pour nous, être écoresponsable, c'est 3 respects :
                            Le respect de vous, nos clientes : des pièces uniques, de qualité, qui durent et qui vous subliment vraiment.
                            Le respect de nos artisans : un savoir-faire local valorisé, rémunéré justement et transmis avec dignité.
                            Le respect de notre environnement : une production raisonnée, moins de gaspillage, plus de sens.
                            
                        </p>
                        <p class="about-text" style="margin-bottom:36px">
                            <strong>Notre conviction :</strong> Consommer écoresponsable, ce n'est pas une tendance. C'est choisir de préserver la nature, 
                            protéger notre environnement et laisser une empreinte lumineuse pour les générations futures.
                            100% PIÈCES UNIQUES | 4 UNIVERS DE CRÉATION | BÉNIN & AU-DELÀ
                        </p>

                        {{-- Stats visuelles --}}
                        <div class="about-stats">
                            <div class="stat-card">
                                <div class="stat-num">100%</div>
                                <div class="stat-lab">Pièces uniques</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-num">4</div>
                                <div class="stat-lab">Univers de création</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-num">🌍</div>
                                <div class="stat-lab">Bénin & au-delà</div>
                            </div>
                            <div class="stat-card">
                                <img src="{{ asset('images/logo2.png') }}" alt="Écoresponsable" style="height: 100px; background:black" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    {{-- ===== FOOTER ===== --}}
    @include('components.footer')



    {{-- ===== TOAST NOTIFICATION ===== --}}
    <div class="toast" id="toast"></div>

    {{-- ===== JS PRINCIPAL ===== --}}
    <script src="{{ asset('js/kekeli.js') }}"></script>
    @stack('scripts')

</body>

</html>
