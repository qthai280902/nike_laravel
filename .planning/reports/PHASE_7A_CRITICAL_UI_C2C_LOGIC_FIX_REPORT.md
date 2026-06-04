# PHASE 7A — CRITICAL UI + C2C LOGIC FIX REPORT

## 1. Mục tiêu phase

Phase 7A tập trung vào việc cải thiện giao diện quản trị, tối ưu trang chủ cửa hàng, và đặc biệt là tái cấu trúc hoàn toàn logic của hệ thống Chợ đồ cũ (C2C Marketplace) để hỗ trợ đăng tin rao bán tự do (freeform) thay vì bắt buộc liên kết với sản phẩm thuộc catalog B2C có sẵn. Đồng thời, bổ sung bộ dữ liệu giày demo phong phú và đa dạng hơn.

Các công việc chính bao gồm:
* Xử lý giao diện Admin Dashboard bị lệch, các thẻ thống kê chen chúc trên một hàng.
* Thiết kế lại giao diện trang chủ gọn gàng, giảm thiểu diện tích thừa và sắp xếp bố cục hợp lý.
* Xây dựng hệ thống chuyển đổi giao diện Sáng / Tối / Hệ thống (Light/Dark/System Theme Toggle) cho trang storefront.
* Thay đổi logic đăng tin rao bán C2C: cho phép người dùng tự điền tên sản phẩm, hãng sản xuất, size, màu sắc, ảnh mà không cần phải chọn từ danh mục sản phẩm (Catalog) sẵn có của cửa hàng.
* Cải tiến thiết kế giao diện thẻ sản phẩm và trang chi tiết sản phẩm cũ Marketplace.
* Mở rộng thêm nhiều sản phẩm giày Nike demo thực tế.

## 2. Vấn đề trước khi sửa

* **Admin Dashboard chen chúc:** Bố cục cũ hiển thị tất cả các thẻ thống kê trên cùng một dòng, gây ra hiện tượng tràn chữ, đè chữ và lệch layout khi xem trên các màn hình có độ phân giải trung bình hoặc nhỏ.
* **Trang chủ rối mắt:** Các phần giới thiệu, bài viết truyền cảm hứng và sản phẩm nổi bật sắp xếp dàn trải, tốn diện tích, thiếu tính phân cấp trực quan của thương hiệu Nike.
* **Chưa có Theme Toggle:** Giao diện storefront mặc định chỉ hiển thị chế độ sáng, chưa hỗ trợ tối hoặc tự động đổi theo hệ điều hành, làm giảm trải nghiệm người dùng vào ban đêm.
* **C2C Marketplace bị trói buộc:** Để đăng bán giày cũ, người dùng bắt buộc phải chọn sản phẩm và biến thể (size, màu) từ catalog chính hãng của cửa hàng. Điều này làm cho tính năng Marketplace không thực tế vì người dùng không thể đăng các dòng giày ngoài danh mục có sẵn của shop.
* **Không hiển thị được tin đăng tự nhập:** Do cơ sở dữ liệu và logic thiết kế cũ giả định tin đăng luôn trỏ đến một `product_variant_id` hợp lệ, nên các tin đăng thiếu liên kết này sẽ gây ra lỗi null pointer (lỗi crash trang) khi render thông tin sản phẩm, thương hiệu, size hay màu sắc.
* **Giao diện Marketplace đơn điệu:** Các thẻ sản phẩm cũ hiển thị nghèo nàn thông tin, thiếu nhãn phân biệt nguồn gốc (hàng chính hãng ký gửi hay hàng tự nhập) và thiếu liên kết trực tiếp để duyệt nhanh.

## 3. Việc đã thực hiện

### 3.1. Mở rộng Seeder Giày (Shoe Seeder Expansion)
* Cập nhật `LiveProductSeeder.php`, bổ sung phương thức `phaseSevenShoeProducts()` để thêm hơn 30 sản phẩm giày Nike nổi tiếng: Air Max 90/97/Plus/Excee, Air Force 1 Low/Shadow, Dunk Low Panda/High, Blazer Mid '77, Court Vision Low, Pegasus 40, Structure 25, InfinityRN 4, Free Run 5.0, Flex Experience Run 12, Revolution 7, Metcon 9 AMP, Free Metcon 5, Air Zoom TR 1, Giannis Immortality 3, LeBron Witness 8, KD Trey 5 X, Air Jordan 1 Low/Mid, Zion 3, và các sản phẩm dành riêng cho Nữ/Trẻ em.
* Cung cấp tên, mô tả chi tiết bằng tiếng Việt, và tích hợp các thuộc tính phong phú (câu chuyện sản phẩm, điểm nổi bật, hướng dẫn chăm sóc) cùng hệ thống ảnh placeholder SVG tối giản được ánh xạ động.

