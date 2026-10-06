{{-- admin/vouchers/index.blade.php — Gestion des bons d'achat --}}
@extends('admin.layouts.app')
@section('title','Bons d\'achat')

@section('content')

{{-- KPIs --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:28px">
    <div class="admin-card" style="padding:18px 20px">
        <div style="font-size:22px;font-weight:700;color:var(--a-black)">{{ $stats['total'] }}</div>
        <div style="font-size:11px;color:var(--a-soft);margin-top:2px">Bons émis</div>
    </div>
    <div class="admin-card" style="padding:18px 20px">
        <div style="font-size:22px;font-weight:700;color:#16a34a">{{ $stats['active'] }}</div>
        <div style="font-size:11px;color:var(--a-soft);margin-top:2px">Actifs (solde > 0)</div>
    </div>
    <div class="admin-card" style="padding:18px 20px">
        <div style="font-size:22px;font-weight:700;color:var(--a-red)">{{ $stats['used'] }}</div>
        <div style="font-size:11px;color:var(--a-soft);margin-top:2px">Épuisés</div>
    </div>
    <div class="admin-card" style="padding:18px 20px">
        <div style="font-size:18px;font-weight:700;color:var(--a-black)">
            {{ number_format($stats['total_issued'], 0, ',', ' ') }} XOF
        </div>
        <div style="font-size:11px;color:var(--a-soft);margin-top:2px">Montant total émis</div>
    </div>
    <div class="admin-card" style="padding:18px 20px">
        <div style="font-size:18px;font-weight:700;color:var(--a-green)">
            {{ number_format($stats['total_balance'], 0, ',', ' ') }} XOF
        </div>
        <div style="font-size:11px;color:var(--a-soft);margin-top:2px">Solde total restant</div>
    </div>
</div>

<div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">

    {{-- Liste --}}
    <div style="flex:2;min-width:300px">

        {{-- Filtres --}}
        <div class="filter-tabs" style="margin-bottom:16px">
            @foreach(['all'=>'Tous','active'=>'✅ Actifs','used'=>'✗ Épuisés','pending'=>'⏳ En attente'] as $f=>$lbl)
                <a href="{{ route('admin.vouchers', ['filter'=>$f==='all'?null:$f]) }}"
                   class="filter-tab {{ request('filter',$f==='all'?null:$f)==($f==='all'?null:$f) ? 'active' : '' }}">
                    {{ $lbl }}
                </a>
            @endforeach
        </div>

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Acheteur</th>
                        <th>Bénéficiaire</th>
                        <th>Montant initial</th>
                        <th>Solde restant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $v)
                    <tr>
                        <td>
                            <strong style="font-family:monospace;color:var(--a-red);
                                           font-size:13px;letter-spacing:.04em">
                                {{ $v->code }}
                            </strong>
                        </td>
                        <td>
                            <div style="font-size:13px;font-weight:600">{{ $v->buyer_name }}</div>
                            <div style="font-size:11px;color:var(--a-soft)">{{ $v->buyer_phone }}</div>
                        </td>
                        <td style="font-size:12px;color:var(--a-mid)">
                            {{ $v->recipient_name ?? '—' }}
                        </td>
                        <td style="font-weight:700;white-space:nowrap">
                            {{ $v->formatted_initial_amount }}
                        </td>
                        <td>
                            @php
                                $pct = $v->initial_amount > 0
                                    ? round(($v->balance / $v->initial_amount) * 100)
                                    : 0;
                            @endphp
                            <div style="font-weight:700;color:{{ $pct > 50 ? '#16a34a' : ($pct > 0 ? '#c2410c' : 'var(--a-soft)') }}">
                                {{ $v->formatted_balance }}
                            </div>
                            <div style="height:3px;background:var(--a-border);border-radius:2px;
                                        margin-top:3px;width:80px">
                                <div style="height:100%;width:{{ $pct }}%;
                                            background:{{ $pct > 50 ? '#16a34a' : ($pct > 0 ? '#c2410c' : 'var(--a-soft)') }};
                                            border-radius:2px;transition:width .3s"></div>
                            </div>
                        </td>
                        <td>
                            @switch($v->status)
                                @case('active')
                                    <span class="status-badge status-green">✅ Actif</span> @break
                                @case('used')
                                    <span class="status-badge status-gray">✗ Épuisé</span> @break
                                @case('expired')
                                    <span class="status-badge status-orange">⏰ Expiré</span> @break
                                @case('cancelled')
                                    <span class="status-badge status-red">🚫 Annulé</span> @break
                                @default
                                    <span class="status-badge status-blue">⏳ En attente</span>
                            @endswitch
                        </td>
                        <td style="font-size:11px;color:var(--a-soft);white-space:nowrap">
                            {{ $v->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div style="display:flex;gap:4px">
                                @if($v->status === 'active')
                                    <form method="POST"
                                          action="{{ route('admin.vouchers.cancel', $v) }}"
                                          onsubmit="return confirm('Annuler ce bon ?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-sm btn-sm-red">Annuler</button>
                                    </form>
                                @endif
                                {{-- Voir usages --}}
                                @if($v->usages->count() > 0)
                                    <button class="btn-sm btn-sm-blue"
                                            onclick="toggleUsages('usages-{{ $v->id }}')">
                                        📋 {{ $v->usages->count() }} usage{{ $v->usages->count() > 1 ? 's' : '' }}
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Historique des usages --}}
                    @if($v->usages->count() > 0)
                    <tr id="usages-{{ $v->id }}" style="display:none">
                        <td colspan="8" style="padding:0 12px 12px">
                            <div style="background:var(--a-cream);border-radius:6px;padding:12px">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                            letter-spacing:.1em;color:var(--a-soft);margin-bottom:8px">
                                    Historique d'utilisation
                                </div>
                                @foreach($v->usages as $usage)
                                    <div style="display:flex;justify-content:space-between;
                                                font-size:12px;padding:5px 0;
                                                border-bottom:1px solid var(--a-border)">
                                        <span>
                                            {{ $usage->created_at->format('d/m/Y H:i') }}
                                            @if($usage->order)
                                                — Commande
                                                <strong>{{ $usage->order->reference }}</strong>
                                            @endif
                                        </span>
                                        <span>
                                            <span style="color:var(--a-red)">
                                                −{{ number_format($usage->amount_used, 0, ',', ' ') }} XOF
                                            </span>
                                            <span style="color:var(--a-soft);font-size:11px">
                                                (solde : {{ number_format($usage->balance_before, 0, ',', ' ') }}
                                                → {{ number_format($usage->balance_after, 0, ',', ' ') }} XOF)
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endif

                    @empty
                    <tr>
                        <td colspan="8" class="empty-msg">Aucun bon d'achat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:14px 16px">{{ $vouchers->links() }}</div>
        </div>
    </div>

    {{-- Création manuelle --}}
    <div style="flex:1;min-width:280px">
        <div class="admin-card">
            <div class="admin-card-title">🎁 Créer un bon manuellement</div>
            <form method="POST" action="{{ route('admin.vouchers.store') }}"
                  style="padding:20px;display:flex;flex-direction:column;gap:14px">
                @csrf

                <div class="form-admin-group">
                    <label class="form-admin-label">Montant (XOF) *</label>
                    <input class="form-admin-input" type="number"
                           name="amount" min="1000" step="1000"
                           placeholder="Ex: 50000" required />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Nom de l'acheteur</label>
                    <input class="form-admin-input" type="text"
                           name="buyer_name" placeholder="Nom complet" />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">WhatsApp acheteur</label>
                    <input class="form-admin-input" type="tel"
                           name="buyer_phone" placeholder="+229 01..." />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Nom du bénéficiaire</label>
                    <input class="form-admin-input" type="text"
                           name="recipient_name" placeholder="Pour qui ?" />
                </div>

                <div class="form-admin-group">
                    <label class="form-admin-label">Date d'expiration</label>
                    <input class="form-admin-input" type="date"
                           name="expires_at"
                           min="{{ now()->addDay()->format('Y-m-d') }}" />
                    <div style="font-size:10px;color:var(--a-soft);margin-top:3px">
                        Laisser vide = sans limite de durée
                    </div>
                </div>

                <button type="submit" class="btn-admin-primary">
                    + Générer le bon
                </button>

                <div style="font-size:10px;color:var(--a-soft);text-align:center">
                    Le code sera généré automatiquement.
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function toggleUsages(id) {
    var row = document.getElementById(id);
    if (row) row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endpush

@endsection
