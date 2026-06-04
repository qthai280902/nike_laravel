<?php

namespace Tests\Feature;

use App\Models\LandingArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminLandingArticleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_cannot_access_admin_landing_articles(): void
    {
        $response = $this->get(route('admin.landing-articles.index'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function customer_cannot_access_admin_landing_articles(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('admin.landing-articles.index'));
        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_access_admin_landing_articles_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $article = LandingArticle::create([
            'title' => 'Bài viết thử nghiệm',
            'slug' => 'bai-viet-thu-nghiem',
            'body' => 'Nội dung bài viết thử nghiệm.',
            'position' => 1,
            'is_published' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.landing-articles.index'));

        $response->assertStatus(200);
        $response->assertSee('Bài viết thử nghiệm');
        $response->assertSee('bai-viet-thu-nghiem');
    }

    #[Test]
    public function admin_can_create_landing_article_with_auto_generated_slug(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.landing-articles.store'), [
            'title' => 'Câu Chuyện Mới Nhất 2026',
            'slug' => '',
            'excerpt' => 'Tóm tắt bài viết.',
            'body' => 'Nội dung chi tiết ở đây.',
            'position' => 5,
            'is_published' => '1',
            'published_at' => '',
        ]);

        $response->assertRedirect(route('admin.landing-articles.index'));
        $this->assertDatabaseHas('landing_articles', [
            'title' => 'Câu Chuyện Mới Nhất 2026',
            'slug' => 'cau-chuyen-moi-nhat-2026',
            'body' => 'Nội dung chi tiết ở đây.',
            'is_published' => true,
        ]);
    }

    #[Test]
    public function admin_can_create_landing_article_with_custom_published_at(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $futureDate = now()->addDays(2)->format('Y-m-d H:i:s');

        $response = $this->actingAs($admin)->post(route('admin.landing-articles.store'), [
            'title' => 'Bài viết tương lai',
            'slug' => 'bai-viet-tuong-lai',
            'body' => 'Nội dung tương lai.',
            'position' => 2,
            'is_published' => '1',
            'published_at' => $futureDate,
        ]);

        $response->assertRedirect(route('admin.landing-articles.index'));
        $this->assertDatabaseHas('landing_articles', [
            'slug' => 'bai-viet-tuong-lai',
            'published_at' => $futureDate,
        ]);
    }

    #[Test]
    public function admin_cannot_create_landing_article_with_duplicate_slug(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        LandingArticle::create([
            'title' => 'Trùng lặp',
            'slug' => 'trung-lap',
            'body' => 'Nội dung.',
            'position' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.landing-articles.create'))
            ->post(route('admin.landing-articles.store'), [
                'title' => 'Trùng lặp mới',
                'slug' => 'trung-lap',
                'body' => 'Nội dung khác.',
                'position' => 2,
            ]);

        $response->assertRedirect(route('admin.landing-articles.create'));
        $response->assertSessionHasErrors('slug');
    }

    #[Test]
    public function admin_cannot_create_landing_article_with_duplicate_auto_generated_slug(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        LandingArticle::create([
            'title' => 'Câu Chuyện Mới Nhất 2026',
            'slug' => 'cau-chuyen-moi-nhat-2026',
            'body' => 'Nội dung cũ.',
            'position' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.landing-articles.create'))
            ->post(route('admin.landing-articles.store'), [
                'title' => 'Câu Chuyện Mới Nhất 2026',
                'slug' => '',
                'body' => 'Nội dung khác.',
                'position' => 2,
            ]);

        $response->assertRedirect(route('admin.landing-articles.create'));
        $response->assertSessionHasErrors('slug');
    }

    #[Test]
    public function admin_can_edit_and_update_landing_article(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $article = LandingArticle::create([
            'title' => 'Bài viết gốc',
            'slug' => 'bai-viet-goc',
            'body' => 'Nội dung gốc.',
            'position' => 1,
            'is_published' => true,
        ]);

        // Form checkbox uncheck (passes null/0 for is_published)
        $response = $this->actingAs($admin)->put(route('admin.landing-articles.update', $article), [
            'title' => 'Bài viết cập nhật',
            'slug' => 'bai-viet-cap-nhat',
            'body' => 'Nội dung mới.',
            'position' => 3,
            'is_published' => '0', // Unchecked
            'published_at' => '',
        ]);

        $response->assertRedirect(route('admin.landing-articles.index'));
        $this->assertDatabaseHas('landing_articles', [
            'id' => $article->id,
            'title' => 'Bài viết cập nhật',
            'slug' => 'bai-viet-cap-nhat',
            'body' => 'Nội dung mới.',
            'position' => 3,
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    #[Test]
    public function admin_can_delete_landing_article(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $article = LandingArticle::create([
            'title' => 'Bài viết sắp xóa',
            'slug' => 'bai-viet-sap-xoa',
            'body' => 'Xóa tôi đi.',
            'position' => 1,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.landing-articles.destroy', $article));

        $response->assertRedirect(route('admin.landing-articles.index'));
        $this->assertDatabaseMissing('landing_articles', [
            'id' => $article->id,
        ]);
    }

    #[Test]
    public function storefront_displays_only_published_articles_and_hides_future_or_draft(): void
    {
        // 1. Published article with null published_at -> visible
        $visible1 = LandingArticle::create([
            'title' => 'Bài viết hiển thị 1',
            'slug' => 'visible-1',
            'body' => 'Nội dung 1.',
            'position' => 1,
            'is_published' => true,
            'published_at' => null,
        ]);

        // 2. Published article with past published_at -> visible
        $visible2 = LandingArticle::create([
            'title' => 'Bài viết hiển thị 2',
            'slug' => 'visible-2',
            'body' => 'Nội dung 2.',
            'position' => 2,
            'is_published' => true,
            'published_at' => now()->subHour(),
        ]);

        // 3. Unpublished article -> hidden
        $hiddenDraft = LandingArticle::create([
            'title' => 'Bản nháp ẩn',
            'slug' => 'hidden-draft',
            'body' => 'Bản nháp.',
            'position' => 3,
            'is_published' => false,
            'published_at' => null,
        ]);

        // 4. Future scheduled article -> hidden
        $hiddenFuture = LandingArticle::create([
            'title' => 'Lên lịch tương lai ẩn',
            'slug' => 'hidden-future',
            'body' => 'Tương lai.',
            'position' => 4,
            'is_published' => true,
            'published_at' => now()->addHour(),
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Bài viết hiển thị 1');
        $response->assertSee('Bài viết hiển thị 2');
        $response->assertDontSee('Bản nháp ẩn');
        $response->assertDontSee('Lên lịch tương lai ẩn');
    }
}
