{{-- ============================================================
     components/cart.blade.php
     Panier flottant — FAB noir qui vire rouge au hover
     + modal blanc élégant
============================================================ --}}

{{-- Bouton flottant --}}
<button class="cart-fab" onclick="toggleCart()" aria-label="Ouvrir le panier">
    🛒
    <span class="cart-badge" id="cart-badge">0</span>
</button>

{{-- Modal panier --}}
<div class="cart-modal" id="cart-modal">

    <div class="cart-modal-title">
        Mon panier
        <button class="cart-close" onclick="toggleCart()">✕</button>
    </div>

    {{-- Items — remplis par kekeli.js --}}
    <div class="cart-items-wrap" id="cart-items">
        <div class="cart-empty">Votre panier est vide.</div>
    </div>

    {{-- Footer panier --}}
    <div class="cart-footer" id="cart-footer" style="display:none">
        <div class="cart-total">
            <span>Total estimé</span>
            <span id="cart-total">—</span>
        </div>
        <div id="cart-actions">
            <button class="btn-checkout"
                    onclick="window.location.href='/checkout/summary'">
                💳 Passer la commande
            </button>
            <button class="btn-checkout btn-checkout-wa"
                    onclick="checkoutWhatsApp()">
                📱 Finaliser sur WhatsApp
            </button>
        </div>
    </div>

</div>
