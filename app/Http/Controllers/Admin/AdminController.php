<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Measurement;
use App\Models\PromoCode;
use App\Models\Review;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /*
    |==========================================================
    | DASHBOARD
    | Vue d'ensemble — stats, top produits, dernières commandes
    |==========================================================
    */
    public function dashboard()
    {
        return view('admin.dashboard.index', [
            // Chiffres clés
            'total_products'     => Product::active()->count(),
            'total_orders'       => Order::count(),
            'total_paid'         => Order::where('payment_status', 'paid')->count(),
            'revenue'            => Order::where('payment_status', 'paid')->sum('total'),
            'total_measurements' => Measurement::count(),
            'total_likes'        => Product::sum('likes'),

            // Top 5 produits par score
            'top_products'  => Product::active()
                                    ->orderByDesc('heart_score')
                                    ->take(5)
                                    ->with('category')
                                    ->get(),

            // 10 dernières commandes
            'latest_orders' => Order::latest()->take(10)->get(),

            // Mensurations non traitées
            'pending_measurements' => Measurement::where('status', 'received')->latest()->take(5)->get(),
        ]);
    }

    /*
    |==========================================================
    | PRODUCTS — CRUD
    |==========================================================
    */

    public function products()
    {
        $products = Product::with('category')
                           ->latest()
                           ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::active()->get();
        return view('admin.products.form', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string',
            'price'        => 'nullable|numeric|min:0',
            'badge'        => 'nullable|string|max:60',
            'badge_color'  => 'in:red,gold,blue',
            'is_custom'    => 'boolean',
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'stock'        => 'nullable|integer|min:0',
            'main_image'   => 'nullable|image|max:5120',
        ]);

        // Génération du slug unique
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        // Upload image principale
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')
                                          ->store('products', 'public');
        }

        $product = Product::create($data);
        $product->recalculateScore();

        return redirect()->route('admin.products')
                         ->with('success', '✦ Produit "' . $product->name . '" créé avec succès.');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string',
            'price'        => 'nullable|numeric|min:0',
            'badge'        => 'nullable|string|max:60',
            'badge_color'  => 'in:red,gold,blue',
            'is_custom'    => 'boolean',
            'is_active'    => 'boolean',
            'is_featured'  => 'boolean',
            'stock'        => 'nullable|integer|min:0',
            'main_image'   => 'nullable|image|max:5120',
        ]);

        // Remplacement image si nouvelle fournie
        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')
                                          ->store('products', 'public');
        }

        $product->update($data);
        $product->recalculateScore();

        return redirect()->route('admin.products')
                         ->with('success', '✦ Produit mis à jour.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }

    /*
    |==========================================================
    | ORDERS — Suivi des commandes
    |==========================================================
    */

    public function orders(Request $request)
    {
        $orders = Order::with('items')
                       ->when($request->status, fn($q) => $q->where('status', $request->status))
                       ->when($request->payment, fn($q) => $q->where('payment_status', $request->payment))
                       ->latest()
                       ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load('items.product', 'promoCode', 'measurement');
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled,refunded',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Statut commande mis à jour → ' . $order->status_label);
    }

    /*
    |==========================================================
    | PROMO CODES
    |==========================================================
    */

    public function promoCodes()
    {
        $promoCodes = PromoCode::latest()->paginate(15);
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function storePromoCode(Request $request)
    {
        $data = $request->validate([
            'code'       => 'required|string|max:30|unique:promo_codes,code',
            'discount'   => 'required|numeric|min:1|max:100',
            'type'       => 'in:percentage,fixed',
            'max_uses'   => 'nullable|integer|min:1',
            'starts_at'  => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active'  => 'boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        PromoCode::create($data);

        return redirect()->route('admin.promo-codes')
                         ->with('success', 'Code promo ' . $data['code'] . ' créé.');
    }

    public function togglePromoCode(PromoCode $promo)
    {
        $promo->update(['is_active' => !$promo->is_active]);
        return back()->with('success', 'Code promo ' . ($promo->is_active ? 'activé' : 'désactivé') . '.');
    }

    /*
    |==========================================================
    | MEASUREMENTS — Suivi des mensurations reçues
    |==========================================================
    */

    public function measurements()
    {
        $measurements = Measurement::with('product')
                                   ->latest()
                                   ->paginate(20);

        return view('admin.measurements.index', compact('measurements'));
    }

    public function updateMeasurementStatus(Request $request, Measurement $measurement)
    {
        $request->validate([
            'status' => 'required|in:received,processing,ordered',
        ]);

        $measurement->update(['status' => $request->status]);

        return back()->with('success', 'Statut mensuration mis à jour.');
    }

    /*
    |==========================================================
    | REVIEWS — Modération des avis clients
    |==========================================================
    */

    public function reviews()
    {
        $reviews = Review::latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approveReview(Review $review)
    {
        // Toggle approbation
        $review->update(['is_approved' => !$review->is_approved]);

        return back()->with('success', 'Avis ' . ($review->is_approved ? 'approuvé' : 'masqué') . '.');
    }

    /*
    |==========================================================
    | CATEGORIES — Édition des 4 catégories (image, description…)
    |==========================================================
    */

    public function categories()
    {
        $categories = Category::active()
            ->withCount(['products as product_count' => fn($q) => $q->where('is_active', true)])
            ->orderBy('order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'description' => 'nullable|string|max:300',
            'icon'        => 'nullable|string|max:4',
            'color'       => 'nullable|string|max:7',
            'order'       => 'nullable|integer|min:1|max:10',
            'image'       => 'nullable|image|max:5120',
        ]);

        /*
        |----------------------------------------------------------
        | Gestion de l'image :
        | 1. Si une nouvelle image est uploadée → stocker + mettre à jour
        | 2. Si "remove_image" coché → supprimer l'image actuelle
        | 3. Sinon → ne pas toucher à l'image
        |----------------------------------------------------------
        */
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')
                                     ->store('categories', 'public');
        } elseif ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        } else {
            // Ne pas écraser l'image si aucune action
            unset($data['image']);
        }

        $category->update($data);

        return redirect()->route('admin.categories')
                         ->with('success', '✦ Catégorie "' . $category->name . '" mise à jour.');
    }

    /*
    |==========================================================
    | HERO SLIDES — Gestion des slides du carrousel
    | L'admin peut ajouter autant de slides qu'il veut,
    | changer les images, titres, boutons, ordre
    |==========================================================
    */

    public function heroSlides()
    {
        $slides = HeroSlide::orderBy('order')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function storeHeroSlide(Request $request)
    {
        $data = $request->validate([
            'tag'                  => 'required|string|max:60',
            'title'                => 'required|string|max:120',
            'title_highlight'      => 'nullable|string|max:80',
            'subtitle'             => 'nullable|string|max:300',
            'btn_primary_label'    => 'nullable|string|max:60',
            'btn_primary_url'      => 'nullable|string|max:200',
            'btn_secondary_label'  => 'nullable|string|max:60',
            'btn_secondary_url'    => 'nullable|string|max:200',
            'overlay_color'        => 'in:red,blue,purple',
            'order'                => 'integer|min:0',
            'is_active'            => 'boolean',
            'image'                => 'nullable|image|max:8192', // 8MB max
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero', 'public');
        }

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides')
                         ->with('success', 'Slide héro créée.');
    }

    public function updateHeroSlide(Request $request, HeroSlide $slide)
    {
        $data = $request->validate([
            'tag'                  => 'required|string|max:60',
            'title'                => 'required|string|max:120',
            'title_highlight'      => 'nullable|string|max:80',
            'subtitle'             => 'nullable|string|max:300',
            'btn_primary_label'    => 'nullable|string|max:60',
            'btn_primary_url'      => 'nullable|string|max:200',
            'btn_secondary_label'  => 'nullable|string|max:60',
            'btn_secondary_url'    => 'nullable|string|max:200',
            'overlay_color'        => 'in:red,blue,purple',
            'order'                => 'integer|min:0',
            'is_active'            => 'boolean',
            'image'                => 'nullable|image|max:8192',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($slide->image) Storage::disk('public')->delete($slide->image);
            $data['image'] = $request->file('image')->store('hero', 'public');
        }

        $slide->update($data);

        return redirect()->route('admin.hero-slides')
                         ->with('success', 'Slide mise à jour.');
    }

    public function destroyHeroSlide(HeroSlide $slide)
    {
        if ($slide->image) Storage::disk('public')->delete($slide->image);
        $slide->delete();

        return back()->with('success', 'Slide supprimée.');
    }

    public function toggleHeroSlide(HeroSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);
        return back()->with('success', 'Slide ' . ($slide->is_active ? 'activée' : 'désactivée') . '.');
    }
}
