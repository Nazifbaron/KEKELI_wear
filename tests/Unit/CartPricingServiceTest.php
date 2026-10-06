<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\PromoCode;
use App\Services\CartPricingService;
use PHPUnit\Framework\TestCase;

class CartPricingServiceTest extends TestCase
{
    public function test_category_code_only_reduces_products_in_its_category(): void
    {
        $pricing = new CartPricingService();
        $promo = new PromoCode(['category_id' => 4, 'discount' => 10, 'type' => 'percentage']);
        $eligible = new Product(['category_id' => 4, 'price' => 30000]);
        $otherCategory = new Product(['category_id' => 8, 'price' => 30000]);

        self::assertSame(27000.0, $pricing->priceFor($eligible, $promo)['unit_price']);
        self::assertSame(30000.0, $pricing->priceFor($otherCategory, $promo)['unit_price']);
    }

    public function test_product_sale_takes_precedence_over_a_promo_code(): void
    {
        $pricing = new CartPricingService();
        $promo = new PromoCode(['category_id' => 4, 'discount' => 10, 'type' => 'percentage']);
        $product = new Product([
            'category_id' => 4,
            'price' => 30000,
            'is_on_sale' => true,
            'sale_price' => 20000,
        ]);

        self::assertSame(20000.0, $pricing->priceFor($product, $promo)['unit_price']);
        self::assertSame('product_sale', $pricing->priceFor($product, $promo)['discount_source']);
    }
}
