{{-- ============================================================
     sections/contact.blade.php
     Informations de contact + formulaire rapide
     Le formulaire envoie vers WhatsApp (pas d'email SMTP requis)
============================================================ --}}
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
                    <a data-v-5ebeeac8 href="https://facebook.com/kekeliwear229" target="_blank" rel="noopener" title="Facebook">f</a>
                    <a data-v-5ebeeac8 href="https://wa.me/{{ config('kekeli.whatsapp') }}" target="_blank" rel="noopener" title="WhatsApp">W</a>
                    <a data-v-5ebeeac8 href="https://tiktok.com/@kekeliwear229" target="_blank" rel="noopener" title="TikTok">T</a>
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
                        autocomplete="tel">
                    <label class="k-form-label" for="c-phone">
                        Téléphone (optionnel)
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
                <a class="contact-map-link" href="https://www.google.com/maps/search/?api=1&amp;query=Pk11%2C%20S%C3%A9yiv%C3%A8%2C%20S%C3%A8m%C3%A9-Kpodji%2C%20Cotonou%2C%20B%C3%A9nin" target="_blank" rel="noopener">
                    Ouvrir l'itinéraire sur Google Maps ↗
                </a>
            </div>
        </div>
    </div>
</section>

