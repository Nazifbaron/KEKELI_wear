{{-- ============================================================
     checkout/summary.blade.php — Récapitulatif + infos client
============================================================ --}}
@extends('checkout.layout')
@section('step', 1)
@section('title', 'Votre commande')

@section('checkout-content')

<div class="checkout-grid">

    {{-- ===== COLONNE GAUCHE : Panier ===== --}}
    <div>

        {{-- En-tête section --}}
        <div class="ck-section-title">🛍 Articles sélectionnés</div>
        <div id="cart-lines" class="ck-cart-lines">
            <div class="ck-loading">Chargement du panier…</div>
        </div>

        {{-- Code promo --}}
        <div class="ck-promo-block">
            <div class="ck-promo-label">🏷 Code promo</div>
            <div class="ck-promo-row">
                <input type="text" id="promo-input" placeholder="Ex: KEKELI10"
                       style="text-transform:uppercase" />
                <button onclick="applyPromo()">Appliquer</button>
            </div>
            <div id="promo-feedback" class="ck-feedback" style="display:none"></div>
        </div>

        {{-- Bon d'achat --}}
        <div class="ck-voucher-block">
            <div class="ck-promo-label">🎁 Bon d'achat KEKELI</div>
            <div class="ck-promo-row">
                <input type="text" id="voucher-input" placeholder="Ex: KW-GIFT-A3X9"
                       style="text-transform:uppercase;letter-spacing:.06em" />
                <button onclick="applyVoucher()">Vérifier</button>
            </div>
            <div id="voucher-feedback" class="ck-feedback" style="display:none"></div>
        </div>

    </div>

    {{-- ===== COLONNE DROITE : Totaux + Formulaire ===== --}}
    <div>

        {{-- Totaux --}}
        <div class="ck-totals" id="ck-totals">
            <div class="ck-totals-title">Récapitulatif</div>
            <div class="ck-total-line" id="line-subtotal" style="display:none">
                <span>Sous-total</span><span id="val-subtotal">—</span>
            </div>
            <div class="ck-total-line ck-discount" id="line-promo" style="display:none">
                <span>Code promo</span><span id="val-promo">—</span>
            </div>
            <div class="ck-total-line ck-discount" id="line-voucher" style="display:none">
                <span>Bon d'achat</span><span id="val-voucher">—</span>
            </div>
            <div class="ck-total-line ck-total-final">
                <span>Total à payer</span>
                <span id="val-total" style="color:var(--ck-red);font-size:20px;font-weight:900">—</span>
            </div>
        </div>

        {{-- Formulaire client --}}
        <div class="ck-section-title" style="margin-top:24px">📋 Vos informations</div>

        <div class="ck-form">
            <div class="ck-field-row">
                <div class="ck-field">
                    <label>Nom complet *</label>
                    <input type="text" id="f-name" placeholder="Votre nom complet" required />
                </div>
                <div class="ck-field">
                    <label>WhatsApp *</label>
                    <input type="tel" id="f-phone" placeholder="+229 01 ..." required />
                </div>
            </div>
            <div class="ck-field">
                <label>Email <span style="font-weight:400;color:var(--ck-soft)">(optionnel)</span></label>
                <input type="email" id="f-email" placeholder="votre@email.com" />
            </div>
            <div class="ck-field-row">
                <div class="ck-field">
                    <label>Ville de livraison *</label>
                    <input type="text" id="f-city" placeholder="Ex: Cotonou" required />
                </div>
                <div class="ck-field">
                    <label>Quartier / Adresse</label>
                    <input type="text" id="f-address" placeholder="Ex: Cadjehoun, face à..." />
                </div>
            </div>

            {{-- Mode de paiement --}}
            <div class="ck-field">
                <label>Mode de paiement *</label>
                <div class="ck-payment-methods" id="payment-methods">
                    <label class="ck-pm-card" data-val="mtn_momo">
                        <input type="radio" name="payment" value="mtn_momo" />
                        <img src="{{ asset('images/mtn.png') }}" alt="MTN MoMo"
                             onerror="this.style.display='none'"
                             style="height:28px;object-fit:contain" />
                        <div class="ck-pm-label">MTN MoMo</div>
                    </label>
                    <label class="ck-pm-card" data-val="moov_money">
                        <input type="radio" name="payment" value="moov_money" />
                        <img src="{{ asset('images/moov.png') }}" alt="Moov Money"
                             onerror="this.style.display='none'"
                             style="height:28px;object-fit:contain" />
                        <div class="ck-pm-label">Moov Money</div>
                    </label>
                    <label class="ck-pm-card" data-val="card">
                        <input type="radio" name="payment" value="card" />
                        <span style="font-size:22px">💳</span>
                        <div class="ck-pm-label">Carte bancaire</div>
                    </label>
                    <label class="ck-pm-card" data-val="whatsapp">
                        <input type="radio" name="payment" value="whatsapp" />
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#25D366">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <div class="ck-pm-label">WhatsApp</div>
                    </label>
                </div>
            </div>

            {{-- Erreur formulaire --}}
            <div id="checkout-error" class="ck-error-msg" style="display:none"></div>

            {{-- Bouton confirmer --}}
            <button class="ck-btn-confirm" id="btn-confirm" onclick="confirmOrder()">
                <span>Confirmer ma commande</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>

            <div style="font-size:11px;color:var(--ck-soft);text-align:center;margin-top:12px">
                🔒 Paiement 100% sécurisé via KKiaPay
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var CSRF        = document.querySelector('meta[name="csrf-token"]')?.content;
var cartData    = null;
var voucherData = null;

/* ============================================================
   CHARGEMENT DU PANIER
============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    /* Sélecteur modes de paiement */
    document.querySelectorAll('.ck-pm-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.ck-pm-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            card.querySelector('input[type=radio]').checked = true;
        });
    });
    loadCart();
});

