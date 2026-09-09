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
            <div>
                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label" style="display:block;margin-bottom:5px">
                        Nom complet
                    </label>
                    <input class="form-input" id="c-name"
                           type="text" placeholder="Votre nom" autocomplete="name" />
                </div>

                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label" style="display:block;margin-bottom:5px">
                        Email (optionnel)
                    </label>
                    <input class="form-input" id="c-email"
                           type="email" placeholder="votre@email.com" autocomplete="email" />
                </div>

                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label" style="display:block;margin-bottom:5px">
                        Message
                    </label>
                    <textarea class="form-input" id="c-message"
                              rows="5"
                              placeholder="Votre message..."></textarea>
                </div>

                {{-- Envoie via WhatsApp --}}
                <button class="btn-contact" onclick="sendContact()">
                    Envoyer le message
                </button>
            </div>

        </div>{{-- /contact-grid --}}

    </div>
</section>
