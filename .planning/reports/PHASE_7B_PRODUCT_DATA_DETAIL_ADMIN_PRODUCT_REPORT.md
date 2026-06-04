# PHASE 7B — PRODUCT DATA + DETAIL + ADMIN PRODUCT MANAGEMENT REPORT

## 1. Mục tiêu phase

Phase 7B tập trung vào việc làm sạch và nâng cấp chất lượng dữ liệu của cửa hàng (B2C Catalog), phong phú hóa nội dung trang chi tiết sản phẩm (PDP - Product Detail Page), tích hợp tính năng Đánh giá sản phẩm (Product Reviews) từ người dùng thực tế và xây dựng hệ thống Quản lý sản phẩm (Product CRUD) toàn diện dành cho quản trị viên.

Các công việc chính bao gồm:
* Khắc phục lỗi hiển thị ảnh sản phẩm hỏng hoặc link ảnh ngoài (Unsplash placeholder) bằng cơ chế tự động hiển thị ảnh dự phòng (Fallback image).
* Mở rộng và làm thật dữ liệu sản phẩm giày Nike: bổ sung câu chuyện sản phẩm, đặc điểm nổi bật, hướng dẫn chăm sóc.
* Xây dựng cơ sở dữ liệu và Model cho hệ thống đánh giá sản phẩm.
* Tích hợp khung Đánh giá và Bình luận trên giao diện chi tiết sản phẩm.
* Phát triển giao diện Quản lý sản phẩm của Admin bao gồm: danh sách có phân trang, bộ lọc nâng cao, chức năng tạo mới, chỉnh sửa thông tin chi tiết sản phẩm và lượng tồn kho của các biến thể.

## 2. Vấn đề trước khi sửa

* **Ảnh sản phẩm bị lỗi hiển thị (Ảnh đỏ/Placeholder hỏng):** Các sản phẩm cũ sử dụng link ảnh từ dịch vụ Unsplash hoặc các URL tĩnh ngoài. Khi không có kết nối internet hoặc dịch vụ ảnh ngoài thay đổi chính sách, ảnh sẽ bị lỗi hiển thị dạng 404 hoặc hiện chữ cảnh báo màu đỏ rất mất thẩm mỹ.
* **Nội dung chi tiết sản phẩm nghèo nàn:** Trang chi tiết sản phẩm cũ chỉ hiển thị tên, giá và mô tả ngắn vài chữ, chưa truyền tải được giá trị thương hiệu và đặc điểm kỹ thuật của giày Nike.
* **Chưa có chức năng đánh giá và bình luận:** Khách mua hàng không thể xem ý kiến phản hồi của người mua trước, và cũng không có cách nào để gửi cảm nhận của mình sau khi trải nghiệm sản phẩm.
* **Trang quản trị storefront quá dài:** Admin phải cuộn trang liên tục để duyệt danh mục sản phẩm do thiếu tính năng phân trang (Pagination).
* **Thiếu bộ lọc và tìm kiếm ở trang quản trị:** Không thể tìm sản phẩm theo mã SKU, tên hay trạng thái, gây khó khăn lớn khi số lượng sản phẩm tăng lên.
* **Chưa có trang Thêm mới/Sửa sản phẩm hoàn chỉnh:** Các nút thao tác chỉ là mockup, admin không thể thay đổi giá sản phẩm hoặc cập nhật số lượng tồn kho trực tiếp từ giao diện.

## 3. Việc đã thực hiện

### 3.1. Cơ chế tự động hiển thị ảnh dự phòng (Product Image Fallback)
* Xây dựng accessor `getImageUrlAttribute()` trong Model `Product.php` để lọc tất cả các URL ảnh lỗi hoặc không an toàn (chứa unsplash, placeholder, hoặc link hỏng).
* Tạo sẵn thư mục ảnh placeholder SVG tối giản tương ứng với các dòng sản phẩm: `airmax.svg`, `dunk.svg`, `running.svg`, `basketball.svg`, `lifestyle.svg`, v.v. Khi phát hiện ảnh lỗi, hệ thống tự động ánh xạ sang tệp SVG nội bộ hoặc sử dụng ảnh mặc định `images/hero.png` làm ảnh đại diện chính thức.

### 3.2. Mở rộng Dữ liệu Giày thật (Real Shoe Data Expansion)
* Cập nhật seeder để làm giàu thông tin sản phẩm: bổ sung câu chuyện giày (Product Story) viết bằng tiếng Việt truyền cảm hứng; danh sách các điểm nổi bật (Highlights) dạng mảng JSON; và hướng dẫn cách giặt/chăm sóc giày (Care Instructions) chi tiết cho từng loại chất liệu (da, lưới, canvas).