### 3.2. Cấu trúc lưới Responsive cho Admin Dashboard
* Thay thế thuộc tính flexbox cứng nhắc cũ bằng cấu trúc CSS Grid linh hoạt: `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6`.
* Nhờ đó, các thẻ widget (Tổng đơn hàng chờ, Tin chờ duyệt, Hỗ trợ mới, Cảnh báo kho hàng) tự động xuống dòng và co giãn mượt mà theo kích thước chiều ngang của trình duyệt mà không bị đè chữ.

### 3.3. Thiết lập Theme Toggle (Sáng / Tối / Hệ thống)
* Tích hợp mã script IIFE gọn nhẹ ở `<head>` của `layouts/app.blade.php` để đọc cấu hình theme lưu trong `localStorage` bằng key `nike-storefront-theme` (mặc định là `system`).
* Nếu là `system`, script tự động kiểm tra chế độ của hệ điều hành thông qua API `window.matchMedia('(prefers-color-scheme: dark)')`.
* Gán các thuộc tính `data-theme` và `data-theme-preference` trực tiếp vào thẻ `<html>` trước khi trang render để tránh hiện tượng nhấp nháy màn hình (FOUC).
* Xây dựng menu dropdown lựa chọn theme trực quan trên Header của storefront. Bổ sung các quy tắc CSS ghi đè trong `resources/css/app.css` để chuyển đổi toàn diện màu nền, màu chữ, viền, ô nhập liệu và các nút bấm sang chế độ Nike Dark Mode cao cấp.

### 3.4. Compact Redesign cho Trang chủ
* Tinh giản khoảng cách spacing, thiết kế lại khu vực bài viết tiếp thị (Article Grid) thành dạng 4 cột cân đối, tối ưu hóa các phần giới thiệu mô hình Nike Hybrid để tạo bố cục liền mạch và thu hút ngay từ cái nhìn đầu tiên.

### 3.5. Logic Đăng bán Tự do (C2C Freeform Selling)
* Loại bỏ sự phụ thuộc bắt buộc vào catalog chính. Người bán có thể chọn nhập thông tin sản phẩm bằng tay (Tên sản phẩm, Thương hiệu, Size, Màu sắc, Ảnh URL hoặc Ảnh file upload).
* Viết các thuộc tính ảo (Accessors) trong Model `MarketplaceListing.php` để tự động chuyển hướng: nếu có `product_variant_id` thì lấy thông tin từ B2C catalog, ngược lại thì trả về dữ liệu tự nhập của người bán.

### 3.6. Cải tiến UI Marketplace
* Cập nhật giao diện thẻ sản phẩm và trang chi tiết Marketplace. Thêm nhãn nguồn gốc nổi bật: "Catalog cửa hàng" hoặc "Tin đăng tự nhập".
* Hiển thị đầy đủ tình trạng giày (Mới nguyên hộp, Như mới, Tốt, Đã qua sử dụng), giá yêu cầu, thông tin người đăng (tên và avatar viết tắt), kèm theo mô tả chi tiết từ người bán.

## 4. Thay đổi Database (Database Changes)

Bản cập nhật sử dụng các file migration sau để hỗ trợ lưu trữ thông tin tự nhập:
* **`2026_06_03_151530_add_freeform_fields_to_marketplace_listings_table.php`:**
  * Thay đổi khóa ngoại `product_variant_id` trong bảng `marketplace_listings` thành `nullable()`.
  * Thêm các cột: `product_name` (string, nullable), `brand` (string, nullable), `size` (string, nullable), `color` (string, nullable), `image_url` (string, nullable).
  * Hỗ trợ cơ chế tái xây dựng bảng (Table Rebuilding) riêng cho SQLite để tránh lỗi không hỗ trợ thay đổi khóa ngoại trực tiếp.
  * Thiết lập khóa ngoại `product_variant_id` thành `nullOnDelete()` để tránh mất tin đăng khi biến thể sản phẩm gốc bị xóa.

## 5. Danh sách các file thay đổi (Files changed)

