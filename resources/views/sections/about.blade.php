{{-- ============================================================
     sections/about.blade.php
     Section À propos — statique (contenu éditorial fixe)
     Les stats (100%, 4 univers...) peuvent être rendues
     dynamiques plus tard via une table site_settings
============================================================ --}}
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
                    « Kekeli » signifie <span style="color:var(--kgold)">"Lumière"</span>. Notre mode est née du désir de
                    révéler la lumière intérieure de chaque femme.
                    Fondée par <strong>Mrs Dègnon</strong> , et co-gérée avec <strong>Mlle Eunice</strong>, <em>Kekeli Wear</em>  
                    est une maison de mode afrofusion basée à Cotonou. Chaque pièce est unique, pensée pour sublimer votre morphologie, raconter une histoire et affirmer la puissance du féminin.
                </p>
                <p class="about-text" style="margin-bottom:36px">   
                    Nous défendons une mode durable et engagée : des créations écoresponsables 
                    et des conseils sincères pour valoriser le style de la femme béninoise, africaine 
                    et internationale — sans jamais chercher la perfection, mais le rayonnement.
                    Née de la vision partagée de
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
                </div>
            </div>

        </div>
    </div>
</section>
