{{-- ============================================================
     checkout/summary.blade.php
     Étape 1 — Récapitulatif de commande.
     - Articles depuis /api/cart avec prix réduits si promo active
     - Infos client + choix moyen de paiement
     - POST /checkout → crée la commande en BD
============================================================ --}}
@extends('checkout.layout')
@section('title', 'Récapitulatif')

@section('content')

<div class="checkout-grid">

    {{-- ===== COLONNE GAUCHE : Articles + Livraison ===== --}}
    <div class="checkout-left">

        <div class="checkout-card">
            <div class="checkout-card-title">🛍 Votre commande</div>

            {{-- Articles chargés via /api/cart --}}
            <div id="checkout-items">
                <div class="checkout-loading">Chargement de votre panier</div>
            </div>

            {{-- Récap montants --}}
            <div class="checkout-totals" id="checkout-totals" style="display:none">

                <div class="total-row">
                    <span>Sous-total</span>
                    <span id="ct-subtotal">—</span>
                </div>

                {{-- Ligne remise — visible seulement si promo active --}}
                <div class="total-row" id="ct-discount-row" style="display:none">
                    <span style="color:#16a34a;display:flex;align-items:center;gap:6px">
                        <span id="ct-promo-badge"
                              style="background:#F0FDF4;border:1px solid #BBF7D0;
                                     color:#16a34a;font-size:9px;font-weight:700;
                                     padding:2px 7px;border-radius:10px;
                                     letter-spacing:.06em;text-transform:uppercase">
                        </span>
                        Remise appliquée
                    </span>
                    <span id="ct-discount" style="color:#16a34a;font-weight:700">—</span>
                </div>

                {{-- Total final --}}
                <div class="total-row total-row-final">
                    <span>Total à payer</span>
                    <strong id="ct-total" style="color:#e42829;font-size:18px">—</strong>
                </div>

            </div>
        </div>

        {{-- Livraison --}}
        <div class="checkout-card" style="margin-top:0">
            <div class="checkout-card-title">🚚 Livraison</div>
            <div style="padding:14px 22px 4px;font-size:13px;color:#4A4A4A;line-height:1.6">
                La livraison est organisée avec l'équipe KEKELI via WhatsApp après confirmation du paiement.
            </div>
            <div class="delivery-options">
                <div class="delivery-opt">
                    <span>📍 Cotonou / Sèmé-Kpodji</span>
                    <span>24 – 48h</span>
                </div>
                <div class="delivery-opt">
                    <span>🇧🇯 Autres villes du Bénin</span>
                    <span>2 – 4 jours</span>
                </div>
                <div class="delivery-opt">
                    <span>🌍 International</span>
                    <span>Sur devis</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== COLONNE DROITE : Infos client + Paiement ===== --}}
    <div class="checkout-right">

        {{-- Informations client --}}
        <div class="checkout-card">
            <div class="checkout-card-title">👤 Vos informations</div>
            <div style="padding:20px 22px">

                <div class="co-form-row">
                    <div class="co-form-group">
                        <label class="co-label">Nom complet *</label>
                        <input class="co-input" id="co-name"
                               type="text" placeholder="Votre nom complet"
                               autocomplete="name" required />
                    </div>
                    <div class="co-form-group">
                        <label class="co-label">Téléphone / WhatsApp *</label>
                        <input class="co-input" id="co-phone"
                               type="tel" placeholder="+229 01..."
                               autocomplete="tel" required />
                    </div>
                </div>

                <div class="co-form-group" style="margin-bottom:14px">
                    <label class="co-label">Email (optionnel)</label>
                    <input class="co-input" id="co-email"
                           type="email" placeholder="votre@email.com"
                           autocomplete="email" />
                </div>

                <div class="co-form-group" style="margin-bottom:14px">
                    <label class="co-label">Adresse de livraison</label>
                    <input class="co-input" id="co-address"
                           type="text" placeholder="Quartier, rue, repère..."
                           autocomplete="street-address" />
                </div>

                <div class="co-form-row">
                    <div class="co-form-group">
                        <label class="co-label">Ville *</label>
                        <input class="co-input" id="co-city"
                               type="text" placeholder="Ex: Cotonou" required />
                    </div>
                    <div class="co-form-group">
                        <label class="co-label">Pays</label>
                        <input class="co-input" id="co-country"
                               type="text" value="Bénin" />
                    </div>
                </div>

            </div>
        </div>

        {{-- Moyen de paiement --}}
        <div class="checkout-card">
            <div class="checkout-card-title">💳 Moyen de paiement</div>
            <div class="payment-methods">

                <label class="payment-opt">
                    <input type="radio" name="payment" value="mtn_momo" checked />
                    <div class="payment-opt-inner">
                        <div class="payment-logo payment-mtn">MTN</div>
                        <div>
                            <div class="payment-name">MTN Mobile Money</div>
                            <div class="payment-desc">Paiement instantané via MTN MoMo</div>
                        </div>
                    </div>
                </label>

                <label class="payment-opt">
                    <input type="radio" name="payment" value="moov_money" />
                    <div class="payment-opt-inner">
                        <div class="payment-logo payment-moov">MOOV</div>
                        <div>
                            <div class="payment-name">Moov Money</div>
                            <div class="payment-desc">Paiement via Moov Money Bénin</div>
                        </div>
                    </div>
                </label>

                <label class="payment-opt">
                    <input type="radio" name="payment" value="card" />
                    <div class="payment-opt-inner">
                        <div class="payment-logo payment-card">💳</div>
                        <div>
                            <div class="payment-name">Carte bancaire</div>
                            <div class="payment-desc">Visa / Mastercard — sécurisé</div>
                        </div>
                    </div>
                </label>

                <label class="payment-opt">
                    <input type="radio" name="payment" value="whatsapp" />
                    <div class="payment-opt-inner">
                        <div class="payment-logo payment-wa">📱</div>
                        <div>
                            <div class="payment-name">Finaliser sur WhatsApp</div>
                            <div class="payment-desc">Paiement convenu directement avec l'équipe</div>
                        </div>
                    </div>
                </label>

            </div>
            <div class="payment-security">
                🔒 Paiements sécurisés via <strong>FedaPay</strong> — aucune donnée bancaire stockée.
            </div>
        </div>

        {{-- Bouton commander --}}
        <button class="btn-checkout-main" id="btn-place-order"
                onclick="placeOrder()" disabled>
            <span id="btn-order-text">Chargement du panier...</span>
        </button>

        <p style="font-size:11px;color:#8A8A8A;text-align:center;margin-top:10px">
            En passant commande, vous acceptez nos
            <a href="#" style="color:#e42829">CGV</a> et notre
            <a href="#" style="color:#e42829">politique de retour</a>.
        </p>

    </div>

