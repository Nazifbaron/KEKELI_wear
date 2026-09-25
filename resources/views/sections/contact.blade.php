{{-- ============================================================
     sections/contact.blade.php
     Informations de contact + formulaire rapide
     Le formulaire envoie vers WhatsApp (pas d'email SMTP requis)
============================================================ --}}
<section id="contact">
    <div class="container">
        <div class="sec-label">Nous joindre</div>
        <div class="sec-title" style="font-size:28px;margin-bottom:36px">Contact</div>
        {{-- ===== CARTES D'INFORMATIONS ===== --}}
        <div class="contact-cards" style="margin-bottom:48px">
            <article class="contact-card">
                <div class="ci-icon">📍</div>
                <div class="ci-label">Adresse</div>
                <div class="ci-value">Pk11, Séyivè, Sèmé-Kpodji<br>Cotonou, Bénin</div>
            </article>
            <article class="contact-card">
                <div class="ci-icon">📧</div>
                <div class="ci-label">Email</div>
                <div class="ci-value">
                    <a href="mailto:kekeliwear229@gmail.com">kekeliwear229@gmail.com</a>
                </div>
            </article>
            <article class="contact-card">
                <div class="ci-icon">📱</div>
                <div class="ci-label">WhatsApp</div>
                <div class="ci-value">
                    <a href="https://wa.me/{{ config('kekeli.whatsapp') }}" target="_blank" rel="noopener">
                        +229 01 40 13 49 49
                    </a>
                </div>
            </article>
            <article class="contact-card">
                <div class="ci-icon">🚚</div>
                <div class="ci-label">Livraison</div>
                <div class="ci-value">Tout le Bénin + International<br>Retrait à Sèmé-Kpodji</div>
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

