{{-- ============================================================
     checkout/summary.blade.php
     Étape 1 — Récapitulatif de commande.
     Affiche les articles du panier, les montants,
     collecte les infos client et le moyen de paiement.
     Soumission → POST /checkout → crée la commande en BD
     puis redirige vers la passerelle de paiement.
============================================================ --}}
@extends('checkout.layout')
@section('title', 'Récapitulatif')

@section('content')

<div class="checkout-grid">

    {{-- ===== COLONNE GAUCHE : Articles ===== --}}
    <div class="checkout-left">

        <div class="checkout-card">
            <div class="checkout-card-title">🛍 Votre commande</div>

            {{-- Liste des articles depuis l'API --}}
            <div id="checkout-items">
                <div class="checkout-loading">Chargement de votre panier...</div>
            </div>

            {{-- Récap montants --}}
            <div class="checkout-totals" id="checkout-totals" style="display:none">
                <div class="total-row">
                    <span>Sous-total</span>
                    <span id="ct-subtotal">—</span>
                </div>
                <div class="total-row" id="ct-discount-row" style="display:none">
                    <span style="color:#4caf50">Remise code promo</span>
                    <span id="ct-discount" style="color:#4caf50">—</span>
                </div>
                <div class="total-row total-row-final">
                    <span>Total</span>
                    <strong id="ct-total" style="color:var(--kgold)">—</strong>
                </div>
            </div>
        </div>

        {{-- Info livraison --}}
        <div class="checkout-card" style="margin-top:16px">
            <div class="checkout-card-title">🚚 Livraison</div>
            <p style="font-size:13px;color:rgba(255,255,255,.7);line-height:1.6">
                La livraison est organisée directement avec l'équipe KEKELI via WhatsApp
                après validation du paiement. Délai selon votre localisation :
            </p>
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

        <div class="checkout-card">
            <div class="checkout-card-title">👤 Vos informations</div>

            <div class="co-form-row">
                <div class="co-form-group">
                    <label class="co-label">Nom complet *</label>
                    <input class="co-input" id="co-name"
                           type="text" placeholder="Votre nom"
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
                           type="text" placeholder="Ex: Cotonou"
                           required />
                </div>
                <div class="co-form-group">
                    <label class="co-label">Pays</label>
                    <input class="co-input" id="co-country"
                           type="text" value="Bénin" />
                </div>
            </div>

        </div>

        {{-- Choix du moyen de paiement --}}
        <div class="checkout-card" style="margin-top:16px">
            <div class="checkout-card-title">💳 Moyen de paiement</div>

            <div class="payment-methods">

                {{-- MTN Mobile Money --}}
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

                {{-- Moov Money --}}
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

                {{-- Carte bancaire --}}
                <label class="payment-opt">
                    <input type="radio" name="payment" value="card" />
                    <div class="payment-opt-inner">
                        <div class="payment-logo payment-card">💳</div>
                        <div>
                            <div class="payment-name">Carte bancaire</div>
                            <div class="payment-desc">Visa / Mastercard — paiement sécurisé</div>
                        </div>
                    </div>
                </label>

                {{-- WhatsApp (fallback sans paiement en ligne) --}}
                <label class="payment-opt">
                    <input type="radio" name="payment" value="whatsapp" />
                    <div class="payment-opt-inner">
                        <div class="payment-logo payment-wa">📱</div>
                        <div>
                            <div class="payment-name">Finaliser sur WhatsApp</div>
                            <div class="payment-desc">Paiement et livraison convenus avec l'équipe</div>
                        </div>
                    </div>
                </label>

            </div>

            {{-- Sécurité --}}
            <div class="payment-security">
                🔒 Paiements sécurisés via <strong>FedaPay</strong> — aucune donnée bancaire stockée.
            </div>
        </div>

        {{-- Bouton commander --}}
        <button class="btn-checkout-main" id="btn-place-order"
                onclick="placeOrder()" disabled>
            <span id="btn-order-text">Chargement...</span>
        </button>

        <p style="font-size:11px;color:var(--kgray);text-align:center;margin-top:10px">
            En passant commande, vous acceptez nos
            <a href="#" style="color:var(--kgold)">CGV</a> et notre
            <a href="#" style="color:var(--kgold)">politique de retour</a>.
        </p>

    </div>

</div>

@endsection

@push('scripts')
<script>
/* ============================================================
   ÉTAPE 1 — Récapitulatif
   1. Charger le panier depuis /api/cart
   2. Afficher les articles + montants
   3. Soumettre la commande → POST /checkout
============================================================ */

// Charger le panier au chargement de la page
document.addEventListener('DOMContentLoaded', loadCheckoutCart);

function loadCheckoutCart() {
    fetch('/api/cart', { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            renderCheckoutItems(data);
            // Activer le bouton commander si panier non vide
            var btn = document.getElementById('btn-place-order');
            if (data.items && data.items.length > 0) {
                btn.disabled = false;
                document.getElementById('btn-order-text').textContent = '✦ Passer la commande';
            } else {
                document.getElementById('btn-order-text').textContent = 'Panier vide';
            }
        })
        .catch(() => {
            document.getElementById('checkout-items').innerHTML =
                '<p style="color:#888;font-size:13px">Impossible de charger le panier.</p>';
        });
}

function renderCheckoutItems(data) {
    var items   = data.items || [];
    var itemsEl = document.getElementById('checkout-items');
    var totals  = document.getElementById('checkout-totals');

    if (!items.length) {
        itemsEl.innerHTML = `
            <div style="text-align:center;padding:32px 0;color:#888">
                <div style="font-size:32px;margin-bottom:10px">🛒</div>
                <p>Votre panier est vide.</p>
                <a href="/#shop" style="color:var(--kgold);font-size:13px">← Retour à la boutique</a>
            </div>`;
        return;
    }

    itemsEl.innerHTML = items.map(item => `
        <div class="co-item">
            <div class="co-item-img"
                 style="${item.image
                    ? `background-image:url('/storage/${item.image}');background-size:cover`
                    : 'background:#1a1a1a'}">
            </div>
            <div class="co-item-info">
                <div class="co-item-name">${item.name}</div>
                <div class="co-item-cat">${item.category}</div>
                ${item.is_custom
                    ? '<span class="co-badge">Sur-Mesure</span>'
                    : ''}
            </div>
            <div class="co-item-price">
                <div>${item.price}</div>
                <div style="font-size:11px;color:#888">× ${item.quantity}</div>
            </div>
        </div>
    `).join('');

    // Montants
    var subtotal = data.amount || 0;
    document.getElementById('ct-subtotal').textContent =
        subtotal.toLocaleString('fr-FR') + ' XOF';
    document.getElementById('ct-total').textContent =
        subtotal.toLocaleString('fr-FR') + ' XOF';
    totals.style.display = 'block';
}

function placeOrder() {
    var name    = document.getElementById('co-name')?.value.trim();
    var phone   = document.getElementById('co-phone')?.value.trim();
    var email   = document.getElementById('co-email')?.value.trim();
    var address = document.getElementById('co-address')?.value.trim();
    var city    = document.getElementById('co-city')?.value.trim();
    var country = document.getElementById('co-country')?.value.trim();
    var payment = document.querySelector('input[name="payment"]:checked')?.value;

    // Validation basique
    if (!name || !phone || !city) {
        showToast('⚠ Nom, téléphone et ville requis.', 'err');
        return;
    }

    var btn = document.getElementById('btn-place-order');
    btn.disabled = true;
    document.getElementById('btn-order-text').textContent = 'Traitement en cours...';

    apiPost('/checkout', {
        customer_name:    name,
        customer_phone:   phone,
        customer_email:   email,
        delivery_address: address,
        delivery_city:    city,
        delivery_country: country || 'Bénin',
        payment_method:   payment,
    })
    .then(data => {
        if (data.success) {
            if (payment === 'whatsapp') {
                // Ouvrir WhatsApp avec le récap
                window.open(
                    'https://wa.me/2290140134949?text=' +
                    encodeURIComponent(data.whatsapp_msg || 'Bonjour, je viens de passer commande.'),
                    '_blank'
                );
                // Rediriger vers confirmation
                window.location.href = '/checkout/confirmation/' + data.order_ref;
            } else {
                // Rediriger vers la passerelle de paiement
                window.location.href = data.redirect_url;
            }
        } else {
            showToast('✗ ' + (data.error || 'Erreur lors de la commande.'), 'err');
            btn.disabled = false;
            document.getElementById('btn-order-text').textContent = '✦ Passer la commande';
        }
    })
    .catch(() => {
        showToast('Erreur réseau. Veuillez réessayer.', 'err');
        btn.disabled = false;
        document.getElementById('btn-order-text').textContent = '✦ Passer la commande';
    });
}
</script>
@endpush
