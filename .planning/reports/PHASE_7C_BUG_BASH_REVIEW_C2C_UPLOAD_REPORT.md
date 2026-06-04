# PHASE 7C — BUG BASH + REVIEW FLOW + C2C UPLOAD REPORT

## 1. Mục tiêu phase

Phase 7C tập trung vào việc khắc phục một loạt lỗi phát hiện trong quá trình Bug Bash (Rà soát lỗi hệ thống), sửa lỗi hiển thị giao diện tối (Dark mode) trên trang cá nhân, sửa lỗi trạng thái active của thanh điều hướng (Navbar), tối ưu hóa tính năng đăng ảnh C2C từ máy tính cá nhân, hoàn thiện form gửi đánh giá thực tế, tạo trang hồ sơ công khai của người đánh giá/người bán (Public Profile) và thay đổi logo quản trị.

Các công việc chính bao gồm:
* Khắc phục lỗi hiển thị giao diện tối: nền sáng chữ trắng hoặc chữ mờ khó đọc trên trang hồ sơ cá nhân.
* Sửa lỗi thanh điều hướng (Navbar) hiển thị sai mục đang chọn (Active state) khi chuyển sang trang Marketplace.
* Đa dạng hóa hình ảnh sản phẩm để tránh hiện tượng lặp ảnh đơn điệu.
* Tích hợp tính năng tải tệp ảnh từ máy tính (`image_file` upload) cho form rao bán C2C thay vì chỉ nhập liên kết URL tĩnh.
* Hoàn thiện luồng gửi đánh giá thực tế: bắt buộc đăng nhập, giới hạn mỗi tài khoản chỉ đánh giá một sản phẩm một lần, lưu ở trạng thái chờ duyệt (`pending`).
* Xây dựng trang hồ sơ công khai của thành viên `/users/{user}` bảo mật thông tin nhạy cảm.
* Thay đổi logo mặc định của trang quản trị thành logo đại diện thương hiệu "Nike Hybrid Admin".

## 2. Vấn đề trước khi sửa

* **Lỗi hiển thị Dark Mode:** Khi chuyển sang chế độ tối, trang cá nhân (Profile) vẫn giữ nguyên một số nền màu xám sáng hoặc chữ xám mờ dẫn đến hiện tượng chói mắt hoặc hoàn toàn không đọc được chữ.
* **Menu hoạt động sai:** Khi người dùng đang ở trang Chợ đồ cũ C2C, nút "Cửa hàng" trên thanh điều hướng chính vẫn được tô đậm là đang chọn (Active) thay vì nút "Chợ đồ cũ", gây hiểu lầm về vị trí trang.
* **Lặp ảnh sản phẩm:** Các sản phẩm catalog được seeder gán chung một vài ảnh mặc định giống nhau, làm giảm tính chuyên nghiệp của giao diện trưng bày.
* **Đăng bán C2C bất tiện:** Biểu mẫu đăng bán yêu cầu người dùng phải tự tìm và dán liên kết ảnh từ internet (`image_url`), không hỗ trợ tải trực tiếp ảnh chụp giày từ máy tính hoặc điện thoại của họ.
* **Form đánh giá chưa hoạt động thực tế:** Nút gửi đánh giá chỉ hiển thị giao diện tĩnh, không gửi được dữ liệu về server hoặc gửi không giới hạn dẫn đến spam đánh giá trùng lặp từ một tài khoản.
* **Thiếu trang hồ sơ công khai của người bán/người đánh giá:** Khi click vào tên người bán tin rao vặt C2C hoặc người viết đánh giá sản phẩm, hệ thống chuyển hướng vào link chết hoặc trang cá nhân của chính mình.
* **Logo Admin mặc định:** Trang quản trị vẫn sử dụng logo mặc định của template, chưa thể hiện nét riêng của dự án Nike Hybrid.

## 3. Việc đã thực hiện

### 3.1. Sửa lỗi Giao diện tối (Theme Dark Mode Fix)
* Tinh chỉnh tệp `resources/css/app.css`. Thêm các quy tắc CSS chi tiết dưới lớp chọn `html[data-theme='dark']` để phủ toàn bộ các khối màu trắng (`bg-white` đổi thành màu tối `#121212`), cập nhật màu chữ sang màu sáng rõ ràng (`#f5f5f5`), chuyển đổi màu của các ô nhập liệu thành màu xám tối và đổi màu các nút bấm để đảm bảo tương phản tuyệt đối ở chế độ tối.

