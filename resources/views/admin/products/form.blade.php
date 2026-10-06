{{-- ============================================================
     admin/products/form.blade.php
     Formulaire création + édition produit.
     Images multiples (max 6) dès la création.
============================================================ --}}
@extends('admin.layouts.app')
@section('title', isset($product) ? 'Éditer · '.$product->name : 'Nouveau produit')

@section('content')

@php
$isEdit = isset($product);
$extraImages = $isEdit ? ($product->extraImages ?? collect()) : collect();
$action = $isEdit
? route('admin.products.update', $product)
: route('admin.products.store');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:20px">
        @foreach($errors->all() as $err)<div>✗ {{ $err }}</div>@endforeach
    </div>
    @endif

    <div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">

        {{-- ===== COLONNE PRINCIPALE ===== --}}
        <div style="flex:2;min-width:320px;display:flex;flex-direction:column;gap:20px">

            {{-- Infos produit --}}
            <div class="admin-card">
                <div class="admin-card-title">📋 Informations du produit</div>
                <div style="padding:20px;display:flex;flex-direction:column;gap:14px">

                    <div class="form-admin-group">
                        <label class="form-admin-label">Nom du produit *</label>
                        <input class="form-admin-input" type="text" name="name"
                            value="{{ old('name', $product->name ?? '') }}"
                            placeholder="Ex: Robe Soleil de Minuit" required />
                    </div>

                    <div class="form-admin-group">
                        <label class="form-admin-label">Catégorie *</label>
                        <select class="form-admin-input" name="category_id" required>
                            <option value="">— Choisir —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-admin-group">
                        <label class="form-admin-label">Description</label>
                        <textarea class="form-admin-input" name="description" rows="3"
                            placeholder="Description détaillée de la pièce...">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="form-admin-group">
                        <label class="form-admin-label">
                            Coupe et mesures de la pièce
                            <span style="font-weight:400;text-transform:none;color:var(--a-soft)">— visibles sur la fiche produit</span>
                        </label>
                        <textarea class="form-admin-input" name="fit_details" rows="3"
                            placeholder="Ex. Taille unique ; poitrine 92 cm, taille 76 cm, hanches 102 cm, longueur 118 cm. Coupe fluide, tissu non extensible.">{{ old('fit_details', $product->fit_details ?? '') }}</textarea>
                        <small style="font-size:11px;color:var(--a-soft)">Indiquez les dimensions du vêtement (et non celles du corps), la taille, la coupe et l’élasticité du tissu. Cela aide la cliente à comparer avec une pièce qui lui va.</small>
                    </div>

                    <div class="form-admin-row">
                        <div class="form-admin-group">
                            <label class="form-admin-label">Prix (XOF) — vide si sur devis</label>
                            <input class="form-admin-input" type="number"
                                name="price" id="field-price"
                                min="0" step="100"
                                value="{{ old('price', $product->price ?? '') }}"
                                placeholder="Ex: 45000" />
                        </div>
                        <div class="form-admin-group">
                            <label class="form-admin-label">Stock — vide si illimité</label>
                            <input class="form-admin-input" type="number" name="stock"
                                min="0"
                                value="{{ old('stock', $product->stock ?? '') }}"
                                placeholder="Ex: 10" />
                        </div>
                    </div>

                    <div class="form-admin-row">
                        <div class="form-admin-group">
                            <label class="form-admin-label">Badge</label>
                            <input class="form-admin-input" type="text" name="badge"
                                value="{{ old('badge', $product->badge ?? '') }}"
                                placeholder="Ex: Best-seller, Pièce unique..." />
                        </div>
                        <div class="form-admin-group">
                            <label class="form-admin-label">Couleur du badge</label>
                            <select class="form-admin-input" name="badge_color">
                                @foreach(['red' => '🔴 Rouge', 'gold' => '🟡 Or', 'blue' => '🔵 Bleu'] as $val => $lbl)
                                <option value="{{ $val }}"
                                    {{ old('badge_color', $product->badge_color ?? 'red') === $val ? 'selected' : '' }}>
                                    {{ $lbl }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-admin-checks">
                        <label class="check-label">
                            <input type="hidden" name="is_custom" value="0" />
                            <input type="checkbox" name="is_custom" value="1"
                                {{ old('is_custom', $product->is_custom ?? false) ? 'checked' : '' }} />
                            <span>Produit sur-mesure</span>
                        </label>
                        <label class="check-label">
                            <input type="hidden" name="is_active" value="0" />
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} />
                            <span>Produit actif (visible sur le site)</span>
                        </label>
                        <label class="check-label">
                            <input type="hidden" name="is_featured" value="0" />
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} />
                            <span>🔥 Forcer comme coup de cœur</span>
                        </label>
                    </div>

                </div>
            </div>

            {{-- Promotion --}}
            <div class="admin-card" style="margin-top:0">
                <div class="admin-card-title" style="display:flex;justify-content:space-between;align-items:center">
                    🏷 Promotion produit
                    @if(isset($product) && $product->is_currently_on_sale)
                    <span class="status-badge status-red">🔥 Promo active</span>
                    @endif
                </div>
                <div style="padding:20px">
                    <p style="font-size:12px;color:var(--a-soft);margin-bottom:16px">
                        Mettez ce produit en promotion directement — le prix réduit sera affiché
                        dans la boutique avec le prix original barré. Indépendant des codes promo.
                    </p>
                    {{-- Active la promo --}}
                    <label class="check-label" style="margin-bottom:16px">
                        <input type="hidden" name="is_on_sale" value="0" />
                        <input type="checkbox" name="is_on_sale" value="1"
                            id="toggle-sale"
                            {{ old('is_on_sale', isset($product) ? $product->is_on_sale : false) ? 'checked' : '' }}
                            onchange="toggleSaleFields(this.checked)" />
                        <span style="font-weight:700">Mettre ce produit en promotion</span>
                    </label>

                    {{-- Champs promo --}}
                    <div id="sale-fields" style="{{ old('is_on_sale', isset($product) ? $product->is_on_sale : false) ? '' : 'display:none' }}">
                        <div class="form-admin-row">
                            <div class="form-admin-group">
                                <label class="form-admin-label">Prix promotionnel (XOF) *</label>
                                <input class="form-admin-input" type="number"
                                    name="sale_price" id="sale-price"
                                    min="0" step="100"
                                    value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}"
                                    placeholder="Ex: 32000"
                                    oninput="calcDiscount()" />
                            </div>
                            <div class="form-admin-group">
                                <label class="form-admin-label">Fin de la promo (optionnel)</label>
                                <input class="form-admin-input" type="datetime-local"
                                    name="sale_ends_at"
                                    value="{{ old('sale_ends_at', isset($product) && $product->sale_ends_at ? $product->sale_ends_at->format('Y-m-d\TH:i') : '') }}" />
                            </div>
                        </div>

                        {{-- Affichage du % de remise calculé --}}
                        @if(isset($product) && $product->price)
                        <div id="discount-preview"
                            style="font-size:12px;color:var(--a-red);font-weight:700;margin-top:-8px;margin-bottom:14px">
                            @if($product->is_currently_on_sale)
                            Remise actuelle : <strong>{{ $product->discount_percent }}</strong>
                            ({{ $product->formatted_original_price }} → {{ $product->formatted_sale_price }})
                            @endif
                        </div>
                        @else
                        <div id="discount-preview" style="font-size:12px;color:var(--a-red);font-weight:700;margin-top:-8px;margin-bottom:14px"></div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- ===== COLONNE DROITE : Images ===== --}}
        <div style="flex:1;min-width:280px;display:flex;flex-direction:column;gap:20px">

            {{-- Image principale --}}
            <div class="admin-card">
                <div class="admin-card-title">🖼 Image principale</div>
                <div style="padding:20px">

                    @if($isEdit && $product->main_image)
                    <div style="margin-bottom:14px;border-radius:6px;overflow:hidden;
                                    border:1px solid var(--a-border);background:var(--a-cream)">
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                            alt="{{ $product->name }}"
                            style="width:100%;height:200px;object-fit:cover;display:block" />
                    </div>
                    <p style="font-size:11px;color:var(--a-soft);margin-bottom:10px">
                        Choisissez un nouveau fichier pour remplacer l'image actuelle.
                    </p>
                    @else
                    <div class="img-upload-placeholder" id="img-placeholder-main"
                        onclick="document.getElementById('input-main-image').click()">
                        <span style="font-size:36px">📷</span>
                        <div style="font-size:13px;font-weight:600;color:var(--a-mid);margin-top:10px">
                            Cliquez pour ajouter l'image principale
                        </div>
                        <div style="font-size:11px;color:var(--a-soft);margin-top:4px">JPG, PNG ou WebP — max 5 Mo</div>
                    </div>
                    @endif

                    <input class="form-admin-input" type="file"
                        name="main_image" id="input-main-image"
                        accept="image/jpeg,image/png,image/webp"
                        style="{{ $isEdit && $product->main_image ? '' : 'display:none' }}"
                        onchange="previewMainImage(this)" />

                    {{-- Prévisualisation avant upload (création) --}}
                    <div id="preview-main" style="display:none;margin-top:12px;
                         border-radius:6px;overflow:hidden;border:1px solid var(--a-border)">
                        <img id="preview-main-img"
                            style="width:100%;height:200px;object-fit:cover;display:block" />
                    </div>

                </div>
            </div>

            {{-- Images supplémentaires --}}
            <div class="admin-card">
                <div class="admin-card-title">
                    📸 Images supplémentaires
                    <span style="font-size:11px;font-weight:400;color:var(--a-soft)">
                        (max 5 en plus de la principale)
                    </span>
                </div>
                <div style="padding:20px">

                    @if($extraImages->count() > 0)
                    {{-- Images existantes avec suppression --}}
                    <div class="extra-images-grid" style="margin-bottom:16px">
                        @foreach($extraImages as $img)
                        <div class="extra-img-item" id="extra-img-{{ $img->id }}">
                            <img src="{{ $img->url }}" alt="Vue {{ $loop->iteration }}" />
                            <button type="button"
                                class="extra-img-delete"
                                onclick="deleteExtraImage({{ $img->id }}, this)"
                                title="Supprimer cette image">✕</button>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Zone d'upload des nouvelles images supplémentaires --}}
                    <div id="extra-images-previews" class="extra-images-grid"
                        style="{{ $extraImages->count() > 0 ? 'margin-bottom:12px' : '' }}">
                        {{-- Prévisualisations JS injectées ici --}}
                    </div>

                    {{-- Bouton d'ajout d'images --}}
                    @php
                    $existingCount = $extraImages->count();
                    $remaining = 5 - $existingCount;
                    @endphp

                    @if($remaining > 0)
                    <div class="img-upload-placeholder" id="img-placeholder-extra"
                        onclick="document.getElementById('input-extra-images').click()"
                        style="padding:20px">
                        <span style="font-size:28px">+</span>
                        <div style="font-size:12px;font-weight:600;color:var(--a-mid);margin-top:6px">
                            Ajouter jusqu'à {{ $remaining }} photo{{ $remaining > 1 ? 's' : '' }}
                        </div>
                        <div style="font-size:11px;color:var(--a-soft);margin-top:3px">
                            JPG, PNG ou WebP — max 5 Mo chacune
                        </div>
                    </div>

                    <input type="file" name="extra_images[]"
                        id="input-extra-images"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        style="display:none"
                        onchange="previewExtraImages(this, {{ $remaining }})" />

                    <div id="extra-count-info"
                        style="font-size:11px;color:var(--a-soft);margin-top:8px;display:none">
                    </div>
                    @else
                    <div style="font-size:12px;color:var(--a-soft);padding:12px;
                                    background:var(--a-cream);border-radius:4px;text-align:center">
                        ✓ Maximum 5 images supplémentaires atteint.
                    </div>
                    @endif

                </div>
            </div>

            {{-- Bouton soumettre --}}
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-admin-primary" style="flex:1;justify-content:center;padding:13px">
                    {{ $isEdit ? '💾 Enregistrer' : '+ Créer le produit' }}
                </button>
                <a href="{{ route('admin.products') }}" class="btn-admin-secondary">Annuler</a>
            </div>

        </div>
    </div>