### 3.3. Xây dựng Cơ sở dữ liệu Đánh giá Sản phẩm (ProductReview Model & Migration)
* Tạo bảng `product_reviews` với ID dạng UUID, lưu trữ liên kết sản phẩm, người dùng (để trống nếu là khách ẩn danh), tên tác giả, số sao đánh giá (1-5), tiêu đề bình luận, nội dung chi tiết và trạng thái phê duyệt (status: `approved`/`pending`).
* Đăng ký quan hệ `reviews()` và `approvedReviews()` trong Model `Product.php`.

### 3.4. Hiển thị và Gửi Đánh giá ở Trang chi tiết (Product Detail Review Section)
* Tích hợp phần hiển thị đánh giá ở cuối trang chi tiết sản phẩm (PDP): hiển thị điểm trung bình (ví dụ: 4.8 / 5.0), số lượng đánh giá và danh sách các bình luận đã phê duyệt kèm tên tác giả, ngày gửi, số sao và nội dung chi tiết.
* Xây dựng form gửi đánh giá trực tiếp cho người dùng đã đăng nhập.

### 3.5. Xây dựng trang Quản trị Sản phẩm của Admin (Admin Product Management)
* **Trang danh sách (Index):** Thiết lập phân trang cố định 10 sản phẩm mỗi trang.
* **Bộ lọc và Tìm kiếm:** Cho phép tìm kiếm sản phẩm theo Tên, Slug, hoặc mã SKU của biến thể. Lọc sản phẩm theo danh mục (Category), trạng thái hiển thị (Active/Inactive), và mức độ tồn kho (Hết hàng/Sắp hết hàng/Sẵn có).
* **Trang xem chi tiết (Show):** Xem đầy đủ cấu trúc biến thể, tổng kho, và danh sách các đánh giá sản phẩm của người dùng kèm điểm trung bình.
* **Chức năng Tạo mới (Create):** Form tạo sản phẩm mới, tự sinh slug độc nhất, chọn danh mục cha-con và tạo biến thể đầu tiên kèm cấu hình mã SKU, kích thước, màu sắc và số lượng tồn kho khởi tạo.
* **Chức năng Cập nhật (Edit):** Form chỉnh sửa thông tin chi tiết của sản phẩm, giá bán chính thức, giá gốc để tính chiết khấu, và cập nhật trực tiếp lượng tồn kho của từng size/màu.

## 4. Thay đổi Database (Database Changes)

* **`2026_06_03_160506_add_detail_content_to_products_table.php`:**
  * Thêm các cột: `product_story` (longText, nullable), `highlights` (json, nullable), `care_instructions` (text, nullable) vào bảng `products`.
  * Thêm các chỉ mục phức hợp để tăng tốc độ truy vấn: `products_status_featured_position_index` và `products_category_status_index`.
* **`2026_06_03_160512_create_product_reviews_table.php`:**
  * Tạo bảng `product_reviews` với UUID làm khóa chính.
  * Định nghĩa khóa ngoại `product_id` (tham chiếu bảng `products`, xóa cascade) và `user_id` (tham chiếu bảng `users`, null on delete).
  * Thêm các trường: `author_name`, `rating`, `title`, `comment`, `status`.
  * Tạo chỉ mục tìm kiếm nhanh theo sản phẩm và đánh giá sao: `(product_id, status, created_at)` và `(status, rating)`.
* **`2026_06_04_084559_add_unique_user_product_to_product_reviews_table.php`:**
  * Bổ sung ràng buộc độc nhất `unique(['product_id', 'user_id'])` để đảm bảo mỗi người dùng chỉ được viết tối đa 1 đánh giá cho một sản phẩm, ngăn chặn hành vi spam đánh giá ảo.

## 5. Dữ liệu Seeder (Seeder & Data)

* **Dữ liệu sản phẩm mẫu:** Tổng cộng 36 sản phẩm chính gốc được mở rộng thêm ~30 dòng sản phẩm giày Nike mới ở Phase 7A, nâng tổng số sản phẩm lên gần 70 mẫu mã đa dạng.
* **Biến thể sản phẩm:** Mỗi mẫu giày được sinh ngẫu nhiên 3 - 6 biến thể khác nhau về kích thước (size US 5 - 9 cho nữ, 7 - 12 cho nam) và màu sắc tiếng Việt (Đen/Trắng, Xám/Đỏ, Xanh/Neon...).
* **Hệ thống đánh giá mẫu:** Viết seeder sinh tự động 3 đánh giá thực tế cho mỗi sản phẩm thuộc catalog. Seeder sử dụng dữ liệu từ 6 tài khoản người dùng thật có họ tên tiếng Việt rõ ràng, avatar cá nhân, và các bình luận mô tả trải nghiệm đi giày thực tế để dữ liệu hiển thị tự nhiên.
* **Tính không trùng lặp (Idempotent):** Seeder áp dụng phương thức `updateOrCreate()` trên khóa chính hoặc các cặp thuộc tính độc nhất, cho phép admin chạy lại lệnh `php artisan db:seed` nhiều lần mà không bị lỗi trùng mã SKU, trùng slug hoặc bị nhân bản bản ghi.

