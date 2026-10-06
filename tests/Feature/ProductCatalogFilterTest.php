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
