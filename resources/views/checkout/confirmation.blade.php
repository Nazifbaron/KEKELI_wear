{{-- ============================================================
     checkout/confirmation.blade.php
     Étape 3 — Confirmation de commande.
     Affichée après paiement réussi OU après commande WhatsApp.
     Récupère les détails de la commande depuis la BD
     via l'API pour afficher le récap final.
============================================================ --}}
@extends('checkout.layout')
@section('title', 'Confirmation')

@section('content')

<div class="confirmation-page">

    <div class="checkout-card confirmation-card">

        {{-- Icône succès --}}
        <div class="confirmation-icon" id="conf-icon">✦</div>

        <div class="confirmation-title" id="conf-title">
            Commande confirmée !
        </div>

        <div class="confirmation-ref">
            Référence : <strong style="color:var(--kgold)">{{ $ref }}</strong>
        </div>

        {{-- Détails commande -- chargés via API --}}
        <div id="conf-details" class="confirmation-details">
            <div class="checkout-loading">Chargement des détails...</div>
        </div>

        {{-- Message selon moyen de paiement --}}
        <div class="confirmation-message" id="conf-message">
            <div style="font-size:18px;margin-bottom:8px">📱</div>
            <p>
                Notre équipe va vous contacter sur WhatsApp pour confirmer
                votre commande et organiser la livraison.
            </p>
        </div>

        {{-- Actions --}}
        <div class="confirmation-actions">
            <a href="https://wa.me/22901401349" target="_blank"
               class="btn-gold" style="justify-content:center">
                📱 Contacter l'équipe WhatsApp
            </a>
            <a href="{{ route('home') }}" class="btn-outline"
               style="justify-content:center">
                ← Retour à la boutique
            </a>
        </div>

        {{-- Note de suivi --}}
        <div class="confirmation-note">
            💌 Conservez votre référence <strong>{{ $ref }}</strong>
            pour le suivi de votre commande.
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.confirmation-page   { display:flex; align-items:center; justify-content:center; min-height:60vh; padding:24px; }
.confirmation-card   { max-width:520px; width:100%; padding:40px; text-align:center; }
.confirmation-icon   { font-size:56px; color:var(--kgold); margin-bottom:16px; animation: popIn .5s ease; }
@keyframes popIn     { 0%{transform:scale(0);opacity:0} 70%{transform:scale(1.15)} 100%{transform:scale(1);opacity:1} }
.confirmation-title  { font-size:22px; font-weight:700; color:#fff; margin-bottom:8px; }
.confirmation-ref    { font-size:13px; color:var(--kgray); margin-bottom:24px; }
.confirmation-details { text-align:left; background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.07); border-radius:6px; padding:16px 20px; margin-bottom:20px; }
.conf-item           { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid rgba(255,255,255,.05); font-size:12px; }
.conf-item:last-child { border-bottom:none; }
.conf-item span:first-child { color:var(--kgray); }
.conf-item span:last-child  { color:#fff; font-weight:600; }
.confirmation-message { background:rgba(37,211,102,.08); border:1px solid rgba(37,211,102,.2); border-radius:6px; padding:16px 20px; margin-bottom:24px; font-size:13px; color:rgba(255,255,255,.8); line-height:1.6; }
.confirmation-actions { display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
.confirmation-note   { font-size:11px; color:var(--kgray); line-height:1.6; padding:12px; background:rgba(255,255,255,.02); border-radius:4px; }
.checkout-loading    { text-align:center; color:var(--kgray); padding:20px; font-size:13px; }
</style>
@endpush

@push('scripts')
<script>
var ORDER_REF = '{{ $ref }}';

document.addEventListener('DOMContentLoaded', function () {
    loadConfirmationDetails();
});

function loadConfirmationDetails() {
    fetch('/api/order/' + ORDER_REF, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        renderConfirmationDetails(data);
    })
    .catch(() => {
        document.getElementById('conf-details').innerHTML =
            '<p style="color:#888;font-size:12px;text-align:center">Détails disponibles dans votre WhatsApp.</p>';
    });
}

function renderConfirmationDetails(data) {
    var el = document.getElementById('conf-details');
    if (!el) return;

    var paymentLabel = {
        mtn_momo:  'MTN Mobile Money',
        moov_money:'Moov Money',
        card:      'Carte bancaire',
        whatsapp:  'WhatsApp',
    }[data.payment_method] || data.payment_method;

    var statusLabel = {
        pending:    '⏳ En attente',
        paid:       '✅ Payée',
        processing: '✂ En confection',
        shipped:    '🚚 Expédiée',
        delivered:  '✓ Livrée',
    }[data.status] || data.status;

    el.innerHTML = `
        <div class="conf-item">
            <span>Client</span>
            <span>${data.customer_name || '—'}</span>
        </div>
        <div class="conf-item">
            <span>Téléphone</span>
            <span>${data.customer_phone || '—'}</span>
        </div>
        <div class="conf-item">
            <span>Ville</span>
            <span>${data.delivery_city || '—'}</span>
        </div>
        <div class="conf-item">
            <span>Paiement</span>
            <span>${paymentLabel}</span>
        </div>
        <div class="conf-item">
            <span>Statut commande</span>
            <span>${statusLabel}</span>
        </div>
        <div class="conf-item">
            <span>Total</span>
            <span style="color:var(--kgold);font-size:14px;font-weight:700">
                ${parseInt(data.total || 0).toLocaleString('fr-FR')} XOF
            </span>
        </div>
    `;

    // Adapter le message selon le statut de paiement
    if (data.payment_status === 'paid') {
        var msgEl = document.getElementById('conf-message');
        if (msgEl) {
            msgEl.innerHTML = `
                <div style="font-size:18px;margin-bottom:8px">✅</div>
                <p>Paiement confirmé ! Votre commande est en cours de traitement.
                L'équipe KEKELI vous contactera sur WhatsApp pour la livraison.</p>
            `;
            msgEl.style.background = 'rgba(76,175,80,.08)';
            msgEl.style.borderColor = 'rgba(76,175,80,.2)';
        }
    }
}
</script>
@endpush
