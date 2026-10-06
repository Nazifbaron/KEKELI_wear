<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PromoCode;

/** Centralise le calcul des prix du panier et du checkout. */
class CartPricingService
{
    /**
     * Une promotion produit prévaut sur un code promo (pas de cumul).
     * Le code promo ne cible que sa catégorie, ou toutes si category_id est nul.
     *
     * @return array{base_price: float, unit_price: float, discount_source: ?string}
     */
    public function priceFor(Product $product, ?PromoCode $promoCode = null): array
    {
        $basePrice = (float) ($product->price ?? 0);

        if ($product->is_currently_on_sale) {
            return [
                'base_price' => $basePrice,
                'unit_price' => (float) $product->sale_price,
                'discount_source' => 'product_sale',
            ];
        }

        if ($promoCode && $promoCode->appliesToProduct($product) && $basePrice > 0) {
            $discount = $promoCode->calculateDiscount($basePrice);

            if ($discount > 0) {
                return [
                    'base_price' => $basePrice,
                    'unit_price' => max(0, $basePrice - $discount),
                    'discount_source' => 'promo_code',
                ];
            }
        }

        return [
            'base_price' => $basePrice,
            'unit_price' => $basePrice,
            'discount_source' => null,
        ];
    }
}
