{{-- ============================================================
     vouchers/index.blade.php — Page achat bon d'achat
============================================================ --}}
@extends('layouts.app')
@section('content')

<div style="padding:80px 24px;margin-top:96px;max-width:680px;margin-left:auto;margin-right:auto">

    <div class="sec-label">Cadeaux</div>
    <h1 style="font-size:32px;font-weight:700;color:var(--black);margin-bottom:10px">
        Offrir un bon d'achat KEKELI
    </h1>
    <p style="font-size:15px;color:var(--mid);margin-bottom:40px;line-height:1.65">
        Offrez la liberté du choix. Votre proche choisira elle-même
        sa création préférée parmi toutes nos pièces.
        Le solde non utilisé est conservé pour un futur achat.
    </p>

    {{-- Avantages --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:40px">
        <div style="background:var(--white);border:1px solid var(--border);border-radius:6px;padding:18px;text-align:center">
            <div style="font-size:28px;margin-bottom:8px">🎁</div>
            <div style="font-size:12px;font-weight:700;color:var(--black);margin-bottom:4px">Code instantané</div>
            <div style="font-size:11px;color:var(--soft)">Reçu immédiatement après paiement</div>
        </div>
        <div style="background:var(--white);border:1px solid var(--border);border-radius:6px;padding:18px;text-align:center">
            <div style="font-size:28px;margin-bottom:8px">♻️</div>
            <div style="font-size:12px;font-weight:700;color:var(--black);margin-bottom:4px">Solde conservé</div>
            <div style="font-size:11px;color:var(--soft)">Le reliquat reste utilisable</div>
        </div>
        <div style="background:var(--white);border:1px solid var(--border);border-radius:6px;padding:18px;text-align:center">
            <div style="font-size:28px;margin-bottom:8px">🛍</div>
            <div style="font-size:12px;font-weight:700;color:var(--black);margin-bottom:4px">Tous les produits</div>
            <div style="font-size:11px;color:var(--soft)">Valable sur toute la boutique</div>
        </div>
    </div>

    {{-- Formulaire --}}
    <div style="background:var(--white);border:1px solid var(--border);border-radius:8px;
                padding:36px;box-shadow:0 4px 24px rgba(0,0,0,.06)">

        {{-- Montant --}}
        <div style="margin-bottom:24px">
            <label style="font-size:10px;font-weight:700;letter-spacing:.12em;
                          text-transform:uppercase;color:var(--soft);display:block;margin-bottom:10px">
                Choisir le montant *
            </label>

            {{-- Montants prédéfinis --}}
            <div class="voucher-amounts" id="voucher-amounts">
                @foreach([10000, 25000, 50000, 75000, 100000] as $amt)
                    <button type="button" class="voucher-amount-btn"
                            onclick="selectAmount({{ $amt }}, this)">
                        {{ number_format($amt, 0, ',', ' ') }} XOF
                    </button>
                @endforeach
            </div>

            {{-- Montant libre --}}
            <div style="margin-top:10px;display:flex;align-items:center;gap:8px">
                <span style="font-size:12px;color:var(--soft)">Ou saisir un montant :</span>
                <input type="number" id="custom-amount" min="5000" step="1000"
                       placeholder="Ex: 35000"
                       style="background:var(--cream);border:1px solid var(--border);
                              border-radius:3px;padding:8px 12px;font-size:13px;
                              width:160px;font-family:inherit"
                       oninput="setCustomAmount(this.value)" />
                <span style="font-size:12px;color:var(--soft)">XOF</span>
            </div>

            {{-- Montant sélectionné --}}
            <div id="amount-display"
                 style="margin-top:10px;font-size:14px;font-weight:700;color:var(--red);display:none">
            </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--border);margin-bottom:24px" />

        {{-- Infos acheteur --}}
        <div style="margin-bottom:20px">
            <div style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
                        color:var(--soft);margin-bottom:12px">Vos informations</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                <div>
                    <label style="font-size:10px;font-weight:700;text-transform:uppercase;
                                  letter-spacing:.1em;color:var(--soft);display:block;margin-bottom:4px">
                        Votre nom *
                    </label>
                    <input class="form-input" id="buyer-name" type="text"
                           placeholder="Votre nom complet" />
                </div>
                <div>
                    <label style="font-size:10px;font-weight:700;text-transform:uppercase;
                                  letter-spacing:.1em;color:var(--soft);display:block;margin-bottom:4px">
                        Votre WhatsApp *
                    </label>
                    <input class="form-input" id="buyer-phone" type="tel"
                           placeholder="+229 01..." />
                </div>
            </div>
            <div>
                <label style="font-size:10px;font-weight:700;text-transform:uppercase;
                              letter-spacing:.1em;color:var(--soft);display:block;margin-bottom:4px">
                    Email (pour recevoir le bon)
                </label>
                <input class="form-input" id="buyer-email" type="email"
                       placeholder="votre@email.com" />
            </div>
        </div>

        {{-- Infos bénéficiaire (optionnel) --}}
        <details style="margin-bottom:24px">
            <summary style="font-size:12px;font-weight:700;color:var(--mid);cursor:pointer;
                            padding:8px 0;border-top:1px solid var(--border)">
                + Préciser le bénéficiaire (optionnel)
            </summary>
            <div style="margin-top:12px;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label style="font-size:10px;font-weight:700;text-transform:uppercase;
                                  letter-spacing:.1em;color:var(--soft);display:block;margin-bottom:4px">
                        Nom du bénéficiaire
                    </label>
                    <input class="form-input" id="recipient-name" type="text"
                           placeholder="Nom de la personne à qui vous offrez" />
                </div>
                <div>
                    <label style="font-size:10px;font-weight:700;text-transform:uppercase;
                                  letter-spacing:.1em;color:var(--soft);display:block;margin-bottom:4px">
                        Son WhatsApp
                    </label>
                    <input class="form-input" id="recipient-phone" type="tel"
                           placeholder="+229 01..." />
                </div>
            </div>
        </details>

        {{-- Note --}}
        <div style="background:#FFF5F5;border:1px solid #FECACA;border-left:3px solid var(--red);
                    border-radius:4px;padding:12px 16px;margin-bottom:24px;font-size:12px;color:var(--mid)">
            🔒 Paiement sécurisé via <strong>KKiaPay</strong>.
            Le code de votre bon sera affiché immédiatement après le paiement
            et envoyé sur WhatsApp.
        </div>

        {{-- Message erreur --}}
        <div id="voucher-error" style="display:none;background:#FFF5F5;border:1px solid #FECACA;
             border-left:3px solid var(--red);border-radius:4px;padding:12px 16px;
             margin-bottom:16px;font-size:13px;color:var(--red)"></div>

        {{-- Bouton payer --}}
        <button class="btn-gold" id="btn-pay-voucher"
                style="width:100%;justify-content:center;padding:15px;font-size:13px"
                onclick="payVoucher()">
            🎁 Acheter le bon d'achat
        </button>

    </div>
</div>

@endsection

@push('styles')
<style>
.voucher-amounts {
    display: flex; gap: 8px; flex-wrap: wrap;
}
.voucher-amount-btn {
    background: var(--cream); border: 1.5px solid var(--border);
    color: var(--mid); padding: 9px 16px; font-size: 12px;
    font-weight: 700; border-radius: 3px; cursor: pointer;
    transition: all .2s; font-family: inherit;
}
.voucher-amount-btn:hover,
.voucher-amount-btn.active {
    background: var(--red); border-color: var(--red); color: #fff;
}
</style>
@endpush

@push('scripts')
{{-- SDK KKiaPay --}}
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
var CSRF        = document.querySelector('meta[name="csrf-token"]')?.content || '';
var selectedAmt = 0;

function selectAmount(amount, btn) {
    selectedAmt = amount;
    document.querySelectorAll('.voucher-amount-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('custom-amount').value = '';
    showAmountDisplay(amount);
}

function setCustomAmount(val) {
    selectedAmt = parseFloat(val) || 0;
    document.querySelectorAll('.voucher-amount-btn').forEach(b => b.classList.remove('active'));
    if (selectedAmt > 0) showAmountDisplay(selectedAmt);
    else document.getElementById('amount-display').style.display = 'none';
}

function showAmountDisplay(amount) {
    var el = document.getElementById('amount-display');
    el.textContent = 'Montant sélectionné : ' + parseInt(amount).toLocaleString('fr-FR') + ' XOF';
    el.style.display = 'block';
}

function payVoucher() {
    var name  = document.getElementById('buyer-name')?.value.trim();
    var phone = document.getElementById('buyer-phone')?.value.trim();
    var email = document.getElementById('buyer-email')?.value.trim();
    var errEl = document.getElementById('voucher-error');

    if (!selectedAmt || selectedAmt < 5000) {
        showError('Choisissez un montant (minimum 5 000 XOF).'); return;
    }
    if (!name) { showError('Votre nom est requis.'); return; }
    if (!phone){ showError('Votre numéro WhatsApp est requis.'); return; }

    /* Masquer l'erreur précédente */
    errEl.style.display = 'none';

    var btn = document.getElementById('btn-pay-voucher');
    btn.disabled = true;
    btn.textContent = 'Ouverture du paiement...';

    /*
    | Ouvrir le widget KKiaPay
    | Documentation : https://docs.kkiapay.me/v1/plugin-et-sdk/sdk-javascript
    */
    openKkiapayWidget({
        amount:    selectedAmt,
        name:      name,
        callback:  handleKkiapaySuccess,
        theme:     '#e42829',
        sandbox:   '{{ config("kekeli.kkiapay_env", "sandbox") }}' === 'sandbox',
        key:       '{{ config("kekeli.kkiapay_public_key") }}',
    });

    btn.disabled = false;
    btn.textContent = '🎁 Acheter le bon d\'achat';
}

function handleKkiapaySuccess(response) {
    /*
    | KKiaPay retourne { transactionId, status }
    | On envoie au backend pour créer le bon
    */
    var btn = document.getElementById('btn-pay-voucher');
    btn.disabled = true;
    btn.textContent = 'Création du bon en cours...';

    fetch('/bons-dachat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            amount:          selectedAmt,
            buyer_name:      document.getElementById('buyer-name')?.value.trim(),
            buyer_phone:     document.getElementById('buyer-phone')?.value.trim(),
            buyer_email:     document.getElementById('buyer-email')?.value.trim() || null,
            recipient_name:  document.getElementById('recipient-name')?.value.trim() || null,
            recipient_phone: document.getElementById('recipient-phone')?.value.trim() || null,
            transaction_id:  response.transactionId,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/bons-dachat/confirmation/' + data.code;
        } else {
            showError(data.error || 'Erreur lors de la création du bon.');
            btn.disabled = false;
            btn.textContent = '🎁 Acheter le bon d\'achat';
        }
    })
    .catch(() => {
        showError('Erreur réseau. Votre paiement a été effectué — contactez-nous sur WhatsApp.');
        btn.disabled = false;
        btn.textContent = '🎁 Acheter le bon d\'achat';
    });
}

function showError(msg) {
    var el = document.getElementById('voucher-error');
    el.textContent = '✗ ' + msg;
    el.style.display = 'block';
}
</script>
@endpush
