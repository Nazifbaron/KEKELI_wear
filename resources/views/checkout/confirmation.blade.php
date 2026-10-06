{{-- checkout/confirmation.blade.php --}}
@extends('checkout.layout')
@section('step', 3)
@section('title', 'Commande confirmée')

@section('checkout-content')

<div style="max-width:560px;margin:0 auto;text-align:center;padding:40px 0">

    {{-- Icône succès --}}
    <div style="width:80px;height:80px;background:linear-gradient(135deg,#16a34a,#15803d);
                border-radius:50%;display:flex;align-items:center;justify-content:center;
                margin:0 auto 24px;box-shadow:0 0 40px rgba(22,163,74,.3)">
        <span style="font-size:36px;color:#fff">✓</span>
    </div>

    <h1 style="font-size:28px;font-weight:700;color:var(--ck-black);margin-bottom:10px">
        Commande confirmée !
    </h1>
    <p style="font-size:15px;color:var(--ck-soft);margin-bottom:32px;line-height:1.65">
        Merci {{ $order->customer_name }}. Votre commande a bien été enregistrée.<br>
        Nous vous contacterons sur WhatsApp pour les détails de livraison.
    </p>

    {{-- Récap --}}
    <div style="background:var(--ck-cream);border:1px solid var(--ck-border);
                border-radius:10px;padding:28px;margin-bottom:28px;text-align:left">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 24px">
            <div>
                <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                            color:var(--ck-soft);margin-bottom:4px">Référence</div>
                <div style="font-size:14px;font-weight:700;font-family:monospace;
                            color:var(--ck-red)">{{ $order->reference }}</div>
            </div>
            <div>
                <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                            color:var(--ck-soft);margin-bottom:4px">Montant total</div>
                <div style="font-size:14px;font-weight:700;color:var(--ck-black)">
                    {{ number_format($order->total, 0, ',', ' ') }} XOF
                    @if($order->discount_amount > 0)
                        <span style="font-size:11px;color:var(--ck-green);font-weight:400">
                            (−{{ number_format($order->discount_amount, 0, ',', ' ') }} XOF)
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                            color:var(--ck-soft);margin-bottom:4px">Livraison</div>
                <div style="font-size:13px;font-weight:600;color:var(--ck-black)">
                    {{ $order->delivery_city }}
                </div>
            </div>
            <div>
                <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;
                            color:var(--ck-soft);margin-bottom:4px">Statut paiement</div>
                <div>
                    @if($order->isPaid())
                        <span style="color:#16a34a;font-weight:700;font-size:13px">✓ Payé</span>
                    @else
                        <span style="color:#c2410c;font-weight:700;font-size:13px">⏳ En attente</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- CTA --}}
    <div style="display:flex;flex-direction:column;gap:10px">
        <a href="https://wa.me/{{ config('kekeli.whatsapp') }}"
           target="_blank"
           style="background:#25D366;color:#fff;text-decoration:none;
                  padding:14px 24px;border-radius:6px;font-size:13px;font-weight:700;
                  display:flex;align-items:center;justify-content:center;gap:8px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            Nous contacter sur WhatsApp
        </a>
        <a href="{{ route('boutique') }}"
           style="background:transparent;border:1px solid var(--ck-border);color:var(--ck-mid);
                  text-decoration:none;padding:12px 24px;border-radius:6px;
                  font-size:12px;font-weight:600;display:flex;align-items:center;
                  justify-content:center">
            ← Continuer mes achats
        </a>
    </div>

</div>
@endsection