* [MarketplaceListing.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Models/MarketplaceListing.php): Thêm các accessors hiển thị động (`getDisplayNameAttribute`, `getDisplayBrandAttribute`, `getDisplaySizeAttribute`, `getDisplayColorAttribute`, `getDisplayImageUrlAttribute`, `getDisplaySourceAttribute`, `getStatusLabelAttribute`, `getConditionLabelAttribute`).
* [MarketplaceController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/MarketplaceController.php): Thay đổi validation trong hàm `store`, hỗ trợ upload ảnh local và xử lý lưu các trường freeform.
* [MarketplaceService.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Services/MarketplaceService.php): Cập nhật phương thức `createListing` nhận dữ liệu freeform và gán trạng thái mặc định `pending`.
* [MarketplaceListingFactory.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/database/factories/MarketplaceListingFactory.php): Thêm trạng thái `freeform()` để tạo tin đăng tự nhập ngẫu nhiên phục vụ testing.
* [LiveProductSeeder.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/database/seeders/LiveProductSeeder.php): Thêm dữ liệu giày phong phú, hàm ánh xạ ảnh fallback SVG, tự sinh nội dung chi tiết.
* [app.css](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/css/app.css): Cấu trúc màu tối, định nghĩa màu sắc hệ thống và quy tắc hiển thị dark mode storefront.
* [app.js](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/js/app.js): Script xử lý menu dropdown theme, lưu trạng thái preference và lắng nghe sự thay đổi theme của hệ thống.
* [app.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/layouts/app.blade.php): Script IIFE chống giật giao diện sáng tối, tích hợp nút điều khiển theme trên header.
* [admin.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/layouts/admin.blade.php): Đảm bảo giao diện quản trị luôn ở chế độ tối độc bản (`class="dark" bg-[#09090b] text-[#fafafa]`).
* [index.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/marketplace/index.blade.php): Render lưới tin đăng, nút "Đăng bán", hiển thị mục "Tin của bạn".
* [create.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/marketplace/create.blade.php): Cập nhật form đăng bán: hỗ trợ nhập tự do song song với chọn catalog, thêm preview thời gian thực bằng JS, cho phép upload file ảnh trực tiếp.
* [show.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/marketplace/show.blade.php): Chi tiết tin rao vặt, hiển thị nhãn nguồn gốc và cảnh báo chưa có Escrow.
* [AdminController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/Admin/AdminController.php): Sắp xếp lại widget dashboard thành lưới 4 cột, hiển thị danh sách tin đăng chờ duyệt thực tế.

## 6. Lỗi đã sửa (Bugs fixed)

| Bug | Nguyên nhân | Cách fix | Trạng thái |
| --- | --- | --- | --- |
| C2C bắt buộc chọn catalog | Schema cũ bắt buộc cột `product_variant_id` phải liên kết với một biến thể thực tế trong hệ thống. | Tạo migration chuyển `product_variant_id` sang dạng nullable và cập nhật controller cho phép bỏ trống. | Đã sửa |
| Crash trang Marketplace khi tin đăng không có variant | View cố gắng truy cập `$listing->variant->product->name` trực tiếp mà không kiểm tra biến null. | Sử dụng các thuộc tính ảo (Accessors) trong Model để tự động fallback sang trường tự nhập khi `product_variant_id` null. | Đã sửa |
| Dashboard admin bị chen chúc | Class CSS flexbox dàn một hàng khiến các thẻ bị thu hẹp diện tích trên màn hình máy tính nhỏ. | Chuyển sang lưới CSS Grid responsive, tự động chuyển về 1 cột trên điện thoại và 2-4 cột trên màn hình rộng. | Đã sửa |
| Trang chủ quá dài, rối rắm | Khoảng cách bố cục rộng, thiếu tính phân tách khu vực tiếp thị và sản phẩm. | Gom gọn bố cục trang chủ, sắp xếp lưới hiển thị bài viết truyền cảm hứng thành 4 cột tinh tế. | Đã sửa |
| Thiếu nút chỉnh chế độ sáng tối | Giao diện chỉ có màu sáng mặc định của hệ thống. | Tích hợp menu Theme Toggle lưu preferences trong `localStorage` và đồng bộ theo chế độ của OS. | Đã sửa |
| Nav/layout chợ đồ cũ chưa đẹp | Thiết kế thiếu thông tin xuất xứ sản phẩm cũ và trạng thái độ mới. | Cập nhật layout thẻ card, thêm nhãn nguồn gốc, màu sắc, size và tình trạng thực tế của giày cũ. | Đã sửa |

## 7. Kết quả Test / Build

* **Chạy thử nghiệm test suite:**
  * Tệp `tests/Feature/MarketplaceTest.php` thực hiện kiểm tra luồng tạo tin đăng freeform, kiểm tra file upload, và kiểm tra hiển thị phía admin/moderator.
  * Kết quả: **17 tests in MarketplaceTest passed** hoàn toàn.
* **Định dạng code (Pint):** Chạy `vendor/bin/pint --dirty --format agent` thành công.
* **Biên dịch Frontend (npm build):** Chạy `cmd /c npm run build` tạo file manifest và asset biên dịch css/js thành công mà không có cảnh báo lỗi.

## 8. Kết quả cuối cùng

Phase 7A đạt trạng thái **PASS / Hoàn thành**. 
* **Rủi ro còn lại:** Do hỗ trợ tải ảnh trực tiếp từ máy của người bán, dung lượng lưu trữ trên đĩa (Disk Storage) có thể tăng nhanh. Cần xem xét tích hợp giới hạn kích thước tệp upload hoặc tối ưu hóa hình ảnh ở các phase sau.
