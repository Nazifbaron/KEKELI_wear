{{-- ============================================================
     admin/categories/index.blade.php
     Gestion des 4 catégories — modifier nom, description,
     icône, couleur, ordre et image de fond.
     Les catégories existent déjà via le seeder,
     ici on les édite uniquement (pas de création libre
     car le système repose sur des slugs fixes).
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Catégories')

@section('content')

<p style="color:#888;font-size:13px;margin-bottom:20px">
    Les 4 catégories sont définies par le système.
    Vous pouvez modifier leur image, description et apparence.
</p>

<div class="cat-admin-grid">

    @foreach($categories as $cat)
    <div class="admin-card">

        {{-- Aperçu image actuelle --}}
        <div class="cat-admin-preview"
             style="{{ $cat->image
                ? 'background-image:url(' . asset('storage/'.$cat->image) . ');background-size:cover;background-position:center'
                : 'background:linear-gradient(135deg,#1a1a1a,#2a1a1a)' }}">
            <div class="cat-admin-preview-overlay">
                <div class="cat-admin-icon">{{ $cat->icon }}</div>
                <div class="cat-admin-name">{{ $cat->name }}</div>
                <div class="cat-admin-count">
                    {{ $cat->product_count }} {{ $cat->unitLabel() }}
                </div>
            </div>
        </div>

        {{-- Formulaire édition --}}
        <form method="POST"
              action="{{ route('admin.categories.update', $cat) }}"
              enctype="multipart/form-data"
              style="padding:16px">
            @csrf @method('PUT')

            {{-- Image --}}
            <div class="form-admin-group">
                <label class="form-admin-label">Image de fond (max 5 Mo)</label>
                <input class="form-admin-input"
                       type="file"
                       name="image"
                       accept="image/jpeg,image/png,image/webp" />
                @if($cat->image)
                    <div style="margin-top:6px;display:flex;align-items:center;gap:8px">
                        <span style="font-size:11px;color:#888">Image actuelle</span>
                        {{-- Option pour supprimer l'image --}}
                        <label style="font-size:11px;color:#e42829;cursor:pointer">
                            <input type="checkbox" name="remove_image" value="1"
                                   style="margin-right:4px" />
                            Supprimer l'image
                        </label>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="form-admin-group">
                <label class="form-admin-label">Description</label>
                <textarea class="form-admin-input"
                          name="description"
                          rows="2">{{ old('description', $cat->description) }}</textarea>
            </div>

            {{-- Icône + Couleur --}}
            <div class="form-admin-row">
                <div class="form-admin-group">
                    <label class="form-admin-label">Icône (emoji)</label>
                    <input class="form-admin-input"
                           type="text" name="icon"
                           value="{{ old('icon', $cat->icon) }}"
                           maxlength="4" />
                </div>
                <div class="form-admin-group">
                    <label class="form-admin-label">Couleur accent</label>
                    <input class="form-admin-input"
                           type="color"
                           name="color"
                           value="{{ old('color', $cat->color) }}"
                           style="height:40px;padding:2px" />
                </div>
            </div>

            {{-- Ordre --}}
            <div class="form-admin-group">
                <label class="form-admin-label">Ordre d'affichage</label>
                <input class="form-admin-input"
                       type="number" name="order"
                       value="{{ old('order', $cat->order) }}"
                       min="1" max="10" />
            </div>

            <button type="submit" class="btn-admin-primary" style="width:100%">
                💾 Enregistrer
            </button>

        </form>

    </div>
    @endforeach

</div>

@endsection