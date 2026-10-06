<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\PromoCode;
use App\Models\Measurement;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Review;
use App\Services\CartPricingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /* ============================================================
       STATS GLOBALES
    ============================================================ */
    public function stats(): JsonResponse
    {
        $categories = Category::active()
            ->withCount(['products as count' => fn($q) => $q->where('is_active', true)])
            ->get();

        return response()->json([
            'total_products' => Product::active()->count(),
            'total_likes'    => Product::active()->sum('likes'),
            'total_views'    => Product::active()->sum('views'),
            'avg_rating'     => number_format(Review::averageRating(), 1, ',', ''),
            'per_category'   => $categories->pluck('count', 'slug'),
        ]);
    }

    /* ============================================================
       GET ORDER — pour pages payment/confirmation
    ============================================================ */
    public function getOrder(string $ref): JsonResponse
    {
        $order = \App\Models\Order::where('reference', $ref)
                                  ->with('items')
                                  ->firstOrFail();
        return response()->json([
            'reference'      => $order->reference,
            'customer_name'  => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'customer_email' => $order->customer_email,
            'delivery_city'  => $order->delivery_city,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'status'         => $order->status,
            'subtotal'       => $order->subtotal,
            'discount'       => $order->discount_amount,
            'total'          => $order->total,
        ]);
    }

    /* ============================================================
       TOGGLE LIKE — 1 like par session par produit
    ============================================================ */
    public function toggleLike(Request $request, int $id): JsonResponse
    {
        $product   = Product::active()->findOrFail($id);
        $sessionId = $request->session()->getId();

        $existing = ProductLike::where('product_id', $id)
                               ->where('session_id', $sessionId)
                               ->first();

        if ($existing) {
            $existing->delete();
            $product->decrement('likes');
            $liked = false;
        } else {
            ProductLike::create([
                'product_id' => $id,
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
            ]);
            $product->increment('likes');
            $liked = true;
        }

        $product->recalculateScore();

        return response()->json([
            'liked' => $liked,
            'likes' => $product->fresh()->likes,
        ]);
    }

    /* ============================================================
       TRACK VIEW
    ============================================================ */
    public function trackView(int $id): JsonResponse
    {
        $product = Product::active()->findOrFail($id);
        $product->incrementViews();
        return response()->json(['views' => $product->fresh()->views]);
    }

    /* ============================================================
       VERIFY PROMO CODE
       - Vérification globale (dates, limite, actif)
       - Vérification portée (catégorie si restreint)
       - Sauvegarde en session pour le panier et le checkout
    ============================================================ */
    public function verifyPromo(Request $request): JsonResponse
    {
        $code  = strtoupper(trim($request->input('code', '')));
        $promo = PromoCode::with('category')->where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json([
                'valid'   => false,
                'message' => 'Code invalide ou expiré.',
            ], 422);
        }

        // Conserver uniquement l'identifiant : prix et portée sont relus en base
        // à chaque calcul du panier/checkout pour éviter une session incohérente.
        $request->session()->put('promo_code_id', $promo->id);

        return response()->json([
            'valid'       => true,
            'promo_id'    => $promo->id,
            'code'        => $promo->code,
            'discount'    => $promo->discount,
            'type'        => $promo->type,
            'label'       => $promo->label,
            'scope'       => $promo->scope_label,
            'category_id' => $promo->category_id,
            'message'     => $promo->label . ' appliqué — ' . $promo->scope_label,
        ]);
    }

    /* ============================================================
       REMOVE PROMO
    ============================================================ */
    public function removePromo(Request $request): JsonResponse
    {
        $request->session()->forget('promo_code_id');
        return response()->json(['success' => true]);
    }

    /* ============================================================
       SAVE MEASUREMENT
    ============================================================ */
    public function saveMeasurement(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'    => 'required|string|max:120',
            'whatsapp'     => 'required|string|max:20',
            'morphology'   => 'nullable|string|max:30',
            'back_size'    => 'nullable|numeric',
            'chest'        => 'nullable|numeric',
            'waist'        => 'nullable|numeric',
            'hips'         => 'nullable|numeric',
            'height'       => 'nullable|numeric',
            'dress_length' => 'nullable|numeric',
            'top_length'   => 'nullable|numeric',
            'skirt_length' => 'nullable|numeric',
            'notes'        => 'nullable|string|max:500',
            'product_id'   => 'nullable|exists:products,id',
        ]);

        $measurement = Measurement::create($validated);
        if (empty($measurement->morphology)) {
            $measurement->update(['morphology' => $measurement->detectMorphology()]);
        }

        return response()->json([
            'success'    => true,
            'morphology' => Measurement::morphologyLabel($measurement->morphology),
            'message'    => 'Mensurations enregistrées.',
        ]);
    }

    /* ============================================================
       CART — GET
       Applique les remises :
       1. Prix promo produit (is_currently_on_sale) — prioritaire
       2. Code promo session sur les produits éligibles
    ============================================================ */
    public function getCart(Request $request, CartPricingService $pricing): JsonResponse
    {
        $sessionId = $request->session()->getId();

        $items = CartItem::where('session_id', $sessionId)
            ->with(['product.category', 'product.extraImages'])
            ->get();

        $promo = $request->session()->get('promo_code_id')
            ? PromoCode::with('category')->find($request->session()->get('promo_code_id'))
            : null;
        if ($promo && !$promo->isValid()) {
            $promo = null;
            $request->session()->forget('promo_code_id');
        }

        $subtotal      = 0;
        $totalDiscount = 0;

        $mappedItems = $items->map(function ($i) use ($promo, $pricing, &$subtotal, &$totalDiscount) {
            $product   = $i->product;
            $price = $pricing->priceFor($product, $promo);
            $basePrice = $price['base_price'];
            $effectivePrice = $price['unit_price'];
            $onSale = $price['discount_source'] === 'product_sale';

            $lineSubtotal      = $basePrice * $i->quantity;
            $lineReduced       = $effectivePrice * $i->quantity;
            $lineDiscount      = $lineSubtotal - $lineReduced;
            $subtotal         += $lineSubtotal;
            $totalDiscount    += $lineDiscount;

            return [
                'id'               => $product->id,
                'name'             => $product->name,
                'category'         => $product->category->name ?? '',
                'category_id'      => $product->category_id,
                'price'            => number_format($basePrice, 0, ',', ' ') . ' XOF',
                'price_reduced'    => ($lineDiscount > 0)
                    ? number_format($effectivePrice, 0, ',', ' ') . ' XOF'
                    : null,
                'raw_price'        => $basePrice,
                'raw_reduced'      => $effectivePrice,
                'image'            => $product->main_image,
                'quantity'         => $i->quantity,
                'is_custom'        => $product->is_custom,
                'on_sale'          => $onSale,
                'sale_percent'     => $onSale ? $product->discount_percent : null,
                'discount_source'  => $price['discount_source'],
                'subtotal'         => $lineSubtotal,
                'subtotal_reduced' => $lineReduced,
            ];
        });

        $totalAfterDiscount = (float)$subtotal - (float)$totalDiscount;

        return response()->json([
            'items'      => $mappedItems,
            'total'      => $mappedItems->sum('quantity'),
            'subtotal'   => $subtotal,
            'discount'   => round($totalDiscount),
            'amount'     => round($totalAfterDiscount),
            'promo_code' => $promo?->code,
            'promo_label'=> $promo?->label,
            'promo'      => $promo ? [
                'id' => $promo->id,
                'code' => $promo->code,
                'discount' => (float) $promo->discount,
                'type' => $promo->type,
                'label' => $promo->label,
                'scope' => $promo->scope_label,
                'category_id' => $promo->category_id,
            ] : null,
        ]);
    }

    /* ============================================================
       CART — ADD
    ============================================================ */
    public function addToCart(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $sessionId = $request->session()->getId();

        $item = CartItem::firstOrCreate(
            ['session_id' => $sessionId, 'product_id' => $request->product_id],
            ['quantity'   => 0]
        );
        $item->increment('quantity');

        return response()->json([
            'total'   => CartItem::where('session_id', $sessionId)->sum('quantity'),
            'message' => 'Produit ajouté au panier.',
        ]);
    }

    /* ============================================================
       CART — UPDATE QTY
    ============================================================ */
    public function updateQty(Request $request, int $productId): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        CartItem::where('session_id', $request->session()->getId())
                ->where('product_id', $productId)
                ->update(['quantity' => $request->quantity]);
        return response()->json(['success' => true]);
    }

    /* ============================================================
       CART — REMOVE
    ============================================================ */
    public function removeFromCart(Request $request, int $productId): JsonResponse
    {
        $sessionId = $request->session()->getId();
        CartItem::where('session_id', $sessionId)->where('product_id', $productId)->delete();
        return response()->json(['total' => CartItem::where('session_id', $sessionId)->sum('quantity')]);
    }

    /* ============================================================
       CART — CLEAR
    ============================================================ */
    public function clearCart(Request $request): JsonResponse
    {
        CartItem::where('session_id', $request->session()->getId())->delete();
        return response()->json(['success' => true]);
    }

    /* ============================================================
       VERIFY VOUCHER — vérifie le bon et sauvegarde en session
    ============================================================ */
    public function verifyVoucher(Request $request): JsonResponse
    {
        $code    = strtoupper(trim($request->input('code', '')));
        $voucher = \App\Models\GiftVoucher::where('code', $code)->first();

        if (!$voucher || !$voucher->isUsable()) {
            return response()->json([
                'valid'   => false,
                'message' => 'Bon invalide, épuisé ou expiré.',
            ], 422);
        }

        $request->session()->put('voucher_code',    $voucher->code);
        $request->session()->put('voucher_id',      $voucher->id);
        $request->session()->put('voucher_balance', (float)$voucher->balance);

        return response()->json([
            'valid'   => true,
            'code'    => $voucher->code,
            'balance' => (float)$voucher->balance,
            'label'   => $voucher->formatted_balance . ' disponibles',
            'message' => '✓ Bon valide — ' . $voucher->formatted_balance . ' disponibles.',
        ]);
    }

    /* ============================================================
       REMOVE VOUCHER
    ============================================================ */
    public function removeVoucher(Request $request): JsonResponse
    {
        $request->session()->forget(['voucher_code', 'voucher_id', 'voucher_balance']);
        return response()->json(['success' => true]);
    }

}
