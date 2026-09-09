{{-- ============================================================
     sections/catalogue.blade.php
     4 univers de créations — données depuis la table categories.
     Chaque carte affiche :
     - Icône + nom + description + compteur produits (BD)
     - Clic → filtre la section #shop + scroll automatique
     - La carte Sur-Mesure affiche une note avec ancre #morphology
     Les compteurs sont rafraîchis toutes les 60s via /api/stats
============================================================ --}}
<section id="catalogue">
    <div class="container">
        {{-- Intro section --}}
        <div class="cat-intro">
            <div class="sec-label">Nos Créations</div>
            <div class="sec-title">Explorez notre univers mode.</div>
            <div class="divider"></div>
            <p class="sec-sub">
                Quatre univers, une seule promesse : des pièces uniques qui vous ressemblent.
                Cliquez sur une catégorie pour découvrir la collection complète.
            </p>
        </div>

        {{-- ===== 4 CARTES UNIVERS depuis la BD ===== --}}
        <div class="universes">
            @foreach($categories as $index => $cat)
            <div class="univ-card {{ $index === 0 ? 'active' : '' }}"
                 id="ucard-{{ $cat->slug }}"
                 onclick="filterByCategory('{{ $cat->slug }}')">

                {{-- Image de fond si disponible — sinon dégradé CSS --}}
                @if($cat->image)
                    <div class="univ-card-bg"
                         style="background-image:url('{{ asset('storage/' . $cat->image) }}')">
                    </div>
                    {{-- Overlay sombre pour lisibilité du texte --}}
                    <div class="univ-card-overlay"></div>
                @endif

                {{-- Contenu de la carte (au-dessus de l'image) --}}
                <div class="univ-card-content">

                    {{-- Icône --}}
                    <div class="univ-icon">{{ $cat->icon }}</div>

                    {{-- Nom --}}
                    <div class="univ-name">{{ $cat->name }}</div>

                    {{-- Description --}}
                    <div class="univ-desc">{{ $cat->description }}</div>

                    {{-- Compteur produits — mis à jour par /api/stats toutes les 60s --}}
                    <div class="univ-counter">
                        <span id="cnt-{{ $cat->slug }}">{{ $cat->product_count }}</span>
                        @if($cat->slug === 'accessoires')
                            pièces
                        @elseif($cat->slug === 'surmesure')
                            modèles
                        @else
                            créations
                        @endif
                    </div>

                </div>

            </div>
            @endforeach

        </div>{{-- /universes --}}

        {{-- ===== NOTE SUR-MESURE ===== --}}
        {{-- Affichée quand l'admin a des produits sur-mesure --}}
        <div class="surmesure-note" id="sm-note-catalogue">
            <div class="sm-note-text">
                <strong>Commande Sur-Mesure :</strong>
                Choisissez une pièce, communiquez-nous
                vos mensurations et nous identifions la
                coupe idéale pour votre morphologie.
                Paiement et livraison organisés directement avec vous sur WhatsApp.
            </div>
            <button class="btn-anchor" onclick="smoothScrollTo('morphology')">
                Renseigner mes mesures ↓
            </button>
        </div>

    </div>
</section>
