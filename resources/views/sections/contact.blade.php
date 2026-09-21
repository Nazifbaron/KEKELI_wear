{{-- ============================================================
     sections/contact.blade.php
     Informations de contact + formulaire rapide
     Le formulaire envoie vers WhatsApp (pas d'email SMTP requis)
============================================================ --}}
<section id="contact">
    <div class="container">
        <div class="sec-label">Nous joindre</div>
        <div class="sec-title" style="font-size:28px;margin-bottom:36px">Contact</div>
        <div class="contact-grid">
            {{-- ===== INFOS DE CONTACT ===== --}}
            <div class="contact-info">
                <div class="ci-item">
                    <div class="ci-icon">📍</div>
                    <div>
                        <div class="ci-label">Adresse</div>
                        <div class="ci-value">
                            Pk11, Séyivè, Sèmé-Kpodji<br>Cotonou, Bénin
                        </div>
                    </div>
                </div>
                <div class="ci-item">
                    <div class="ci-icon">📧</div>
                    <div>
                        <div class="ci-label">Email</div>
                        <div class="ci-value">
                            <a href="mailto:kekeliwear229@gmail.com"
                                style="color:inherit;text-decoration:none">
                                kekeliwear229@gmail.com
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ci-item">
                    <div class="ci-icon">📱</div>
                    <div>
                        <div class="ci-label">WhatsApp</div>
                        <div class="ci-value">
                            <a href="https://wa.me/{{ config('kekeli.whatsapp') }}"
                                target="_blank" rel="noopener"
                                style="color:inherit;text-decoration:none">
                                +229 01 40 13 49 49
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ci-item">
                    <div class="ci-icon">🚚</div>
                    <div>
                        <div class="ci-label">Livraison</div>
                        <div class="ci-value">
                            Tout le Bénin + International<br>
                            Retrait possible à Sèmé-Kpodji
                        </div>
                    </div>
                </div>
                {{-- Boutons d'action rapide --}}
                <div class="contact-btns">
                    <a class="btn-gold"
                        href="https://wa.me/{{ config('kekeli.whatsapp') }}"
                        target="_blank" rel="noopener">
                        📱 Écrire sur WhatsApp
                    </a>
                    <a class="btn-outline"
                        href="mailto:kekeliwear229@gmail.com">
                        ✉ Envoyer un email
                    </a>
                </div>
            </div>{{-- /contact-info --}}
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
        </div>{{-- /contact-grid --}}
    </div>
</section>