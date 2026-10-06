{{-- ============================================================
     admin/categories/index.blade.php
     CRUD complet des univers/catégories
     - Cartes visuelles avec aperçu image de fond
     - Création d'un nouvel univers
     - Édition inline (nom, slug, description, icône, couleur, image)
     - Activation / désactivation
     - Suppression avec confirmation (uniquement si aucun produit lié)
============================================================ --}}
@extends('admin.layouts.app')
@section('title', 'Univers & Catégories')

@section('content')

{{-- ===== EN-TÊTE ===== --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;flex-wrap:wrap;gap:12px">
    <div>
        <p style="font-size:13px;color:var(--a-soft)">
            {{ $categories->total() }} univers configurés ·
            Chaque univers regroupe une famille de créations dans la boutique.
        </p>
    </div>
    <button class="btn-admin-primary" onclick="openCreateModal()">
        + Nouvel univers
    </button>
</div>

{{-- ===== GRILLE DES UNIVERS EXISTANTS ===== --}}
<div class="cat-admin-grid">
    @forelse($categories as $cat)
    <div class="cat-admin-card" id="cat-card-{{ $cat->id }}">

        {{-- Aperçu visuel de la carte --}}
        <div class="cat-admin-preview"
             style="{{ $cat->image
                ? 'background-image:url(' . asset('storage/'.$cat->image) . ');background-size:cover ;background-position:start'
                : 'background:linear-gradient(135deg,' . ($cat->color ?? '#1A1A1A') . '22, ' . ($cat->color ?? '#e42829') . '44)' }}">

            {{-- Overlay --}}
            <div class="cat-admin-preview-overlay"></div>

            {{-- Contenu superposé --}}
            <div class="cat-admin-preview-content">
                <div class="cat-admin-icon-big">{{ $cat->icon }}</div>
                <div class="cat-admin-name-preview">{{ $cat->name }}</div>
                <div class="cat-admin-count-preview">
                    {{ $cat->product_count }} {{ $cat->unitLabel() }}
                </div>
            </div>

            {{-- Pastille statut --}}
            <div class="cat-admin-status-pill">
                @if($cat->is_active)
                    <span class="status-badge status-green" style="font-size:9px">● Actif</span>
                @else
                    <span class="status-badge status-gray" style="font-size:9px">● Inactif</span>
                @endif
            </div>
        </div>

        {{-- Infos et actions --}}
        <div class="cat-admin-body">

            <div class="cat-admin-info">
                <div class="cat-admin-title">{{ $cat->name }}</div>
                <div class="cat-admin-slug">
                    <code>{{ $cat->slug }}</code>
                </div>
                @if($cat->description)
                    <div class="cat-admin-desc">{{ Str::limit($cat->description, 72) }}</div>
                @endif
            </div>

            <div class="cat-admin-actions">

                {{-- Modifier --}}
                <button class="btn-sm btn-sm-blue"
                        onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->slug) }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->icon }}', '{{ $cat->color ?? '#e42829' }}', {{ $cat->is_active ? 'true' : 'false' }})">
                    ✏ Modifier
                </button>

                {{-- Toggle actif --}}
                <form method="POST" action="{{ route('admin.categories.toggle', $cat) }}" style="display:inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-sm">
                        {{ $cat->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>

                {{-- Supprimer — uniquement si aucun produit lié --}}
                @if($cat->product_count === 0)
                    <form method="POST"
                          action="{{ route('admin.categories.destroy', $cat) }}"
                          onsubmit="return confirm('Supprimer l\'univers « {{ $cat->name }} » ? Cette action est irréversible.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-sm btn-sm-red">🗑 Supprimer</button>
                    </form>
                @else
                    <span style="font-size:10px;color:var(--a-soft)" title="Retirez d'abord les {{ $cat->product_count }} produit(s) de cet univers.">
                        🔒 {{ $cat->product_count }} produit{{ $cat->product_count > 1 ? 's' : '' }}
                    </span>
                @endif

            </div>

        </div>

        {{-- Formulaire upload image (inline sous la carte) --}}
        <div class="cat-admin-img-form">
            <form method="POST"
                  action="{{ route('admin.categories.update', $cat) }}"
                  enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <input class="form-admin-input" type="file"
                           name="image" accept="image/jpeg,image/png,image/webp"
                           style="flex:1;font-size:11px;padding:6px 8px" />
                    <button type="submit" class="btn-sm">📷 Upload</button>
                    @if($cat->image)
                        <button type="submit" name="remove_image" value="1"
                                class="btn-sm btn-sm-red"
                                onclick="return confirm('Supprimer l\'image ?')">✕</button>
                    @endif
                </div>
                {{-- Champs cachés pour ne pas écraser les autres données --}}
                <input type="hidden" name="name"        value="{{ $cat->name }}" />
                <input type="hidden" name="description" value="{{ $cat->description }}" />
                <input type="hidden" name="icon"        value="{{ $cat->icon }}" />
                <input type="hidden" name="color"       value="{{ $cat->color }}" />
                <input type="hidden" name="order"       value="{{ $cat->order }}" />
            </form>
        </div>

    </div>
    @empty
        <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--a-soft)">
            <p style="font-size:24px;margin-bottom:12px">🗂</p>
            <p>Aucun univers créé. Commencez par en créer un.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($categories->hasPages())
    <div style="margin-top:24px">{{ $categories->links() }}</div>
@endif


{{-- ============================================================
     MODAL — CRÉER UN NOUVEL UNIVERS
============================================================ --}}
<div class="modal-backdrop" id="modal-create" style="display:none" onclick="closeModal('modal-create')">
    <div class="modal-box" onclick="event.stopPropagation()">

        <div class="modal-header">
            <div class="modal-title">✦ Nouvel univers</div>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any() && old('_form') === 'create')
                <div class="alert alert-error" style="margin-bottom:16px">
                    @foreach($errors->all() as $err)<div>✗ {{ $err }}</div>@endforeach
                </div>
            @endif
            <input type="hidden" name="_form" value="create" />

            <div class="form-admin-row">
                <div class="form-admin-group">
                    <label class="form-admin-label">Nom de l'univers *</label>
                    <input class="form-admin-input" type="text" name="name"
                           value="{{ old('name') }}"
                           placeholder="Ex: Robes de Soirée"
                           oninput="autoSlug(this.value)"
                           required />
                </div>
                <div class="form-admin-group">
                    <label class="form-admin-label">
                        Slug (URL)
                        <span style="font-weight:400;text-transform:none;color:var(--a-soft)">— auto-généré</span>
                    </label>
                    <input class="form-admin-input" type="text" name="slug"
                           id="slug-preview"
                           value="{{ old('slug') }}"
                           placeholder="robes-de-soiree"
                           required />
                </div>
            </div>

            <div class="form-admin-group">
                <label class="form-admin-label">Description</label>
                <textarea class="form-admin-input" name="description" rows="2"
                          placeholder="Courte description de cet univers...">{{ old('description') }}</textarea>
            </div>

            <div class="form-admin-row">
                <div class="form-admin-group">
                    <label class="form-admin-label">Icône (emoji)</label>
                    <input class="form-admin-input" type="text" name="icon"
                           value="{{ old('icon', '✨') }}"
                           maxlength="4"
                           placeholder="Ex: 👗" />
                </div>
                <div class="form-admin-group">
                    <label class="form-admin-label">Couleur accent</label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input class="form-admin-input" type="color"
                               name="color" value="{{ old('color', '#e42829') }}"
                               style="height:40px;padding:2px;width:60px;flex-shrink:0" />
                        <input class="form-admin-input" type="text"
                               id="color-hex-create"
                               value="{{ old('color', '#e42829') }}"
                               placeholder="#e42829"
                               style="font-family:monospace"
                               oninput="syncColorPicker(this, 'color-create')" />
                    </div>
                </div>
            </div>

            <div class="form-admin-row">
                <div class="form-admin-group">
                    <label class="form-admin-label">Image de fond (max 5 Mo)</label>
                    <input class="form-admin-input" type="file" name="image"
                           accept="image/jpeg,image/png,image/webp" />
                </div>
                <div class="form-admin-group">
                    <label class="form-admin-label">Ordre d'affichage</label>
                    <input class="form-admin-input" type="number" name="order"
                           value="{{ old('order', $categories->count() + 1) }}"
                           min="1" max="99" />
                </div>
            </div>

            <label class="check-label" style="margin-bottom:20px">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" value="1" checked />
                <span>Activer immédiatement sur le site</span>
            </label>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-admin-primary">✦ Créer l'univers</button>
                <button type="button" class="btn-admin-secondary" onclick="closeModal('modal-create')">Annuler</button>
            </div>

        </form>
    </div>
