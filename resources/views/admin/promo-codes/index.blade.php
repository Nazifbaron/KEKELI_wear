{{-- admin/promo-codes/index.blade.php — avec restriction par catégorie --}}
@extends('admin.layouts.app')
@section('title','Codes Promo')

@section('content')

<div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">

    {{-- Liste --}}
    <div style="flex:2;min-width:300px">
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Portée</th>
                        <th>Remise</th>
                        <th>Utilisations</th>
                        <th>Expiration</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $promo)
                    <tr>
                        <td>
                            <strong style="font-family:monospace;color:var(--a-red);font-size:14px">
                                {{ $promo->code }}
                            </strong>
                        </td>
                        <td style="font-size:12px">
                            @if($promo->category)
                                <span class="status-badge status-blue">
                                    {{ $promo->category->icon }} {{ $promo->category->name }}
                                </span>
                            @else
                                <span class="status-badge status-gray">🌐 Tous</span>
                            @endif
                        </td>
                        <td><strong>{{ $promo->label }}</strong></td>
                        <td style="text-align:center">
                            {{ $promo->used_count }}
                            {{ $promo->max_uses ? '/ ' . $promo->max_uses : '/ ∞' }}
                        </td>
                        <td style="font-size:11px;color:var(--a-soft)">
                            {{ $promo->expires_at ? $promo->expires_at->format('d/m/Y') : 'Sans limite' }}
                        </td>
                        <td>
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
                    <tr><td colspan="7" class="empty-msg">Aucun code promo.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:20px">{{ $promoCodes->links() }}</div>
        </div>
    </div>

    {{-- Formulaire création --}}
    <div style="flex:1;min-width:280px">
        <div class="admin-card">
            <div class="admin-card-title">+ Nouveau code promo</div>
            <form method="POST" action="{{ route('admin.promo-codes.store') }}"
                  style="padding:20px">
                @csrf

                @if($errors->any())
                    <div class="alert alert-error" style="margin-bottom:16px">
                        @foreach($errors->all() as $err)<div>✗ {{ $err }}</div>@endforeach
                    </div>
                @endif

                <div class="form-admin-group">
                    <label class="form-admin-label">Code *</label>
                    <input class="form-admin-input" type="text" name="code"
                           placeholder="Ex: KEKELI10" value="{{ old('code') }}"
                           style="text-transform:uppercase" required />
                </div>

                {{-- NOUVEAU : Restriction par catégorie --}}
                <div class="form-admin-group">
                    <label class="form-admin-label">
                        Portée du code
                        <span style="font-weight:400;text-transform:none;color:var(--a-soft)">
                            — À quel univers s'applique-t-il ?
                        </span>
                    </label>
                    <select class="form-admin-input" name="category_id">
                        <option value="">🌐 Tous les produits</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }} uniquement
                            </option>
                        @endforeach
                    </select>
                    <div style="font-size:10px;color:var(--a-soft);margin-top:4px">
                        Si "Tous les produits" → le code s'applique à tout le panier.<br>
                        Sinon → uniquement aux produits de l'univers sélectionné.
                    </div>
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Remise *</label>
                          <input class="form-admin-input" type="number" name="discount" id="promo-discount"
                              placeholder="Ex: 10" min="1" max="100" step="0.5"
                           value="{{ old('discount') }}" required />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Type</label>
                    <select class="form-admin-input" name="type" id="promo-type" onchange="syncPromoDiscountInput()">
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
                           placeholder="Vide = illimité" value="{{ old('max_uses') }}" min="1" />
                </div>

                <div class="form-admin-row">
                    <div class="form-admin-group">
                        <label class="form-admin-label">Début</label>
                        <input class="form-admin-input" type="date" name="starts_at"
                               value="{{ old('starts_at') }}" />
                    </div>
                    <div class="form-admin-group">
                        <label class="form-admin-label">Fin</label>
                        <input class="form-admin-input" type="date" name="expires_at"
                               value="{{ old('expires_at') }}" />
                    </div>
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

<script>
    function syncPromoDiscountInput() {
        var type = document.getElementById('promo-type')?.value;
        var amount = document.getElementById('promo-discount');
        if (!amount) return;
        var percentage = type === 'percentage';
        amount.max = percentage ? '100' : '';
        amount.step = percentage ? '0.5' : '1';
        amount.placeholder = percentage ? 'Ex. : 10' : 'Ex. : 5 000 XOF';
        if (percentage && Number(amount.value) > 100) amount.value = '';
    }
    syncPromoDiscountInput();
</script>

@endsection
