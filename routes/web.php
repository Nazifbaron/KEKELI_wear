<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| FRONT — One-page principale
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| API — Requêtes AJAX front (likes, vues, promo, panier, mensurations)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {

    Route::get ('stats',                  [ApiController::class, 'stats'])->name('stats');

    // Produits — likes et vues sans connexion (via session_id)
    Route::post('products/{id}/like',     [ApiController::class, 'toggleLike'])->name('products.like');
    Route::post('products/{id}/view',     [ApiController::class, 'trackView'])->name('products.view');

    // Promo
    Route::post('promo/verify',           [ApiController::class, 'verifyPromo'])->name('promo.verify');

    // Mensurations — sauvegardées en BD avant envoi WhatsApp
    Route::post('measurements',           [ApiController::class, 'saveMeasurement'])->name('measurements.save');

    // Panier — géré par session sans compte client
    Route::get ('cart',                   [ApiController::class, 'getCart'])->name('cart.get');
    Route::post('cart/add',               [ApiController::class, 'addToCart'])->name('cart.add');
    Route::patch('cart/{productId}/qty',  [ApiController::class, 'updateQty'])->name('cart.qty');
    Route::delete('cart/{productId}',     [ApiController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('cart',                 [ApiController::class, 'clearCart'])->name('cart.clear');
});

/*
|--------------------------------------------------------------------------
| AVIS CLIENTS — soumission depuis le front sans connexion
|--------------------------------------------------------------------------
*/
Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');

/*
|--------------------------------------------------------------------------
| CHECKOUT + PAIEMENT
|--------------------------------------------------------------------------
*/
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get ('summary',                   [CheckoutController::class, 'summary'])->name('summary');
    Route::post('/',                         [CheckoutController::class, 'store'])->name('store');
    Route::get ('payment/{ref}',             fn($ref) => view('checkout.payment', compact('ref')))->name('payment.gateway');
    Route::post('payment/{ref}/callback',    [CheckoutController::class, 'paymentCallback'])->name('payment.callback');
    Route::get ('confirmation/{ref}',        fn($ref) => view('checkout.confirmation', compact('ref')))->name('confirmation');
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH — login/logout (sans middleware admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get ('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login',  [AuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN — routes protégées par AdminMiddleware
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin'])->group(function () {

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Catégories
    Route::get('categories',                          [AdminController::class, 'categories'])->name('categories');
    Route::put('categories/{category}',               [AdminController::class, 'updateCategory'])->name('categories.update');

    // Produits
    Route::get   ('products',                         [AdminController::class, 'products'])->name('products');
    Route::get   ('products/create',                  [AdminController::class, 'createProduct'])->name('products.create');
    Route::post  ('products',                         [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get   ('products/{product}/edit',          [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put   ('products/{product}',               [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('products/{product}',               [AdminController::class, 'destroyProduct'])->name('products.destroy');

    // Hero slides
    Route::get   ('hero-slides',                      [AdminController::class, 'heroSlides'])->name('hero-slides');
    Route::post  ('hero-slides',                      [AdminController::class, 'storeHeroSlide'])->name('hero-slides.store');
    Route::put   ('hero-slides/{slide}',              [AdminController::class, 'updateHeroSlide'])->name('hero-slides.update');
    Route::delete('hero-slides/{slide}',              [AdminController::class, 'destroyHeroSlide'])->name('hero-slides.destroy');
    Route::patch ('hero-slides/{slide}/toggle',       [AdminController::class, 'toggleHeroSlide'])->name('hero-slides.toggle');

    // Commandes
    Route::get  ('orders',                            [AdminController::class, 'orders'])->name('orders');
    Route::get  ('orders/{order}',                    [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('orders/{order}/status',             [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Codes promo
    Route::get  ('promo-codes',                       [AdminController::class, 'promoCodes'])->name('promo-codes');
    Route::post ('promo-codes',                       [AdminController::class, 'storePromoCode'])->name('promo-codes.store');
    Route::patch('promo-codes/{promo}/toggle',        [AdminController::class, 'togglePromoCode'])->name('promo-codes.toggle');

    // Mensurations
    Route::get  ('measurements',                      [AdminController::class, 'measurements'])->name('measurements');
    Route::patch('measurements/{measurement}/status', [AdminController::class, 'updateMeasurementStatus'])->name('measurements.status');

    // Avis
    Route::get  ('reviews',                           [AdminController::class, 'reviews'])->name('reviews');
    Route::patch('reviews/{review}/approve',          [AdminController::class, 'approveReview'])->name('reviews.approve');
});
