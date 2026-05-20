<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function homepage_returns_a_successful_response_with_migrated_database(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    #[Test]
    public function homepage_displays_a_valid_featured_catalog_hero(): void
    {
        Product::factory()->create([
            'name' => 'Nike Catalog Hero',
            'slug' => 'nike-catalog-hero',
            'image_url' => 'https://static.nike.com/a/images/catalog-hero.png',
            'featured_position' => 'hero',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Nike Catalog Hero');
        $response->assertSee('https://static.nike.com/a/images/catalog-hero.png', false);
    }

    #[Test]
    public function homepage_does_not_render_unsplash_featured_images(): void
    {
        Product::factory()->create([
            'name' => 'Wrong Featured Product',
            'slug' => 'wrong-featured-product',
            'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff',
            'featured_position' => 'secondary',
        ]);

        Product::factory()->create([
            'name' => 'Right Featured Product',
            'slug' => 'right-featured-product',
            'image_url' => 'https://static.nike.com/a/images/right-featured-product.png',
            'featured_position' => 'secondary',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('images.unsplash.com', false);
        $response->assertDontSee('Wrong Featured Product');
        $response->assertSee('Right Featured Product');
    }

    #[Test]
    public function homepage_falls_back_to_the_local_hero_asset_without_a_valid_hero_product(): void
    {
        Product::factory()->create([
            'name' => 'Unsplash Hero',
            'slug' => 'unsplash-hero',
            'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a',
            'featured_position' => 'hero',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('images.unsplash.com', false);
        $response->assertSee('images/hero.png', false);
    }
}
