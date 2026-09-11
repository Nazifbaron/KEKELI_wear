{{-- ============================================================
     checkout/payment.blade.php
     Étape 2 — Paiement via FedaPay.
     FedaPay est la passerelle la plus utilisée en Afrique
     de l'Ouest — supporte MTN MoMo, Moov Money, carte.
     Le SDK JS FedaPay est chargé dynamiquement.
     En mode sandbox (test) : aucune vraie transaction.
     En mode live : remplacer la clé publique dans .env.
============================================================ --}}
@extends('checkout.layout')
@section('title', 'Paiement')

@section('content')

<div class="payment-page">

    <div class="checkout-card payment-card-main">

        {{-- Icône sécurité --}}
        <div style="text-align:center;margin-bottom:28px">
            <div style="font-size:48px;margin-bottom:12px">🔒</div>
            <div style="font-size:18px;font-weight:700;color:#fff;margin-bottom:6px">
                Paiement sécurisé
            </div>
            <div style="font-size:13px;color:var(--kgray)">
                Référence commande : <strong style="color:var(--kgold)">{{ $ref }}</strong>
            </div>
        </div>

        {{-- Récap montant --}}
        <div class="payment-amount-box" id="payment-amount-box">
            <div style="font-size:12px;color:var(--kgray);margin-bottom:4px">Montant à régler</div>
            <div style="font-size:32px;font-weight:700;color:var(--kgold)" id="payment-amount">
                Chargement...
            </div>
        </div>

        {{-- Bouton de paiement FedaPay --}}
        <button class="btn-fedapay" id="btn-fedapay" onclick="initFedaPay()">
            💳 Payer maintenant
        </button>

        {{-- Méthodes acceptées --}}
        <div class="payment-methods-logos">
            <span class="pm-logo pm-mtn">MTN MoMo</span>
            <span class="pm-logo pm-moov">Moov Money</span>
            <span class="pm-logo pm-card">Visa / MC</span>
        </div>

        {{-- Message d'état --}}
        <div id="payment-status" style="display:none"></div>

        {{-- Lien retour --}}
        <div style="text-align:center;margin-top:20px">
            <a href="{{ route('checkout.summary') }}"
               style="font-size:12px;color:var(--kgray);text-decoration:none">
                ← Modifier ma commande
            </a>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.payment-page        { display:flex; align-items:center; justify-content:center; min-height:60vh; padding:24px; }
.payment-card-main   { max-width:440px; width:100%; padding:40px; }
.payment-amount-box  { background:rgba(237,197,48,.08); border:1px solid rgba(237,197,48,.2); border-radius:6px; padding:20px; text-align:center; margin-bottom:28px; }
.btn-fedapay         { width:100%; background:var(--kred); color:#fff; border:none; padding:15px; font-size:13px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; cursor:pointer; border-radius:4px; transition:all .2s; margin-bottom:16px; }
.btn-fedapay:hover   { background:#c01f20; }
.btn-fedapay:disabled { opacity:.5; cursor:not-allowed; }
.payment-methods-logos { display:flex; gap:8px; justify-content:center; flex-wrap:wrap; margin-bottom:20px; }
.pm-logo   { font-size:10px; font-weight:700; padding:4px 10px; border-radius:3px; letter-spacing:.08em; }
.pm-mtn    { background:rgba(255,200,0,.15); color:#ffc800; border:1px solid rgba(255,200,0,.3); }
.pm-moov   { background:rgba(0,120,215,.15); color:#4fa8ff; border:1px solid rgba(0,120,215,.3); }
.pm-card   { background:rgba(255,255,255,.06); color:#aaa; border:1px solid rgba(255,255,255,.1); }
.payment-status-ok  { background:rgba(76,175,80,.1); border:1px solid rgba(76,175,80,.3); border-left:3px solid #4caf50; border-radius:4px; padding:14px 16px; font-size:13px; color:#a5d6a7; text-align:center; margin-top:16px; }
.payment-status-err { background:rgba(228,40,41,.1); border:1px solid rgba(228,40,41,.3); border-left:3px solid var(--kred); border-radius:4px; padding:14px 16px; font-size:13px; color:#ef9a9a; text-align:center; margin-top:16px; }
</style>
@endpush

@push('scripts')
{{-- SDK FedaPay — chargé depuis leur CDN --}}
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>

<script>
var ORDER_REF = '{{ $ref }}';
var orderData = null;

/*
|----------------------------------------------------------
| Au chargement — récupérer les infos de la commande
| depuis l'API pour afficher le montant et initialiser FedaPay
|----------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/order/' + ORDER_REF, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        orderData = data;
        var amountEl = document.getElementById('payment-amount');
        if (amountEl && data.total) {
            amountEl.textContent =
                parseInt(data.total).toLocaleString('fr-FR') + ' XOF';
        }
    })
    .catch(() => {
        // Fallback si l'API est indisponible
        document.getElementById('payment-amount').textContent = 'Voir la confirmation';
    });
});

/*
|----------------------------------------------------------
| Initialiser le widget FedaPay
| Documentation : https://docs.fedapay.com/checkout
|
| En mode TEST  → clé pk_sandbox_xxx (aucune vraie transaction)
| En mode LIVE  → clé pk_live_xxx (transactions réelles)
| La clé est injectée depuis config/kekeli.php → .env
|----------------------------------------------------------
*/
function initFedaPay() {
    var btn = document.getElementById('btn-fedapay');
    btn.disabled = true;
    btn.textContent = 'Connexion au service de paiement...';

    FedaPay.init({
        /*
        | Clé publique FedaPay — configurer dans .env :
        | FEDAPAY_PUBLIC_KEY=pk_sandbox_xxx  (test)
        | FEDAPAY_PUBLIC_KEY=pk_live_xxx     (production)
        */
        public_key: '{{ config("kekeli.fedapay_public_key", "pk_sandbox_xxx") }}',
        environment: '{{ config("kekeli.fedapay_env", "sandbox") }}',

        transaction: {
            amount:      orderData?.total   || 0,
            description: 'Commande KEKELI WEAR — ' + ORDER_REF,
        },
        customer: {
            firstname: orderData?.customer_name?.split(' ')[0] || '',
            lastname:  orderData?.customer_name?.split(' ').slice(1).join(' ') || '',
            email:     orderData?.customer_email || 'client@kekeliwear.com',
            phone_number: {
                number:  orderData?.customer_phone || '',
                country: 'BJ',
            },
        },

        /*
        |----------------------------------------------------------
        | Callbacks FedaPay
        |----------------------------------------------------------
        */
        onComplete: function(response) {
            if (response.reason === FedaPay.DIALOG_DISMISSED) {
                // Client a fermé sans payer
                btn.disabled  = false;
                btn.textContent = '💳 Réessayer le paiement';
                showStatus('Paiement annulé. Vous pouvez réessayer.', 'err');
                return;
            }

            if (response.reason === FedaPay.CHECKOUT_COMPLETED) {
                var transaction = response.transaction;

                // Notifier le backend du succès
                apiPost('/checkout/payment/' + ORDER_REF + '/callback', {
                    status:         'SUCCESS',
                    transaction_id: transaction.id,
                    reference:      ORDER_REF,
                })
                .then(() => {
                    // Rediriger vers la page de confirmation
                    window.location.href = '/checkout/confirmation/' + ORDER_REF;
                })
                .catch(() => {
                    // Paiement ok mais erreur callback → quand même rediriger
                    window.location.href = '/checkout/confirmation/' + ORDER_REF;
                });
            }
        },
    }).open();

    // Réactiver le bouton après 3s si le widget ne s'ouvre pas
    setTimeout(() => {
        btn.disabled    = false;
        btn.textContent = '💳 Payer maintenant';
    }, 3000);
}

function showStatus(msg, type) {
    var el = document.getElementById('payment-status');
    el.style.display = 'block';
    el.className     = type === 'err' ? 'payment-status-err' : 'payment-status-ok';
    el.textContent   = msg;
}
</script>
@endpush
