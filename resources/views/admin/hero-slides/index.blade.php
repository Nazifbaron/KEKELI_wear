@extends('admin.layouts.app')
@section('title','Slides Hero')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
    <p style="font-size:13px;color:var(--a-soft)">
        {{ $slides->count() }} slide{{ $slides->count() > 1 ? 's' : '' }} ·
        <strong>Glissez-déposez</strong> les lignes (poignée ⠿) pour choisir l'ordre d'affichage.
    </p>
    <button class="btn-admin-primary" onclick="openSlideModal('modal-create-slide')">+ Nouvelle slide</button>
</div>

<div id="order-toast" style="display:none;position:fixed;bottom:24px;right:24px;z-index:2000;background:#16a34a;color:#fff;padding:10px 18px;border-radius:6px;font-size:12px;font-weight:700;box-shadow:0 8px 24px rgba(0,0,0,.2)"></div>

<div class="admin-card" id="slides-list">
    @forelse($slides as $slide)
    <div class="slide-admin-item" data-id="{{ $slide->id }}">

        <div class="slide-handle" title="Glisser pour réordonner">⠿</div>
        <div class="slide-rank">{{ $loop->iteration }}</div>

        <div class="slide-thumb">
            @if($slide->is_video)
                <video src="{{ $slide->video_url }}" muted preload="metadata" poster="{{ $slide->image_url }}"
                       style="width:100%;height:100%;object-fit:cover"></video>
                <span class="slide-type-pill">🎬 Vidéo</span>
            @elseif($slide->image)
                <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" />
                <span class="slide-type-pill">🖼 Image</span>
            @else
                <div class="slide-thumb-placeholder">🖼</div>
            @endif
        </div>

        <div style="flex:1;min-width:0">
            <div style="font-size:10px;color:var(--a-gold);font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:3px">{{ $slide->tag }}</div>
            <div style="font-size:14px;font-weight:700;color:var(--a-black)">
                {{ $slide->title }} <span style="color:var(--a-red)">{{ $slide->title_highlight }}</span>
            </div>
            @if($slide->subtitle)
                <div style="font-size:11px;color:var(--a-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:340px">{{ Str::limit($slide->subtitle, 80) }}</div>
            @endif
        </div>

        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0">
            <span class="status-badge {{ $slide->is_active ? 'status-green' : 'status-gray' }}">
                ● {{ $slide->is_active ? 'Actif' : 'Inactif' }}
            </span>
            <div style="display:flex;gap:6px">
                <button class="btn-sm btn-sm-blue" data-slide="{{ json_encode($slide->only(['id','tag','title','title_highlight','subtitle','media_type','btn_primary_label','btn_primary_url','btn_secondary_label','btn_secondary_url','overlay_color','is_active']) + ['has_video' => (bool) $slide->video]) }}"
                        onclick="openEditSlide(this)">✏ Modifier</button>
                <form method="POST" action="{{ route('admin.hero-slides.toggle', $slide) }}">
                    @csrf @method('PATCH')
                    <button class="btn-sm">{{ $slide->is_active ? 'Désactiver' : 'Activer' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" onsubmit="return confirm('Supprimer cette slide ?')">
                    @csrf @method('DELETE')
                    <button class="btn-sm btn-sm-red">🗑</button>
                </form>
            </div>
        </div>
    </div>
    @empty
        <div class="empty-msg" style="padding:40px">Aucune slide — les slides par défaut sont utilisées.</div>
    @endforelse
</div>

{{-- MODAL CRÉER --}}
<div class="modal-backdrop" id="modal-create-slide" style="display:none" onclick="closeSlideModal('modal-create-slide')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-title">🎬 Nouvelle slide hero</div>
            <button class="modal-close" onclick="closeSlideModal('modal-create-slide')">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data" style="padding:22px 28px 28px">
            @csrf
            <input type="hidden" name="_slide_form" value="create" />
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom:16px">
                    @foreach($errors->all() as $e)<div>✗ {{ $e }}</div>@endforeach
                </div>
            @endif
            @include('admin.hero-slides._form_fields')
            <div style="display:flex;gap:12px;margin-top:20px">
                <button type="submit" class="btn-admin-primary">✦ Créer la slide</button>
                <button type="button" class="btn-admin-secondary" onclick="closeSlideModal('modal-create-slide')">Annuler</button>
            </div>
            <div style="font-size:10px;color:var(--a-soft);margin-top:10px">La slide est ajoutée en dernière position — glissez-la ensuite où vous voulez.</div>
        </form>
    </div>
</div>

{{-- MODAL MODIFIER --}}
<div class="modal-backdrop" id="modal-edit-slide" style="display:none" onclick="closeSlideModal('modal-edit-slide')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-title">✏ Modifier la slide</div>
            <button class="modal-close" onclick="closeSlideModal('modal-edit-slide')">✕</button>
        </div>
        <form method="POST" id="edit-slide-form" enctype="multipart/form-data" style="padding:22px 28px 28px">
            @csrf @method('PUT')
            @include('admin.hero-slides._form_fields', ['edit' => true])
            <div style="display:flex;gap:12px;margin-top:20px">
                <button type="submit" class="btn-admin-primary">💾 Enregistrer</button>
                <button type="button" class="btn-admin-secondary" onclick="closeSlideModal('modal-edit-slide')">Annuler</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.slide-admin-item{display:flex;align-items:center;gap:14px;padding:14px 18px;border-bottom:1px solid var(--a-border);background:var(--a-white);transition:background .2s}
.slide-admin-item:last-child{border-bottom:none}
.slide-handle{cursor:grab;font-size:22px;color:var(--a-soft);padding:0 4px;user-select:none}
.slide-handle:active{cursor:grabbing}
.slide-rank{width:26px;height:26px;border-radius:50%;background:var(--a-black);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.slide-thumb{position:relative;width:120px;height:68px;border-radius:5px;overflow:hidden;flex-shrink:0;background:#eee;border:1px solid var(--a-border)}
.slide-thumb img{width:100%;height:100%;object-fit:cover}
.slide-thumb-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:22px}
.slide-type-pill{position:absolute;bottom:4px;left:4px;background:rgba(0,0,0,.7);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:3px}
.sortable-ghost{opacity:.35;background:#FFF5F5}
.sortable-chosen{box-shadow:0 8px 28px rgba(0,0,0,.15)}
.media-switch{display:flex;gap:10px}
.media-opt{flex:1;cursor:pointer}
.media-opt input{display:none}
.media-opt span{display:block;text-align:center;padding:11px;border:1.5px solid var(--a-border);border-radius:6px;font-size:13px;font-weight:700;color:var(--a-mid);transition:all .2s}
.media-opt input:checked + span{border-color:var(--a-red);background:#FFF5F5;color:var(--a-red)}
.modal-backdrop{position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;padding:20px}
.modal-box{background:var(--a-white);border-radius:10px;width:100%;max-width:620px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 80px rgba(0,0,0,.2)}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px 16px;border-bottom:1px solid var(--a-border)}
.modal-title{font-size:18px;font-weight:700}
.modal-close{background:none;border:none;font-size:22px;color:var(--a-soft);cursor:pointer}
@media(max-width:700px){.slide-admin-item{flex-wrap:wrap}.slide-thumb{width:100%;height:140px}}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
var CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

/* ---------- Glisser-déposer ---------- */
var list = document.getElementById('slides-list');
if (list && list.querySelector('.slide-admin-item')) {
    Sortable.create(list, {
        handle: '.slide-handle', animation: 180,
        ghostClass: 'sortable-ghost', chosenClass: 'sortable-chosen',
        onEnd: saveOrder
    });
}
function saveOrder() {
    var ids = Array.from(document.querySelectorAll('.slide-admin-item')).map(function(el){ return parseInt(el.dataset.id); });
    document.querySelectorAll('.slide-rank').forEach(function(el, i){ el.textContent = i + 1; });
    fetch('{{ route("admin.hero-slides.reorder") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({ids: ids})
    }).then(function(r){ return r.json(); })
      .then(function(){ toast('✓ Ordre enregistré'); })
      .catch(function(){ toast('✗ Erreur — rechargez la page', true); });
}
function toast(msg, err) {
    var t = document.getElementById('order-toast');
    t.textContent = msg; t.style.background = err ? '#c01f20' : '#16a34a'; t.style.display = 'block';
    clearTimeout(window._tt); window._tt = setTimeout(function(){ t.style.display = 'none'; }, 2200);
}

/* ---------- Modals ---------- */
function openSlideModal(id){ document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; toggleMedia(''); }
function closeSlideModal(id){ document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeSlideModal('modal-create-slide'); closeSlideModal('modal-edit-slide'); }});

function toggleMedia(p) {
    var isVideo = document.getElementById(p + 'media-video')?.checked;
    var box = document.getElementById(p + 'video-box');
    if (box) box.style.display = isVideo ? 'block' : 'none';
    var lbl = document.getElementById(p + 'image-label');
    if (lbl) lbl.textContent = isVideo ? 'Image d\'affiche de la vidéo (optionnel)' : 'Image de fond (max 8 Mo)';
    var hint = document.getElementById(p + 'image-hint');
    if (hint) hint.style.display = isVideo ? 'block' : 'none';
}

function openEditSlide(btn) {
    var s = JSON.parse(btn.dataset.slide);
    document.getElementById('edit-slide-form').action = '/admin/hero-slides/' + s.id;
    var map = {'edit-tag':'tag','edit-title':'title','edit-title-highlight':'title_highlight','edit-subtitle':'subtitle',
               'edit-btn-primary-label':'btn_primary_label','edit-btn-primary-url':'btn_primary_url',
               'edit-btn-secondary-label':'btn_secondary_label','edit-btn-secondary-url':'btn_secondary_url',
               'edit-overlay-color':'overlay_color'};
    Object.keys(map).forEach(function(id){ var el = document.getElementById(id); if (el) el.value = s[map[id]] || ''; });
    document.getElementById('edit-is-active').checked = !!s.is_active;
    document.getElementById('edit-media-' + (s.media_type === 'video' ? 'video' : 'image')).checked = true;
    document.getElementById('edit-video-current').style.display = s.has_video ? 'block' : 'none';
    toggleMedia('edit-');
    document.getElementById('modal-edit-slide').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

@if($errors->any() && old('_slide_form') === 'create')
document.addEventListener('DOMContentLoaded', function(){ openSlideModal('modal-create-slide'); });
@endif
</script>
@endpush
