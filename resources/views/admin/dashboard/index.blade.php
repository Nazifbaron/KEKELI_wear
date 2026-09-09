{{-- ============================================================
     admin/dashboard/index.blade.php
     Vue d'ensemble — KPIs, top produits, dernières commandes,
     mensurations en attente.
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Dashboard')

@section('content')

{{-- ===== KPI CARDS ===== --}}
<div class="kpi-grid">

    <div class="kpi-card kpi-red">
        <div class="kpi-icon">👗</div>
        <div class="kpi-value">{{ $total_products }}</div>
        <div class="kpi-label">Produits actifs</div>
    </div>

    <div class="kpi-card kpi-gold">
        <div class="kpi-icon">🛍</div>
        <div class="kpi-value">{{ $total_orders }}</div>
        <div class="kpi-label">Commandes totales</div>
    </div>

    <div class="kpi-card kpi-green">
        <div class="kpi-icon">💳</div>
        <div class="kpi-value">{{ $total_paid }}</div>
        <div class="kpi-label">Commandes payées</div>
    </div>

    <div class="kpi-card kpi-blue">
        <div class="kpi-icon">💰</div>
        <div class="kpi-value">{{ number_format($revenue, 0, ',', ' ') }} XOF</div>
        <div class="kpi-label">Chiffre d'affaires</div>
    </div>

    <div class="kpi-card kpi-purple">
        <div class="kpi-icon">❤</div>
        <div class="kpi-value">{{ number_format($total_likes) }}</div>
        <div class="kpi-label">Total likes</div>
    </div>

    <div class="kpi-card kpi-dark">
        <div class="kpi-icon">📐</div>
        <div class="kpi-value">{{ $total_measurements }}</div>
        <div class="kpi-label">Mensurations reçues</div>
    </div>

</div>

{{-- ===== GRILLE INFOS ===== --}}
<div class="admin-grid-2">

    {{-- Top produits par score --}}
    <div class="admin-card">
        <div class="admin-card-title">🔥 Top produits (par score)</div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Likes</th>
                    <th>Vues</th>
                    <th>Score</th>
                    <th>Coup de cœur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_products as $p)
                <tr>
                    <td><strong>{{ $p->name }}</strong></td>
                    <td>{{ $p->category->name }}</td>
                    <td>{{ $p->likes }}</td>
                    <td>{{ number_format($p->views) }}</td>
                    <td>
                        <span class="badge-score">{{ number_format($p->heart_score, 1) }}</span>
                    </td>
                    <td>
                        @if($p->is_featured)
                            <span class="status-badge status-green">✓ Oui</span>
                        @else
                            <span class="status-badge status-gray">Non</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mensurations en attente --}}
    <div class="admin-card">
        <div class="admin-card-title">📐 Mensurations récentes</div>
        @forelse($pending_measurements as $m)
        <div class="meas-item">
            <div>
                <div class="meas-name">{{ $m->full_name }}</div>
                <div class="meas-meta">
                    {{ $m->whatsapp }}
                    @if($m->morphology)
                        · <em>{{ \App\Models\Measurement::morphologyLabel($m->morphology) }}</em>
                    @endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <span class="status-badge
                    {{ $m->status === 'received' ? 'status-orange'
                     : ($m->status === 'processing' ? 'status-blue' : 'status-green') }}">
                    {{ match($m->status) {
                        'received'   => 'Reçue',
                        'processing' => 'En cours',
                        'ordered'    => 'Commandée',
                    } }}
                </span>
                <a href="{{ route('admin.measurements') }}"
                   class="btn-sm">Voir</a>
            </div>
        </div>
        @empty
        <p class="empty-msg">Aucune mensuration en attente.</p>
        @endforelse
    </div>

</div>

{{-- Dernières commandes --}}
<div class="admin-card" style="margin-top:24px">
    <div class="admin-card-title" style="display:flex;justify-content:space-between;align-items:center">
        🛍 Dernières commandes
        <a href="{{ route('admin.orders') }}" class="btn-sm">Voir tout</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Client</th>
                <th>Total</th>
                <th>Paiement</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($latest_orders as $order)
            <tr>
                <td><strong>{{ $order->reference }}</strong></td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ number_format($order->total, 0, ',', ' ') }} XOF</td>
                <td>
                    <span class="status-badge
                        {{ $order->payment_status === 'paid' ? 'status-green'
                         : ($order->payment_status === 'failed' ? 'status-red' : 'status-orange') }}">
                        {{ $order->payment_status_label }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-gray">{{ $order->status_label }}</span>
                </td>
                <td style="font-size:11px;color:#888">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-msg">Aucune commande pour le moment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
