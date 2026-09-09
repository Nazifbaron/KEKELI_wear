{{-- ============================================================
     admin/orders/index.blade.php
     Liste des commandes avec filtres statut + paiement
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Commandes')

@section('content')

{{-- Filtres rapides --}}
<div class="filter-tabs" style="margin-bottom:20px">
    <a href="{{ route('admin.orders') }}"
       class="filter-tab {{ !request('status') && !request('payment') ? 'active' : '' }}">
        Toutes
    </a>
    <a href="{{ route('admin.orders', ['payment' => 'pending']) }}"
       class="filter-tab {{ request('payment') === 'pending' ? 'active' : '' }}">
        ⏳ En attente de paiement
    </a>
    <a href="{{ route('admin.orders', ['payment' => 'paid']) }}"
       class="filter-tab {{ request('payment') === 'paid' ? 'active' : '' }}">
        ✅ Payées
    </a>
    <a href="{{ route('admin.orders', ['status' => 'processing']) }}"
       class="filter-tab {{ request('status') === 'processing' ? 'active' : '' }}">
        ✂ En confection
    </a>
    <a href="{{ route('admin.orders', ['status' => 'shipped']) }}"
       class="filter-tab {{ request('status') === 'shipped' ? 'active' : '' }}">
        🚚 Expédiées
    </a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Client</th>
                <th>Articles</th>
                <th>Total</th>
                <th>Moyen paiement</th>
                <th>Paiement</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong style="color:var(--gold)">{{ $order->reference }}</strong></td>
                <td>
                    <div>{{ $order->customer_name }}</div>
                    <div style="font-size:11px;color:#888">{{ $order->customer_phone }}</div>
                </td>
                <td style="text-align:center">{{ $order->items->count() }}</td>
                <td><strong>{{ number_format($order->total, 0, ',', ' ') }} XOF</strong></td>
                <td style="text-transform:uppercase;font-size:11px">
                    {{ str_replace('_', ' ', $order->payment_method) }}
                </td>
                <td>
                    <span class="status-badge
                        {{ match($order->payment_status) {
                            'paid'     => 'status-green',
                            'failed'   => 'status-red',
                            'refunded' => 'status-blue',
                            default    => 'status-orange',
                        } }}">
                        {{ $order->payment_status_label }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-gray">{{ $order->status_label }}</span>
                </td>
                <td style="font-size:11px;color:#888">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="btn-sm btn-sm-blue">Détail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="empty-msg">Aucune commande.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $orders->links() }}</div>
</div>

@endsection
