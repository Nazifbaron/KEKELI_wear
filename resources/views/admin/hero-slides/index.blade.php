{{-- ============================================================
     admin/hero-slides/index.blade.php
     Gestion des slides du carrousel hero.
     L'admin peut ajouter, modifier, activer/désactiver,
     supprimer et réordonner les slides.
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Slides Hero')

@section('content')

<div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">

    {{-- ===== LISTE DES SLIDES ===== --}}
    <div style="flex:2;min-width:300px">
        <div class="admin-card">
            @forelse($slides as $slide)
            <div class="slide-admin-item">

                {{-- Aperçu image --}}
                <div class="slide-thumb">
                    @if($slide->image)
                        <img src="{{ asset('storage/' . $slide->image) }}"
                             alt="{{ $slide->title }}" />
                    @else
                        <div class="slide-thumb-placeholder">🖼</div>
                    @endif
                </div>

                {{-- Infos --}}
                <div style="flex:1">
                    <div style="font-size:10px;color:var(--gold);font-weight:700;
                                text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">
                        {{ $slide->tag }}
                    </div>
                    <div style="font-size:14px;font-weight:700;color:#fff;margin-bottom:4px">
                        {{ $slide->title }}
                        @if($slide->title_highlight)
                            <span style="color:var(--gold)">{{ $slide->title_highlight }}</span>
                        @endif
                    </div>
                    @if($slide->subtitle)
                        <div style="font-size:11px;color:#888;margin-bottom:6px">
                            {{ Str::limit($slide->subtitle, 80) }}
                        </div>
                    @endif
                    <div style="font-size:10px;color:#666">
                        Ordre : {{ $slide->order }}
                        · Overlay : {{ $slide->overlay_color }}
                        @if($slide->btn_primary_label)
                            · Bouton : "{{ $slide->btn_primary_label }}"
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end">
                    {{-- Actif / Inactif --}}
                    @if($slide->is_active)
                        <span class="status-badge status-green">Actif</span>
                    @else
                        <span class="status-badge status-gray">Inactif</span>
                    @endif

                    <div style="display:flex;gap:6px">
                        {{-- Toggle actif --}}
                        <form method="POST"
                              action="{{ route('admin.hero-slides.toggle', $slide) }}">
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
                            <button type="submit" class="btn-sm btn-sm-red">✕</button>
                        </form>
                    </div>
                </div>

            </div>
            @empty
            <p class="empty-msg">
                Aucune slide configurée — les 3 slides par défaut sont actives.
            </p>
            @endforelse
        </div>
    </div>

    {{-- ===== FORMULAIRE AJOUT SLIDE ===== --}}
    <div style="flex:1;min-width:280px">
        <div class="admin-card">
            <div class="admin-card-title">+ Nouvelle slide</div>

            <form method="POST"
                  action="{{ route('admin.hero-slides.store') }}"
                  enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                    <div class="alert alert-error" style="margin-bottom:16px">
                        @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
                    </div>
                @endif

                <div class="form-admin-group">
                    <label class="form-admin-label">Tag (étiquette) *</label>
                    <input class="form-admin-input" type="text" name="tag"
                           placeholder="Ex: Collection 2026"
                           value="{{ old('tag') }}" required />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Titre principal *</label>
                    <input class="form-admin-input" type="text" name="title"
                           placeholder="Ex: Une lumière pour"
                           value="{{ old('title') }}" required />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Partie en couleur gold (optionnel)</label>
                    <input class="form-admin-input" type="text" name="title_highlight"
                           placeholder="Ex: la mode au féminin."
                           value="{{ old('title_highlight') }}" />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Sous-titre</label>
                    <textarea class="form-admin-input" name="subtitle"
                              rows="2"
                              placeholder="Description courte...">{{ old('subtitle') }}</textarea>
                </div>

                <div class="form-admin-row">
                    <div class="form-admin-group">
                        <label class="form-admin-label">Bouton 1 — texte</label>
                        <input class="form-admin-input" type="text" name="btn_primary_label"
                               placeholder="Ex: Découvrir"
                               value="{{ old('btn_primary_label') }}" />
                    </div>
                    <div class="form-admin-group">
                        <label class="form-admin-label">Bouton 1 — lien</label>
                        <input class="form-admin-input" type="text" name="btn_primary_url"
                               placeholder="Ex: #catalogue"
                               value="{{ old('btn_primary_url') }}" />
                    </div>
                </div>

                <div class="form-admin-row">
                    <div class="form-admin-group">
                        <label class="form-admin-label">Bouton 2 — texte</label>
                        <input class="form-admin-input" type="text" name="btn_secondary_label"
                               placeholder="Ex: WhatsApp"
                               value="{{ old('btn_secondary_label') }}" />
                    </div>
                    <div class="form-admin-group">
                        <label class="form-admin-label">Bouton 2 — lien</label>
                        <input class="form-admin-input" type="text" name="btn_secondary_url"
                               placeholder="Ex: https://wa.me/..."
                               value="{{ old('btn_secondary_url') }}" />
                    </div>
                </div>

                <div class="form-admin-row">
                    <div class="form-admin-group">
                        <label class="form-admin-label">Couleur overlay</label>
                        <select class="form-admin-input" name="overlay_color">
                            <option value="red"    {{ old('overlay_color') === 'red'    ? 'selected' : '' }}>🔴 Rouge</option>
                            <option value="blue"   {{ old('overlay_color') === 'blue'   ? 'selected' : '' }}>🔵 Bleu</option>
                            <option value="purple" {{ old('overlay_color') === 'purple' ? 'selected' : '' }}>🟣 Violet</option>
                        </select>
                    </div>
                    <div class="form-admin-group">
                        <label class="form-admin-label">Ordre d'affichage</label>
                        <input class="form-admin-input" type="number" name="order"
                               value="{{ old('order', $slides->count() + 1) }}" min="0" />
                    </div>
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Image de fond (max 8 Mo)</label>
                    <input class="form-admin-input" type="file" name="image"
                           accept="image/jpeg,image/png,image/webp" />
                </div>

                <label class="check-label" style="margin-bottom:16px">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" value="1" checked />
                    <span>Activer immédiatement</span>
                </label>

                <button type="submit" class="btn-admin-primary" style="width:100%">
                    Ajouter la slide
                </button>

            </form>
        </div>
    </div>

</div>

@endsection
