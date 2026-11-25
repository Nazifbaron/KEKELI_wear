<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- CSRF token lu par kekeli.js pour toutes les requêtes POST --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>KEKELI WEAR - Contact</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon-rounded.png') }}?v={{ filemtime(public_path('images/icon-rounded.png')) }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/kekeli.css') }}" />
    @stack('styles')
</head>

<body class="nav-solid-page" style="margin-top: 30px;">

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
                <a href="{{ url('/contact') }}" class="nav-link">Contact</a>
            </div>

            {{-- Bouton menu mobile --}}
            <button class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu">☰</button>

        </div>
    </nav>

    {{-- Menu mobile plein écran --}}
    <div class="mobile-menu" id="mobile-menu">
        <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Fermer">✕</button>
        <a href="{{ url('/') }}" onclick="closeMobileMenu()">Accueil</a>
        <a href="{{ url('/contact') }}" onclick="closeMobileMenu()">Contact</a>
    </div>

    <main>
        <section id="contact">
            <div class="container">
                <header class="ct__head" data-v-5ebeeac8>
                    <p class="ct__eyebrow" data-v-5ebeeac8>Contact</p>
                    <h1 class="ct__title" data-v-5ebeeac8>Écrivez-nous</h1>
                    <p class="ct__intro" data-v-5ebeeac8>Une question sur une commande,
                        un produit ou une création sur-mesure ? Notre équipe vous répond avec plaisir.</p>
                </header>
                {{-- ===== CARTES D'INFORMATIONS ===== --}}
                <div class="contact-cards" style="margin-bottom:48px">
                    <article class="contact-card">
                        <h2 data-v-5ebeeac8="" class="ct__bt">Nos coordonnées</h2>
                        <p data-v-5ebeeac8="" class="ct__line">
                            <strong data-v-5ebeeac8="">Adresse</strong><br data-v-5ebeeac8="">
                            Pk11, Séyivè, Sèmé-Kpodji<br data-v-5ebeeac8="">Cotonou, Bénin
                        </p>
                        <p data-v-5ebeeac8="" class="ct__line">
                            <strong data-v-5ebeeac8="">Téléphone whatsapp</strong><br data-v-5ebeeac8="">
                            <a data-v-5ebeeac8="" href="https://wa.me/{{ config('kekeli.whatsapp') }}" target="_blank" rel="noopener">
                                +229 01 40 13 49 49
                            </a>
                        </p>
                        <p data-v-5ebeeac8="" class="ct__line">
                            <strong data-v-5ebeeac8="">Email</strong><br data-v-5ebeeac8="">
                            <a data-v-5ebeeac8="" href="mailto:kekeliwear229@gmail.com">kekeliwear229@gmail.com</a>
                        </p>
                    </article>
                    <article class="contact-card">
                        <h2 data-v-5ebeeac8="" class="ct__bt">Horaires</h2>
                        <p data-v-5ebeeac8="" class="ct__line">Du lundi au samedi : 09h00 à 20h00</p>
                        <p data-v-5ebeeac8="" class="ct__line ct__muted">Dimanche : Ouverture exceptionnelle en cas de nécessité sinon fermé</p>
                    </article>
                    <article class="contact-card">
                        <h2 data-v-5ebeeac8="" class="ct__bt">Suivez-nous</h2>
                        <p data-v-5ebeeac8="" class="ct__line ct__muted">Retrouvez nos nouveautés et coulisses sur les réseaux.</p>
                        <div class="ct__social" data-v-5ebeeac8>
                            <a data-v-5ebeeac8 href="https://facebook.com/kekeliwear229" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                            <a data-v-5ebeeac8 href="https://wa.me/{{ config('kekeli.whatsapp') }}" target="_blank" rel="noopener noreferrer" title="WhatsApp" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>
                            <a data-v-5ebeeac8 href="https://tiktok.com/@kekeliwear229" target="_blank" rel="noopener noreferrer" title="TikTok" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                            <a data-v-5ebeeac8 href="https://www.instagram.com/kekeliwear229/" target="_blank" rel="noopener noreferrer" title="Instagram" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                            <a data-v-5ebeeac8 href="https://www.youtube.com/@kekeliwear229" target="_blank" rel="noopener noreferrer" title="YouTube" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                        </div>

                    </article>
                    <article class="contact-card">
                        <h2 data-v-5ebeeac8="" class="ct__bt">Livraison</h2>
                        <p data-v-5ebeeac8="" class="ct__line">Tout le Bénin + International<br>Retrait à Sèmé-Kpodji</p>
                        <div class="ci-value"></div>
                    </article>
                </div>

                {{-- <div class="contact-btns">
            <a class="btn-gold" href="https://wa.me/{{ config('kekeli.whatsapp') }}" target="_blank" rel="noopener">
                📱 Écrire sur WhatsApp
                </a>
                <a class="btn-outline" href="mailto:kekeliwear229@gmail.com">✉ Envoyer un email</a>
            </div>--}}

            <div class="contact-lower">
                {{-- ===== FORMULAIRE RAPIDE ===== --}}
                <div class="k-contact-form">
                    <div class="k-form-group">
                        <input
                            class="k-form-input"
                            id="c-name"
                            type="text"
                            placeholder=" "
                            autocomplete="name">
                        <label class="k-form-label" for="c-name">
                            Nom complet
                        </label>
                    </div>
                    <div class="k-form-group">
                        <input
                            class="k-form-input"
                            id="c-phone"
                            type="tel"
                            placeholder=" "
                            autocomplete="tel" required>
                        <label class="k-form-label" for="c-phone">
                            Téléphone
                        </label>
                    </div>
                    <div class="k-form-group">
                        <input
                            class="k-form-input"
                            id="c-email"
                            type="email"
                            placeholder=" "
                            autocomplete="email">
                        <label class="k-form-label" for="c-email">
                            Email (optionnel)
                        </label>
                    </div>

                    <div class="k-form-group">
                        <textarea
                            class="k-form-input k-form-textarea"
                            id="c-message"
                            rows="5"
                            placeholder=" ">
                    </textarea>
                        <label class="k-form-label" for="c-message">
                            Message
                        </label>
                    </div>

                    {{-- Envoie via WhatsApp --}}
                    <button
                        type="button"
                        class="k-btn-contact"
                        onclick="sendContact()">
                        Envoyer le message
                    </button>
                </div>

                {{-- ===== CARTE ===== --}}
                <div class="contact-map">
                    <div class="contact-map-heading">
                        <span class="ci-label">Nous trouver</span>
                        <span class="contact-map-place">Pk11, Séyivè</span>
                    </div>
                    <div class="contact-map-frame">
                        <iframe
                            src="https://www.google.com/maps?q=Pk11%2C%20S%C3%A9yiv%C3%A8%2C%20S%C3%A8m%C3%A9-Kpodji%2C%20Cotonou%2C%20B%C3%A9nin&amp;output=embed"
                            title="Carte de localisation de KEKELI Wear à Pk11, Séyivè"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <!--<a class="contact-map-link" href="https://www.google.com/maps/search/?api=1&amp;query=Pk11%2C%20S%C3%A9yiv%C3%A8%2C%20S%C3%A8m%C3%A9-Kpodji%2C%20Cotonou%2C%20B%C3%A9nin" target="_blank" rel="noopener">
                        Ouvrir l'itinéraire sur Google Maps ↗
                    </a>-->
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
