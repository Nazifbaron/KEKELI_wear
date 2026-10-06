{{-- sections/shop.blade.php — max 12 produits + lien vers le catalogue complet --}}
<section id="shop">
    <div class="container">
        <div class="boutique-header">
            <div>
                <div class="sec-label">Boutique</div>
                <div class="sec-title" style="font-size:28px">
                    Nos créations
                </div>
            </div>
            <div class="boutique-controls">
                <div class="filter-bar" id="filter-bar">
                    <button class="filter-btn active" onclick="filterByCategory('all', this)">Tout</button>
                    @foreach($categories as $cat)
                        <button class="filter-btn" onclick="filterByCategory('{{ $cat->slug }}', this)">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

            </div>
        </div>

        <div class="shop-results-count" style="font-size:12px;color:var(--soft);margin-bottom:16px">
            Affichage :
            <strong id="filter-count-num" style="color:var(--red)">{{ $products->count() }}</strong>
            création{{ $products->count() > 1 ? 's' : '' }}
            @if(isset($totalProducts) && $totalProducts > 12)
                <a href="{{ route('boutique') }}" class="shop-view-all" aria-label="Voir toutes les créations" style="margin-left:950px">
                    Voir tout <span aria-hidden="true">→</span>
                </a>
            @endif
        </div>

        @include('products.partials.promo-form')

        <div class="surmesure-note" id="sm-note">
            <div class="sm-note-text">
                <strong>Commande sur-mesure :</strong>
                Renseignez vos mensurations pour que nos artisanes confectionnent votre pièce à votre exacte morphologie.
            </div>
            <button class="btn-anchor" onclick="smoothScrollTo('morphology')">Mes mensurations ↓</button>
        </div>

        <div class="products-grid" id="products-grid">
            @forelse($products as $product)
            <div class="prod-card" data-cat="{{ $product->category->slug }}" data-category-id="{{ $product->category_id }}" data-id="{{ $product->id }}">
                <div class="prod-img">
                    <a class="prod-image-link" href="{{ route('product.show', $product->slug) }}" aria-label="Découvrir {{ $product->name }}">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" loading="lazy" />
                        @else
                            <span class="prod-image-placeholder">✦</span>
                        @endif
                        <span class="prod-image-discover">Découvrir la pièce <span aria-hidden="true">↗</span></span>
                    </a>
                    @if($product->badge)
                        <div class="prod-badge">
                            <span class="badge badge-{{ $product->badge_color ?? 'red' }}">{{ $product->badge }}</span>
                        </div>
                    @endif
                    <button class="prod-like" onclick="toggleLikeApi(this, {{ $product->id }})" aria-label="Aimer">♡</button>
                    <div class="prod-views">👁 <span id="views-{{ $product->id }}">{{ number_format($product->views) }}</span></div>
                    @if($product->extraImages->count() > 0)
                        <div class="prod-img-count">+{{ $product->extraImages->count() }} photo{{ $product->extraImages->count() > 1 ? 's' : '' }}</div>
                    @endif
                </div>
                <div class="prod-info">
                    <a class="prod-info-link" href="{{ route('product.show', $product->slug) }}">
                        <div class="prod-type">{{ $product->category->name }}</div>
                        <div class="prod-title">{{ $product->name }}</div>
                        <div class="prod-footer">
                            @include('products.partials.price', ['product' => $product])
                            <span class="prod-card-arrow" aria-hidden="true">↗</span>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:48px 0;color:var(--soft)">
                <p>Aucun produit disponible pour le moment.</p>
            </div>
            @endforelse
        </div>

    </div>
</section>
