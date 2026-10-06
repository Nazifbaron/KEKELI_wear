{{-- ============================================================
     admin/hero-slides/_form_fields.blade.php
     Champs partagés entre le modal création et le modal édition.
     En mode édition les id des champs ont le préfixe "edit-"
============================================================ --}}
@php $prefix = isset($edit) && $edit ? 'edit-' : ''; @endphp

<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Tag (étiquette) *</label>
        <input class="form-admin-input" type="text"
               name="tag" id="{{ $prefix }}tag"
               value="{{ old('tag', $slide->tag ?? '') }}"
               placeholder="Ex: Collection 2026" required />
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Ordre d'affichage</label>
        <input class="form-admin-input" type="number"
               name="order" id="{{ $prefix }}order"
               value="{{ old('order', $slide->order ?? 1) }}"
               min="0" max="99" />
    </div>
</div>

<div class="form-admin-group">
    <label class="form-admin-label">Titre principal *</label>
    <input class="form-admin-input" type="text"
           name="title" id="{{ $prefix }}title"
           value="{{ old('title', $slide->title ?? '') }}"
           placeholder="Ex: Une lumière pour" required />
</div>

<div class="form-admin-group">
    <label class="form-admin-label">
        Partie en couleur
        <span style="font-weight:400;text-transform:none;color:var(--a-soft)">— affiché en rouge sous le titre principal</span>
    </label>
    <input class="form-admin-input" type="text"
           name="title_highlight" id="{{ $prefix }}title-highlight"
           value="{{ old('title_highlight', $slide->title_highlight ?? '') }}"
           placeholder="Ex: la mode au féminin." />
</div>

<div class="form-admin-group">
    <label class="form-admin-label">Sous-titre</label>
    <textarea class="form-admin-input" name="subtitle" id="{{ $prefix }}subtitle"
              rows="2" placeholder="Description courte...">{{ old('subtitle', $slide->subtitle ?? '') }}</textarea>
</div>

{{-- Bouton 1 --}}
<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton principal — texte</label>
        <input class="form-admin-input" type="text"
               name="btn_primary_label" id="{{ $prefix }}btn-primary-label"
               value="{{ old('btn_primary_label', $slide->btn_primary_label ?? '') }}"
               placeholder="Ex: Découvrir nos créations" />
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton principal — lien</label>
        <input class="form-admin-input" type="text"
               name="btn_primary_url" id="{{ $prefix }}btn-primary-url"
               value="{{ old('btn_primary_url', $slide->btn_primary_url ?? '') }}"
               placeholder="Ex: #catalogue" />
    </div>
</div>

{{-- Bouton 2 --}}
<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton secondaire — texte</label>
        <input class="form-admin-input" type="text"
               name="btn_secondary_label" id="{{ $prefix }}btn-secondary-label"
               value="{{ old('btn_secondary_label', $slide->btn_secondary_label ?? '') }}"
               placeholder="Ex: Commander sur WhatsApp" />
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton secondaire — lien</label>
        <input class="form-admin-input" type="text"
               name="btn_secondary_url" id="{{ $prefix }}btn-secondary-url"
               value="{{ old('btn_secondary_url', $slide->btn_secondary_url ?? '') }}"
               placeholder="Ex: https://wa.me/22901401349" />
    </div>
</div>

<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Couleur de l'overlay</label>
        <select class="form-admin-input" name="overlay_color" id="{{ $prefix }}overlay-color">
            <option value="red"    {{ old('overlay_color', $slide->overlay_color ?? 'red')    === 'red'    ? 'selected' : '' }}>🔴 Rouge (défaut)</option>
            <option value="blue"   {{ old('overlay_color', $slide->overlay_color ?? '')        === 'blue'   ? 'selected' : '' }}>🔵 Bleu</option>
            <option value="purple" {{ old('overlay_color', $slide->overlay_color ?? '')        === 'purple' ? 'selected' : '' }}>🟣 Violet</option>
        </select>
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Image de fond (max 8 Mo)</label>
        <input class="form-admin-input" type="file"
               name="image" accept="image/jpeg,image/png,image/webp" />
    </div>
</div>

<label class="check-label">
    <input type="hidden" name="is_active" value="0" />
    <input type="checkbox" name="is_active" value="1"
           id="{{ $prefix }}is-active"
           {{ old('is_active', $slide->is_active ?? true) ? 'checked' : '' }} />
    <span>Slide active (visible sur le site)</span>
</label>
