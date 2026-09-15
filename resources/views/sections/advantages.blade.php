{{-- ============================================================
     sections/advantages.blade.php
     4 avantages clés de la marque — entre hero et about.
     Fond blanc, icônes SVG rouges, hover crème.
     Révélation progressive au scroll via .reveal class.
============================================================ --}}
<section id="advantages">
    <div class="container">
        <div class="advantages-grid">

            <div class="advantage reveal">
                <svg class="ic" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.4">
                    <path d="M12 2C7 6 4 9.5 4 13.5A8 8 0 0020 13.5C20 9.5 17 6 12 2Z"/>
                </svg>
                <h4>Mode écoresponsable</h4>
                <p>Des matières et un savoir-faire pensés pour durer,
                   dans le respect d'une mode plus consciente.</p>
            </div>

            <div class="advantage reveal">
                <svg class="ic" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.4">
                    <path d="M4 4h16v4H4zM6 8v12h12V8M10 12h4"/>
                </svg>
                <h4>Pièces uniques</h4>
                <p>Chaque création est confectionnée en exemplaire unique —
                   jamais deux femmes dans la même tenue.</p>
            </div>

            <div class="advantage reveal">
                <svg class="ic" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.4">
                    <circle cx="12" cy="8" r="3.2"/>
                    <path d="M5 21c0-4 3.2-6.5 7-6.5S19 17 19 21"/>
                </svg>
                <h4>Conseil personnalisé</h4>
                <p>Vos mensurations, votre morphologie : nous vous suggérons
                   la tenue qui vous révèle vraiment.</p>
            </div>

            <div class="advantage reveal">
                <svg class="ic" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.4">
                    <path d="M3 7l2-3h14l2 3M3 7v13h18V7M3 7h18M9 11a3 3 0 006 0"/>
                </svg>
                <h4>Livraison &amp; retrait</h4>
                <p>Expédition dans tout le Bénin et à l'international,
                   ou retrait directement à Sèmé-Kpodji.</p>
            </div>

        </div>
    </div>
</section>
