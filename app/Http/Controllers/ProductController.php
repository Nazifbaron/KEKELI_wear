<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\PromoCode;
use App\Services\CartPricingService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
    |----------------------------------------------------------
    | PAGE CATALOGUE — Tous les produits
    | Filtrables par catégorie via ?cat=slug
    | Paginés par 24 (chiffre raisonnable pour une grille 4 cols)
    |----------------------------------------------------------
    */
    public function index(Request $request)
    {
        $categories = Category::active()
            ->withCount(['products as product_count' => fn($q) =>
                $q->where('is_active', true)
            ])
            ->get();

        $query = Product::active()->with(['category', 'extraImages']);

        /* Filtre catégorie depuis ?cat=slug */
        if ($request->filled('cat')) {
            $query->whereHas('category', fn($q) =>
                $q->where('slug', $request->cat)
            );
        }

        $products = $query->orderByDesc('heart_score')->paginate(24)->withQueryString();

        $currentCat = $request->cat;

        return view('products.index', compact('products', 'categories', 'currentCat'));
    }

    /*
    |----------------------------------------------------------
    | PAGE DÉTAIL PRODUIT
    | - Galerie d'images (principale + jusqu'à 5 supplémentaires)
    | - Description complète
    | - Notes morphologie
    | - Bouton "Ajouter au panier" ou "Envoyer mes mesures"
    | - Produits similaires (même catégorie, max 4)
    |----------------------------------------------------------
    */
    public function show(Request $request, CartPricingService $pricing, string $slug)
    {
        $product = Product::active()
            ->with(['category', 'extraImages'])
            ->where('slug', $slug)
            ->firstOrFail();

        /* Incrémenter les vues */
        $product->incrementViews();

        /* Produits similaires — même catégorie, pas le produit actuel */
        $related = Product::active()
            ->with(['category', 'extraImages'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('heart_score')
            ->take(4)
            ->get();

        $promoCode = $request->session()->get('promo_code_id')
            ? PromoCode::with('category')->find($request->session()->get('promo_code_id'))
            : null;

        if ($promoCode && !$promoCode->isValid()) {
            $promoCode = null;
            $request->session()->forget('promo_code_id');
        }

        $detailPricing = $pricing->priceFor($product, $promoCode);
        $detailPromo = $detailPricing['discount_source'] === 'promo_code' ? $promoCode : null;

        return view('products.show', compact('product', 'related', 'detailPricing', 'detailPromo'));
    }
}
