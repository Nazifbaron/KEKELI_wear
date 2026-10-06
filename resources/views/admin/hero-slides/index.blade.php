{{-- ============================================================
     admin/hero-slides/index.blade.php
     Gestion des slides hero — ajout, modification, toggle, suppression
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Slides Hero')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <p style="font-size:13px;color:var(--a-soft)">
        {{ $slides->count() }} slide{{ $slides->count() > 1 ? 's' : '' }} configurée{{ $slides->count() > 1 ? 's' : '' }}
        · Les slides s'affichent dans l'ordre défini.
    </p>
    <button class="btn-admin-primary" onclick="openCreateSlideModal()">
        + Nouvelle slide
    </button>
</div>

{{-- ===== LISTE DES SLIDES ===== --}}
<div class="admin-card">
    @forelse($slides as $slide)
    <div class="slide-admin-item" id="slide-item-{{ $slide->id }}">

        {{-- Miniature --}}
        <div class="slide-thumb">
            @if($slide->image)
                <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" />
            @else
                <div class="slide-thumb-placeholder"
                     style="background:linear-gradient(135deg,#1a1a1a,#{{ substr(md5($slide->title), 0, 6) }})">
                    🖼
                </div>
            @endif
        </div>

        {{-- Infos slide --}}
        <div style="flex:1;min-width:0">
            <div style="font-size:10px;color:var(--a-gold);font-weight:700;
                        text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px">
                {{ $slide->tag }}
            </div>
            <div style="font-size:14px;font-weight:700;color:var(--a-black);margin-bottom:2px">
                {{ $slide->title }}
                @if($slide->title_highlight)
                    <span style="color:var(--a-red)">{{ $slide->title_highlight }}</span>
                @endif
            </div>
            @if($slide->subtitle)
                <div style="font-size:11px;color:var(--a-soft);
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:340px">
                    {{ Str::limit($slide->subtitle, 80) }}
                </div>
            @endif
            <div style="font-size:10px;color:var(--a-soft);margin-top:4px;display:flex;gap:10px;flex-wrap:wrap">
                <span>Ordre : <strong>{{ $slide->order }}</strong></span>
                <span>Overlay : <strong>{{ ucfirst($slide->overlay_color) }}</strong></span>
                @if($slide->btn_primary_label)
                    <span>Bouton : <strong>"{{ $slide->btn_primary_label }}"</strong></span>
                @endif
            </div>
        </div>

        {{-- Statut + Actions --}}
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0">

            {{-- Statut --}}
            @if($slide->is_active)
                <span class="status-badge status-green">● Actif</span>
            @else
                <span class="status-badge status-gray">● Inactif</span>
            @endif

            {{-- Boutons --}}
            <div style="display:flex;gap:6px">

                {{-- Modifier --}}
                <button class="btn-sm btn-sm-blue"
                        onclick="openEditSlideModal(
                            {{ $slide->id }},
                            '{{ addslashes($slide->tag) }}',
                            '{{ addslashes($slide->title) }}',
                            '{{ addslashes($slide->title_highlight ?? '') }}',
                            '{{ addslashes($slide->subtitle ?? '') }}',
                            '{{ addslashes($slide->btn_primary_label ?? '') }}',
                            '{{ addslashes($slide->btn_primary_url ?? '') }}',
                            '{{ addslashes($slide->btn_secondary_label ?? '') }}',
                            '{{ addslashes($slide->btn_secondary_url ?? '') }}',
                            '{{ $slide->overlay_color }}',
                            {{ $slide->order }},
                            {{ $slide->is_active ? 'true' : 'false' }}
                        )">
                    ✏ Modifier
                </button>

                {{-- Toggle --}}
                <form method="POST" action="{{ route('admin.hero-slides.toggle', $slide) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-sm">
                        {{ $slide->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>

                {{-- Supprimer --}}
                <form method="POST"
                      action="{{ route('admin.hero-slides.destroy', $slide) }}"
                      onsubmit="return confirm('Supprimer cette slide ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sm btn-sm-red">🗑</button>
                </form>

            </div>
        </div>

    </div>
    @empty
    <div class="empty-msg" style="padding:40px">
        Aucune slide configurée — les 3 slides par défaut sont utilisées.
    </div>
    @endforelse
</div>


{{-- ============================================================
     MODAL — CRÉER UNE SLIDE
============================================================ --}}
<div class="modal-backdrop" id="modal-create-slide" style="display:none"
     onclick="closeSlideModal('modal-create-slide')">
    <div class="modal-box" onclick="event.stopPropagation()">

        <div class="modal-header">
            <div class="modal-title">🖼 Nouvelle slide hero</div>
            <button class="modal-close" onclick="closeSlideModal('modal-create-slide')">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.hero-slides.store') }}"
              enctype="multipart/form-data" style="padding:22px 28px 28px">
            @csrf

            @if($errors->any() && old('_slide_form') === 'create')
                <div class="alert alert-error" style="margin-bottom:16px">
                    @foreach($errors->all() as $err)<div>✗ {{ $err }}</div>@endforeach
                </div>
            @endif
            <input type="hidden" name="_slide_form" value="create" />

            @include('admin.hero-slides._form_fields', ['slide' => null])

            <div style="display:flex;gap:12px;margin-top:20px">
                <button type="submit" class="btn-admin-primary">✦ Créer la slide</button>
                <button type="button" class="btn-admin-secondary"
                        onclick="closeSlideModal('modal-create-slide')">Annuler</button>
            </div>
        </form>
    </div>
</div>


{{-- ============================================================
     MODAL — MODIFIER UNE SLIDE
============================================================ --}}
<div class="modal-backdrop" id="modal-edit-slide" style="display:none"
     onclick="closeSlideModal('modal-edit-slide')">
    <div class="modal-box" onclick="event.stopPropagation()">

        <div class="modal-header">
            <div class="modal-title">✏ Modifier la slide</div>
            <button class="modal-close" onclick="closeSlideModal('modal-edit-slide')">✕</button>
        </div>

        <form method="POST" id="edit-slide-form" enctype="multipart/form-data"
              style="padding:22px 28px 28px">
            @csrf @method('PUT')

            @include('admin.hero-slides._form_fields', ['slide' => null, 'edit' => true])

            <div style="display:flex;gap:12px;margin-top:20px">
                <button type="submit" class="btn-admin-primary">💾 Enregistrer</button>
                <button type="button" class="btn-admin-secondary"
                        onclick="closeSlideModal('modal-edit-slide')">Annuler</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
.modal-backdrop {
    position:fixed;inset:0;z-index:1000;
    background:rgba(0,0,0,.5);backdrop-filter:blur(4px);
    display:flex;align-items:center;justify-content:center;padding:20px;
}
.modal-box {
    background:var(--a-white);border-radius:10px;
    width:100%;max-width:620px;max-height:90vh;overflow-y:auto;
    box-shadow:0 24px 80px rgba(0,0,0,.2);
    animation:modalIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalIn { from{transform:scale(.92);opacity:0} to{transform:scale(1);opacity:1} }
.modal-header {
    display:flex;align-items:center;justify-content:space-between;
    padding:22px 28px 16px;border-bottom:1px solid var(--a-border);
}
.modal-title { font-size:18px;font-weight:700;color:var(--a-black); }
.modal-close {
    background:none;border:none;font-size:22px;color:var(--a-soft);
    cursor:pointer;line-height:1;transition:color .2s;
}
.modal-close:hover { color:var(--a-red); }
</style>
@endpush

@push('scripts')
<script>
function openCreateSlideModal() {
    document.getElementById('modal-create-slide').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function openEditSlideModal(id, tag, title, titleHighlight, subtitle,
    btnPrimaryLabel, btnPrimaryUrl, btnSecondaryLabel, btnSecondaryUrl,
    overlayColor, order, isActive) {

    var form = document.getElementById('edit-slide-form');
    form.action = '/admin/hero-slides/' + id;

    /* Remplir tous les champs du modal édition */
    setField('edit-tag',                   tag);
    setField('edit-title',                 title);
    setField('edit-title-highlight',       titleHighlight);
    setField('edit-subtitle',              subtitle);
    setField('edit-btn-primary-label',     btnPrimaryLabel);
    setField('edit-btn-primary-url',       btnPrimaryUrl);
    setField('edit-btn-secondary-label',   btnSecondaryLabel);
    setField('edit-btn-secondary-url',     btnSecondaryUrl);
    setField('edit-overlay-color',         overlayColor);
    setField('edit-order',                 order);

    var activeCheckbox = document.getElementById('edit-is-active');
    if (activeCheckbox) activeCheckbox.checked = isActive;

    document.getElementById('modal-edit-slide').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function setField(id, value) {
    var el = document.getElementById(id);
    if (el) {
        if (el.tagName === 'SELECT') {
            el.value = value;
        } else {
            el.value = value || '';
        }
    }
}

function closeSlideModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSlideModal('modal-create-slide');
        closeSlideModal('modal-edit-slide');
    }
});

/* Rouvrir le modal si erreurs */
@if($errors->any() && old('_slide_form') === 'create')
    document.addEventListener('DOMContentLoaded', openCreateSlideModal);
@endif
</script>
@endpush
