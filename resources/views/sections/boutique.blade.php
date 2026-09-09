<section id="boutique">
  <div class="container">
    <div class="boutique-header">
      <div>
        <div class="sec-label">Boutique</div>
        <div class="sec-title" style="font-size:28px">Toutes nos créations</div>
      </div>
      <div class="filter-bar" id="filter-bar">
        <button class="filter-btn active" onclick="filterByCategory('all', this)">Tout</button>
        @foreach($categories as $cat)
          <button class="filter-btn" onclick="filterByCategory('{{ $cat->slug }}', this)">
            {{ $cat->nom }}
          </button>
        @endforeach
      </div>
    </div>

    {{-- Compteur résultats --}}
    <div id="filter-count" style="font-size:12px;color:var(--kgray);margin-bottom:16px">
      Affichage : <strong id="filter-count-num" style="color:var(--kgold)">{{ $produits->count() }}</strong> créations
    </div>

    {{-- Code promo --}}
    <div class="promo-bar">
      <div class="promo-label">🎁 Vous avez un code promo ?</div>
      <div class="promo-input-wrap">
        <input class="promo-input" id="promo-input" placeholder="Ex : KEKELI10" />
        <button class="btn-promo" onclick="verifierPromo()">Appliquer</button>
      </div>
      <span class="promo-feedback promo-ok"  id="promo-ok"></span>
      <span class="promo-feedback promo-err" id="promo-err"></span>
    </div>

    {{-- Grille produits depuis BD --}}
    <div class="products-grid" id="products-grid">
      @foreach($produits as $produit)
      <div class="prod-card" data-cat="{{ $produit->categorie->slug }}" data-id="{{ $produit->id }}">
        <div class="prod-img" style="{{ !$produit->image_principale ? 'background:linear-gradient(145deg,#1a0a0a,#2a1010)' : '' }}">

          @if($produit->image_principale)
            <img src="{{ asset('storage/' . $produit->image_principale) }}" alt="{{ $produit->nom }}" />
          @endif

          @if($produit->badge)
            <div class="prod-badge">
              <span class="badge badge-{{ $produit->badge_couleur ?? 'red' }}">{{ $produit->badge }}</span>
            </div>
          @endif

          <button class="prod-like {{ session('liked_' . $produit->id) ? 'liked' : '' }}"
                  onclick="toggleLikeApi(this, {{ $produit->id }})"
                  aria-label="Aimer">
            {{ session('liked_' . $produit->id) ? '♥' : '♡' }}
          </button>

          <div class="prod-views">👁 <span id="vues-{{ $produit->id }}">{{ number_format($produit->vues) }}</span></div>

          <div class="prod-overlay">
            @if($produit->sur_mesure)
              <button class="btn-wa" onclick="smoothScrollTo('morphologie')">📐 Envoyer mes mesures</button>
            @else
              <button class="btn-wa" onclick="openWhatsApp('{{ addslashes($produit->nom) }}')">📱 Commander via WhatsApp</button>
            @endif
          </div>
        </div>

        <div class="prod-info">
          <div class="prod-type">{{ $produit->categorie->nom }}</div>
          <div class="prod-title">{{ $produit->nom }}</div>
          <div class="prod-footer">
            <div>
              <span class="prod-price" data-base="{{ $produit->prix ?? 0 }}" id="price-{{ $produit->id }}">
                {{ $produit->prix_format }}
              </span>
              <span class="prod-price-promo" id="promo-price-{{ $produit->id }}" style="display:none"></span>
            </div>
            @if($produit->sur_mesure)
              <button class="btn-add-cart" onclick="smoothScrollTo('morphologie')">Mes mesures</button>
            @else
              <button class="btn-add-cart"
                      onclick="addToCartApi({{ $produit->id }}, '{{ addslashes($produit->nom) }}', '{{ $produit->prix_format }}')">
                + Panier
              </button>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>
