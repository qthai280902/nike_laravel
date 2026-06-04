<?php

namespace Tests\Feature;

use App\Enums\MarketplaceListingCondition;
use App\Enums\MarketplaceListingStatus;
use App\Models\Category;
use App\Models\MarketplaceListing;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RoutingQaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    private Product $product;

    private ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard categories
        $parentCategory = Category::create([
            'name' => 'Men',
            'slug' => 'men',
            'description' => 'Men category',
        ]);
        $category = Category::create([
            'parent_id' => $parentCategory->id,
            'name' => 'Lifestyle',
            'slug' => 'men-lifestyle',
            'description' => 'Men lifestyle',
        ]);

        // Create a product
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => "Nike Air Force 1 '07",
            'slug' => 'nike-air-force-1-07',
            'price' => 2800000,
            'description' => 'Iconic shoes',
            'image_url' => 'https://static.nike.com/placeholder.png',
            'status' => 'active',
        ]);

        // Create product variant
        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'sku' => 'NK-AF1-07-M9',
            'size' => 'US 9',
            'color' => 'Black/White',
            'stock' => 50,
        ]);

        // Create admin and customer users
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'name' => 'Customer User',
            'email' => 'customer@example.com',
        ]);
    }

    #[Test]
    public function guest_can_access_storefront_homepage(): void
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    #[Test]
    public function guest_can_access_catalog_listing(): void
    {
        $response = $this->get('/catalog/products');
        $response->assertOk();
    }

    #[Test]
    public function guest_can_access_product_detail_page(): void
    {
        $response = $this->get('/catalog/products/nike-air-force-1-07');
        $response->assertOk();
        $response->assertSee("Nike Air Force 1 '07");
    }

    #[Test]
    public function guest_checkout_redirects_to_login(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_customer_can_access_checkout_if_cart_not_empty(): void
    {
        $response = $this->actingAs($this->customer)
            ->withSession([
                'nike_cart' => [
                    $this->variant->id => [
                        'id' => $this->variant->id,
                        'product_name' => $this->product->name,
                        'variant_name' => "{$this->variant->color} - {$this->variant->size}",
                        'price' => $this->product->price,
                        'qty' => 1,
                        'image' => $this->product->image_url,
                        'size' => $this->variant->size,
                        'color' => $this->variant->color,
                        'slug' => $this->product->slug,
                    ],
                ],
            ])
            ->get('/checkout');

        $response->assertOk();
    }

    #[Test]
    public function guest_profile_redirects_to_login(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_customer_can_access_profile(): void
    {
        $response = $this->actingAs($this->customer)->get('/profile');
        $response->assertOk();
    }

    #[Test]
    public function guest_accessing_marketplace_redirects_to_login(): void
    {
        $response = $this->get('/marketplace');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_customer_can_access_marketplace_homepage(): void
    {
        $response = $this->actingAs($this->customer)->get('/marketplace');
        $response->assertOk();
    }

    #[Test]
    public function authenticated_customer_can_access_marketplace_details(): void
    {
        // Create a C2C listing
        $listing = MarketplaceListing::create([
            'user_id' => $this->customer->id,
            'product_variant_id' => $this->variant->id,
            'asking_price' => 1800000,
            'condition' => MarketplaceListingCondition::LikeNew,
            'seller_description' => 'Worn twice, excellent condition.',
            'status' => MarketplaceListingStatus::Active,
        ]);

        $response = $this->actingAs($this->customer)->get("/marketplace/{$listing->id}");
        $response->assertOk();
        $response->assertSee('excellent condition');
    }

    #[Test]
    public function guest_can_access_support_page(): void
    {
        $response = $this->get('/support');
        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertOk();
    }

    #[Test]
    public function guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function admin_can_access_admin_members_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/members');
        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_admin_member_detail_page(): void
    {
        $response = $this->actingAs($this->admin)->get("/admin/members/{$this->customer->id}");
        $response->assertOk();
        $response->assertSee($this->customer->name);
    }

    #[Test]
    public function admin_can_access_admin_reports(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports');
        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_admin_support_tickets(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/support');
        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_admin_landing_articles(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/landing-articles');
        $response->assertOk();
    }
}
