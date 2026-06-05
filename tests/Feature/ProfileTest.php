<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_is_redirected_from_profile_edit(): void
    {
        $this->get(route('profile.edit'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function user_can_update_name_and_upload_avatar_without_editing_email(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'locked-email@example.com',
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'attacker@example.com',
                'avatar_file' => UploadedFile::fake()->createWithContent(
                    'avatar.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
                ),
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('success');

        $user->refresh();

        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('locked-email@example.com', $user->email);
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);

        $this->actingAs($user)
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Updated Name')
            ->assertSee('/storage/'.$user->avatar_path, false);
    }

    #[Test]
    public function profile_shows_private_review_history_with_moderation_states(): void
    {
        $user = User::factory()->create(['name' => 'Review Owner']);
        $otherUser = User::factory()->create(['name' => 'Other Reviewer']);
        $moderator = User::factory()->create(['role' => 'admin', 'name' => 'Review Moderator']);
        $approvedProduct = Product::factory()->create([
            'name' => 'Nike Private Approved Pair',
            'slug' => 'nike-private-approved-pair',
        ]);
        $pendingProduct = Product::factory()->create([
            'name' => 'Nike Private Pending Pair',
            'slug' => 'nike-private-pending-pair',
        ]);
        $hiddenProduct = Product::factory()->create([
            'name' => 'Nike Private Hidden Pair',
            'slug' => 'nike-private-hidden-pair',
        ]);
        $rejectedProduct = Product::factory()->create([
            'name' => 'Nike Private Rejected Pair',
            'slug' => 'nike-private-rejected-pair',
        ]);
        $otherProduct = Product::factory()->create([
            'name' => 'Nike Other Private Review Pair',
            'slug' => 'nike-other-private-review-pair',
        ]);

        ProductReview::factory()->create([
            'product_id' => $approvedProduct->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'title' => 'Review đã duyệt riêng',
            'comment' => 'Nội dung approved của chính user.',
            'moderated_by_user_id' => $moderator->id,
            'moderated_at' => now(),
        ]);
        ProductReview::factory()->pending()->create([
            'product_id' => $pendingProduct->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'title' => 'Review đang chờ',
            'comment' => 'Nội dung pending của chính user.',
        ]);
        ProductReview::factory()->hidden()->create([
            'product_id' => $hiddenProduct->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'title' => 'Review đang ẩn',
            'comment' => 'Nội dung hidden của chính user.',
            'moderated_by_user_id' => $moderator->id,
            'moderated_at' => now(),
        ]);
        ProductReview::factory()->rejected()->create([
            'product_id' => $rejectedProduct->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'title' => 'Review bị từ chối',
            'comment' => 'Nội dung rejected của chính user.',
            'rejection_reason' => 'Review cần bổ sung trải nghiệm thật.',
            'moderated_by_user_id' => $moderator->id,
            'moderated_at' => now(),
        ]);
        ProductReview::factory()->create([
            'product_id' => $otherProduct->id,
            'user_id' => $otherUser->id,
            'author_name' => $otherUser->name,
            'title' => 'Review không thuộc user',
            'comment' => 'Không được hiện trong profile này.',
        ]);

        $this->actingAs($user)
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Đánh giá của tôi')
            ->assertSee('Review đã duyệt riêng')
            ->assertSee('Review đang chờ')
            ->assertSee('Review đang ẩn')
            ->assertSee('Review bị từ chối')
            ->assertSee('Review cần bổ sung trải nghiệm thật.')
            ->assertSee('Review Moderator')
            ->assertDontSee('Review không thuộc user')
            ->assertDontSee('Không được hiện trong profile này.');
    }
}
