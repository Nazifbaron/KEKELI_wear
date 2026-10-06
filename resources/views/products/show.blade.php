{{-- products/show.blade.php — Page détail produit --}}
@extends('layouts.app')
@push('styles')
<style>
    /* CSS critique en ligne : conserve la mise en page de la fiche dès le premier rendu. */
    body { background: #FAF8F5; color: #1A1A1A; font-family: Montserrat, sans-serif; }
    .product-detail-page { padding: 0 24px 80px; margin-top: 100px; }
    .product-detail-page .container { max-width: 1200px; margin: 0 auto; }
    .product-detail-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 56px; align-items: start; margin-bottom: 64px; }
    .product-gallery { position: sticky; top: 100px; min-width: 0; }
    .gallery-main { position: relative; aspect-ratio: 3 / 4; overflow: hidden; background: #FAF8F5; border: 1px solid #EBEBEB; margin-bottom: 12px; }
    .gallery-main-img { width: 100%; height: 100%; object-fit: cover; }
    .product-detail-name { color: #1A1A1A; font-size: 28px; font-weight: 700; line-height: 1.2; margin-bottom: 12px; }
    .product-detail-price { display: flex; align-items: center; gap: 10px; color: #e42829; font-size: 24px; font-weight: 700; margin-bottom: 10px; }
    .product-detail-price-wrap { display: flex; flex-direction: column; gap: 4px; }
    .detail-price-original { color: #8A8A8A; font-size: 16px; font-weight: 400; text-decoration: line-through; }
    .detail-price-sale { color: #e42829; font-size: 28px; font-weight: 700; }
    .detail-promo-badge { display: inline-flex; width: fit-content; background: #e42829; color: #fff; font-size: 12px; font-weight: 700; padding: 4px 12px; }
    @media (max-width: 768px) {
        .product-detail-page { padding-right: 16px; padding-left: 16px; margin-top: 80px; }
        .product-detail-grid { grid-template-columns: 1fr; gap: 28px; }
        .product-gallery { position: static; }
    }
</style>
@endpush
@section('content')

<div class="product-detail-page">
<div class="container">

    <div class="breadcrumb">
        <a href="{{ route('home') }}">Accueil</a>
        <span>›</span>
        <a href="{{ route('boutique') }}">Boutique</a>
        <span>›</span>
        <a href="{{ route('boutique', ['cat' => $product->category->slug]) }}">{{ $product->category->name }}</a>
        <span>›</span>
        <span>{{ $product->name }}</span>
    </div>

    <div class="product-detail-grid">

        {{-- GALERIE --}}
        <div class="product-gallery">
            <div class="gallery-main" id="gallery-main">
                @php $allImages = $product->all_images; @endphp
                @if(count($allImages))
                    <img src="{{ $allImages[0] }}" alt="{{ $product->name }}"
                         id="gallery-main-img" class="gallery-main-img" />
                @else
                    <div class="gallery-placeholder"><span style="font-size:56px">👗</span></div>
                @endif

                @if($product->badge)
                    <div style="position:absolute;top:16px;left:16px;z-index:2">
                        <span class="badge badge-{{ $product->badge_color ?? 'red' }}">{{ $product->badge }}</span>
                    </div>
                @endif
                @if($product->is_featured)
                    <div class="gallery-featured-tag">🔥 Coup de cœur</div>
                @endif

                @if(count($allImages) > 1)
                    <button class="gallery-arrow gallery-prev" onclick="galleryPrev()">‹</button>
                    <button class="gallery-arrow gallery-next" onclick="galleryNext()">›</button>
                    <div class="gallery-counter" id="gallery-counter">1 / {{ count($allImages) }}</div>
                @endif
            </div>

            @if(count($allImages) > 1)
            <div class="gallery-thumbs">
                @foreach($allImages as $i => $url)
                    <div class="gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                         onclick="selectImage({{ $i }}, '{{ $url }}')" id="thumb-{{ $i }}">
                        <img src="{{ $url }}" alt="{{ $product->name }} — {{ $i + 1 }}" />
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- INFOS --}}
        <div class="product-info-panel">

            <div class="prod-type" style="margin-bottom:8px">{{ $product->category->icon }} {{ $product->category->name }}</div>
            <h1 class="product-detail-name">{{ $product->name }}</h1>

            <div class="product-detail-price">
                @if($product->is_currently_on_sale)
                    <div class="product-detail-price-wrap">
                        <span class="detail-price-original">{{ $product->formatted_original_price }}</span>
                        <span class="detail-price-sale">{{ $product->formatted_sale_price }}</span>
                        <span class="detail-promo-badge">PROMOTION {{ $product->discount_percent }}</span>
                    </div>
                @elseif($detailPromo)
                    <div class="product-detail-price-wrap">
                        <span class="detail-price-original">{{ number_format((float) $product->price, 0, ',', ' ') }} XOF</span>
                        <span class="detail-price-sale">{{ number_format($detailPricing['unit_price'], 0, ',', ' ') }} XOF</span>
                        <span class="detail-promo-badge">CODE PROMO {{ $detailPromo->label }}</span>
                    </div>
                @else
                    {{ $product->formatted_price }}
                @endif
                @if($product->is_custom)
                    <span class="price-note">— Tarif selon mensurations</span>
                @endif
            </div>

            <div class="product-detail-stats">
                <span>❤️ <span id="likes-{{ $product->id }}">{{ $product->likes }}</span> j'aime</span>
                <span class="stats-sep">·</span>
                <span>👁 {{ number_format($product->views) }} vues</span>
                @if($product->stock && $product->stock <= 3)
                    <span class="stats-sep">·</span>
                    <span style="color:var(--red);font-weight:700">⚠ Plus que {{ $product->stock }} dispo.</span>
                @endif
            </div>

            <div class="divider"></div>

            @if($product->description)
                <div class="product-detail-section-label">Description</div>
                <div class="product-detail-desc">{{ $product->description }}</div>
            @endif

            {{-- GUIDE COUPE ET MESURES --}}
            <div class="morpho-advice-box">
                <div class="morpho-advice-header">
                    <span class="morpho-advice-icon">📐</span>
                    <div>
                        <div class="morpho-advice-title">Coupe &amp; mesures de la pièce</div>
                        <div class="morpho-advice-sub">Vérifiez l’ajustement avant de commander</div>
                    </div>
                </div>
                <div class="morpho-advice-content fit-details-content">
                    @if($product->fit_details)
                        {!! nl2br(e($product->fit_details)) !!}
                    @else
                        Les dimensions détaillées de cette pièce ne sont pas encore renseignées. Vous pouvez nous contacter via WhatsApp pour plus d’informations.
                    @endif
                </div>
                <button class="btn-morpho-send"
                        onclick="openMorphoForProduct({{ $product->id }}, '{{ addslashes($product->name) }}')">
                    📐 Besoin d’une adaptation ? Transmettre mes mesures
                </button>
            </div>

            {{-- ACTIONS --}}
            <div class="product-detail-actions">
                @if($product->is_custom)
                    <button class="btn-gold btn-full"
                            onclick="openMorphoForProduct({{ $product->id }}, '{{ addslashes($product->name) }}')">
                        📐 Commander sur mesure
                    </button>
                @else
                    <button class="btn-gold btn-full" id="btn-add-{{ $product->id }}"
                            onclick="addToCartDetail({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->formatted_price }}')">
                        🛒 Ajouter au panier
                    </button>
                @endif
                <button class="btn-outline-dark btn-full btn-whatsapp-detail"
                        onclick="openWhatsApp('{{ addslashes($product->name) }}')">
                    <span class="whatsapp-button-icon" aria-hidden="true">WA</span>
                    Commander via WhatsApp
                </button>
                <button class="btn-like-detail"
                        onclick="toggleLikeApi(this, {{ $product->id }})">
                    ♡ J'aime ce modèle
                </button>
            </div>

            <div class="product-delivery-info">
                <div class="delivery-info-item"><span>🚚</span><span>Livraison dans tout le Bénin et à l'international</span></div>
                <div class="delivery-info-item"><span>🔒</span><span>Paiement sécurisé — MTN MoMo · Moov Money · Carte</span></div>
                <div class="delivery-info-item"><span>✦</span><span>Pièce {{ $product->is_custom ? 'créée sur mesure' : 'artisanale unique' }}</span></div>
            </div>
        </div>
    </div>

    {{-- FORMULAIRE MENSURATIONS POUR CE PRODUIT --}}
    <div class="product-morpho-form" id="product-morpho-form" style="display:none">
        <div class="product-morpho-form-inner">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
                <div>
                    <div style="font-size:16px;font-weight:700;color:var(--black)">
                        Mes mensurations pour <em style="color:var(--red)" id="morpho-product-name"></em>
                    </div>
                    <div style="font-size:12px;color:var(--soft);margin-top:4px">
                        Indiquez vos mensurations et nous vous conseillerons sur l’ajustement de cette pièce.
                    </div>
                </div>
                <button onclick="closeMorphoForm()" style="background:none;border:none;font-size:24px;color:var(--soft);cursor:pointer">✕</button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nom complet *</label>
                    <input class="form-input" id="pm-name" type="text" placeholder="Votre nom" />
                </div>
                <div class="form-group">
                    <label class="form-label">Numéro WhatsApp *</label>
                    <input class="form-input" id="pm-whatsapp" type="tel" placeholder="+229 01..." />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tour de poitrine (cm)</label>
                    <input class="form-input" id="pm-chest" type="number" placeholder="Ex: 90" />
                </div>
                <div class="form-group">
                    <label class="form-label">Tour de taille (cm)</label>
                    <input class="form-input" id="pm-waist" type="number" placeholder="Ex: 72" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tour de hanches (cm)</label>
                    <input class="form-input" id="pm-hips" type="number" placeholder="Ex: 98" />
                </div>
                <div class="form-group">
                    <label class="form-label">Hauteur (cm)</label>
                    <input class="form-input" id="pm-height" type="number" placeholder="Ex: 165" />
                </div>
            </div>
            <div class="form-group" style="margin-bottom:20px">
                <label class="form-label">Notes ou demandes particulières</label>
                <textarea class="form-input" id="pm-notes" rows="2"
                          placeholder="Ex: longueur midi, couleur préférée..."></textarea>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <button class="btn-gold" onclick="sendProductMorpho()">📱 Envoyer sur WhatsApp</button>
                <button class="btn-outline-dark" onclick="closeMorphoForm()">Annuler</button>
            </div>
        </div>
    </div>

    {{-- PRODUITS SIMILAIRES --}}
    @if($related->isNotEmpty())
    <div class="related-products">
        <div class="sec-label">Vous aimerez aussi</div>
        <div class="sec-title" style="font-size:22px;margin-bottom:28px">Créations similaires</div>
        <div class="products-grid" style="grid-template-columns:repeat(4,1fr)">
            @foreach($related as $rel)
            <div class="prod-card" data-category-id="{{ $rel->category_id }}">
                <div class="prod-img">
                    <a class="prod-image-link" href="{{ route('product.show', $rel->slug) }}" aria-label="Découvrir {{ $rel->name }}">
                        @if($rel->main_image)
                            <img src="{{ asset('storage/' . $rel->main_image) }}" alt="{{ $rel->name }}" loading="lazy" />
                        @else
                            <span class="prod-image-placeholder">✦</span>
                        @endif
                        <span class="prod-image-discover">Découvrir la pièce <span aria-hidden="true">↗</span></span>
                    </a>
                    @if($rel->badge)
                        <div class="prod-badge">
                            <span class="badge badge-{{ $rel->badge_color ?? 'red' }}">{{ $rel->badge }}</span>
                        </div>
                    @endif
                    @if($rel->extraImages->count() > 0)
                        <div class="prod-img-count">+{{ $rel->extraImages->count() }} photo{{ $rel->extraImages->count() > 1 ? 's' : '' }}</div>
                    @endif
                </div>
                <div class="prod-info">
                    <a class="prod-info-link" href="{{ route('product.show', $rel->slug) }}">
                        <div class="prod-type">{{ $rel->category->name }}</div>
                        <div class="prod-title">{{ $rel->name }}</div>
                        <div class="prod-footer">
                            @include('products.partials.price', ['product' => $rel])
                            <span class="prod-card-arrow" aria-hidden="true">↗</span>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
</div>

@push('scripts')
<script>
var PRODUCT_ID   = {{ $product->id }};
var PRODUCT_NAME = '{{ addslashes($product->name) }}';
var PRODUCT_FIT_DETAILS = @json($product->fit_details);
var ALL_IMAGES   = @json($product->all_images);
var WA_NUMBER    = '{{ config("kekeli.whatsapp") }}';
var currentImageIndex = 0;
var morphoProductId   = null;

function selectImage(index, url) {
    currentImageIndex = index;
    var img = document.getElementById('gallery-main-img');
    if (img) img.src = url;
    document.querySelectorAll('.gallery-thumb').forEach(function(t, i) {
        t.classList.toggle('active', i === index);
    });
    var counter = document.getElementById('gallery-counter');
    if (counter) counter.textContent = (index + 1) + ' / ' + ALL_IMAGES.length;
}
function galleryNext() { var n = (currentImageIndex + 1) % ALL_IMAGES.length; selectImage(n, ALL_IMAGES[n]); }
function galleryPrev() { var p = (currentImageIndex - 1 + ALL_IMAGES.length) % ALL_IMAGES.length; selectImage(p, ALL_IMAGES[p]); }

function addToCartDetail(productId, name, price) {
    var btn = document.getElementById('btn-add-' + productId);
    if (btn) { btn.disabled = true; btn.textContent = 'Ajout...'; }
    addToCartApi(productId, name, price)
    .then(function(result) {
        if (result === false) {
            if (btn) { btn.disabled = false; btn.textContent = '🛒 Ajouter au panier'; }
            return;
        }
        if (btn) { btn.disabled = false; btn.textContent = '✓ Ajouté !'; }
        setTimeout(function() { if (btn) btn.textContent = '🛒 Ajouter au panier'; }, 2000);
    })
    .catch(function() {
        if (btn) { btn.disabled = false; btn.textContent = '🛒 Ajouter au panier'; }
    });
}

function openMorphoForProduct(productId, productName) {
    morphoProductId = productId;
    var nameEl = document.getElementById('morpho-product-name');
    if (nameEl) nameEl.textContent = productName;
    var form = document.getElementById('product-morpho-form');
    if (form) { form.style.display = 'block'; setTimeout(function() { form.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 100); }
}
function closeMorphoForm() {
    var form = document.getElementById('product-morpho-form');
    if (form) form.style.display = 'none';
}

function sendProductMorpho() {
    var name     = document.getElementById('pm-name')?.value.trim();
    var whatsapp = document.getElementById('pm-whatsapp')?.value.trim();
    var chest    = document.getElementById('pm-chest')?.value;
    var waist    = document.getElementById('pm-waist')?.value;
    var hips     = document.getElementById('pm-hips')?.value;
    var height   = document.getElementById('pm-height')?.value;
    var notes    = document.getElementById('pm-notes')?.value.trim();
    if (!name || !whatsapp) { showToast('⚠ Nom et WhatsApp requis.', 'err'); return; }
    fetch('/api/measurements', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 'Accept': 'application/json' },
        body: JSON.stringify({ full_name: name, whatsapp: whatsapp, chest: chest || null, waist: waist || null, hips: hips || null, height: height || null, notes: notes || null, product_id: morphoProductId }),
    })
    .then(function() {
        var msg = 'Bonjour KEKELI Wear ✦\n\nJe souhaite commander : ' + PRODUCT_NAME + '\n\nNom : ' + name
            + '\nMes mensurations (cm) :\n• Poitrine : ' + (chest || 'N/R')
            + '\n• Taille   : ' + (waist || 'N/R')
            + '\n• Hanches  : ' + (hips  || 'N/R')
            + '\n• Hauteur  : ' + (height || 'N/R')
            + (PRODUCT_FIT_DETAILS ? '\n\nMesures/coupe indiquées pour la pièce :\n' + PRODUCT_FIT_DETAILS : '')
            + (notes ? '\n\nNotes : ' + notes : '')
            + '\n\nMerci de confirmer la disponibilité.';
        window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
        closeMorphoForm();
        showToast('✦ Mensurations envoyées !');
    })
    .catch(function() {
        window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent('Bonjour, je souhaite commander : ' + PRODUCT_NAME), '_blank');
    });
}
</script>
@endpush
