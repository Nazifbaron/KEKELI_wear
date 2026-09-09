{{-- ============================================================
     admin/orders/show.blade.php
     Détail d'une commande — articles, client, paiement,
     mensuration liée, changement de statut.
============================================================ --}}
@extends('admin.layouts.app')
@section('title', 'Commande · ' . $order->reference)

@section('content')

<div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">

    {{-- ===== COLONNE PRINCIPALE ===== --}}
    <div style="flex:2;min-width:300px">

        {{-- Articles --}}
        <div class="admin-card" style="margin-bottom:20px">
            <div class="admin-card-title">🛍 Articles commandés</div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Prix unitaire</th>
                        <th>Qté</th>
                        <th>Sous-total</th>
                        <th>Sur-mesure</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td><strong>{{ $item->product_name }}</strong></td>
                        <td>{{ $item->category_name }}</td>
                        <td>{{ number_format($item->unit_price, 0, ',', ' ') }} XOF</td>
                        <td style="text-align:center">{{ $item->quantity }}</td>
                        <td><strong>{{ number_format($item->subtotal, 0, ',', ' ') }} XOF</strong></td>
                        <td style="text-align:center">
                            {{ $item->is_custom ? '✓ Oui' : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Récap montants --}}
            <div style="padding:16px;border-top:1px solid #1a1a1a;text-align:right">
                <div style="font-size:13px;color:#888;margin-bottom:4px">
                    Sous-total : {{ number_format($order->subtotal, 0, ',', ' ') }} XOF
                </div>
                @if($order->discount_amount > 0)
                <div style="font-size:13px;color:#4caf50;margin-bottom:4px">
                    Remise : −{{ number_format($order->discount_amount, 0, ',', ' ') }} XOF
                    @if($order->promoCode)
                        ({{ $order->promoCode->code }})
                    @endif
                </div>
                @endif
                <div style="font-size:16px;font-weight:700;color:var(--gold)">
                    Total : {{ number_format($order->total, 0, ',', ' ') }} XOF
                </div>
            </div>
        </div>

        {{-- Mensuration liée si sur-mesure --}}
        @if($order->measurement)
        <div class="admin-card">
            <div class="admin-card-title">📐 Mensuration associée</div>
            <div class="meas-grid">
                @foreach([
                    'Nom'       => $order->measurement->full_name,
                    'WhatsApp'  => $order->measurement->whatsapp,
                    'Morpho.'   => \App\Models\Measurement::morphologyLabel($order->measurement->morphology ?? ''),
                    'Dos'       => $order->measurement->back_size . ' cm',
                    'Poitrine'  => $order->measurement->chest . ' cm',
                    'Taille'    => $order->measurement->waist . ' cm',
                    'Hanches'   => $order->measurement->hips . ' cm',
                    'Hauteur'   => $order->measurement->height . ' cm',
                ] as $label => $val)
                <div class="meas-row">
                    <span class="meas-label">{{ $label }}</span>
                    <span class="meas-val">{{ $val ?: '—' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ===== COLONNE LATÉRALE ===== --}}
    <div style="flex:1;min-width:260px">

        {{-- Infos client --}}
        <div class="admin-card" style="margin-bottom:20px">
            <div class="admin-card-title">👤 Client</div>
            <div class="info-list">
                <div><span>Nom</span><strong>{{ $order->customer_name }}</strong></div>
                <div><span>Téléphone</span><strong>{{ $order->customer_phone }}</strong></div>
                @if($order->customer_email)
                <div><span>Email</span><strong>{{ $order->customer_email }}</strong></div>
                @endif
                @if($order->delivery_address)
                <div><span>Adresse</span><strong>{{ $order->delivery_address }}</strong></div>
                <div><span>Ville</span><strong>{{ $order->delivery_city }}</strong></div>
                <div><span>Pays</span><strong>{{ $order->delivery_country }}</strong></div>
                @endif
            </div>
            {{-- Bouton WhatsApp direct --}}
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer_phone) }}"
               target="_blank"
               class="btn-admin-primary"
               style="display:block;text-align:center;margin-top:12px;font-size:12px">
                📱 Contacter sur WhatsApp
            </a>
        </div>

        {{-- Paiement --}}
        <div class="admin-card" style="margin-bottom:20px">
            <div class="admin-card-title">💳 Paiement</div>
            <div class="info-list">
                <div>
                    <span>Méthode</span>
                    <strong style="text-transform:uppercase">
                        {{ str_replace('_', ' ', $order->payment_method) }}
                    </strong>
                </div>
                <div>
                    <span>Statut</span>
                    <span class="status-badge
                        {{ $order->payment_status === 'paid' ? 'status-green' : 'status-orange' }}">
                        {{ $order->payment_status_label }}
                    </span>
                </div>
                @if($order->payment_reference)
                <div>
                    <span>Réf. paiement</span>
                    <strong>{{ $order->payment_reference }}</strong>
                </div>
                @endif
                @if($order->paid_at)
                <div>
                    <span>Payée le</span>
                    <strong>{{ $order->paid_at->format('d/m/Y H:i') }}</strong>
                </div>
                @endif
            </div>
        </div>

        {{-- Changer le statut --}}
        <div class="admin-card">
            <div class="admin-card-title">🔄 Changer le statut</div>
            <form method="POST"
                  action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <select class="form-admin-input" name="status" style="margin-bottom:10px">
                    @foreach([
                        'pending'    => 'En attente',
                        'paid'       => 'Payée',
                        'processing' => 'En confection',
                        'shipped'    => 'Expédiée',
                        'delivered'  => 'Livrée',
                        'cancelled'  => 'Annulée',
                        'refunded'   => 'Remboursée',
                    ] as $val => $label)
                    <option value="{{ $val }}"
                        {{ $order->status === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-admin-primary" style="width:100%">
                    Mettre à jour
                </button>
            </form>
        </div>

    </div>

</div>

@endsection