## 6. Danh sách các file thay đổi (Files changed)

* [Product.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Models/Product.php): Khai báo quan hệ `reviews()`, `approvedReviews()`, thêm thuộc tính ảo và hàm fallback ảnh.
* [ProductReview.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Models/ProductReview.php): Định nghĩa model đánh giá sản phẩm mới, các trường được điền và scope `approved`.
* [ProductReviewController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/ProductReviewController.php): Tiếp nhận gửi đánh giá mới, validate dữ liệu, kiểm tra chống spam và lưu database dạng chờ duyệt (`pending`).
* [StorefrontController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/Admin/StorefrontController.php): Nâng cấp toàn diện các hàm `index`, `create`, `store`, `show`, `edit`, `update` phục vụ hệ thống quản lý sản phẩm CRUD của admin.
* [LiveProductSeeder.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/database/seeders/LiveProductSeeder.php): Mở rộng seeder, dọn dẹp các review ẩn danh cũ và gán review người dùng thật vào từng mã sản phẩm.
* [show.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/catalog/show.blade.php): Thiết kế PDP hiển thị thông tin câu chuyện sản phẩm, đặc tính nổi bật, hướng dẫn chăm sóc, danh sách bình luận đã duyệt và form gửi đánh giá mới.
* [admin/storefront/index.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/admin/storefront/index.blade.php) (liên kết với admin.products.index): Giao diện quản lý danh sách sản phẩm của admin có phân trang và bộ tìm lọc.
* Thư mục views quản trị sản phẩm: `admin/products/create.blade.php`, `admin/products/edit.blade.php`, `admin/products/show.blade.php` hiển thị thông tin kho hàng và danh sách review chờ phê duyệt.

## 7. Lỗi đã sửa (Bugs fixed)

| Bug | Nguyên nhân | Cách fix | Trạng thái |
| --- | --- | --- | --- |
| Ảnh bị lỗi 404 hiển thị icon hỏng | Sử dụng link ảnh ngoài không còn khả dụng hoặc không có kết nối internet. | Viết bộ lọc trong accessor tự động thay thế link ngoài bằng ảnh placeholder SVG nội bộ tương ứng. | Đã sửa |
| PDP nghèo nội dung | Thiếu các cột lưu thông tin chi tiết trên bảng dữ liệu sản phẩm. | Tạo migration bổ sung các cột `product_story`, `highlights`, `care_instructions` và hiển thị trực quan lên view. | Đã sửa |
| Chưa lưu trữ đánh giá sản phẩm | Thiếu bảng liên kết trong database. | Tạo bảng `product_reviews`, thiết lập quan hệ 1-N giữa sản phẩm và đánh giá. | Đã sửa |
| Admin duyệt sản phẩm quá dài | Hiển thị tất cả sản phẩm trên một trang, làm giảm hiệu năng load trang và tốn thời gian cuộn. | Bổ sung phân trang `paginate(10)` trong controller quản lý sản phẩm. | Đã sửa |
| Không tìm kiếm hay lọc được sản phẩm | Thiếu logic xử lý tham số lọc trong `StorefrontController`. | Viết các câu lệnh điều kiện `where` kiểm tra sự tồn tại của từ khóa tìm kiếm, SKU, trạng thái và lượng kho. | Đã sửa |
| Thiếu chức năng CRUD sản phẩm | Các đường dẫn admin CRUD sản phẩm chưa được phát triển hoặc chỉ trỏ đến trang trống. | Hoàn thiện đầy đủ logic điều hướng và các tệp blade tạo mới, xem chi tiết và sửa sản phẩm/kho hàng. | Đã sửa |

## 8. Kết quả Test / Build

* **Chạy thử nghiệm test suite:**
  * Tệp `tests/Feature/ProductDetailTest.php` kiểm tra việc hiển thị nội dung câu chuyện sản phẩm, đặc tính nổi bật, hướng dẫn chăm sóc, và kiểm tra cơ chế hiển thị ảnh dự phòng.
  * Tệp `tests/Feature/AdminProductTest.php` kiểm tra luồng tìm kiếm, bộ lọc nâng cao, tạo mới sản phẩm và sửa biến thể của admin.
  * Kết quả: **Tất cả các bài test tương ứng passed** thành công.
* **Định dạng code (Pint):** Chạy `vendor/bin/pint --dirty --format agent` hoàn tất.
* **Biên dịch Frontend (npm build):** Hoạt động ổn định.

## 9. Kết quả cuối cùng

Phase 7B đạt trạng thái **PASS / Hoàn thành**.
* **Rủi ro còn lại:** Việc hiển thị điểm đánh giá trung bình dựa trên tính toán thời gian thực (`avg('rating')`) có thể gây chậm truy vấn khi sản phẩm có hàng ngàn lượt đánh giá. Ở quy mô lớn, nên cân nhắc lưu cache điểm đánh giá trung bình trực tiếp vào bảng `products` mỗi khi có review mới được duyệt.
