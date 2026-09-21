<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /*
    |----------------------------------------------------------
    | Page principale one-page
    | Toutes les données nécessaires aux sections sont chargées
    | ici pour éviter les requêtes AJAX au premier chargement.
    |----------------------------------------------------------
    */
    public function index()
    {
        /* Catégories actives avec compteur produits */
        $categories = Category::active()
            ->withCount(['products as product_count' => fn($q) =>
                $q->where('is_active', true)
            ])
            ->get();

        /* Tous les produits actifs avec leur catégorie */
        $products = Product::active()
            ->with('category')
            ->orderByDesc('heart_score')
            ->get();

        /* Coups de cœur */
        $featured = Product::featured()
            ->with('category')
            ->orderByDesc('heart_score')
            ->take(config('kekeli.max_featured', 6))
            ->get();

        /* Slides hero depuis la BD — fallback 3 slides codées si vide */
        $slides = HeroSlide::where('is_active', true)
            ->orderBy('order')
            ->get();

        /* Avis approuvés */
        $reviews   = Review::approved()->latest()->take(6)->get();
        $avgRating = Review::averageRating() ?: 5.0;

        /* Stats globales pour la barre et le JS */
        $stats = [
            'total_products' => $products->count(),
            'total_likes'    => Product::active()->sum('likes'),
            'total_views'    => Product::active()->sum('views'),
            'avg_rating'     => number_format($avgRating, 1, ',', ''),
            'per_category'   => $categories->pluck('product_count', 'slug'),
        ];

        return view('home', compact(
            'categories', 'products', 'featured',
            'slides', 'reviews', 'avgRating', 'stats'
        ));
    }

    public function about()
    {
        return view('about');
    }
}
