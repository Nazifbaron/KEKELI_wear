<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |----------------------------------------------------------
        | Catégories actives avec compteur produits
        | Utilisées pour les 4 univers + les filtres boutique
        |----------------------------------------------------------
        */
        $categories = Category::active()
            ->withCount(['products as product_count' => fn($q) => $q->where('is_active', true)])
            ->get();

        /*
        |----------------------------------------------------------
        | Tous les produits actifs avec leur catégorie
        | Triés par score coup de cœur décroissant
        |----------------------------------------------------------
        */
        $products = Product::active()
            ->with('category')
            ->orderByDesc('heart_score')
            ->get();

        /*
        |----------------------------------------------------------
        | Coups de cœur — auto-détectés (is_featured = true)
        | + admin override possible depuis le backoffice
        |----------------------------------------------------------
        */
        $featured = Product::featured(config('kekeli.max_featured', 6))
            ->with('category')
            ->get();

        /*
        |----------------------------------------------------------
        | Slides du hero carousel
        | Si l'admin n'a pas encore configuré de slides,
        | on passe un tableau vide et la vue utilisera
        | les slides par défaut codés en dur (fallback).
        |----------------------------------------------------------
        */
        $slides = HeroSlide::where('is_active', true)
            ->orderBy('order')
            ->get();

        /*
        |----------------------------------------------------------
        | Avis clients validés + note moyenne
        |----------------------------------------------------------
        */
        $reviews    = Review::approved()->latest()->take(6)->get();
        $avgRating  = Review::averageRating();

        /*
        |----------------------------------------------------------
        | Stats globales pour la barre de stats (nav)
        | Ces données sont aussi exposées via /api/stats
        | pour le refresh AJAX toutes les 60s
        |----------------------------------------------------------
        */
        $stats = [
            'total_products' => Product::active()->count(),
            'total_likes'    => Product::active()->sum('likes'),
            'total_views'    => Product::active()->sum('views'),
            'avg_rating'     => $avgRating,
            'per_category'   => $categories->pluck('product_count', 'slug'),
        ];

        return view('home', compact(
            'categories',
            'products',
            'featured',
            'slides',
            'reviews',
            'avgRating',
            'stats'
        ));
    }
}
