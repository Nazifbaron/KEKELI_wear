{{-- checkout/payment.blade.php — Paiement KKiaPay --}}
@extends('checkout.layout')
@section('step', 2)
@section('title', 'Paiement')

@section('checkout-content')

<div style="max-width:520px;margin:0 auto">

    {{-- Récap commande --}}
    <div class="ck-recap-card">
        <div class="ck-recap-ref">
            Commande <strong>{{ $ref }}</strong>
        </div>
        <div class="ck-recap-amount">
            <span>Total à payer</span>
            <span class="ck-recap-total" id="recap-total">
                {{ number_format($order->total, 0, ',', ' ') }} XOF
            </span>
        </div>
        @if($order->discount_amount > 0)
            <div style="font-size:11px;color:var(--ck-soft);text-align:right;margin-top:4px">
                Dont {{ number_format($order->discount_amount, 0, ',', ' ') }} XOF de remise appliquée
            </div>
        @endif
    </div>

    {{-- Choisir le mode de paiement --}}
    <div class="ck-section-title" style="margin-bottom:16px">Finaliser le paiement</div>

    <div class="ck-pay-options">

        @if($order->payment_method !== 'whatsapp')
        {{-- Payer via KKiaPay --}}
        <div class="ck-pay-option" onclick="payWithKkiapay()">
            <div class="ck-pay-icon">💳</div>
            <div class="ck-pay-info">
                <div class="ck-pay-name">Payer maintenant via KKiaPay</div>
                <div class="ck-pay-sub">MTN MoMo · Moov Money · Carte bancaire</div>
            </div>
            <div class="ck-pay-arrow">→</div>
        </div>
        @endif

        {{-- Commande WhatsApp --}}
        <div class="ck-pay-option ck-pay-wa" onclick="payViaWhatsapp()">
            <div class="ck-pay-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#25D366">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
            </div>
            <div class="ck-pay-info">
                <div class="ck-pay-name">Commander via WhatsApp</div>
                <div class="ck-pay-sub">Paiement à la livraison ou arrangement direct</div>
            </div>
            <div class="ck-pay-arrow">→</div>
        </div>

    </div>

    {{-- Sécurité --}}
    <div style="display:flex;align-items:center;justify-content:center;gap:10px;
                margin-top:24px;padding:14px;background:var(--ck-cream);
                border-radius:6px;border:1px solid var(--ck-border)">
        <span style="font-size:20px">🔒</span>
        <div>
            <div style="font-size:11px;font-weight:700;color:var(--ck-black)">Paiement sécurisé KKiaPay</div>
            <div style="font-size:10px;color:var(--ck-soft)">
                Vos données bancaires ne transitent jamais par nos serveurs
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
{{-- SDK KKiaPay --}}
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
var ORDER_REF = '{{ $ref }}';
var AMOUNT    = {{ $order->total }};
var CUSTOMER  = '{{ addslashes($order->customer_name) }}';
var CSRF      = document.querySelector('meta[name="csrf-token"]')?.content;

function payWithKkiapay() {
    openKkiapayWidget({
        amount:   AMOUNT,
        name:     CUSTOMER,
        callback: handlePaymentSuccess,
        theme:    '#e42829',
        sandbox:  '{{ config("kekeli.kkiapay_env") }}' === 'sandbox',
        key:      '{{ config("kekeli.kkiapay_public_key") }}',
    });
}

function handlePaymentSuccess(response) {
    /* Vérification côté serveur + mise à jour de la commande */
    fetch('/checkout/payment/' + ORDER_REF + '/callback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            transaction_id: response.transactionId,
            status: response.status,
        }),
    })
    .then(r => r.json())
    .then(function(d) {
        if (d.success) {
            window.location.href = '/checkout/confirmation/' + ORDER_REF;
        } else {
            alert('Paiement non confirmé. Contactez-nous sur WhatsApp si vous avez été débité.');
        }
    });
}

function payViaWhatsapp() {
    fetch('/api/order/' + ORDER_REF, { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(function(d) {
        var msg = '🛍 *Commande KEKELI WEAR*\n\n'
                + 'Réf : *' + d.reference + '*\n'
                + 'Client : ' + d.customer_name + '\n'
                + 'Téléphone : ' + d.customer_phone + '\n'
                + 'Ville : ' + d.delivery_city + '\n'
                + 'Total : *' + parseInt(d.total).toLocaleString('fr-FR') + ' XOF*\n\n'
                + 'Je souhaite confirmer cette commande.';
        window.open('https://wa.me/{{ config("kekeli.whatsapp") }}?text=' + encodeURIComponent(msg), '_blank');
        window.location.href = '/checkout/confirmation/' + ORDER_REF;
    });
}
</script>
@endpush
