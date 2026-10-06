<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\GiftVoucherController;

/*
|--------------------------------------------------------------------------
| FRONT — One-page principale
|--------------------------------------------------------------------------
*/
Route::get('/bon', [GiftVoucherController::class, 'index'])->name('bon');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/boutique', [\App\Http\Controllers\ProductController::class, 'index'])->name('boutique');
Route::get('/produit/{slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::view('/view', 'view')->name('view');
/*
|--------------------------------------------------------------------------
| API — Requêtes AJAX front (likes, vues, promo, panier, mensurations)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {

    Route::get ('stats',                  [ApiController::class, 'stats'])->name('stats');

    // Détail commande pour checkout/payment et checkout/confirmation
    Route::get ('order/{ref}',            [ApiController::class, 'getOrder'])->name('order.get');

    // Produits — likes et vues sans connexion (via session_id)
    Route::post('products/{id}/like',     [ApiController::class, 'toggleLike'])->name('products.like');
    Route::post('products/{id}/view',     [ApiController::class, 'trackView'])->name('products.view');

    // Promo — vérifier ET sauvegarder en session
    Route::post('promo/verify',           [ApiController::class, 'verifyPromo'])->name('promo.verify');
    Route::delete('promo',                [ApiController::class, 'removePromo'])->name('promo.remove');

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

    /*
    | Étape 1 — Récapitulatif panier + infos client
    | GET  → afficher la vue summary
    | POST → créer la commande en BD
    */
    Route::get('summary', [
        \App\Http\Controllers\CheckoutController::class,
        'summary'
    ])->name('summary');

    Route::post('/', [
        \App\Http\Controllers\CheckoutController::class,
        'store'
    ])->name('store');

    Route::get('payment/{ref}', [
        \App\Http\Controllers\CheckoutController::class,
        'paymentPage'
    ])->name('payment.gateway');

    Route::post('payment/{ref}/callback', [
        \App\Http\Controllers\CheckoutController::class,
        'paymentCallback'
    ])->name('payment.callback');

    Route::get('confirmation/{ref}', [
        \App\Http\Controllers\CheckoutController::class,
        'confirmation'
    ])->name('confirmation');

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
    // Univers / Catégories — CRUD complet
    Route::get   ('categories',               [\App\Http\Controllers\Admin\CategoryController::class, 'index'])  ->name('categories');
    Route::post  ('categories',               [\App\Http\Controllers\Admin\CategoryController::class, 'store'])  ->name('categories.store');
    Route::put   ('categories/{category}',    [\App\Http\Controllers\Admin\CategoryController::class, 'update']) ->name('categories.update');
    Route::patch ('categories/{category}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggle']) ->name('categories.toggle');
    Route::delete('categories/{category}',    [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Produits
    Route::get   ('products',                         [AdminController::class, 'products'])->name('products');
    Route::get   ('products/create',                  [AdminController::class, 'createProduct'])->name('products.create');
    Route::post  ('products',                         [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get   ('products/{product}/edit',          [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put   ('products/{product}',               [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('products/{product}',               [AdminController::class, 'destroyProduct'])->name('products.destroy');

    // Hero slides — CRUD complet (ajouter update qui manquait)
    Route::get   ('hero-slides',                   [\App\Http\Controllers\Admin\HeroSlideController::class, 'index'])  ->name('hero-slides');
    Route::post  ('hero-slides',                   [\App\Http\Controllers\Admin\HeroSlideController::class, 'store'])  ->name('hero-slides.store');
    Route::put   ('hero-slides/{slide}',           [\App\Http\Controllers\Admin\HeroSlideController::class, 'update']) ->name('hero-slides.update');
    Route::patch ('hero-slides/{slide}/toggle',    [\App\Http\Controllers\Admin\HeroSlideController::class, 'toggle']) ->name('hero-slides.toggle');
    Route::delete('hero-slides/{slide}',           [\App\Http\Controllers\Admin\HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');

    // Commandes
    Route::get  ('orders',                            [AdminController::class, 'orders'])->name('orders');
    Route::get  ('orders/{order}',                    [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('orders/{order}/status',             [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Codes promo
    Route::get  ('promo-codes',                       [AdminController::class, 'promoCodes'])->name('promo-codes');
    Route::post ('promo-codes',                       [AdminController::class, 'storePromoCode'])->name('promo-codes.store');
    Route::patch('promo-codes/{promo}/toggle',        [AdminController::class, 'togglePromoCode'])->name('promo-codes.toggle');

    /* ---- Routes front bons d'achat ---- */
    Route::get ('bons-dachat',                  [\App\Http\Controllers\GiftVoucherController::class, 'index'])        ->name('vouchers.index');
    Route::post('bons-dachat',                  [\App\Http\Controllers\GiftVoucherController::class, 'store'])        ->name('vouchers.store');
    Route::get ('bons-dachat/confirmation/{code}', [\App\Http\Controllers\GiftVoucherController::class, 'confirmation'])->name('vouchers.confirmation');

    /* ---- Routes API voucher (dans le groupe /api) ---- */
    Route::post  ('voucher/verify',  [\App\Http\Controllers\ApiController::class, 'verifyVoucher']) ->name('api.voucher.verify');
    Route::delete('voucher',         [\App\Http\Controllers\ApiController::class, 'removeVoucher']) ->name('api.voucher.remove');

    /* ---- Routes admin bons d'achat (dans le groupe /admin protégé) ---- */
    Route::get  ('vouchers',                  [\App\Http\Controllers\Admin\VoucherController::class, 'index'])  ->name('vouchers');
    Route::post ('vouchers',                  [\App\Http\Controllers\Admin\VoucherController::class, 'store'])  ->name('vouchers.store');
    Route::patch('vouchers/{voucher}/cancel', [\App\Http\Controllers\Admin\VoucherController::class, 'cancel']) ->name('vouchers.cancel');

    // Mensurations
    Route::get  ('measurements',                      [AdminController::class, 'measurements'])->name('measurements');
    Route::patch('measurements/{measurement}/status', [AdminController::class, 'updateMeasurementStatus'])->name('measurements.status');

    // Avis
    Route::get  ('reviews',                           [AdminController::class, 'reviews'])->name('reviews');
    Route::patch('reviews/{review}/approve',          [AdminController::class, 'approveReview'])->name('reviews.approve');
    Route::delete('reviews/{review}',                 [AdminController::class, 'destroyReview'])->name('reviews.destroy');

    Route::post('products/{product}/images', [\App\Http\Controllers\Admin\ProductImageController::class, 'upload'])
     ->name('products.images.upload');
    Route::delete('products/images/{image}', [\App\Http\Controllers\Admin\ProductImageController::class, 'delete'])
     ->name('products.image.delete');
});