### 3.2. Sửa lỗi Trạng thái hoạt động của Navbar (Navbar Active Fix)
* Cập nhật logic phân cấp route trong `layouts/app.blade.php`. Khai báo biến `$isMarketplace = request()->routeIs('marketplace.*')`.
* Khi truy cập bất kỳ route nào thuộc Marketplace, nút "Chợ đồ cũ" được áp dụng class tô nền tối chữ trắng (`bg-nike-black text-white shadow-lg`), đồng thời nút "Cửa hàng" được trả về trạng thái bình thường.

### 3.3. Đa dạng hóa ảnh sản phẩm (Product Image Diversity)
* Nâng cấp seeder để ánh xạ ảnh sản phẩm theo nhóm danh mục cụ thể bằng các placeholder SVG động, đảm bảo các dòng giày chạy bộ, bóng rổ, thời trang có hình dáng và phong cách thiết kế đại diện riêng biệt, không bị lặp hình ảnh.

### 3.4. Upload ảnh trực tiếp cho C2C Marketplace (C2C Image Upload)
* Thêm ô nhập tệp `image_file` (kiểu file, chỉ nhận tệp ảnh JPG, PNG, WEBP tối đa 4MB) song song với ô URL ảnh cũ. Thiết lập thuộc tính `enctype="multipart/form-data"` cho thẻ form.
* Trong `MarketplaceController.php`, khi người dùng tải tệp lên, hệ thống sẽ lưu file vào thư mục lưu trữ public `marketplace-listings` và lưu đường dẫn vào cột `image_path` trong database.
* Tích hợp mã Javascript lắng nghe sự kiện thay đổi file để tạo URL tạm thời thông qua `URL.createObjectURL(file)` giúp hiển thị ảnh preview tức thì trước khi nhấn nút đăng tin.

### 3.5. Luồng gửi đánh giá thực tế (Real Review Form Flow)
* Hoàn thiện form gửi đánh giá trên PDP: người dùng chọn số sao (1-5), nhập tiêu đề và bình luận chi tiết. Form gửi request POST đến route `products.reviews.store`.
* Bộ lọc kiểm tra: nếu chưa đăng nhập thì chuyển hướng sang trang đăng nhập; nếu tài khoản đã từng đánh giá sản phẩm này thì chặn lại và trả về lỗi thông báo "Bạn đã gửi đánh giá cho sản phẩm này."
* Review mới được lưu dưới trạng thái `pending` (Chờ duyệt) và chỉ hiển thị công khai sau khi admin phê duyệt.

### 3.6. Hồ sơ công khai của thành viên (Public Reviewer/Seller Profile)
* Xây dựng route `/users/{user}` và view `users/show.blade.php` hiển thị thông tin công khai của thành viên: tên hiển thị, chữ cái viết tắt đại diện (Initials), ngày tham gia, tổng số đánh giá đã được duyệt, danh sách các sản phẩm cũ họ đang đăng bán ở trạng thái `active` công khai.
* Để bảo vệ quyền riêng tư, hệ thống ẩn hoàn toàn địa chỉ email của người dùng và không hiển thị các đánh giá/tin đăng đang chờ duyệt hoặc bị từ chối của họ.

### 3.7. Thay đổi Logo Admin (Admin Logo Change)
* Thay thế logo cũ trong layout quản trị bằng mã SVG vẽ tay tối giản, hiển thị hai chữ cái lồng nhau **N** và **H** tượng trưng cho thương hiệu **Nike Hybrid**, đặt trong khối vuông bo góc trắng nổi bật và đổi tên thương hiệu thành "Nike Hybrid Admin".

## 4. Thay đổi Database & Storage (Database/Storage Changes)

* **`2026_06_04_084559_add_image_path_to_marketplace_listings_table.php`:**
  * Bổ sung cột `image_path` (string, nullable) lưu đường dẫn tệp ảnh lưu trên máy chủ cho các tin đăng Marketplace.
* **Storage link:** Dự án yêu cầu chạy lệnh `php artisan storage:link` để tạo liên kết từ thư mục lưu trữ nội bộ sang thư mục công khai của web, đảm bảo ảnh tải lên từ form C2C hiển thị bình thường trên giao diện khách hàng.

## 5. Danh sách các file thay đổi (Files changed)

