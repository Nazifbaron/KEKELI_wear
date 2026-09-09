{{-- ============================================================
     sections/featured.blade.php
     Coups de cœur — produits avec is_featured = true
     Détectés automatiquement via heart_score (likes×2 + views×0.5)
     ou forcés manuellement depuis l'admin.
     Carrousel horizontal avec flèches gauche/droite.
============================================================ --}}
<section id="featured">
    <div class="container">

        {{-- En-tête section --}}
        <div class="featured-header">
            <div>
                <div class="sec-label">Tendances</div>
                <div class="sec-title" style="font-size:28px">Découvrez nos Coups de cœur ✦</div>
                <p style="font-size:13px;color:var(--kgray);margin-top:6px">
                    Les pièces les plus aimées et les plus commandées.
                </p>
            </div>
            {{-- Flèches de navigation du carrousel --}}
            <div class="fav-nav">
                <button class="fnav-btn" onclick="scrollFavs(-1)" aria-label="Précédent">←</button>
                <button class="fnav-btn" onclick="scrollFavs(1)"  aria-label="Suivant">→</button>
            </div>
        </div>

        {{-- Carrousel --}}
        <div class="fav-wrapper">
            <div class="fav-track" id="fav-track">

                @forelse($featured as $product)
                <div class="fav-card">

                    {{-- Image produit --}}
                    <div class="fav-img"
                         style="{{ !$product->main_image
                            ? 'background:linear-gradient(145deg,#1a1a1a,#2a1a0a)'
                            : '' }}">

                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy" />
                        @endif

                        {{-- Badges --}}
                        <div class="fav-badges">
                            <span class="badge badge-red">🔥 Coup de cœur</span>
                            @if($product->badge)
                                <span class="badge badge-gold">{{ $product->badge }}</span>
                            @endif
                        </div>

                        {{-- Bouton like --}}
                        <div class="fav-actions">
                            <button class="fav-like"
                                    onclick="toggleLikeApi(this, {{ $product->id }})"
                                    aria-label="Aimer cette création">
                                ♡
                            </button>
                        </div>

                        {{-- Stats likes + vues --}}
                        <div class="fav-stats">
                            <span class="fav-stat">
                                ♡ <span id="likes-{{ $product->id }}">{{ $product->likes }}</span>
                            </span>
                            <span class="fav-stat">
                                👁 {{ number_format($product->views) }}
                            </span>
                        </div>

                    </div>{{-- /fav-img --}}

                    {{-- Infos produit --}}
                    <div class="fav-info">
                        <div class="fav-univers">{{ $product->category->name }}</div>
                        <div class="fav-title">{{ $product->name }}</div>
                        <div class="fav-footer">
                            <div class="fav-price">{{ $product->formatted_price }}</div>

                            @if($product->is_custom)
                                {{-- Sur-mesure → rediriger vers mensurations --}}
                                <button class="btn-cart"
                                        onclick="smoothScrollTo('morphology')"
                                        style="font-size:9px">
                                    Mes mesures →
                                </button>
                            @else
                                {{-- Produit standard → ajouter au panier --}}
                                <button class="btn-cart"
                                        onclick="addToCartApi(
                                            {{ $product->id }},
                                            '{{ addslashes($product->name) }}',
                                            '{{ $product->formatted_price }}'
                                        )">
                                    + Panier
                                </button>
                            @endif
                        </div>
                    </div>

                </div>{{-- /fav-card --}}
                @empty
                {{-- Aucun coup de cœur — message discret --}}
                <p style="color:var(--kgray);font-size:13px;padding:20px 0">
                    Les coups de cœur apparaissent automatiquement lorsque des produits
                    accumulent des likes et des vues. Revenez bientôt !
                </p>
                @endforelse

            </div>{{-- /fav-track --}}
        </div>{{-- /fav-wrapper --}}

    </div>
</section>
