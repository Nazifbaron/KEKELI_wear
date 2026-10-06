{{-- ============================================================
     vouchers/confirmation.blade.php
     Page affichée après achat du bon d'achat
============================================================ --}}
@extends('layouts.app')
@section('title', 'Votre bon d\'achat KEKELI')

@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#0a0a0a 0%,#1a1a1a 50%,#0f0f0f 100%);
            display:flex;align-items:center;justify-content:center;padding:40px 20px;margin-top:0">

    <div style="max-width:540px;width:100%">

        {{-- Animation succès --}}
        <div style="text-align:center;margin-bottom:32px">
            <div style="width:72px;height:72px;background:linear-gradient(135deg,#e42829,#c01f20);
                        border-radius:50%;display:flex;align-items:center;justify-content:center;
                        margin:0 auto 16px;box-shadow:0 0 40px rgba(228,40,41,.4);
                        animation:pulse 2s ease-in-out infinite">
                <span style="font-size:32px">✓</span>
            </div>
            <div style="font-size:11px;letter-spacing:.2em;text-transform:uppercase;
                        color:rgba(255,255,255,.5);margin-bottom:8px">Paiement confirmé</div>
            <h1 style="font-size:28px;font-weight:700;color:#fff;margin-bottom:8px">
                Votre bon d'achat est prêt
            </h1>
            <p style="font-size:14px;color:rgba(255,255,255,.55);line-height:1.6">
                Conservez ce code précieusement.<br>
                Il sera demandé lors du passage en caisse.
            </p>
        </div>

        {{-- Carte du bon --}}
        <div id="gift-card"
             style="background:linear-gradient(135deg,#1c1c1c,#2a2a2a);
                    border:1px solid rgba(228,40,41,.3);
                    border-radius:16px;padding:36px;margin-bottom:24px;
                    box-shadow:0 24px 64px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.06);
                    position:relative;overflow:hidden">

            {{-- Motif décoratif --}}
            <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;
                        border-radius:50%;background:radial-gradient(circle,rgba(228,40,41,.12),transparent 70%)"></div>
            <div style="position:absolute;bottom:-60px;left:-30px;width:200px;height:200px;
                        border-radius:50%;background:radial-gradient(circle,rgba(228,40,41,.08),transparent 70%)"></div>

            <div style="position:relative;z-index:1">
                {{-- Logo + titre --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px">
                    <div>
                        <div style="font-size:20px;font-weight:900;color:#fff;
                                    letter-spacing:.12em;text-transform:uppercase">
                            KEKELI <span style="color:#e42829">WEAR</span>
                        </div>
                        <div style="font-size:9px;letter-spacing:.18em;text-transform:uppercase;
                                    color:rgba(255,255,255,.35);margin-top:2px">Bon d'achat</div>
                    </div>
                    <div style="font-size:36px">🎁</div>
                </div>

                {{-- Montant --}}
                <div style="margin-bottom:24px">
                    <div style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;
                                color:rgba(255,255,255,.4);margin-bottom:6px">Valeur du bon</div>
                    <div style="font-size:42px;font-weight:900;color:#fff;line-height:1">
                        {{ number_format($voucher->initial_amount, 0, ',', ' ') }}
                        <span style="font-size:18px;font-weight:400;color:rgba(255,255,255,.5)">XOF</span>
                    </div>
                    @if((float)$voucher->balance < (float)$voucher->initial_amount)
                        <div style="font-size:12px;color:#e42829;margin-top:4px">
                            Solde restant : {{ $voucher->formatted_balance }}
                        </div>
                    @endif
                </div>

                {{-- Code --}}
                <div style="background:rgba(0,0,0,.4);border:1px solid rgba(255,255,255,.08);
                            border-radius:8px;padding:18px 20px;margin-bottom:20px">
                    <div style="font-size:9px;letter-spacing:.18em;text-transform:uppercase;
                                color:rgba(255,255,255,.35);margin-bottom:8px">Code du bon</div>
                    <div id="voucher-code"
                         style="font-family:'Courier New',monospace;font-size:26px;
                                font-weight:700;color:#fff;letter-spacing:.2em;
                                text-align:center">
                        {{ $voucher->code }}
                    </div>
                </div>

                {{-- Infos --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:0">
                    <div>
                        <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                                    color:rgba(255,255,255,.3);margin-bottom:3px">Acheté par</div>
                        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,.75)">
                            {{ $voucher->buyer_name }}
                        </div>
                    </div>
                    @if($voucher->recipient_name)
                    <div>
                        <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                                    color:rgba(255,255,255,.3);margin-bottom:3px">Pour</div>
                        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,.75)">
                            {{ $voucher->recipient_name }}
                        </div>
                    </div>
                    @endif
                    <div>
                        <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                                    color:rgba(255,255,255,.3);margin-bottom:3px">Créé le</div>
                        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,.75)">
                            {{ $voucher->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                                    color:rgba(255,255,255,.3);margin-bottom:3px">Validité</div>
                        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,.75)">
                            {{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y') : 'Sans limite' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px">

            {{-- Copier le code --}}
            <button onclick="copyCode('{{ $voucher->code }}')"
                    style="background:linear-gradient(135deg,#e42829,#c01f20);color:#fff;
                           border:none;padding:14px 24px;border-radius:6px;font-size:13px;
                           font-weight:700;letter-spacing:.06em;text-transform:uppercase;
                           cursor:pointer;transition:all .2s;font-family:inherit;width:100%"
                    id="copy-btn">
                📋 Copier le code
            </button>

            {{-- Envoyer via WhatsApp --}}
            @php
                $waMsg = urlencode(
                    "🎁 *Bon d'achat KEKELI WEAR*\n\n" .
                    "Bonjour " . ($voucher->recipient_name ?? '') . ",\n" .
                    "Voici ton bon d'achat d'une valeur de " .
                    number_format($voucher->initial_amount, 0, ',', ' ') . " XOF.\n\n" .
                    "🔑 Code : *" . $voucher->code . "*\n\n" .
                    "Utilise ce code lors de ta commande sur kekeliwear.com\n" .
                    "Le solde non utilisé est conservé pour un futur achat. ✨"
                );
                $waPhone = $voucher->recipient_phone
                    ? preg_replace('/\D/', '', $voucher->recipient_phone)
                    : '';
                $waUrl = 'https://wa.me/' . $waPhone . '?text=' . $waMsg;
            @endphp

            <a href="{{ $waUrl }}" target="_blank"
               style="background:#25D366;color:#fff;text-decoration:none;
                      padding:14px 24px;border-radius:6px;font-size:13px;
                      font-weight:700;letter-spacing:.06em;text-transform:uppercase;
                      display:flex;align-items:center;justify-content:center;gap:8px;
                      transition:all .2s">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Envoyer par WhatsApp
            </a>

            {{-- Retour boutique --}}
            <a href="{{ route('boutique') }}"
               style="background:transparent;color:rgba(255,255,255,.5);text-decoration:none;
                      padding:12px 24px;border-radius:6px;font-size:12px;font-weight:600;
                      letter-spacing:.06em;text-transform:uppercase;
                      border:1px solid rgba(255,255,255,.1);
                      display:flex;align-items:center;justify-content:center;gap:6px;
                      transition:all .2s">
                ← Aller à la boutique
            </a>
        </div>

        {{-- Note solde conservé --}}
        <div style="background:rgba(228,40,41,.08);border:1px solid rgba(228,40,41,.2);
                    border-radius:6px;padding:14px 18px;text-align:center">
            <div style="font-size:12px;color:rgba(255,255,255,.55);line-height:1.6">
                ♻️ <strong style="color:rgba(255,255,255,.8)">Solde conservé</strong>
                — Si la commande est inférieure au montant du bon,
                le reliquat reste disponible pour un futur achat.
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 40px rgba(228,40,41,.4); }
    50%       { box-shadow: 0 0 60px rgba(228,40,41,.7); }
}
</style>
@endpush

@push('scripts')
<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        var btn = document.getElementById('copy-btn');
        btn.textContent = '✓ Code copié !';
        btn.style.background = 'linear-gradient(135deg,#16a34a,#15803d)';
        setTimeout(function() {
            btn.textContent = '📋 Copier le code';
            btn.style.background = 'linear-gradient(135deg,#e42829,#c01f20)';
        }, 2500);
    });
}
</script>
@endpush

@endsection
