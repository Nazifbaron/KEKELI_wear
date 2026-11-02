<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductCatalogFilterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_catalogue_displays_only_products_from_the_selected_category(): void
    {
        $category = Category::create([
            'name' => 'Catégorie test',
            'slug' => 'categorie-test-' . uniqid(),
            'is_active' => true,
        ]);
        $otherCategory = Category::create([
            'name' => 'Autre catégorie test',
            'slug' => 'autre-categorie-test-' . uniqid(),
            'is_active' => true,
        ]);

        $selectedProduct = $this->createProduct($category, 'Produit sélectionné');
        $otherProduct = $this->createProduct($otherCategory, 'Produit hors catégorie');

        $this->get(route('boutique', ['cat' => $category->slug]))
            ->assertOk()
            ->assertSee($selectedProduct->name)
            ->assertDontSee($otherProduct->name);
    }

    public function test_catalogue_products_are_grouped_by_universe_order(): void
    {
        $firstCategory = Category::create([
            'name' => 'Premier univers',
            'slug' => 'premier-univers-' . uniqid(),
            'order' => 1,
            'is_active' => true,
        ]);
        $secondCategory = Category::create([
            'name' => 'Second univers',
            'slug' => 'second-univers-' . uniqid(),
            'order' => 2,
            'is_active' => true,
        ]);

        // Le score supérieur du second produit ne doit pas le faire passer devant le premier univers.
        $firstProduct = $this->createProduct($firstCategory, 'Produit univers un');
        $secondProduct = $this->createProduct($secondCategory, 'Produit univers deux');
        $secondProduct->update(['heart_score' => 999]);

        $response = $this->get(route('boutique'))
            ->assertOk()
            ->assertSee($firstProduct->name)
            ->assertSee($secondProduct->name);

        self::assertLessThan(
            strpos($response->getContent(), $secondProduct->name),
            strpos($response->getContent(), $firstProduct->name),
            'Les produits doivent respecter l’ordre des univers avant le score de popularité.'
        );
    }

    private function createProduct(Category $category, string $name): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str()->slug($name) . '-' . uniqid(),
            'price' => 25000,
            'is_active' => true,
        ]);
    }
}
