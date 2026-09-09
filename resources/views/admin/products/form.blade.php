{{-- ============================================================
     admin/products/form.blade.php
     Formulaire partagé création + édition produit.
     Si $product existe → mode édition, sinon → création.
============================================================ --}}
@extends('admin.layouts.app')
@section('title', isset($product) ? 'Éditer · '.$product->name : 'Nouveau produit')

@section('content')

@php
    $isEdit  = isset($product);
    $action  = $isEdit
        ? route('admin.products.update', $product)
        : route('admin.products.store');
@endphp

<div class="admin-card" style="max-width:760px">

    <form method="POST"
          action="{{ $action }}"
          enctype="multipart/form-data">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- Erreurs de validation --}}
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px">
                @foreach($errors->all() as $err)
                    <div>✗ {{ $err }}</div>
                @endforeach
            </div>
        @endif

        {{-- Nom --}}
        <div class="form-admin-group">
            <label class="form-admin-label">Nom du produit *</label>
            <input class="form-admin-input"
                   type="text" name="name"
                   value="{{ old('name', $product->name ?? '') }}"
                   placeholder="Ex: Robe Soleil de Minuit"
                   required />
        </div>

        {{-- Catégorie --}}
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

        {{-- Description --}}
        <div class="form-admin-group">
            <label class="form-admin-label">Description</label>
            <textarea class="form-admin-input"
                      name="description"
                      rows="3"
                      placeholder="Description de la pièce...">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        {{-- Prix --}}
        <div class="form-admin-row">
            <div class="form-admin-group">
                <label class="form-admin-label">Prix (XOF) — laisser vide si sur devis</label>
                <input class="form-admin-input"
                       type="number" name="price" min="0" step="100"
                       value="{{ old('price', $product->price ?? '') }}"
                       placeholder="Ex: 45000" />
            </div>
            <div class="form-admin-group">
                <label class="form-admin-label">Stock — laisser vide si illimité</label>
                <input class="form-admin-input"
                       type="number" name="stock" min="0"
                       value="{{ old('stock', $product->stock ?? '') }}"
                       placeholder="Ex: 10" />
            </div>
        </div>

        {{-- Badge --}}
        <div class="form-admin-row">
            <div class="form-admin-group">
                <label class="form-admin-label">Badge (texte)</label>
                <input class="form-admin-input"
                       type="text" name="badge"
                       value="{{ old('badge', $product->badge ?? '') }}"
                       placeholder="Ex: Pièce unique, Best-seller..." />
            </div>
            <div class="form-admin-group">
                <label class="form-admin-label">Couleur du badge</label>
                <select class="form-admin-input" name="badge_color">
                    @foreach(['red' => '🔴 Rouge', 'gold' => '🟡 Or', 'blue' => '🔵 Bleu'] as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('badge_color', $product->badge_color ?? 'red') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Image principale --}}
        <div class="form-admin-group">
            <label class="form-admin-label">Image principale</label>
            @if($isEdit && $product->main_image)
                <div style="margin-bottom:10px">
                    <img src="{{ asset('storage/' . $product->main_image) }}"
                         alt="{{ $product->name }}"
                         style="height:100px;width:auto;border-radius:4px;border:1px solid #222" />
                    <p style="font-size:11px;color:#888;margin-top:4px">
                        Image actuelle — une nouvelle image la remplacera.
                    </p>
                </div>
            @endif
            <input class="form-admin-input"
                   type="file" name="main_image"
                   accept="image/jpeg,image/png,image/webp" />
        </div>

        {{-- Options booléennes --}}
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

        {{-- Actions --}}
        <div style="display:flex;gap:12px;margin-top:28px">
            <button type="submit" class="btn-admin-primary">
                {{ $isEdit ? '💾 Enregistrer les modifications' : '+ Créer le produit' }}
            </button>
            <a href="{{ route('admin.products') }}"
               class="btn-admin-secondary">Annuler</a>
        </div>

    </form>

</div>

@endsection
