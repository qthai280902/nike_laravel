<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_product_index_is_paginated_and_searchable_by_sku(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        Product::factory()
            ->count(11)
            ->for($category)
            ->has(ProductVariant::factory()->state(['stock' => 10]), 'variants')
            ->create();

        $targetProduct = Product::factory()->for($category)->create([
            'name' => 'Nike Admin Search Pair',
            'slug' => 'nike-admin-search-pair',
        ]);
        ProductVariant::factory()->create([
            'product_id' => $targetProduct->id,
            'sku' => 'PHASE7B-SEARCH-SKU',
            'stock' => 2,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.storefront.index'))
            ->assertOk()
            ->assertSee('Hiển thị 1-10', false);

        $this->actingAs($admin)
            ->get(route('admin.storefront.index', ['search' => 'PHASE7B-SEARCH-SKU']))
            ->assertOk()
            ->assertSee('Nike Admin Search Pair')
            ->assertSee('PHASE7B-SEARCH-SKU');
    }

    #[Test]
    public function admin_can_create_product_with_initial_variant_and_detail_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Nike Admin Created Pair',
            'slug' => 'nike-admin-created-pair',
            'description' => 'Mô tả ngắn cho admin.',
            'product_story' => 'Câu chuyện từ admin.',
            'highlights_text' => "Đệm êm\nForm chắc",
            'care_instructions' => 'Lau sạch bằng khăn mềm.',
            'price' => 2990000,
            'original_price' => 3490000,
            'image_url' => '/images/hero.png',
            'featured_position' => null,
            'status' => 'active',
            'variant_sku' => 'PHASE7B-CREATE-SKU',
            'variant_size' => 'US 9',
            'variant_color' => 'Đen/Trắng',
            'variant_stock' => 12,
        ]);

        $product = Product::where('slug', 'nike-admin-created-pair')->firstOrFail();

        $response->assertRedirect(route('admin.products.show', $product));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nike Admin Created Pair',
            'product_story' => 'Câu chuyện từ admin.',
        ]);
        $this->assertSame(['Đệm êm', 'Form chắc'], $product->fresh()->highlights);
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'sku' => 'PHASE7B-CREATE-SKU',
            'stock' => 12,
        ]);
    }

    #[Test]
    public function admin_can_view_product_detail_with_inventory_and_review_summary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create([
            'name' => 'Nike Admin Detail Pair',
            'slug' => 'nike-admin-detail-pair',
        ]);
        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'sku' => 'PHASE7B-SHOW-SKU',
            'stock' => 7,
        ]);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'rating' => 5,
            'title' => 'Review admin thấy',
            'comment' => 'Review approved trong admin.',
            'status' => 'approved',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.products.show', $product))
            ->assertOk()
            ->assertSee('Nike Admin Detail Pair')
            ->assertSee('PHASE7B-SHOW-SKU')
            ->assertSee('Review admin thấy')
            ->assertSee('5.0/5');
    }

    #[Test]
    public function admin_can_update_product_and_variant_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create([
            'name' => 'Nike Before Update',
            'slug' => 'nike-before-update',
        ]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Nike After Update',
            'slug' => 'nike-after-update',
            'description' => 'Mô tả sau cập nhật.',
            'product_story' => 'Story sau cập nhật.',
            'highlights_text' => 'Một điểm nổi bật mới',
            'care_instructions' => 'Chăm sóc sau cập nhật.',
            'price' => 3190000,
            'original_price' => null,
            'image_url' => '/images/hero.png',
            'featured_position' => null,
            'status' => 'active',
            'variant_stock' => [
                $variant->id => 3,
            ],
            'variant_price_override' => [
                $variant->id => 2990000,
            ],
        ]);

        $response->assertRedirect(route('admin.products.show', $product));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nike After Update',
            'slug' => 'nike-after-update',
        ]);
        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock' => 3,
            'price_override' => 2990000,
        ]);
    }

    #[Test]
    public function non_admin_cannot_access_admin_product_management(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get(route('admin.products.index'))
            ->assertNotFound();
    }
}
