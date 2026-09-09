{{-- ============================================================
     components/cart.blade.php
     Panier flottant — FAB + modal
     Les items sont chargés via /api/cart (kekeli.js → loadCart())
     Le checkout redirige vers /checkout
============================================================ --}}

{{-- Bouton flottant panier --}}
<button class="cart-fab" onclick="toggleCart()" aria-label="Ouvrir le panier">
    🛒
    <span class="cart-badge" id="cart-badge">0</span>
</button>

{{-- Modal panier --}}
<div class="cart-modal" id="cart-modal">

    <div class="cart-modal-title">
        Mon panier
        <button class="cart-close" onclick="toggleCart()" aria-label="Fermer">✕</button>
    </div>

    {{-- Liste items — remplie dynamiquement par kekeli.js --}}
    <div id="cart-items">
        <div class="cart-empty">Votre panier est vide.</div>
    </div>

    {{-- Total --}}
    <div class="cart-total" id="cart-total" style="display:none"></div>

    {{-- Boutons d'action --}}
    <div id="cart-actions" style="display:none">
        {{-- Paiement en ligne --}}
        <button class="btn-checkout" onclick="goToCheckout()">
            💳 Passer la commande
        </button>
        {{-- Alternative WhatsApp --}}
        <button class="btn-checkout btn-checkout-wa" onclick="checkoutWhatsApp()">
            📱 Finaliser sur WhatsApp
        </button>
    </div>
</div>