</div>

@endsection

@push('scripts')
<script>
/* ============================================================
   CHECKOUT SUMMARY — JavaScript
   1. Charger le panier depuis /api/cart (avec prix réduits)
   2. Afficher articles + montants corrects
   3. Soumettre la commande → POST /checkout
============================================================ */

var cartData = null; // Données du panier stockées globalement

document.addEventListener('DOMContentLoaded', loadCheckoutCart);

/* ----------------------------------------------------------
   Charger le panier depuis l'API
   /api/cart retourne déjà les prix réduits si promo en session
---------------------------------------------------------- */
function loadCheckoutCart() {
    fetch('/api/cart', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        cartData = data;
        renderCheckoutItems(data);

        var btn = document.getElementById('btn-place-order');
        if (data.items && data.items.length > 0) {
            btn.disabled = false;
            document.getElementById('btn-order-text').textContent = '✦ Passer la commande';
        } else {
            document.getElementById('btn-order-text').textContent = 'Panier vide';
        }
    })
    .catch(function() {
        document.getElementById('checkout-items').innerHTML =
            '<p style="color:#8A8A8A;font-size:13px;padding:20px">Impossible de charger le panier.</p>';
    });
}

/* ----------------------------------------------------------
   Afficher les articles du panier
   Gère les prix réduits calculés côté serveur (promo session)
---------------------------------------------------------- */
function renderCheckoutItems(data) {
    var items   = data.items || [];
    var itemsEl = document.getElementById('checkout-items');
    var totals  = document.getElementById('checkout-totals');

    if (!items.length) {
        itemsEl.innerHTML =
            '<div style="text-align:center;padding:40px 0;color:#8A8A8A">'
            + '<div style="font-size:36px;margin-bottom:12px">🛒</div>'
            + '<p style="margin-bottom:12px">Votre panier est vide.</p>'
            + '<a href="/#shop" style="color:#e42829;font-size:13px;font-weight:600">← Retour à la boutique</a>'
            + '</div>';
        return;
    }

    /* Construire les lignes articles */
    itemsEl.innerHTML = items.map(function(item) {
        var imgStyle = item.image
            ? 'background-image:url(/storage/' + item.image + ');background-size:cover;background-position:center'
            : '';

        /*
        | Prix à afficher :
        | - Si price_reduced existe (promo active) → barrer prix original + afficher prix réduit
        | - Sinon → afficher prix normal
        */
        var priceHtml;
        if (item.price_reduced) {
            priceHtml = '<span style="text-decoration:line-through;color:#8A8A8A;font-size:11px">'
                + item.price + '</span>'
                + ' <span style="color:#16a34a;font-weight:700;font-size:13px">'
                + item.price_reduced + '</span>';
        } else {
            priceHtml = '<span style="font-weight:700">' + item.price + '</span>';
        }

        return '<div class="co-item">'
            + '<div class="co-item-img" style="' + imgStyle + '"></div>'
            + '<div class="co-item-info">'
            + '<div class="co-item-name">' + item.name + '</div>'
            + '<div class="co-item-cat">' + item.category + '</div>'
            + (item.is_custom ? '<span class="co-badge">Sur-Mesure</span>' : '')
            + '</div>'
            + '<div class="co-item-price">'
            + priceHtml
            + '<div style="font-size:11px;color:#8A8A8A;margin-top:3px">× ' + item.quantity + '</div>'
            + '</div>'
            + '</div>';
    }).join('');

    /* ----------------------------------------------------------
       Montants — toujours lus depuis l'API (jamais recalculés JS)
       data.subtotal = prix avant remise
       data.discount = montant de la remise
       data.amount   = total final après remise  ← C'est CE montant qui est facturé
    ---------------------------------------------------------- */
    var subtotal = data.subtotal || 0;
    var discount = data.discount || 0;
    var total    = data.amount   || subtotal; // montant final correct

    /* Sous-total */
    document.getElementById('ct-subtotal').textContent =
        parseInt(subtotal).toLocaleString('fr-FR') + ' XOF';

    /* Remise — afficher seulement si > 0 */
    if (discount > 0) {
        var discountRow   = document.getElementById('ct-discount-row');
        var discountEl    = document.getElementById('ct-discount');
        var promoBadgeEl  = document.getElementById('ct-promo-badge');

        if (discountRow)  discountRow.style.display = 'flex';
        if (discountEl)   discountEl.textContent = '−' + parseInt(discount).toLocaleString('fr-FR') + ' XOF';
        if (promoBadgeEl && data.promo_code) {
            promoBadgeEl.textContent = data.promo_code;
        }
    }

    /* Total final */
    document.getElementById('ct-total').textContent =
        parseInt(total).toLocaleString('fr-FR') + ' XOF';

    totals.style.display = 'block';
}

