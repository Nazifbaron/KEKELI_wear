<div class="promo-bar">
    <label class="promo-label" for="promo-input">🎁 Vous avez un code promo ?</label>
    <div class="promo-input-wrap">
        <input class="promo-input" id="promo-input" type="text" placeholder="Ex. : KEKELI10" autocomplete="off" />
        <button class="btn-promo" type="button" onclick="verifyPromo()">Appliquer</button>
        <button class="btn-promo-remove" id="promo-remove" type="button" onclick="removePromo()" style="display:none">Retirer</button>
    </div>
    <span class="promo-feedback promo-ok" id="promo-ok" role="status" aria-live="polite"></span>
    <span class="promo-feedback promo-err" id="promo-err" role="alert"></span>
</div>