</div>


{{-- ============================================================
     MODAL — MODIFIER UN UNIVERS
============================================================ --}}
<div class="modal-backdrop" id="modal-edit" style="display:none" onclick="closeModal('modal-edit')">
    <div class="modal-box" onclick="event.stopPropagation()">

        <div class="modal-header">
            <div class="modal-title">✏ Modifier l'univers</div>
            <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        </div>

        <form method="POST" id="edit-form" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-admin-row">
                <div class="form-admin-group">
                    <label class="form-admin-label">Nom de l'univers *</label>
                    <input class="form-admin-input" type="text"
                           name="name" id="edit-name" required />
                </div>
                <div class="form-admin-group">
                    <label class="form-admin-label">Slug (URL)</label>
                    <input class="form-admin-input" type="text"
                           name="slug" id="edit-slug" required />
                    <div style="font-size:10px;color:var(--a-soft);margin-top:3px">
                        ⚠ Modifier le slug peut casser les liens existants.
                    </div>
                </div>
            </div>

            <div class="form-admin-group">
                <label class="form-admin-label">Description</label>
                <textarea class="form-admin-input" name="description"
                          id="edit-description" rows="2"></textarea>
            </div>

            <div class="form-admin-row">
                <div class="form-admin-group">
                    <label class="form-admin-label">Icône (emoji)</label>
                    <input class="form-admin-input" type="text"
                           name="icon" id="edit-icon" maxlength="4" />
                </div>
                <div class="form-admin-group">
                    <label class="form-admin-label">Couleur accent</label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input class="form-admin-input" type="color"
                               name="color" id="edit-color-picker"
                               style="height:40px;padding:2px;width:60px;flex-shrink:0"
                               oninput="document.getElementById('edit-color-hex').value = this.value" />
                        <input class="form-admin-input" type="text"
                               id="edit-color-hex" placeholder="#e42829"
                               style="font-family:monospace"
                               oninput="document.getElementById('edit-color-picker').value = this.value; document.querySelector('[name=color]').value = this.value" />
                    </div>
                </div>
            </div>

            <div class="form-admin-group">
                <label class="form-admin-label">Image de fond (remplace l'actuelle)</label>
                <input class="form-admin-input" type="file" name="image"
                       accept="image/jpeg,image/png,image/webp" />
            </div>

            <label class="check-label" style="margin-bottom:20px">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" value="1" id="edit-active" />
                <span>Univers actif sur le site</span>
            </label>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-admin-primary">💾 Enregistrer</button>
                <button type="button" class="btn-admin-secondary" onclick="closeModal('modal-edit')">Annuler</button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ============================================================
   GRILLE CATÉGORIES
============================================================ */
.cat-admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}
.cat-admin-card {
    background: var(--a-white);
    border: 1px solid var(--a-border);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--a-shadow);
    transition: box-shadow .2s, transform .2s;
}
.cat-admin-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.1);
    transform: translateY(-2px);
}

