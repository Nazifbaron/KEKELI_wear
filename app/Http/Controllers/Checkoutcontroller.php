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

class CheckoutController extends Controller
{
    /**
     * Résumé du panier avant paiement.
     */
    public function summary(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();
        $items     = CartItem::where('session_id', $sessionId)
                             ->with('product.category')
                             ->get();

        if ($items->isEmpty()) {
            return response()->json(['error' => 'Panier vide.'], 422);
        }

        $promoCode = $items->first()->promoCode;
        $subtotal  = $items->sum(fn($i) => $i->product->price * $i->quantity ?? 0);
        $discount  = 0;

        if ($promoCode && $promoCode->isValid()) {
            $discount = $promoCode->type === 'percentage'
                ? $subtotal * ($promoCode->discount / 100)
                : $promoCode->discount;
        }

        $total = max(0, $subtotal - $discount);

        return response()->json([
            'items'     => $items->map(fn($i) => [
                'id'        => $i->product_id,
                'name'      => $i->product->name,
                'category'  => $i->product->category->name,
                'price'     => $i->product->formatted_price,
                'quantity'  => $i->quantity,
                'is_custom' => $i->product->is_custom,
            ]),
            'subtotal'  => $subtotal,
            'discount'  => $discount,
            'total'     => $total,
            'promo'     => $promoCode?->label,
        ]);
    }

    /**
     * Crée la commande et redirige vers le paiement.
     * POST /checkout
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:120',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:120',
            'delivery_address' => 'nullable|string|max:300',
            'delivery_city'    => 'nullable|string|max:80',
            'payment_method'   => 'required|in:mtn_momo,moov_money,card,whatsapp',
            'measurement_id'   => 'nullable|exists:measurements,id',
        ]);

        $sessionId = $request->session()->getId();
        $cartItems = CartItem::where('session_id', $sessionId)
                             ->with('product', 'promoCode')
                             ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Panier vide.'], 422);
        }

        // Calculer les montants
        $promoCode = $cartItems->first()->promoCode;
        $subtotal  = $cartItems->sum(fn($i) => ($i->product->price ?? 0) * $i->quantity);
        $discount  = 0;

        if ($promoCode && $promoCode->isValid()) {
            $discount = $promoCode->type === 'percentage'
                ? $subtotal * ($promoCode->discount / 100)
                : $promoCode->discount;
        }

        $total = max(0, $subtotal - $discount);

        DB::beginTransaction();
        try {
            // Créer la commande
            $order = Order::create(array_merge($validated, [
                'reference'       => Order::generateReference(),
                'session_id'      => $sessionId,
                'subtotal'        => $subtotal,
                'discount_amount' => $discount,
                'delivery_fee'    => 0,
                'total'           => $total,
                'promo_code_id'   => $promoCode?->id,
                'status'          => 'pending',
                'payment_status'  => 'pending',
            ]));

            // Créer les lignes de commande (snapshot)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'category_name' => $item->product->category->name,
                    'unit_price'    => $item->product->price ?? 0,
                    'quantity'      => $item->quantity,
                    'subtotal'      => ($item->product->price ?? 0) * $item->quantity,
                    'is_custom'     => $item->product->is_custom,
                ]);

                // Incrémenter compteur commandes produit
                $item->product->increment('orders_count');
            }

            // Consommer le code promo
            $promoCode?->markUsed();

            // Vider le panier
            CartItem::where('session_id', $sessionId)->delete();

            DB::commit();

            // Redirection selon le moyen de paiement
            $redirectUrl = $this->getPaymentRedirect($order, $validated['payment_method']);

            return response()->json([
                'success'      => true,
                'order_ref'    => $order->reference,
                'redirect_url' => $redirectUrl,
                'whatsapp_msg' => $order->to_whats_app_message,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de la commande : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Callback paiement — MTN MoMo / Moov / Card
     * À adapter selon la passerelle choisie (FedaPay, CinetPay, etc.)
     */
    public function paymentCallback(Request $request, string $reference): JsonResponse
    {
        $order = Order::where('reference', $reference)->firstOrFail();

        // Simulé — remplacer par la vérification réelle de la passerelle
        $paymentSuccess = $request->input('status') === 'SUCCESS';

        if ($paymentSuccess) {
            $order->update([
                'payment_status'    => 'paid',
                'payment_reference' => $request->input('transaction_id'),
                'paid_at'           => now(),
                'status'            => 'processing',
            ]);

            return response()->json(['success' => true, 'message' => 'Paiement confirmé.']);
        }

        $order->update(['payment_status' => 'failed']);
        return response()->json(['success' => false, 'message' => 'Paiement échoué.'], 422);
    }

    /**
     * Retourne l'URL de redirection selon le moyen de paiement.
     */
    private function getPaymentRedirect(Order $order, string $method): string
    {
        return match($method) {
            'mtn_momo', 'moov_money', 'card' =>
                // À remplacer par l'URL réelle de FedaPay / CinetPay
                route('payment.gateway', ['ref' => $order->reference]),
            'whatsapp' =>
                'https://wa.me/' . config('kekeli.whatsapp') .
                '?text=' . urlencode($order->toWhatsAppMessage()),
            default => '/',
        };
    }
}
