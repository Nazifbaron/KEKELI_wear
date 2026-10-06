<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /* ============================================================
       SUMMARY — Vue récapitulatif (Blade)
    ============================================================ */
    public function summary(Request $request)
    {
        $cartCount = CartItem::where('session_id', $request->session()->getId())->count();
        if ($cartCount === 0) {
            return redirect()->route('home')->with('info', 'Votre panier est vide.');
        }
        return view('checkout.summary');
    }

    /* ============================================================
       STORE — Créer la commande en BD
       Calcul des montants côté serveur — anti-fraude.
       Priorité remises :
       1. Prix promo produit (is_currently_on_sale)
       2. Code promo session (si applicable à la catégorie)
    ============================================================ */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:120',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:120',
            'delivery_address' => 'nullable|string|max:300',
            'delivery_city'    => 'required|string|max:80',
            'delivery_country' => 'nullable|string|max:60',
            'payment_method'   => 'required|in:mtn_momo,moov_money,card,whatsapp',
            'measurement_id'   => 'nullable|exists:measurements,id',
        ]);

        $sessionId = $request->session()->getId();
        $cartItems = CartItem::where('session_id', $sessionId)
                             ->with('product.category')
                             ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'error' => 'Votre panier est vide.'], 422);
        }

        /*
        |----------------------------------------------------------
        | Récupérer code promo depuis la session
        |----------------------------------------------------------
        */
        $promoCodeId     = $request->session()->get('promo_code_id');
        $promoCategoryId = $request->session()->get('promo_category_id');
        $promoCode       = $promoCodeId ? PromoCode::find($promoCodeId) : null;

        if ($promoCode && !$promoCode->isValid()) {
            $promoCode = null;
            $request->session()->forget(['promo_code_id','promo_code','promo_discount','promo_type','promo_category_id']);
        }

        // Récupérer bon d'achat depuis la session
        $voucherId      = $request->session()->get('voucher_id');
        $voucherBalance = (float)$request->session()->get('voucher_balance', 0);
        $voucher        = $voucherId ? \App\Models\GiftVoucher::find($voucherId) : null;

        if ($voucher && !$voucher->isUsable()) {
            $voucher = null;
            $voucherBalance = 0;
            $request->session()->forget(['voucher_id','voucher_code','voucher_balance']);
        }

        /*
        |----------------------------------------------------------
        | Calcul des montants côté serveur
        |----------------------------------------------------------
        */
        $subtotal      = 0;
        $totalDiscount = 0;
         // Dans le calcul du total, après $totalDiscount :
        $voucherDeduction = $voucher ? min($voucherBalance, max(0, $subtotal - $totalDiscount)) : 0;
        $total            = max(0, $subtotal - $totalDiscount - $voucherDeduction);

        foreach ($cartItems as $item) {
            $product   = $item->product;
            $basePrice = (float)($product->price ?? 0);

            /* Priorité 1 — Promo produit */
            if ($product->is_currently_on_sale && $product->sale_price > 0) {
                $effectivePrice = (float)$product->sale_price;
            }
            /* Priorité 2 — Code promo (si catégorie correspond) */
            elseif ($promoCode) {
                $catMatch = !$promoCategoryId || $product->category_id === $promoCategoryId;
                if ($catMatch) {
                    $effectivePrice = $promoCode->type === 'percentage'
                        ? $basePrice * (1 - $promoCode->discount / 100)
                        : max(0, $basePrice - $promoCode->discount);
                } else {
                    $effectivePrice = $basePrice;
                }
            } else {
                $effectivePrice = $basePrice;
            }

            $subtotal      += $basePrice * $item->quantity;
            $totalDiscount += ($basePrice - $effectivePrice) * $item->quantity;
        }

        $total = max(0, $subtotal - $totalDiscount);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'reference'        => Order::generateReference(),
                'session_id'       => $sessionId,
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_email'   => $validated['customer_email'] ?? null,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_city'    => $validated['delivery_city'],
                'delivery_country' => $validated['delivery_country'] ?? 'Bénin',
                'subtotal'         => $subtotal,
                'discount_amount'  => round($totalDiscount),
                'delivery_fee'     => 0,
                'total'            => round($total),
                'promo_code_id'    => $promoCode?->id,
                'payment_method'   => $validated['payment_method'],
                'payment_status'   => 'pending',
                'status'           => 'pending',
                'measurement_id'   => $validated['measurement_id'] ?? null,
                'gift_voucher_id'       => $voucher?->id,
                'voucher_deduction'     => round($voucherDeduction),
            ]);

            foreach ($cartItems as $item) {
                $product   = $item->product;
                $basePrice = (float)($product->price ?? 0);

                /* Recalculer le prix effectif pour chaque ligne */
                if ($product->is_currently_on_sale && $product->sale_price > 0) {
                    $unitPrice = (float)$product->sale_price;
                } elseif ($promoCode) {
                    $catMatch  = !$promoCategoryId || $product->category_id === $promoCategoryId;
                    $unitPrice = $catMatch
                        ? ($promoCode->type === 'percentage'
                            ? $basePrice * (1 - $promoCode->discount / 100)
                            : max(0, $basePrice - $promoCode->discount))
                        : $basePrice;
                } else {
                    $unitPrice = $basePrice;
                }

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $product->name,
                    'category_name' => $product->category->name ?? '',
                    'unit_price'    => round($unitPrice),
                    'quantity'      => $item->quantity,
                    'subtotal'      => round($unitPrice * $item->quantity),
                    'is_custom'     => $product->is_custom,
                ]);

                $product->increment('orders_count');
                $product->recalculateScore();
            }

            $promoCode?->markUsed();
            CartItem::where('session_id', $sessionId)->delete();
            $request->session()->forget([
                'promo_code_id','promo_code','promo_discount','promo_type','promo_category_id',
            ]);

            DB::commit();
            // Après DB::commit(), utiliser le bon :
            if ($voucher && $voucherDeduction > 0) {
                $voucher->use($voucherDeduction, $order->id);
                $request->session()->forget(['voucher_id','voucher_code','voucher_balance']);
            }

            return response()->json([
                'success'      => true,
                'order_ref'    => $order->reference,
                'redirect_url' => $this->buildRedirectUrl($order),
                'whatsapp_msg' => $order->toWhatsAppMessage(),
                'total'        => round($total),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Erreur lors de la commande. Veuillez réessayer.'], 500);
        }
    }

    /* ============================================================
       PAYMENT PAGE
    ============================================================ */
    public function paymentPage(string $ref)
    {
        $order = Order::where('reference', $ref)->firstOrFail();
        if ($order->isPaid()) return redirect()->route('checkout.confirmation', $ref);
        return view('checkout.payment', compact('ref', 'order'));
    }

    /* ============================================================
       PAYMENT CALLBACK — KKiaPay
    ============================================================ */
    public function paymentCallback(Request $request, string $reference): JsonResponse
    {
        $order = Order::where('reference', $reference)->firstOrFail();
        if ($order->payment_status === 'paid') {
            return response()->json(['success' => true, 'message' => 'Commande déjà confirmée.']);
        }

        $transactionId = $request->input('transaction_id');
        $status        = strtoupper((string) $request->input('status', ''));

        if ($transactionId && config('kekeli.kkiapay_env') !== 'sandbox') {
            $verified = $this->verifyKkiapayTransaction($transactionId, (float) $order->total);
        } else {
            $verified = in_array($status, ['SUCCESS', 'PAID', 'APPROVED'], true);
        }

        if ($verified) {
            $order->update([
                'payment_status'    => 'paid',
                'payment_reference' => $transactionId,
                'paid_at'           => now(),
                'status'            => 'processing',
            ]);
            return response()->json(['success' => true, 'order_ref' => $order->reference]);
        }

        $order->update(['payment_status' => 'failed']);
        return response()->json(['success' => false, 'error' => 'Paiement non abouti.'], 422);
    }

    /* ============================================================
       CONFIRMATION
    ============================================================ */
    public function confirmation(string $ref)
    {
        $order = Order::where('reference', $ref)->firstOrFail();
        return view('checkout.confirmation', compact('ref', 'order'));
    }

    /* ============================================================
       PRIVÉ — Vérification KKiaPay côté serveur
    ============================================================ */
    private function verifyKkiapayTransaction(string $transactionId, float $amount): bool
    {
        try {
            $response = Http::withHeaders([
                'x-private-key' => config('kekeli.kkiapay_private_key'),
            ])->get('https://api.kkiapay.me/api/v1/transactions/' . $transactionId);

            if ($response->successful()) {
                $data   = $response->json();
                $status = strtoupper((string) ($data['status'] ?? ''));
                $txAmt  = (float) ($data['amount'] ?? 0);

                return in_array($status, ['SUCCESS', 'PAID', 'APPROVED'], true)
                    && $txAmt >= $amount;
            }
        } catch (\Throwable $e) {
            Log::error('KKiaPay verify error: ' . $e->getMessage());
        }

        return config('kekeli.kkiapay_env', 'sandbox') === 'sandbox';
    }

    private function buildRedirectUrl(Order $order): string
    {
        return match($order->payment_method) {
            'mtn_momo', 'moov_money', 'card' =>
                route('checkout.payment.gateway', ['ref' => $order->reference]),
            default =>
                route('checkout.confirmation', ['ref' => $order->reference]),
        };
    }
}

