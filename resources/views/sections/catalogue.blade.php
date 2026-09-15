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
            {{-- TENUES RÉINVENTÉES --}}
            @if($index === 0)
            <div class="univ-card univ-card-main {{ $index === 0 ? 'active' : '' }}"
                id="ucard-{{ $cat->slug }}"
                onclick="filterByCategory('{{ $cat->slug }}')">
                @if($cat->image)
                <div class="univ-card-bg"
                    style="background-image:url('{{ asset('storage/' . $cat->image) }}')">
                </div>
                <div class="univ-card-overlay"></div>
                @endif
                <div class="univ-card-content">
                    <div class="univ-icon">
                        {{ $cat->icon }}
                    </div>
                    <div class="univ-card-bottom">
                        <div class="univ-name">
                            {{ $cat->name }}
                        </div>
                        <div class="univ-desc">
                            {{ $cat->description }}
                        </div>
                        <div class="univ-counter">
                            <span id="cnt-{{ $cat->slug }}">
                                {{ $cat->product_count }}
                            </span>
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
            </div>

            {{-- Ouverture de la colonne droite --}}
            <div class="univ-card-column">

                {{-- CONFECTIONS + SUR-MESURE --}}
                @elseif($index === 1 || $index === 2)

                <div class="univ-card {{ $index === 1 ? '' : '' }}"
                    id="ucard-{{ $cat->slug }}"
                    onclick="filterByCategory('{{ $cat->slug }}')">

                    @if($cat->image)
                    <div class="univ-card-bg"
                        style="background-image:url('{{ asset('storage/' . $cat->image) }}')">
                    </div>
                    <div class="univ-card-overlay"></div>
                    @endif

                    <div class="univ-card-content">

                        <div class="univ-icon">
                            {{ $cat->icon }}
                        </div>

                        <div class="univ-card-bottom">

                            <div class="univ-name">
                                {{ $cat->name }}
                            </div>

                            <div class="univ-desc">
                                {{ $cat->description }}
                            </div>

                            <div class="univ-counter">
                                <span id="cnt-{{ $cat->slug }}">
                                    {{ $cat->product_count }}
                                </span>

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
                </div>

                {{-- Fermeture de la colonne droite --}}
                @if($index === 2)
            </div>
            @endif

            {{-- ACCESSOIRES --}}
            @elseif($index === 3)

            <div class="univ-card univ-card-accessories"
                id="ucard-{{ $cat->slug }}"
                onclick="filterByCategory('{{ $cat->slug }}')">

                @if($cat->image)
                <div class="univ-card-bg"
                    style="background-image:url('{{ asset('storage/' . $cat->image) }}')">
                </div>
                <div class="univ-card-overlay"></div>
                @endif

                <div class="univ-card-content">

                    <div class="univ-icon">
                        {{ $cat->icon }}
                    </div>

                    <div class="univ-card-bottom">

                        <div class="univ-name">
                            {{ $cat->name }}
                        </div>

                        <div class="univ-desc">
                            {{ $cat->description }}
                        </div>

                        <div class="univ-counter">
                            <span id="cnt-{{ $cat->slug }}">
                                {{ $cat->product_count }}
                            </span>

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
            </div>

            @endif

            @endforeach

        </div>



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