/* Aperçu image */
.cat-admin-preview {
    height: 170px;
    position: relative;
    background: #f0ece8;
}
.cat-admin-preview-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.15) 60%, transparent 100%);
}
.cat-admin-preview-content {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: flex-end;
    padding-bottom: 16px; gap: 4px; z-index: 2;
}
.cat-admin-icon-big    { font-size: 28px; }
.cat-admin-name-preview { font-size: 15px; font-weight: 700; color: #fff; text-align: center; }
.cat-admin-count-preview { font-size: 11px; color: rgba(255,255,255,.65); }
.cat-admin-status-pill {
    position: absolute; top: 12px; right: 12px; z-index: 3;
}

/* Corps de la carte */
.cat-admin-body { padding: 16px 18px; }
.cat-admin-title { font-size: 14px; font-weight: 700; color: var(--a-black); margin-bottom: 4px; }
.cat-admin-slug  { font-size: 11px; color: var(--a-soft); margin-bottom: 6px; }
.cat-admin-slug code {
    background: var(--a-cream); padding: 2px 6px;
    border-radius: 3px; font-size: 11px;
}
.cat-admin-desc  { font-size: 12px; color: var(--a-mid); line-height: 1.5; margin-bottom: 0; }
.cat-admin-actions {
    display: flex; gap: 6px; flex-wrap: wrap; margin-top: 12px;
    padding-top: 12px; border-top: 1px solid var(--a-border);
}

/* Zone upload image */
.cat-admin-img-form {
    padding: 10px 18px 14px;
    background: var(--a-cream);
    border-top: 1px solid var(--a-border);
}
.cat-admin-img-form p {
    font-size: 10px; color: var(--a-soft);
    margin-bottom: 8px; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase;
}

/* ============================================================
   MODALS
============================================================ */
.modal-backdrop {
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(0,0,0,.5);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
}
.modal-box {
    background: var(--a-white);
    border-radius: 10px;
    width: 100%; max-width: 580px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 24px 80px rgba(0,0,0,.2);
    animation: modalIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalIn {
    from { transform: scale(.92); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}
.modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 22px 28px 16px;
    border-bottom: 1px solid var(--a-border);
}
.modal-title {
    font-size: 18px; font-weight: 700; color: var(--a-black);
}
.modal-close {
    background: none; border: none; font-size: 22px;
    color: var(--a-soft); cursor: pointer; line-height: 1;
    transition: color .2s;
}
.modal-close:hover { color: var(--a-red); }
.modal-box .form-admin-group,
.modal-box .form-admin-row,
.modal-box .check-label,
.modal-box [type="submit"],
.modal-box .btn-admin-secondary {
    margin-left: 0;
}
.modal-box form {
    padding: 22px 28px 28px;
}
.modal-box .alert { margin-bottom: 16px; margin-left: 0; }

@media (max-width: 600px) {
    .cat-admin-grid { grid-template-columns: 1fr; }
    .modal-box { border-radius: 6px; }
}
</style>
@endpush

@push('scripts')
<script>
/* ============================================================
   MODALS
============================================================ */
function openCreateModal() {
    document.getElementById('modal-create').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function openEditModal(id, name, slug, description, icon, color, isActive) {
    var form = document.getElementById('edit-form');
    form.action = '/admin/categories/' + id;

    document.getElementById('edit-name').value        = name;
    document.getElementById('edit-slug').value        = slug;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-icon').value        = icon;
    document.getElementById('edit-color-picker').value = color;
    document.getElementById('edit-color-hex').value    = color;
    document.getElementById('edit-active').checked     = isActive;

    document.getElementById('modal-edit').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}

/* Fermer les modals avec Echap */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('modal-create');
        closeModal('modal-edit');
    }
});

/* Auto-génération du slug depuis le nom */
function autoSlug(value) {
    var slug = value
        .toLowerCase()
        .normalize('NFD').replace(/\p{Diacritic}/gu, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    document.getElementById('slug-preview').value = slug;
}

/* Réouvrir le modal si erreurs de validation */
@if($errors->any() && old('_form') === 'create')
    document.addEventListener('DOMContentLoaded', function() { openCreateModal(); });
@endif
</script>
@endpush
