<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\HeroSlide;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->withCount(['products as product_count' => fn($q) =>
                $q->where('is_active', true)
            ])
            ->get();
        /*
        |----------------------------------------------------------
        | HOME — Max 12 produits affichés
        | Les autres sont accessibles via /boutique
        |----------------------------------------------------------
        */
        $products = Product::active()
            ->with(['category', 'extraImages'])
            ->orderByDesc('heart_score')
            ->take(12)
            ->get();

        /* Total réel pour le bouton "Voir tous les X produits" */
        $totalProducts = Product::active()->count();

        $featured = Product::featured()
            ->with(['category', 'extraImages'])
            ->orderByDesc('heart_score')
            ->take(config('kekeli.max_featured', 6))
            ->get();

        $slides = HeroSlide::where('is_active', true)
            ->orderBy('order')
            ->get();

        $reviews   = Review::approved()->latest()->take(6)->get();
        $avgRating = Review::averageRating() ?: 5.0;

        $stats = [
            'total_products' => $totalProducts,
            'total_likes'    => Product::active()->sum('likes'),
            'total_views'    => Product::active()->sum('views'),
            'avg_rating'     => number_format($avgRating, 1, ',', ''),
            'per_category'   => $categories->pluck('product_count', 'slug'),
        ];

        return view('home', compact(
            'categories', 'products', 'featured',
            'slides', 'reviews', 'avgRating', 'stats',
            'totalProducts'
        ));
    }

    public function about()
    {
        return view('about');
    }
}
