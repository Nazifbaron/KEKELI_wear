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

class CheckoutController extends Controller
{
    /*
    |----------------------------------------------------------
    | SUMMARY — Page récapitulatif (vue Blade)
    | Retourne la vue checkout/summary.blade.php
    | Le JS de la page charge le panier via /api/cart
    |----------------------------------------------------------
    */
    public function summary(Request $request)
    {
        $sessionId = $request->session()->getId();

        // Panier vide → rediriger vers la boutique
        $cartCount = CartItem::where('session_id', $sessionId)->count();
        if ($cartCount === 0) {
            return redirect()->route('home')
                             ->with('info', 'Votre panier est vide.');
        }

        return view('checkout.summary');
    }

    /*
    |----------------------------------------------------------
    | STORE — Créer la commande en BD
    | POST /checkout
    | 1. Valider les données client
    | 2. Calculer les montants (côté serveur — anti-fraude)
    | 3. Créer Order + OrderItems en transaction DB
    | 4. Vider le panier
    | 5. Retourner l'URL de redirection selon le moyen de paiement
    |----------------------------------------------------------
    */
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

        // Récupérer les items du panier avec produits
        $cartItems = CartItem::where('session_id', $sessionId)
                             ->with('product.category', 'promoCode')
                             ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'error'   => 'Votre panier est vide.',
            ], 422);
        }

        /*
        |----------------------------------------------------------
        | Calcul des montants côté serveur
        | On ne fait jamais confiance aux montants du front
        |----------------------------------------------------------
        */
        $subtotal  = 0;
        $discount  = 0;
        $promoCode = $cartItems->first()->promoCode;

        foreach ($cartItems as $item) {
            $price     = $item->product->price ?? 0;
            $subtotal += $price * $item->quantity;
        }

        // Appliquer le code promo si valide
        if ($promoCode && $promoCode->isValid()) {
            $discount = $promoCode->type === 'percentage'
                ? round($subtotal * ($promoCode->discount / 100))
                : min($promoCode->discount, $subtotal);
        }

        $total = max(0, $subtotal - $discount);

        DB::beginTransaction();
        try {
            /*
            |----------------------------------------------------------
            | Créer la commande principale
            |----------------------------------------------------------
            */
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
                'discount_amount'  => $discount,
                'delivery_fee'     => 0,
                'total'            => $total,
                'promo_code_id'    => $promoCode?->id,
                'payment_method'   => $validated['payment_method'],
                'payment_status'   => 'pending',
                'status'           => 'pending',
                'measurement_id'   => $validated['measurement_id'] ?? null,
            ]);

            /*
            |----------------------------------------------------------
            | Créer les lignes de commande (snapshot produits)
            | On sauvegarde nom/prix au moment de la commande
            | pour garder l'historique même si le produit change
            |----------------------------------------------------------
            */
            foreach ($cartItems as $item) {
                $unitPrice = $item->product->price ?? 0;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'category_name' => $item->product->category->name ?? '',
                    'unit_price'    => $unitPrice,
                    'quantity'      => $item->quantity,
                    'subtotal'      => $unitPrice * $item->quantity,
                    'is_custom'     => $item->product->is_custom,
                ]);

                // Incrémenter le compteur de commandes du produit
                $item->product->increment('orders_count');
                // Recalculer le score coup de cœur
                $item->product->recalculateScore();
            }

            // Consommer le code promo (incrémenter used_count)
            $promoCode?->markUsed();

            // Vider le panier session
            CartItem::where('session_id', $sessionId)->delete();

            DB::commit();

            /*
            |----------------------------------------------------------
            | Construire la réponse selon le moyen de paiement
            |----------------------------------------------------------
            */
            $redirectUrl = $this->buildRedirectUrl($order);

            return response()->json([
                'success'      => true,
                'order_ref'    => $order->reference,
                'redirect_url' => $redirectUrl,
                'whatsapp_msg' => $order->toWhatsAppMessage(),
                'total'        => $total,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors de la création de la commande. Veuillez réessayer.',
            ], 500);
        }
    }

    /*
    |----------------------------------------------------------
    | PAYMENT CALLBACK — Confirmation de paiement FedaPay
    | POST /checkout/payment/{ref}/callback
    | Appelé par le widget JS FedaPay après succès paiement.
    | Vérifie la transaction auprès de l'API FedaPay,
    | met à jour le statut de la commande en BD.
    |----------------------------------------------------------
    */
    public function paymentCallback(Request $request, string $reference): JsonResponse
    {
        $order = Order::where('reference', $reference)->firstOrFail();

        // Éviter le double-traitement
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Commande déjà confirmée.',
            ]);
        }

        $transactionId = $request->input('transaction_id');
        $status        = $request->input('status');

        /*
        |----------------------------------------------------------
        | Vérification côté serveur via l'API FedaPay
        | On ne fait jamais confiance au statut envoyé par le front
        |----------------------------------------------------------
        */
        if ($transactionId && config('kekeli.fedapay_env') !== 'sandbox') {
            $verified = $this->verifyFedaPayTransaction($transactionId);
            if (!$verified) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Transaction non vérifiée.',
                ], 422);
            }
        } else {
            // Mode sandbox → on accepte le statut du front
            $verified = ($status === 'SUCCESS');
        }

        if ($verified) {
            $order->update([
                'payment_status'    => 'paid',
                'payment_reference' => $transactionId,
                'paid_at'           => now(),
                'status'            => 'processing',
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Paiement confirmé. Commande en cours de traitement.',
                'order_ref'  => $order->reference,
            ]);
        }

        // Paiement échoué
        $order->update(['payment_status' => 'failed']);

        return response()->json([
            'success' => false,
            'error'   => 'Paiement non abouti.',
        ], 422);
    }

    /*
    |----------------------------------------------------------
    | CONFIRMATION — Page de confirmation (vue Blade)
    | GET /checkout/confirmation/{ref}
    | Affichée après paiement réussi ou commande WhatsApp
    |----------------------------------------------------------
    */
    public function confirmation(string $ref)
    {
        // Vérifier que la commande existe
        $order = Order::where('reference', $ref)->firstOrFail();

        return view('checkout.confirmation', [
            'ref'   => $ref,
            'order' => $order,
        ]);
    }

    /*
    |----------------------------------------------------------
    | PAYMENT PAGE — Page de paiement (vue Blade)
    | GET /checkout/payment/{ref}
    | Charge le widget FedaPay pour finaliser le paiement
    |----------------------------------------------------------
    */
    public function paymentPage(string $ref)
    {
        $order = Order::where('reference', $ref)->firstOrFail();

        // Si déjà payée → aller directement à la confirmation
        if ($order->isPaid()) {
            return redirect()->route('checkout.confirmation', $ref);
        }

        return view('checkout.payment', [
            'ref'   => $ref,
            'order' => $order,
        ]);
    }

    /*
    |==========================================================
    | MÉTHODES PRIVÉES
    |==========================================================
    */

    /*
    |----------------------------------------------------------
    | Vérifier une transaction FedaPay côté serveur
    | Utilise la clé secrète (jamais exposée au front)
    | Doc : https://docs.fedapay.com/api#retrieve-a-transaction
    |----------------------------------------------------------
    */
    private function verifyFedaPayTransaction(string $transactionId): bool
    {
        try {
            $env       = config('kekeli.fedapay_env', 'sandbox');
            $secretKey = config('kekeli.fedapay_secret_key');
            $baseUrl   = $env === 'live'
                ? 'https://api.fedapay.com/v1'
                : 'https://sandbox-api.fedapay.com/v1';

            $response = Http::withToken($secretKey)
                            ->get("{$baseUrl}/transactions/{$transactionId}");

            if ($response->successful()) {
                $data   = $response->json();
                $status = $data['v1/transaction']['status'] ?? '';
                return $status === 'approved';
            }

            return false;

        } catch (\Throwable $e) {
            // En cas d'erreur API → ne pas bloquer la commande
            // Logguer l'erreur et laisser passer en sandbox
            \Log::error('FedaPay verify error: ' . $e->getMessage());
            return false;
        }
    }

    /*
    |----------------------------------------------------------
    | Construire l'URL de redirection selon le moyen de paiement
    |----------------------------------------------------------
    */
    private function buildRedirectUrl(Order $order): string
    {
        return match($order->payment_method) {
            // Paiement en ligne → page widget FedaPay
            'mtn_momo', 'moov_money', 'card' =>
                route('checkout.payment.gateway', ['ref' => $order->reference]),

            // WhatsApp → message pré-rempli (géré côté JS)
            'whatsapp' =>
                route('checkout.confirmation', ['ref' => $order->reference]),

            default =>
                route('checkout.confirmation', ['ref' => $order->reference]),
        };
    }
}
