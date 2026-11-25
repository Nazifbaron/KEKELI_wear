@php $p = isset($edit) && $edit ? 'edit-' : ''; @endphp

{{-- Type de média --}}
<div class="form-admin-group">
    <label class="form-admin-label">Type de fond de la slide</label>
    <div class="media-switch">
        <label class="media-opt">
            <input type="radio" name="media_type" value="image" id="{{ $p }}media-image"
                   {{ old('media_type','image') === 'image' ? 'checked' : '' }}
                   onchange="toggleMedia('{{ $p }}')" />
            <span>🖼 Image</span>
        </label>
        <label class="media-opt">
            <input type="radio" name="media_type" value="video" id="{{ $p }}media-video"
                   {{ old('media_type') === 'video' ? 'checked' : '' }}
                   onchange="toggleMedia('{{ $p }}')" />
            <span>🎬 Vidéo</span>
        </label>
    </div>
</div>

<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Tag (étiquette) *</label>
        <input class="form-admin-input" type="text" name="tag" id="{{ $p }}tag"
               value="{{ old('tag') }}" placeholder="Ex: Collection 2026" required />
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Couleur de l'overlay</label>
        <select class="form-admin-input" name="overlay_color" id="{{ $p }}overlay-color">
            <option value="red"    {{ old('overlay_color','red') === 'red' ? 'selected' : '' }}>🔴 Rouge</option>
            <option value="blue"   {{ old('overlay_color') === 'blue' ? 'selected' : '' }}>🔵 Bleu</option>
            <option value="purple" {{ old('overlay_color') === 'purple' ? 'selected' : '' }}>🟣 Violet</option>
        </select>
    </div>
</div>

<div class="form-admin-group">
    <label class="form-admin-label">Titre principal *</label>
    <input class="form-admin-input" type="text" name="title" id="{{ $p }}title"
           value="{{ old('title') }}" placeholder="Ex: Une lumière pour" required />
</div>

<div class="form-admin-group">
    <label class="form-admin-label">Partie en couleur <span style="font-weight:400;text-transform:none;color:var(--a-soft)">— affichée en rouge</span></label>
    <input class="form-admin-input" type="text" name="title_highlight" id="{{ $p }}title-highlight"
           value="{{ old('title_highlight') }}" placeholder="Ex: la mode au féminin." />
</div>

<div class="form-admin-group">
    <label class="form-admin-label">Sous-titre</label>
    <textarea class="form-admin-input" name="subtitle" id="{{ $p }}subtitle" rows="2">{{ old('subtitle') }}</textarea>
</div>

{{-- Bloc VIDÉO --}}
<div class="form-admin-group" id="{{ $p }}video-box" style="display:none">
    <label class="form-admin-label">Fichier vidéo * <span style="font-weight:400;text-transform:none;color:var(--a-soft)">— MP4 ou WebM, 50 Mo max</span></label>
    <input class="form-admin-input" type="file" name="video"
           accept="video/mp4,video/webm,video/quicktime" />
    <div id="{{ $p }}video-current" style="display:none;font-size:11px;color:#16a34a;margin-top:4px">
        ✓ Une vidéo est déjà enregistrée — choisissez un fichier seulement pour la remplacer.
    </div>
    <div style="font-size:10px;color:var(--a-soft);margin-top:4px">
        Conseil : vidéo courte (10–20 s), sans son, 1080p max, pour un chargement rapide.
        La vidéo est lue en boucle et muette.
    </div>
</div>

{{-- Image (fond ou affiche de la vidéo) --}}
<div class="form-admin-group">
    <label class="form-admin-label" id="{{ $p }}image-label">Image de fond (max 8 Mo)</label>
    <input class="form-admin-input" type="file" name="image" accept="image/jpeg,image/png,image/webp" />
    <div style="font-size:10px;color:var(--a-soft);margin-top:4px;display:none" id="{{ $p }}image-hint">
        Pour une vidéo, l'image sert d'affiche affichée pendant le chargement.
    </div>
</div>

<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton principal — texte</label>
        <input class="form-admin-input" type="text" name="btn_primary_label" id="{{ $p }}btn-primary-label" value="{{ old('btn_primary_label') }}" />
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton principal — lien</label>
        <input class="form-admin-input" type="text" name="btn_primary_url" id="{{ $p }}btn-primary-url" value="{{ old('btn_primary_url') }}" placeholder="#catalogue" />
    </div>
</div>
<div class="form-admin-row">
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton secondaire — texte</label>
        <input class="form-admin-input" type="text" name="btn_secondary_label" id="{{ $p }}btn-secondary-label" value="{{ old('btn_secondary_label') }}" />
    </div>
    <div class="form-admin-group">
        <label class="form-admin-label">Bouton secondaire — lien</label>
        <input class="form-admin-input" type="text" name="btn_secondary_url" id="{{ $p }}btn-secondary-url" value="{{ old('btn_secondary_url') }}" />
    </div>
</div>

<label class="check-label">
    <input type="hidden" name="is_active" value="0" />
    <input type="checkbox" name="is_active" value="1" id="{{ $p }}is-active" {{ old('is_active', true) ? 'checked' : '' }} />
    <span>Slide active (visible sur le site)</span>
</label>