function loadCart() {
    fetch('/api/cart', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(function(data) {
        cartData = data;
        renderCartLines(data);
        updateTotals(data);
    });
}

function renderCartLines(data) {
    var el = document.getElementById('cart-lines');
    if (!data.items || data.items.length === 0) {
        el.innerHTML = '<div class="ck-empty">Votre panier est vide.</div>';
        return;
    }
    el.innerHTML = data.items.map(function(item) {
        var priceHtml = item.price_reduced
            ? '<span class="ck-price-original">' + item.price + '</span>'
              + '<span class="ck-price-sale">' + item.price_reduced + '</span>'
            : '<span class="ck-price-normal">' + item.price + '</span>';

        if (item.on_sale) {
            priceHtml = '<span class="ck-badge-sale">' + item.sale_percent + '</span>'
                      + '<span class="ck-price-original">' + item.price + '</span>'
                      + '<span class="ck-price-sale">' + item.price_reduced + '</span>';
        }

        return '<div class="ck-cart-line">'
            + '<div class="ck-item-img">'
            + (item.image
                ? '<img src="/storage/' + item.image + '" alt="' + item.name + '">'
                : '<div class="ck-item-img-placeholder">👗</div>')
            + '</div>'
            + '<div class="ck-item-info">'
            + '  <div class="ck-item-name">' + item.name + '</div>'
            + '  <div class="ck-item-cat">' + item.category + '</div>'
            + '</div>'
            + '<div class="ck-item-right">'
            + '  ' + priceHtml
            + '  <div class="ck-qty-wrap">'
            + '    <button onclick="changeQty(' + item.id + ',-1,this)">−</button>'
            + '    <span id="qty-' + item.id + '">' + item.quantity + '</span>'
            + '    <button onclick="changeQty(' + item.id + ',1,this)">+</button>'
            + '  </div>'
            + '</div>'
            + '</div>';
    }).join('');
}

function updateTotals(data) {
    var vBalance   = voucherData ? Math.min(voucherData.balance, data.amount) : 0;
    var finalTotal = Math.max(0, data.amount - vBalance);

    /* Sous-total */
    if (data.discount > 0 || vBalance > 0) {
        show('line-subtotal');
        setText('val-subtotal', fmt(data.subtotal) + ' XOF');
    } else { hide('line-subtotal'); }

    /* Code promo */
    if (data.discount > 0) {
        show('line-promo');
        setText('val-promo', '−' + fmt(data.discount) + ' XOF');
    } else { hide('line-promo'); }

    /* Bon d'achat */
    if (vBalance > 0) {
        show('line-voucher');
        setText('val-voucher', '−' + fmt(vBalance) + ' XOF');
    } else { hide('line-voucher'); }

    /* Total final */
    setText('val-total', finalTotal <= 0
        ? '✓ Gratuit'
        : fmt(finalTotal) + ' XOF');
}

function changeQty(productId, delta, btn) {
    var el  = document.getElementById('qty-' + productId);
    var qty = parseInt(el.textContent) + delta;

    if (qty < 1) {
        fetch('/api/cart/' + productId, {
            method: 'DELETE',
            headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        }).then(() => loadCart());
        return;
    }

    fetch('/api/cart/' + productId + '/qty', {
        method: 'PATCH',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ quantity: qty }),
    }).then(() => loadCart());
}