/* ----------------------------------------------------------
   Passer la commande — POST /checkout
   Le backend recalcule tout depuis la session + BD
   → on ne transmet PAS les montants depuis le front
---------------------------------------------------------- */
function placeOrder() {
    var name    = document.getElementById('co-name')?.value.trim();
    var phone   = document.getElementById('co-phone')?.value.trim();
    var email   = document.getElementById('co-email')?.value.trim();
    var address = document.getElementById('co-address')?.value.trim();
    var city    = document.getElementById('co-city')?.value.trim();
    var country = document.getElementById('co-country')?.value.trim();
    var payment = document.querySelector('input[name="payment"]:checked')?.value;

    if (!name || !phone || !city) {
        showToast('⚠ Nom, téléphone et ville requis.', 'err');
        return;
    }

    var btn = document.getElementById('btn-place-order');
    btn.disabled = true;
    document.getElementById('btn-order-text').textContent = 'Traitement en cours...';

    /* Envoyer uniquement les infos client et le moyen de paiement
       Le backend récupère le code promo depuis la session Laravel */
    fetch('/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            customer_name:    name,
            customer_phone:   phone,
            customer_email:   email   || null,
            delivery_address: address || null,
            delivery_city:    city,
            delivery_country: country || 'Bénin',
            payment_method:   payment,
        }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            if (payment === 'whatsapp') {
                /* Ouvrir WhatsApp avec le récap de commande */
                window.open(
                    'https://wa.me/22901401349?text='
                    + encodeURIComponent(data.whatsapp_msg || 'Bonjour, je viens de passer commande.'),
                    '_blank'
                );
                window.location.href = '/checkout/confirmation/' + data.order_ref;
            } else {
                /* Rediriger vers la page de paiement FedaPay */
                window.location.href = data.redirect_url;
            }
        } else {
            showToast('✗ ' + (data.error || 'Erreur lors de la commande.'), 'err');
            btn.disabled = false;
            document.getElementById('btn-order-text').textContent = '✦ Passer la commande';
        }
    })
    .catch(function() {
        showToast('Erreur réseau. Veuillez réessayer.', 'err');
        btn.disabled = false;
        document.getElementById('btn-order-text').textContent = '✦ Passer la commande';
    });
}

/* showToast est disponible via checkout/layout.blade.php */
</script>
@endpush
