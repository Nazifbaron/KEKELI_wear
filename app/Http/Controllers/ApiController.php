<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use App\Models\PromoCode;
use App\Models\Measurement;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /*
    |----------------------------------------------------------
    | STATS GLOBALES
    | Appelée au chargement + toutes les 60s via setInterval
    | Pour mettre à jour la barre stats et les compteurs
    |----------------------------------------------------------
    */
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

    /*
    |----------------------------------------------------------
    | TOGGLE LIKE — 1 like par session par produit
    | Incrémente/décrémente + recalcule le score coup de cœur
    |----------------------------------------------------------
    */
    public function toggleLike(Request $request, int $id): JsonResponse
    {
        $product   = Product::active()->findOrFail($id);
        $sessionId = $request->session()->getId();

        $existing = ProductLike::where('product_id', $id)
                               ->where('session_id', $sessionId)
                               ->first();

        if ($existing) {
            // Unlike
            $existing->delete();
            $product->decrement('likes');
            $liked = false;
        } else {
            // Like
            ProductLike::create([
                'product_id'  => $id,
                'session_id'  => $sessionId,
                'ip_address'  => $request->ip(),
            ]);
            $product->increment('likes');
            $liked = true;
        }

        // Recalcule heart_score + is_featured auto
        $product->recalculateScore();

        return response()->json([
            'liked' => $liked,
            'likes' => $product->fresh()->likes,
        ]);
    }

    /*
    |----------------------------------------------------------
    | TRACK VIEW — incrémentée au survol du produit (mouseenter)
    | Evite les appels multiples grâce au flag JS `tracked`
    |----------------------------------------------------------
    */
    public function trackView(int $id): JsonResponse
    {
        $product = Product::active()->findOrFail($id);
        $product->incrementViews();

        return response()->json(['views' => $product->fresh()->views]);
    }

    /*
    |----------------------------------------------------------
    | VERIFY PROMO CODE
    | Vérifie validité + retourne le % ou montant de remise
    |----------------------------------------------------------
    */
    public function verifyPromo(Request $request): JsonResponse
    {
        $code  = strtoupper(trim($request->input('code', '')));
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json([
                'valid'   => false,
                'message' => 'Code invalide ou expiré.',
            ], 422);
        }

        return response()->json([
            'valid'    => true,
            'discount' => $promo->discount,
            'type'     => $promo->type,
            'label'    => $promo->label,
            'message'  => $promo->label . ' appliqué !',
        ]);
    }

    /*
    |----------------------------------------------------------
    | SAVE MEASUREMENT
    | Enregistre les mensurations en BD avant envoi WhatsApp
    | Détecte automatiquement la morphologie si non fournie
    |----------------------------------------------------------
    */
    public function saveMeasurement(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'    => 'required|string|max:120',
            'whatsapp'     => 'required|string|max:20',
            'morphology'   => 'nullable|string|max:30',
            'back_size'    => 'nullable|numeric|min:20|max:200',
            'chest'        => 'nullable|numeric|min:40|max:200',
            'waist'        => 'nullable|numeric|min:30|max:200',
            'hips'         => 'nullable|numeric|min:50|max:250',
            'height'       => 'nullable|numeric|min:100|max:230',
            'dress_length' => 'nullable|numeric|min:30|max:200',
            'top_length'   => 'nullable|numeric|min:20|max:120',
            'skirt_length' => 'nullable|numeric|min:20|max:150',
            'notes'        => 'nullable|string|max:500',
            'product_id'   => 'nullable|exists:products,id',
        ]);

        $measurement = Measurement::create($validated);

        // Détection auto si morphologie non fournie par le front
        if (empty($measurement->morphology)) {
            $detected = $measurement->detectMorphology();
            $measurement->update(['morphology' => $detected]);
        }

        return response()->json([
            'success'    => true,
            'morphology' => Measurement::morphologyLabel($measurement->morphology),
            'message'    => 'Mensurations enregistrées.',
        ]);
    }

    /*
    |----------------------------------------------------------
    | CART — GET
    | Retourne les items du panier pour la session courante
    |----------------------------------------------------------
    */
    public function getCart(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();

        $items = CartItem::where('session_id', $sessionId)
            ->with('product.category')
            ->get()
            ->map(fn($i) => [
                'id'        => $i->product_id,
                'name'      => $i->product->name,
                'category'  => $i->product->category->name,
                'price'     => $i->product->formatted_price,
                'raw_price' => $i->product->price,
                'image'     => $i->product->main_image,
                'quantity'  => $i->quantity,
                'is_custom' => $i->product->is_custom,
                'subtotal'  => $i->subtotal,
            ]);

        return response()->json([
            'items' => $items,
            'total' => $items->sum('quantity'),
            'amount'=> $items->sum('subtotal'),
        ]);
    }

    /*
    |----------------------------------------------------------
    | CART — ADD
    | Ajoute un produit au panier (ou incrémente la quantité)
    |----------------------------------------------------------
    */
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

    /*
    |----------------------------------------------------------
    | CART — UPDATE QTY
    | Met à jour la quantité d'un produit dans le panier
    |----------------------------------------------------------
    */
    public function updateQty(Request $request, int $productId): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);

        $sessionId = $request->session()->getId();

        CartItem::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true]);
    }

    /*
    |----------------------------------------------------------
    | CART — REMOVE
    | Supprime un produit du panier
    |----------------------------------------------------------
    */
    public function removeFromCart(Request $request, int $productId): JsonResponse
    {
        $sessionId = $request->session()->getId();

        CartItem::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->delete();

        return response()->json([
            'total' => CartItem::where('session_id', $sessionId)->sum('quantity'),
        ]);
    }

    /*
    |----------------------------------------------------------
    | CART — CLEAR
    | Vide complètement le panier
    |----------------------------------------------------------
    */
    public function clearCart(Request $request): JsonResponse
    {
        CartItem::where('session_id', $request->session()->getId())->delete();
        return response()->json(['success' => true]);
    }
}
