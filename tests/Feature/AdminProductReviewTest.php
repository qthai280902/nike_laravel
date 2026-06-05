<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminProductReviewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_is_redirected_from_review_moderation_queue(): void
    {
        $this->get(route('admin.reviews.index'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function customer_cannot_access_review_moderation_queue(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.reviews.index'))
            ->assertNotFound();
    }

    #[Test]
    public function admin_can_view_pending_review_queue_and_filter_search_results(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create([
            'name' => 'Nike Moderation Queue Pair',
            'slug' => 'nike-moderation-queue-pair',
        ]);
        ProductReview::factory()->pending()->create([
            'product_id' => $product->id,
            'author_name' => 'Pending Reviewer',
            'title' => 'Queue review title',
            'comment' => 'Review đang đợi admin đọc.',
        ]);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'author_name' => 'Approved Reviewer',
            'title' => 'Approved title',
            'comment' => 'Review đã duyệt không nằm trong queue mặc định.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reviews.index', ['q' => 'Moderation Queue']))
            ->assertOk()
            ->assertSee('Queue review title')
            ->assertSee('Pending Reviewer')
            ->assertDontSee('Approved title');

        $this->actingAs($admin)
            ->get(route('admin.reviews.index', ['status' => ProductReview::STATUS_APPROVED]))
            ->assertOk()
            ->assertSee('Approved title')
            ->assertDontSee('Queue review title');
    }

    #[Test]
    public function admin_can_approve_review_and_make_it_public(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['slug' => 'nike-approve-review-public']);
        ProductVariant::factory()->create(['product_id' => $product->id]);
        $review = ProductReview::factory()->pending()->create([
            'product_id' => $product->id,
            'title' => 'Review được duyệt',
            'comment' => 'Nội dung sẽ hiển thị ngoài storefront.',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.approve', $review))
            ->assertRedirect();

        $review->refresh();

        $this->assertSame(ProductReview::STATUS_APPROVED, $review->status);
        $this->assertSame($admin->id, $review->moderated_by_user_id);
        $this->assertNotNull($review->moderated_at);
        $this->assertNull($review->rejection_reason);

        $this->get(route('catalog.show', $product->slug))
            ->assertOk()
            ->assertSee('Review được duyệt')
            ->assertSee('Nội dung sẽ hiển thị ngoài storefront.');
    }

    #[Test]
    public function admin_can_hide_approved_review_from_public_storefront(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['slug' => 'nike-hide-review-public']);
        ProductVariant::factory()->create(['product_id' => $product->id]);
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'title' => 'Review bị ẩn',
            'comment' => 'Nội dung không được hiện ngoài storefront.',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.hide', $review))
            ->assertRedirect();

        $review->refresh();

        $this->assertSame(ProductReview::STATUS_HIDDEN, $review->status);
        $this->assertSame($admin->id, $review->moderated_by_user_id);
        $this->assertNotNull($review->moderated_at);

        $this->get(route('catalog.show', $product->slug))
            ->assertOk()
            ->assertDontSee('Review bị ẩn')
            ->assertDontSee('Nội dung không được hiện ngoài storefront.');
    }

    #[Test]
    public function admin_rejects_review_with_required_reason(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $review = ProductReview::factory()->pending()->create([
            'title' => 'Review cần từ chối',
            'comment' => 'Nội dung không đạt chuẩn.',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.reject', $review), [
                'rejection_reason' => '',
            ])
            ->assertSessionHasErrors('rejection_reason');

        $this->actingAs($admin)
            ->patch(route('admin.reviews.reject', $review), [
                'rejection_reason' => 'Nội dung chứa thông tin không phù hợp.',
            ])
            ->assertRedirect();

        $review->refresh();

        $this->assertSame(ProductReview::STATUS_REJECTED, $review->status);
        $this->assertSame('Nội dung chứa thông tin không phù hợp.', $review->rejection_reason);
        $this->assertSame($admin->id, $review->moderated_by_user_id);
        $this->assertNotNull($review->moderated_at);
    }
}
