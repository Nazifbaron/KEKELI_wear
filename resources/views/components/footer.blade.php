{{-- ============================================================
     components/footer.blade.php
     Footer 4 colonnes + copyright + réseaux sociaux
============================================================ --}}
<footer>
    <div class="container">
        <div class="foot-grid">

            {{-- Colonne 1 : Marque --}}
            <div>
                <div class="foot-brand">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="KEKELI WEAR"
                         style="height:36px;width:auto;filter:brightness(0) invert(1)" />
                </div>
                <p class="foot-tagline">
                    Une lumière pour la mode au féminin.<br>
                    Mode afrofusion écoresponsable — Cotonou, Bénin.
                </p>
            </div>

            {{-- Colonne 2 : Explorer --}}
            <div>
                <div class="foot-col-title">Explorer</div>
                <div class="foot-links">
                    <a href="#catalogue">Collections</a>
                    <a href="#shop">Boutique</a>
                    <a href="#morphology">Mon profil morphologique</a>
                    <a href="#featured">Coups de cœur</a>
                </div>
            </div>

            {{-- Colonne 3 : Service client --}}
            <div>
                <div class="foot-col-title">Service client</div>
                <div class="foot-links">
                    <a href="#contact">Livraisons</a>
                    <a href="#contact">Retours & échanges</a>
                    <a href="#morphology">Guide morphologie</a>
                    <a href="#contact">FAQ</a>
                </div>
            </div>

            {{-- Colonne 4 : Légal --}}
            <div>
                <div class="foot-col-title">Légal</div>
                <div class="foot-links">
                    <a href="#">Mentions légales</a>
                    <a href="#">CGV</a>
                    <a href="#">Politique de retour</a>
                    <a href="#">Confidentialité des données</a>
                </div>
            </div>

        </div>

        {{-- Pied de footer --}}
        <div class="foot-bottom">
            <div class="foot-copy">
                © {{ date('Y') }} KEKELI WEAR — L'ÉLÉGANCE DANS CHAQUE FIL.
                Réalisé par <strong>ACCES UNIVERSEL SARL</strong>.
            </div>
            <div class="foot-social">
                <a href="https://facebook.com/kekeliwear229"
                   target="_blank" rel="noopener" title="Facebook">f</a>
                <a href="https://wa.me/{{ config('kekeli.whatsapp') }}"
                   target="_blank" rel="noopener" title="WhatsApp">W</a>
                <a href="https://tiktok.com/@kekeliwear229"
                   target="_blank" rel="noopener" title="TikTok">T</a>
            </div>
        </div>
    </div>
</footer>
