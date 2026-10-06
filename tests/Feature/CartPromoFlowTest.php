<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CartPromoFlowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $sessionPath = storage_path('framework/testing/sessions');
        File::ensureDirectoryExists($sessionPath);
        config(['session.driver' => 'file', 'session.files' => $sessionPath]);
        $this->withCredentials();
    }

    public function test_cart_add_is_retrievable_and_category_promo_is_applied_only_to_matching_items(): void
    {
        $category = Category::create([
            'name' => 'Promo ' . uniqid(),
            'slug' => 'promo-' . uniqid(),
            'is_active' => true,
        ]);
        $otherCategory = Category::create([
            'name' => 'Other ' . uniqid(),
            'slug' => 'other-' . uniqid(),
            'is_active' => true,
        ]);
        $eligible = $this->createProduct($category, 30000);
        $ineligible = $this->createProduct($otherCategory, 25000);
        $promo = PromoCode::create([
            'code' => strtoupper('TEST' . substr(uniqid(), -6)),
            'category_id' => $category->id,
            'discount' => 10,
            'type' => 'percentage',
            'is_active' => true,
        ]);

        $addResponse = $this->postJson('/api/cart/add', ['product_id' => $eligible->id])
            ->assertOk()
            ->assertJsonPath('total', '1');
        $cookieName = config('session.cookie');
        $sessionCookie = $addResponse->getCookie($cookieName)->getValue();
        $this->withCookie($cookieName, $sessionCookie);

        $this->postJson('/api/cart/add', ['product_id' => $ineligible->id])
            ->assertOk()
            ->assertJsonPath('total', '2');

        $this->withCookie($cookieName, $sessionCookie);
        $this->getJson('/api/cart')
            ->assertOk()
            ->assertJsonCount(2, 'items');

        $this->withCookie($cookieName, $sessionCookie);
        $this->postJson('/api/promo/verify', ['code' => $promo->code])
            ->assertOk()
            ->assertJsonPath('valid', true)
            ->assertJsonPath('category_id', $category->id);

        $this->withCookie($cookieName, $sessionCookie);
        $this->get('/produit/' . $eligible->slug)
            ->assertOk()
            ->assertSee('30 000 XOF')
            ->assertSee('27 000 XOF')
            ->assertSee('CODE PROMO');

        $this->withCookie($cookieName, $sessionCookie);
        $this->get('/produit/' . $ineligible->slug)
            ->assertOk()
            ->assertSee('25 000 XOF')
            ->assertDontSee('CODE PROMO');

        $this->withCookie($cookieName, $sessionCookie);
        $cart = $this->getJson('/api/cart')->assertOk();
        $items = collect($cart->json('items'))->keyBy('id');

        self::assertSame(27000.0, (float) $items[$eligible->id]['raw_reduced']);
        self::assertSame(25000.0, (float) $items[$ineligible->id]['raw_reduced']);
        self::assertSame(52000, $cart->json('amount'));
    }

    private function createProduct(Category $category, int $price): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => 'Produit ' . uniqid(),
            'slug' => 'produit-' . uniqid(),
            'price' => $price,
            'is_active' => true,
        ]);
    }
}
