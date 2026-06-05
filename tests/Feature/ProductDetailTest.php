<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function product_detail_displays_db_backed_content_and_only_approved_reviews(): void
    {
        $product = Product::factory()->create([
            'name' => 'Nike Detail Content Pair',
            'slug' => 'nike-detail-content-pair',
            'product_story' => 'Câu chuyện riêng của sản phẩm trong database.',
            'highlights' => [
                'Đệm êm cho lịch di chuyển dài.',
                'Upper thoáng và dễ vệ sinh.',
            ],
            'care_instructions' => 'Lau bằng khăn ẩm và để khô tự nhiên.',
        ]);
        ProductVariant::factory()->create(['product_id' => $product->id, 'size' => 'US 9']);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'rating' => 5,
            'title' => 'Rất đáng mua',
            'comment' => 'Mang cả ngày vẫn thoải mái.',
            'status' => 'approved',
        ]);
        ProductReview::factory()->pending()->create([
            'product_id' => $product->id,
            'title' => 'Review chưa duyệt',
            'comment' => 'Không được hiện ngoài storefront.',
        ]);
        ProductReview::factory()->hidden()->create([
            'product_id' => $product->id,
            'title' => 'Review đang ẩn',
            'comment' => 'Hidden không được hiện ngoài storefront.',
        ]);
        ProductReview::factory()->rejected()->create([
            'product_id' => $product->id,
            'title' => 'Review bị từ chối',
            'comment' => 'Rejected không được hiện ngoài storefront.',
        ]);

        $response = $this->get(route('catalog.show', $product->slug));

        $response->assertOk()
            ->assertSee('Câu chuyện sản phẩm')
            ->assertSee('Câu chuyện riêng của sản phẩm trong database.')
            ->assertSee('Điểm nổi bật')
            ->assertSee('Đệm êm cho lịch di chuyển dài.')
            ->assertSee('Cách phối đồ / chăm sóc')
            ->assertSee('Lau bằng khăn ẩm và để khô tự nhiên.')
            ->assertSee('Rất đáng mua')
            ->assertSee('Mang cả ngày vẫn thoải mái.')
            ->assertSee('5.0/5')
            ->assertDontSee('Review chưa duyệt')
            ->assertDontSee('Không được hiện ngoài storefront.')
            ->assertDontSee('Review đang ẩn')
            ->assertDontSee('Hidden không được hiện ngoài storefront.')
            ->assertDontSee('Review bị từ chối')
            ->assertDontSee('Rejected không được hiện ngoài storefront.');
    }

    #[Test]
    public function product_detail_uses_local_fallback_for_unsafe_image_urls(): void
    {
        $product = Product::factory()->create([
            'name' => 'Nike Unsafe Image Pair',
            'slug' => 'nike-unsafe-image-pair',
            'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff',
        ]);

        ProductVariant::factory()->create(['product_id' => $product->id]);

        $response = $this->get(route('catalog.show', $product->slug));

        $response->assertOk()
            ->assertSee('images/hero.png', false)
            ->assertDontSee('images.unsplash.com', false)
            ->assertDontSee('IMAGE UNAVAILABLE');
    }
}