* [app.css](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/css/app.css): Sửa đổi giao diện tối, bổ sung lớp tương phản và định dạng đầu vào form trong Dark Mode.
* [app.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/layouts/app.blade.php): Sửa logic tô màu navbar active của storefront.
* [admin.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/layouts/admin.blade.php): Thay thế SVG logo admin và chữ tiêu đề trang quản lý.
* [MarketplaceController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/MarketplaceController.php): Tiếp nhận file upload trong hàm `store`, thực hiện lưu trữ file vật lý trên đĩa cứng và cập nhật thuộc tính `image_path`.
* [create.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/marketplace/create.blade.php): Thêm input chọn file ảnh, hỗ trợ xem trước ảnh thời gian thực bằng JS.
* [ProductReviewController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/ProductReviewController.php): Hoàn thiện hàm lưu review mới, kiểm tra quyền đăng nhập và chống ghi đè trùng lặp.
* [UserPublicProfileController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/UserPublicProfileController.php): Controller mới tiếp nhận xem hồ sơ công khai, nạp số lượng đếm và lấy ra các đánh giá được duyệt.
* [show.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/catalog/show.blade.php): Thêm form gửi đánh giá ở chân trang chi tiết sản phẩm.
* [users/show.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/users/show.blade.php): Giao diện hồ sơ công khai hiển thị avatar viết tắt, thống kê tin bán và đánh giá an toàn.

## 6. Lỗi đã sửa (Bugs fixed)

| Bug | Nguyên nhân | Cách fix | Trạng thái |
| --- | --- | --- | --- |
| Lỗi màu nền/chữ Dark mode ở Profile | Chưa định nghĩa lớp ghi đè màu sắc cho trang profile trong CSS chế độ tối. | Bổ sung các class CSS thay thế màu nền trắng sang `#121212` và chữ xám sang `#f5f5f5` trong file CSS. | Đã sửa |
| Navbar active sai mục | Logic navbar cũ kiểm tra route chung chung không phân biệt rõ trang Marketplace. | Định nghĩa rõ ràng `$isMarketplace` và áp dụng điều kiện loại trừ trên menu B2C Catalog. | Đã sửa |
| C2C bắt nhập URL ảnh ngoài | Form tạo tin chỉ có input text nhập link URL, gây khó khăn cho người bán. | Thêm input loại file, cấu hình lưu file vật lý trên máy chủ và ưu tiên hiển thị ảnh tải lên. | Đã sửa |
| Thiếu form gửi review sản phẩm | View PDP chỉ hiển thị danh sách đánh giá tĩnh, chưa thiết lập form gửi dữ liệu. | Tạo form gửi POST, liên kết đến ProductReviewController và thiết lập thông báo thành công. | Đã sửa |
| Người dùng gửi nhiều review cho một sản phẩm | Thiếu ràng buộc kiểm tra trùng lặp trên controller và database. | Thêm kiểm tra `exists()` trước khi ghi và tạo migration thiết lập index duy nhất `(product_id, user_id)`. | Đã sửa |
| Logo Admin mặc định | Thiết kế SVG logo quản trị cũ chưa phù hợp thương hiệu Nike Hybrid. | Thay thế bằng SVG chữ N và H lồng nhau đơn giản, hiện đại và đồng bộ với thiết kế phẳng. | Đã sửa |

## 7. Kết quả Test / Build

* **Chạy thử nghiệm test suite:**
  * Tệp `tests/Feature/ProductReviewTest.php` thực hiện các bài test: kiểm tra khách ẩn danh bị chặn gửi review, người dùng thật gửi review pending thành công, chặn gửi review lần hai, và kiểm tra hiển thị link profile công khai trên PDP.
  * Tệp `tests/Feature/MarketplaceTest.php` kiểm tra quá trình upload file ảnh bằng mock `UploadedFile::fake()->image('shoe.jpg')` và kiểm tra file được lưu thành công trên đĩa.
  * Kết quả: **Tất cả các bài test passed** hoàn thành.
* **Định dạng code (Pint):** Chạy `vendor/bin/pint --dirty --format agent` hoàn tất.
* **Biên dịch Frontend (npm build):** Hoạt động ổn định.

## 8. Kết quả cuối cùng

Phase 7C đạt trạng thái **PASS / Hoàn thành**.
* **Rủi ro còn lại:** Do các tệp tin tải lên được lưu trực tiếp vào thư mục công khai của ứng dụng, cần thiết lập cơ chế dọn dẹp các tệp ảnh mồ côi (Orphaned images) khi tin rao bán bị xóa vĩnh viễn khỏi database để tránh lãng phí dung lượng ổ cứng.
