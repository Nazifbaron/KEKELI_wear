{{-- ============================================================
     sections/shop.blade.php
     Boutique — grille de tous les produits actifs depuis la BD.
     Fonctionnalités :
     - Filtres par catégorie (sync avec les univ-cards du catalogue)
     - Code promo → /api/promo/verify
     - Like → /api/products/{id}/like
     - Vue trackée au survol → /api/products/{id}/view
     - Ajouter au panier → /api/cart/add
     - Commander via WhatsApp (overlay au hover)
     - Note sur-mesure avec ancre vers #morphology
============================================================ --}}
<section id="shop">
    <div class="container">

        {{-- ===== EN-TÊTE BOUTIQUE ===== --}}
        <div class="boutique-header">
            <div>
                <div class="sec-label">Boutique</div>
                <div class="sec-title" style="font-size:28px">Toutes nos créations</div>
            </div>

            {{-- Filtres par catégorie --}}
            <div class="filter-bar" id="filter-bar">
                <button class="filter-btn active"
                        onclick="filterByCategory('all', this)">
                    Tout
                </button>
                @foreach($categories as $cat)
                    <button class="filter-btn"
                            onclick="filterByCategory('{{ $cat->slug }}', this)">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Compteur de résultats visible --}}
        <div id="filter-count"
             style="font-size:12px;color:var(--kgray);margin-bottom:16px">
            Affichage :
            <strong id="filter-count-num"
                    style="color:var(--kgold)">{{ $products->count() }}</strong>
            créations
        </div>

        {{-- ===== BARRE CODE PROMO ===== --}}
        <div class="promo-bar">
            <div class="promo-label">🎁 Vous avez un code promo ?</div>
            <div class="promo-input-wrap">
                <input class="promo-input"
                       id="promo-input"
                       type="text"
                       placeholder="Ex : KEKELI10"
                       autocomplete="off" />
                <button class="btn-promo" onclick="verifyPromo()">Appliquer</button>
            </div>
            {{-- Messages retour vérification --}}
            <span class="promo-feedback promo-ok"  id="promo-ok"></span>
            <span class="promo-feedback promo-err" id="promo-err"></span>
        </div>

        {{-- ===== NOTE SUR-MESURE (affichée quand filtre = surmesure) ===== --}}
        <div class="surmesure-note" id="sm-note">
            <div class="sm-note-text">
                <strong>Commande Sur-Mesure :</strong>
                Pour les modèles sur-mesure, renseignez vos mensurations afin que
                nos artisanes confectionnent votre pièce à votre exacte morphologie.
                Aucun tarif fixe — nous vous contactons après réception de vos mesures.
            </div>
            <button class="btn-anchor" onclick="smoothScrollTo('morphology')">
                Renseigner mes mesures ↓
            </button>
        </div>

        {{-- ===== GRILLE PRODUITS depuis la BD ===== --}}
        <div class="products-grid" id="products-grid">

            @forelse($products as $product)
            @php
                $isCustomProduct = $product->is_custom
                    || $product->category->slug === 'surmesure';
            @endphp
            <div class="prod-card"
                 data-cat="{{ $product->category->slug }}"
                 data-id="{{ $product->id }}">

                {{-- Image --}}
                <div class="prod-img"
                     style="{{ !$product->main_image
                        ? 'background:linear-gradient(145deg,#1a0a0a,#2a1010)'
                        : '' }}">

                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->name }}"
                             loading="lazy" />
                    @endif

                    {{-- Badge produit --}}
                    @if($product->badge)
                        <div class="prod-badge">
                            <span class="badge badge-{{ $product->badge_color }}">
                                {{ $product->badge }}
                            </span>
                        </div>
                    @endif

                    {{-- Bouton like --}}
                    <button class="prod-like"
                            onclick="toggleLikeApi(this, {{ $product->id }})"
                            aria-label="Aimer">
                        ♡
                    </button>

                    {{-- Compteur de vues --}}
                    <div class="prod-views">
                        👁 <span id="views-{{ $product->id }}">
                            {{ number_format($product->views) }}
                        </span>
                    </div>

                    {{-- Overlay au hover --}}
                    <div class="prod-overlay">
                        @if($isCustomProduct)
                            {{-- Sur-mesure → rediriger vers mensurations --}}
                            <button class="btn-wa"
                                    onclick="smoothScrollTo('morphology')">
                                📐 Envoyer mes mesures
                            </button>
                        @else
                            {{-- Standard → commander via WhatsApp --}}
                            <button class="btn-wa"
                                    onclick="openWhatsApp('{{ addslashes($product->name) }}')">
                                📱 Commander via WhatsApp
                            </button>
                        @endif
                    </div>

                </div>{{-- /prod-img --}}

                {{-- Infos produit --}}
                <div class="prod-info">
                    <div class="prod-type">{{ $product->category->name }}</div>
                    <div class="prod-title">{{ $product->name }}</div>

                    <div class="prod-footer">
                        <div>
                            {{-- Prix normal (barré si promo active) --}}
                            <span class="prod-price"
                                  data-base="{{ $product->price ?? 0 }}"
                                  id="price-{{ $product->id }}">
                                {{ $product->formatted_price }}
                            </span>
                            {{-- Prix après promo — affiché par applyDiscount() en JS --}}
                            @if($product->price)
                                <span class="prod-price-promo"
                                      id="promo-price-{{ $product->id }}"
                                      style="display:none">
                                </span>
                            @endif
                        </div>

                        @if($isCustomProduct)
                            {{-- Sur-mesure → bouton mesures --}}
                            <button class="btn-add-cart"
                                    onclick="smoothScrollTo('morphology')">
                                Mes mesures
                            </button>
                        @else
                            {{-- Standard → ajouter au panier --}}
                            <button class="btn-add-cart"
                                    onclick="addToCartApi(
                                        {{ $product->id }},
                                        '{{ addslashes($product->name) }}',
                                        '{{ $product->formatted_price }}'
                                    )">
                                + Panier
                            </button>
                        @endif
                    </div>
                </div>{{-- /prod-info --}}

            </div>{{-- /prod-card --}}

            @empty
            {{-- Aucun produit --}}
            <div style="grid-column:1/-1;text-align:center;padding:48px 0;color:var(--kgray)">
                <p style="font-size:16px;margin-bottom:8px">Aucun produit disponible pour le moment.</p>
                <p style="font-size:13px">Revenez bientôt — de nouvelles créations arrivent !</p>
            </div>
            @endforelse

        </div>{{-- /products-grid --}}

    </div>
</section>
