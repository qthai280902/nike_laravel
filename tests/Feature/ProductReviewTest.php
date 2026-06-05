<?php

namespace Tests\Feature;

use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_is_redirected_when_submitting_product_review(): void
    {
        $product = Product::factory()->create();

        $this->post(route('products.reviews.store', $product), [
            'rating' => 5,
            'comment' => 'Sản phẩm rất ổn.',
        ])->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_submit_pending_product_review(): void
    {
        $user = User::factory()->create(['name' => 'Nguyen Reviewer']);
        $product = Product::factory()->create([
            'name' => 'Nike Review Flow Pair',
            'slug' => 'nike-review-flow-pair',
        ]);

        $this->actingAs($user)
            ->post(route('products.reviews.store', $product), [
                'rating' => 4,
                'title' => 'Đáng mua',
                'comment' => 'Mang đi làm cả ngày vẫn thoải mái.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'author_name' => 'Nguyen Reviewer',
            'rating' => 4,
            'status' => 'pending',
        ]);

        $this->get(route('catalog.show', $product->slug))
            ->assertOk()
            ->assertDontSee('Mang đi làm cả ngày vẫn thoải mái.');
    }

    #[Test]
    public function user_cannot_submit_duplicate_review_for_same_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        ProductReview::factory()->pending()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->post(route('products.reviews.store', $product), [
                'rating' => 5,
                'comment' => 'Gửi lần hai.',
            ])
            ->assertSessionHasErrors('review');

        $this->assertSame(1, ProductReview::where('product_id', $product->id)->where('user_id', $user->id)->count());
    }

    #[Test]
    public function product_detail_links_approved_review_author_to_public_profile(): void
    {
        $author = User::factory()->create([
            'name' => 'Public Reviewer',
            'avatar_url' => '/images/avatars/lan-anh.svg',
        ]);
        $product = Product::factory()->create(['slug' => 'nike-public-review-link']);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $author->id,
            'author_name' => 'Public Reviewer',
            'status' => 'approved',
        ]);

        $this->get(route('catalog.show', $product->slug))
            ->assertOk()
            ->assertSee('Public Reviewer')
            ->assertSee(route('users.show', $author), false)
            ->assertSee('/images/avatars/lan-anh.svg', false);
    }

    #[Test]
    public function product_detail_only_shows_reviews_for_that_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Nike Product Specific Pair',
            'slug' => 'nike-product-specific-pair',
        ]);
        $otherProduct = Product::factory()->create([
            'name' => 'Nike Other Review Pair',
            'slug' => 'nike-other-review-pair',
        ]);

        ProductReview::factory()->create([
            'product_id' => $product->id,
            'title' => 'Review đúng sản phẩm',
            'comment' => 'Nội dung chỉ thuộc sản phẩm hiện tại.',
            'status' => 'approved',
        ]);
        ProductReview::factory()->create([
            'product_id' => $otherProduct->id,
            'title' => 'Review của sản phẩm khác',
            'comment' => 'Không được xuất hiện ở trang này.',
            'status' => 'approved',
        ]);

        $this->get(route('catalog.show', $product->slug))
            ->assertOk()
            ->assertSee('Review đúng sản phẩm')
            ->assertSee('Nội dung chỉ thuộc sản phẩm hiện tại.')
            ->assertDontSee('Review của sản phẩm khác')
            ->assertDontSee('Không được xuất hiện ở trang này.');
    }

    #[Test]
    public function public_user_profile_shows_safe_review_and_listing_data_without_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Safe Public User',
            'email' => 'private-user@example.com',
            'avatar_url' => '/images/avatars/minh-khoi.svg',
        ]);
        $product = Product::factory()->create([
            'name' => 'Nike Public Profile Pair',
            'slug' => 'nike-public-profile-pair',
        ]);
        $hiddenProduct = Product::factory()->create([
            'name' => 'Nike Hidden Public Profile Pair',
            'slug' => 'nike-hidden-public-profile-pair',
        ]);
        $rejectedProduct = Product::factory()->create([
            'name' => 'Nike Rejected Public Profile Pair',
            'slug' => 'nike-rejected-public-profile-pair',
        ]);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'comment' => 'Review công khai đã duyệt.',
            'status' => 'approved',
        ]);
        ProductReview::factory()->pending()->create([
            'product_id' => $product->id,
            'user_id' => User::factory(),
            'comment' => 'Review chưa duyệt không được hiện.',
        ]);
        ProductReview::factory()->hidden()->create([
            'product_id' => $hiddenProduct->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'comment' => 'Review hidden không được hiện.',
        ]);
        ProductReview::factory()->rejected()->create([
            'product_id' => $rejectedProduct->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'comment' => 'Review rejected không được hiện.',
        ]);
        MarketplaceListing::factory()->create([
            'user_id' => $user->id,
            'status' => MarketplaceListingStatus::Active,
        ]);
        MarketplaceListing::factory()->create([
            'user_id' => $user->id,
            'status' => MarketplaceListingStatus::Rejected,
        ]);

        $this->get(route('users.show', $user))
            ->assertOk()
            ->assertSee('Safe Public User')
            ->assertSee('/images/avatars/minh-khoi.svg', false)
            ->assertSee('Nike Public Profile Pair')
            ->assertSee('Review công khai đã duyệt.')
            ->assertDontSee('Review chưa duyệt không được hiện.')
            ->assertDontSee('Review hidden không được hiện.')
            ->assertDontSee('Review rejected không được hiện.')
            ->assertDontSee('private-user@example.com');
    }
}
