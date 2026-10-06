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
use Illuminate\Validation\Rule;

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
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:150',
            'description'      => 'nullable|string',
            'fit_details'      => 'nullable|string|max:5000',
            'price'            => 'nullable|numeric|min:0',
            'is_on_sale'       => 'boolean',
            'sale_price'       => 'nullable|numeric|min:0',
            'sale_ends_at'     => 'nullable|date|after:now',
            'badge'            => 'nullable|string|max:60',
            'badge_color'      => 'in:red,gold,blue',
            'is_custom'        => 'boolean',
            'is_active'        => 'boolean',
            'is_featured'      => 'boolean',
            'stock'            => 'nullable|integer|min:0',
            'main_image'       => 'nullable|image|max:5120',
            'extra_images'     => 'nullable|array|max:5',
            'extra_images.*'   => 'image|max:5120',

        ]);

        $data['slug']      = \Illuminate\Support\Str::slug($data['name']) . '-' . \Illuminate\Support\Str::random(5);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_custom'] = $request->boolean('is_custom');
        $data['is_featured']= $request->boolean('is_featured');
        $data['is_on_sale']= $request->boolean('is_on_sale');

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $product = \App\Models\Product::create($data);

            /* Images supplémentaires — uploadées dès la création */
            if ($request->hasFile('extra_images')) {
                $order = 0;
                foreach ($request->file('extra_images') as $img) {
                    if ($order >= 5) break;
                    \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $img->store('products', 'public'),
                        'order'      => $order++,
                    ]);
                }
            }

            $product->recalculateScore();
            \Illuminate\Support\Facades\DB::commit();

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.products')
                         ->with('success', '✦ Produit "' . $product->name . '" créé avec succès.');
    }

    public function editProduct(Product $product)
    {
        $product->load('extraImages');
        $categories = Category::active()->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, \App\Models\Product $product)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:150',
            'description'      => 'nullable|string',
            'fit_details'      => 'nullable|string|max:5000',
            'price'            => 'nullable|numeric|min:0',
            'is_on_sale'       => 'boolean',
            'sale_price'       => 'nullable|numeric|min:0',
            'sale_ends_at'     => 'nullable|date',
            'badge'            => 'nullable|string|max:60',
            'badge_color'      => 'in:red,gold,blue',
            'is_custom'        => 'boolean',
            'is_active'        => 'boolean',
            'is_featured'      => 'boolean',
            'stock'            => 'nullable|integer|min:0',
            'main_image'       => 'nullable|image|max:5120',
            'extra_images'     => 'nullable|array|max:5',
            'extra_images.*'   => 'image|max:5120',

        ]);

        $data['is_active']  = $request->boolean('is_active');
        $data['is_custom']  = $request->boolean('is_custom');
        $data['is_featured']= $request->boolean('is_featured');
        $data['is_on_sale'] = $request->boolean('is_on_sale');

        if ($request->hasFile('main_image')) {
            if ($product->main_image) \Illuminate\Support\Facades\Storage::disk('public')->delete($product->main_image);
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $product->update($data);

            /* Nouvelles images supplémentaires */
            if ($request->hasFile('extra_images')) {
                $currentCount = $product->extraImages()->count();
                $order        = $currentCount;
                foreach ($request->file('extra_images') as $img) {
                    if ($order >= 5) break; /* Max 5 supplémentaires */
                    \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $img->store('products', 'public'),
                        'order'      => $order++,
                    ]);
                }
            }

            $product->recalculateScore();
            \Illuminate\Support\Facades\DB::commit();

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.products')
                         ->with('success', '✦ Produit "' . $product->name . '" mis à jour.');
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
        $promoCodes = PromoCode::with('category')->latest()->paginate(15);
        $categories = Category::active()->get();
        return view('admin.promo-codes.index', compact('promoCodes', 'categories'));
    }


    public function storePromoCode(Request $request)
    {
        $data = $request->validate([
            'code'        => 'required|string|max:30|unique:promo_codes,code',
            'category_id' => 'nullable|exists:categories,id',
            'discount'    => [
                'required', 'numeric', 'min:1',
                Rule::when($request->input('type', 'percentage') === 'percentage', ['max:100']),
            ],
            'type'        => 'required|in:percentage,fixed',
            'max_uses'    => 'nullable|integer|min:1',
            'starts_at'   => 'nullable|date',
            'expires_at'  => 'nullable|date|after:starts_at',
            'is_active'   => 'boolean',
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

    public function reviews(Request $request)
    {
        $filter = $request->query('filter');
        $reviews = Review::query()
            ->when($filter === 'pending', fn($query) => $query->where('is_approved', false))
            ->when($filter === 'approved', fn($query) => $query->where('is_approved', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $approvedCount = Review::where('is_approved', true)->count();
        $pendingCount = Review::where('is_approved', false)->count();
        $averageRating = Review::approved()->avg('rating');
        $avgRating = $averageRating === null ? '—' : number_format((float) $averageRating, 1);

        return view('admin.reviews.index', compact(
            'reviews', 'approvedCount', 'pendingCount', 'avgRating'
        ));
    }

    public function approveReview(Review $review)
    {
        // Toggle approbation
        $review->update(['is_approved' => !$review->is_approved]);

        return back()->with('success', 'Avis ' . ($review->is_approved ? 'approuvé' : 'masqué') . '.');
    }

    public function destroyReview(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Avis supprimé.');
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

}
