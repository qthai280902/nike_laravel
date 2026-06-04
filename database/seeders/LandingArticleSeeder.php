<?php

namespace Database\Seeders;

use App\Models\LandingArticle;
use Illuminate\Database\Seeder;

class LandingArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Câu chuyện Nike Hybrid',
                'slug' => 'cau-chuyen-nike-hybrid',
                'excerpt' => 'Tìm hiểu về mô hình kết hợp (Hybrid) đột phá kết hợp sản phẩm B2C chính hãng và chợ C2C Marketplace trao đổi sneaker đã qua sử dụng.',
                'body' => 'Nike Hybrid mang đến giải pháp toàn diện cho các tín đồ đam mê sneaker. Tại đây, bạn không chỉ mua được những đôi giày Nike chính hãng, nguyên hộp từ hệ thống phân phối B2C chất lượng cao, mà còn có thể tham gia vào Chợ đồ cũ C2C Marketplace - nơi người dùng trao đổi những đôi giày cũ nhưng vẫn còn rất mới với quy trình kiểm duyệt nghiêm ngặt. Đây chính là tương lai của thời trang bền vững và tuần hoàn.',
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/AIR+FORCE+1+%2707.png',
                'position' => 1,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Mua giày mới chính hãng',
                'slug' => 'mua-giay-moi-chinh-hang',
                'excerpt' => 'Cam kết 100% sản phẩm phân phối B2C trên hệ thống là hàng chính hãng từ Nike Việt Nam và các đối tác ủy quyền.',
                'body' => 'Chúng tôi hiểu rằng chất lượng và uy tín là yếu tố sống còn khi mua sắm giày hiệu trực tuyến. Toàn bộ danh mục sản phẩm mới B2C trên Nike Hybrid đều được nhập trực tiếp, có hóa đơn chứng từ đầy đủ và hưởng chính sách bảo hành chính hãng. Bạn sẽ hoàn toàn yên tâm về chất lượng, form dáng và những công nghệ đệm khí Air mới nhất.',
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/9d58ca09-3252-4e00-8b17-38435d8a8b84/AIR+MAX+270.png',
                'position' => 2,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Chợ đồ cũ sneaker tuần hoàn',
                'slug' => 'cho-do-cu-sneaker-tuan-hoan',
                'excerpt' => 'Cơ hội sở hữu những mẫu sneaker hiếm có với giá cực hời, hoặc thanh lý đôi giày ít đi để bảo vệ môi trường.',
                'body' => 'Mô hình kinh tế tuần hoàn được áp dụng triệt để tại Nike Hybrid. Chợ đồ cũ C2C Marketplace cho phép khách hàng đăng bán những đôi sneaker Nike đã qua sử dụng. Mỗi sản phẩm đăng bán đều phải đi kèm hình ảnh thực tế, thông tin chi tiết về độ mới, size giày và được đội ngũ quản trị viên kiểm duyệt chặt chẽ trước khi hiển thị công khai để tránh hàng giả, hàng nhái.',
                'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8e97f699-245c-4433-875f-3ee0a1f49615/NIKE+DUNK+LOW+RETRO.png',
                'position' => 3,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Hướng dẫn chăm sóc giày',
                'slug' => 'huong-dan-cham-soc-giay',
                'excerpt' => 'Bí quyết giữ gìn đôi sneaker của bạn luôn trắng sáng và bền đẹp như mới từ các chuyên gia chăm sóc giày hàng đầu.',
                'body' => 'Để một đôi sneaker luôn bền đẹp qua năm tháng, việc chăm sóc đúng cách là cực kỳ quan trọng. Bạn nên vệ sinh giày bằng bàn chải lông mềm, sử dụng dung dịch chuyên dụng thay vì chất tẩy mạnh, phơi ở nơi thoáng mát tránh ánh nắng trực tiếp, và luôn sử dụng shoe tree để giữ form dáng. Đọc bài viết này để biết thêm chi tiết từng bước nhé!',
                'image_url' => '/images/hero.png',
                'position' => 4,
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($articles as $data) {
            LandingArticle::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'body' => $data['body'],
                    'image_url' => $data['image_url'],
                    'position' => $data['position'],
                    'is_published' => $data['is_published'],
                    'published_at' => $data['published_at'],
                ]
            );
        }
    }
}
