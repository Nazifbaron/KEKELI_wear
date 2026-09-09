{{-- ============================================================
     admin/promo-codes/index.blade.php
     Gestion des codes promo — liste + formulaire création
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Codes Promo')

@section('content')

<div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">

    {{-- Liste codes --}}
    <div style="flex:2;min-width:300px">
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Remise</th>
                        <th>Type</th>
                        <th>Utilisations</th>
                        <th>Expiration</th>
                        <th>Actif</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $promo)
                    <tr>
                        <td>
                            <strong style="font-family:monospace;color:var(--gold);font-size:14px">
                                {{ $promo->code }}
                            </strong>
                        </td>
                        <td>{{ $promo->label }}</td>
                        <td style="text-transform:capitalize">{{ $promo->type }}</td>
                        <td style="text-align:center">
                            {{ $promo->used_count }}
                            {{ $promo->max_uses ? '/ ' . $promo->max_uses : '/ ∞' }}
                        </td>
                        <td style="font-size:11px;color:#888">
                            {{ $promo->expires_at
                                ? $promo->expires_at->format('d/m/Y')
                                : 'Pas de limite' }}
                        </td>
                        <td style="text-align:center">
                            @if($promo->is_active && $promo->isValid())
                                <span class="status-badge status-green">Actif</span>
                            @elseif($promo->is_active)
                                <span class="status-badge status-orange">Expiré</span>
                            @else
                                <span class="status-badge status-gray">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.promo-codes.toggle', $promo) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-sm">
                                    {{ $promo->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-msg">Aucun code promo.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:20px">{{ $promoCodes->links() }}</div>
        </div>
    </div>

    {{-- Formulaire création --}}
    <div style="flex:1;min-width:260px">
        <div class="admin-card">
            <div class="admin-card-title">+ Nouveau code promo</div>
            <form method="POST" action="{{ route('admin.promo-codes.store') }}">
                @csrf

                @if($errors->any())
                    <div class="alert alert-error" style="margin-bottom:16px">
                        @foreach($errors->all() as $err)
                            <div>✗ {{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="form-admin-group">
                    <label class="form-admin-label">Code *</label>
                    <input class="form-admin-input" type="text" name="code"
                           placeholder="Ex: KEKELI10"
                           value="{{ old('code') }}"
                           style="text-transform:uppercase" required />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Remise *</label>
                    <input class="form-admin-input" type="number" name="discount"
                           placeholder="Ex: 10" min="1" max="100" step="0.5"
                           value="{{ old('discount') }}" required />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Type</label>
                    <select class="form-admin-input" name="type">
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>
                            Pourcentage (%)
                        </option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>
                            Montant fixe (XOF)
                        </option>
                    </select>
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Limite d'utilisations</label>
                    <input class="form-admin-input" type="number" name="max_uses"
                           placeholder="Laisser vide = illimité"
                           value="{{ old('max_uses') }}" min="1" />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Date de début</label>
                    <input class="form-admin-input" type="date" name="starts_at"
                           value="{{ old('starts_at') }}" />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Date d'expiration</label>
                    <input class="form-admin-input" type="date" name="expires_at"
                           value="{{ old('expires_at') }}" />
                </div>

                <label class="check-label" style="margin-bottom:16px">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" value="1" checked />
                    <span>Activer immédiatement</span>
                </label>

                <button type="submit" class="btn-admin-primary" style="width:100%">
                    Créer le code
                </button>

            </form>
        </div>
    </div>

</div>

@endsection
