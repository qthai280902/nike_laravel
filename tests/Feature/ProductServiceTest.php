<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
    }

    #[Test]
    public function it_fetches_child_category_products_when_querying_by_parent_category_slug(): void
    {
        // 1. Create parent category "Men"
        $parent = Category::create([
            'name' => 'Men',
            'slug' => 'men',
            'description' => 'Men products',
        ]);

        // 2. Create child category "Running" under "Men"
        $child = Category::create([
            'parent_id' => $parent->id,
            'name' => 'Running',
            'slug' => 'men-running',
            'description' => 'Men running gear',
        ]);

        // 3. Create products
        $productInChild = Product::factory()->create([
            'category_id' => $child->id,
            'name' => 'Nike Pegasus 41',
            'slug' => 'nike-pegasus-41',
            'price' => 3790000,
        ]);

        $productInParent = Product::factory()->create([
            'category_id' => $parent->id,
            'name' => 'Nike Classic Tee',
            'slug' => 'nike-classic-tee',
            'price' => 650000,
        ]);

        // 4. Query by parent slug using getCatalogProducts
        $catalogResults = $this->productService->getCatalogProducts(['category' => 'men']);

        $this->assertCount(2, $catalogResults);
        $this->assertTrue($catalogResults->contains($productInChild));
        $this->assertTrue($catalogResults->contains($productInParent));

        // 5. Query by parent slug using getProductsByCategory
        $categoryResults = $this->productService->getProductsByCategory('men');

        $this->assertCount(2, $categoryResults);
        $this->assertTrue($categoryResults->contains($productInChild));
        $this->assertTrue($categoryResults->contains($productInParent));

        // 6. Query by child slug using getCatalogProducts
        $childCatalogResults = $this->productService->getCatalogProducts(['category' => 'men-running']);

        $this->assertCount(1, $childCatalogResults);
        $this->assertTrue($childCatalogResults->contains($productInChild));
        $this->assertFalse($childCatalogResults->contains($productInParent));
    }
}