/* ============================================================
   CODE PROMO
============================================================ */
function applyPromo() {
    var code = document.getElementById('promo-input').value.trim().toUpperCase();
    if (!code) return;
    fetch('/api/promo/verify', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ code }),
    })
    .then(r => r.json())
    .then(function(d) {
        if (d.valid) {
            showFeedback('promo', 'ok', '✓ ' + d.message);
            loadCart();
        } else {
            showFeedback('promo', 'err', '✗ ' + d.message);
        }
    });
}

/* ============================================================
   BON D'ACHAT
============================================================ */
function applyVoucher() {
    var code = document.getElementById('voucher-input').value.trim().toUpperCase();
    if (!code) return;
    fetch('/api/voucher/verify', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ code }),
    })
    .then(r => r.json())
    .then(function(d) {
        if (d.valid) {
            voucherData = d;
            showFeedback('voucher', 'ok', '✓ ' + d.message);
            if (cartData) updateTotals(cartData);
        } else {
            voucherData = null;
            showFeedback('voucher', 'err', '✗ ' + d.message);
        }
    });
}

/* ============================================================
   CONFIRMER LA COMMANDE
============================================================ */
function confirmOrder() {
    var name    = document.getElementById('f-name').value.trim();
    var phone   = document.getElementById('f-phone').value.trim();
    var email   = document.getElementById('f-email').value.trim();
    var city    = document.getElementById('f-city').value.trim();
    var address = document.getElementById('f-address').value.trim();
    var payment = document.querySelector('input[name=payment]:checked')?.value;

    if (!name)    { showErr('Votre nom est requis.'); return; }
    if (!phone)   { showErr('Votre numéro WhatsApp est requis.'); return; }
    if (!city)    { showErr('La ville de livraison est requise.'); return; }
    if (!payment) { showErr('Choisissez un mode de paiement.'); return; }

    document.getElementById('checkout-error').style.display = 'none';
    var btn = document.getElementById('btn-confirm');
    btn.disabled = true;
    btn.innerHTML = '<span>Traitement en cours…</span>';

    fetch('/checkout', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({
            customer_name:    name,
            customer_phone:   phone,
            customer_email:   email || null,
            delivery_city:    city,
            delivery_address: address || null,
            payment_method:   payment,
        }),
    })
    .then(r => r.json())
    .then(function(d) {
        if (d.success) {
            if (payment === 'whatsapp') {
                var whatsappUrl = 'https://wa.me/{{ config("kekeli.whatsapp") }}?text='
                    + encodeURIComponent(d.whatsapp_msg || 'Bonjour, je souhaite confirmer ma commande.');
                var popup = window.open(whatsappUrl, '_blank');
                if (!popup) {
                    window.location.href = whatsappUrl;
                } else {
                    window.location.href = d.redirect_url || '/checkout/confirmation/' + d.order_ref;
                }
            } else {
                window.location.href = d.redirect_url;
            }
        } else {
            showErr(d.error || 'Erreur lors de la commande.');
            btn.disabled = false;
            btn.innerHTML = '<span>Confirmer ma commande</span>';
        }
    });
}

/* ============================================================
   UTILITAIRES
============================================================ */
function fmt(n) { return parseInt(n).toLocaleString('fr-FR'); }
function show(id) { var e = document.getElementById(id); if(e) e.style.display='flex'; }
function hide(id) { var e = document.getElementById(id); if(e) e.style.display='none'; }
function setText(id, txt) { var e = document.getElementById(id); if(e) e.textContent = txt; }
function showErr(msg) {
    var e = document.getElementById('checkout-error');
    e.textContent = '✗ ' + msg;
    e.style.display = 'block';
    e.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
function showFeedback(type, state, msg) {
    var el = document.getElementById(type + '-feedback');
    el.textContent = msg;
    el.className   = 'ck-feedback ck-feedback-' + state;
    el.style.display = 'block';
}
</script>
@endpush