</form>

@endsection

@push('styles')
<style>
    .img-upload-placeholder {
        border: 2px dashed var(--a-border);
        border-radius: 6px;
        padding: 28px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        background: var(--a-cream);
    }

    .img-upload-placeholder:hover {
        border-color: var(--a-red);
        background: #FFF5F5;
    }

    .extra-images-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .extra-img-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid var(--a-border);
    }

    .extra-img-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .extra-img-delete {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        background: rgba(228, 40, 41, .9);
        color: #fff;
        border: none;
        border-radius: 50%;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .2s;
    }

    .extra-img-delete:hover {
        background: #c01f20;
    }
</style>
@endpush

@push('scripts')
<script>
    var CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ============================================================
       PRÉVISUALISATION IMAGE PRINCIPALE
    ============================================================ */
    function previewMainImage(input) {
        if (!input.files || !input.files[0]) return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('preview-main');
            var img = document.getElementById('preview-main-img');
            var placeholder = document.getElementById('img-placeholder-main');

            if (img) img.src = e.target.result;
            if (preview) preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';

            /* Afficher l'input file maintenant */
            input.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }

    /* ============================================================
       PRÉVISUALISATION IMAGES SUPPLÉMENTAIRES
    ============================================================ */
    var selectedExtraFiles = [];

    function previewExtraImages(input, maxRemaining) {
        if (!input.files || !input.files.length) return;

        var files = Array.from(input.files);
        var allowed = Math.min(files.length, maxRemaining);

        if (files.length > maxRemaining) {
            alert('Maximum ' + maxRemaining + ' image(s) supplémentaire(s). Seules les ' + maxRemaining + ' premières seront utilisées.');
            files = files.slice(0, maxRemaining);
        }

        var container = document.getElementById('extra-images-previews');
        var infoEl = document.getElementById('extra-count-info');
        if (!container) return;

        container.innerHTML = '';
        selectedExtraFiles = files;

        files.forEach(function(file, i) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.className = 'extra-img-item';
                div.id = 'new-extra-' + i;

                var img = document.createElement('img');
                img.src = e.target.result;

                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'extra-img-delete';
                btn.textContent = '✕';
                btn.onclick = function() {
                    selectedExtraFiles.splice(i, 1);
                    div.remove();
                    updateExtraCount();
                };

                div.appendChild(img);
                div.appendChild(btn);
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        updateExtraCount();
    }

    function updateExtraCount() {
        var infoEl = document.getElementById('extra-count-info');
        if (!infoEl) return;
        if (selectedExtraFiles.length > 0) {
            infoEl.style.display = 'block';
            infoEl.textContent = selectedExtraFiles.length + ' photo(s) sélectionnée(s) — sera(ont) uploadée(s) à la sauvegarde.';
        } else {
            infoEl.style.display = 'none';
        }
    }

    /* ============================================================
       SUPPRESSION D'UNE IMAGE EXISTANTE (mode édition)
    ============================================================ */
    function deleteExtraImage(imageId, btn) {
        if (!confirm('Supprimer cette image ?')) return;
        btn.disabled = true;

        fetch('/admin/products/images/' + imageId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
            })
            .then(function(r) {
                return r.json();
            })
            .then(function(data) {
                var item = document.getElementById('extra-img-' + imageId);
                if (item) {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(.8)';
                    item.style.transition = 'all .3s';
                    setTimeout(function() {
                        item.remove();
                    }, 300);
                }
            })
            .catch(function() {
                btn.disabled = false;
                alert('Erreur lors de la suppression.');
            });
    }

    /* ============================================================
       CALCUL REMISE PROMOTION
    ============================================================ */
    function toggleSaleFields(show) {
        var fields = document.getElementById('sale-fields');
        if (fields) fields.style.display = show ? 'block' : 'none';
    }

    function calcDiscount() {
        var priceInput = document.querySelector('input[name="price"]');
        var salePriceInput = document.getElementById('sale-price');
        var previewEl = document.getElementById('discount-preview');
        if (!priceInput || !salePriceInput || !previewEl) return;

        var price = parseFloat(priceInput.value);
        var salePrice = parseFloat(salePriceInput.value);

        if (price > 0 && salePrice > 0 && salePrice < price) {
            var pct = Math.round((1 - salePrice / price) * 100);
            previewEl.textContent = 'Remise : -' + pct + '% (' +
                price.toLocaleString('fr-FR') + ' XOF → ' +
                salePrice.toLocaleString('fr-FR') + ' XOF)';
            previewEl.style.color = 'var(--a-red)';
        } else if (salePrice >= price) {
            previewEl.textContent = '⚠ Le prix promo doit être inférieur au prix original.';
            previewEl.style.color = 'orange';
        } else {
            previewEl.textContent = '';
        }
    }
    document.querySelector('input[name="price"]')?.addEventListener('input', calcDiscount);
</script>

@endpush
