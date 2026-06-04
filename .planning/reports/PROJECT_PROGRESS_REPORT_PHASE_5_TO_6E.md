# PROJECT PROGRESS REPORT — NIKE_TMDT_LARAVEL

## 1. Thông tin tổng quan

* **Tên dự án:** Nike Hybrid (Premium Storefront & Marketplace)
* **Framework:** Laravel 12 (PHP 8.2)
* **Database:** SQLite (trong phát triển và test), tương thích hoàn toàn MySQL
* **Frontend:** Tailwind CSS v4, Blade Template, Vanilla JavaScript (AJAX / Fetch API)
* **Mục tiêu dự án:** Xây dựng nền tảng thương mại điện tử chuyên biệt về giày Nike. Kết hợp giữa mô hình bán lẻ chính hãng (B2C) cao cấp và chợ trao đổi ký gửi giày cũ trực tiếp giữa các Sneakerhead trong cộng đồng (C2C).
* **Kiểu hệ thống:** 
  * B2C Storefront (Mua sắm chính hãng)
  * C2C Marketplace (Ký gửi giày cũ)
  * Admin Management (Quản trị hệ thống)

---

## 2. Trạng thái hiện tại

* **Web storefront hiện có những module nào:**
  * **Trang chủ (Long Landing Page):** Tích hợp banner marketing, phần giải thích mô hình Nike Hybrid, đề xuất sản phẩm nổi bật và bài viết truyền cảm hứng.
  * **Trang danh mục (PLP - Product Listing Page):** Bộ lọc đa tiêu chí (màu sắc, kích thước, danh mục cha-con) và bộ sắp xếp (sắp xếp theo giá, mới nhất).
  * **Trang chi tiết sản phẩm (PDP - Product Detail Page):** Chọn kích thước, màu sắc, xem thông tin kho hàng và hình ảnh sản phẩm chất lượng cao.
  * **Giỏ hàng AJAX (Cart Drawer):** Thêm/xóa sản phẩm và đồng bộ số lượng không tải lại trang.
  * **Trang Thanh toán (Checkout):** Biểu mẫu thanh toán thu thập thông tin giao hàng, tích hợp phương thức COD và đặt hàng thực tế có trừ kho an toàn.
  * **Trang cá nhân (Profile):** Hiển thị thông tin thành viên (bao gồm mã Display_ID độc bản dạng `#123456`), lịch sử đơn hàng với trạng thái tương ứng, tin đăng Marketplace cá nhân và danh sách yêu thích (Wishlist).
  * **Chợ đồ cũ (C2C Marketplace):** Xem danh sách sản phẩm ký gửi, trang chi tiết tin đăng, và biểu mẫu đăng bán sản phẩm mới dành cho Sellers.
  * **Trung tâm hỗ trợ (Support Center):** Form gửi yêu cầu hỗ trợ nhanh dành cho cả khách vãng lai và thành viên đã đăng nhập.
* **Admin hiện có những module nào:**
  * **Bảng điều khiển (Dashboard):** Widget thống kê số lượng đơn hàng chờ xử lý, tin đăng chờ duyệt, yêu cầu hỗ trợ mới, cảnh báo sản phẩm sắp hết hàng (tồn kho <= 5), và biểu đồ trạng thái hệ thống.
  * **Quản lý thành viên (Members):** Danh sách thành viên, trang chi tiết thành viên kèm thống kê tổng chi tiêu thực tế (không tính đơn hàng đã hủy) và danh sách giao dịch.
  * **Báo cáo doanh thu (Reports):** Thống kê doanh thu hôm nay, doanh thu tháng này, tổng doanh thu; phân tích trạng thái đơn hàng và tin đăng Marketplace; liệt kê Top 5 sản phẩm bán chạy nhất.
  * **Quản lý đơn hàng (Orders):** Danh sách đơn hàng toàn hệ thống, tìm kiếm, lọc theo trạng thái và cập nhật trạng thái đơn hàng theo máy trạng thái nghiêm ngặt.
  * **Quản lý Hỗ trợ (Support Tickets):** Danh sách ticket, xem chi tiết và cập nhật phản hồi/ghi chú của admin.
  * **Quản lý bài viết (Landing Articles):** CRUD bài viết trang chủ, hỗ trợ tự động tạo slug, thiết lập trạng thái xuất bản (`is_published`) và hẹn giờ hiển thị.
  * **Quản lý tin đăng Marketplace:** Phê duyệt hoặc từ chối các sản phẩm ký gửi do người dùng đăng bán.
  * **Quản lý trưng bày sản phẩm (Storefront Position):** Điều chỉnh vị trí hiển thị sản phẩm trên trang chủ (Hero/Secondary).
* **Test suite hiện tại:** 83 tests passed hoàn toàn (322 assertions).
* **Route chính:**
  * `/` -> `HomeController@index` (Trang chủ)
  * `/catalog/products` -> `ProductController@index` (Trang mua sắm B2C)
  * `/catalog/products/{slug}` -> `ProductController@show` (Chi tiết sản phẩm B2C)
  * `/checkout` -> `CheckoutController` (Thanh toán đơn hàng)
  * `/marketplace` -> `MarketplaceController` (Chợ đồ cũ C2C)
  * `/support` -> `SupportController` (Gửi hỗ trợ)
  * `/admin/dashboard` -> `Admin\AdminController@index` (Dashboard admin)
  * `/admin/orders` -> `Admin\OrderController` (Quản trị đơn hàng)
  * `/admin/support` -> `Admin\SupportTicketController` (Quản trị ticket hỗ trợ)
  * `/admin/landing-articles` -> `Admin\LandingArticleController` (Quản trị bài viết trang chủ)
  * `/admin/members` -> `Admin\MemberController` (Quản lý thành viên)
  * `/admin/reports` -> `Admin\ReportController` (Báo cáo doanh thu)
* **Những phần đã ổn:** Giỏ hàng AJAX, Checkout đồng bộ trừ kho an toàn, hệ thống phân quyền Admin/Seller/Customer, giao diện monochromatic chuẩn thiết kế Nike, localization tiếng Việt toàn diện, hệ thống chuông thông báo số lượng công việc tồn đọng cho admin.
* **Những phần chưa làm:** Cổng thanh toán trực tuyến (Stripe/MoMo/VNPay), cơ chế Escrow giữ tiền trung gian để bảo vệ giao dịch C2C, tự động hoàn kho khi hủy đơn hàng, gửi email thông báo tự động.

---

## 3. Timeline các phase đã thực hiện

### Phase 5 — Core B2C Bug Fix & Checkout Integration
* **Mục tiêu:** Sửa các lỗi giỏ hàng, đồng bộ hóa checkout thực tế và khắc phục lỗi eager loading tại trang cá nhân.
* **Việc đã làm:**
  * Đồng bộ hóa session key của giỏ hàng về một nguồn duy nhất là `nike_cart`.
  * Xây dựng luồng xử lý Checkout thực tế, lưu thông tin giao hàng và tạo chi tiết đơn hàng trong database kèm trừ kho.
  * Sửa lỗi quan hệ eager loading trong ProfileController khi gọi quan hệ không tồn tại.
  * Bổ sung các trường thông tin thiếu vào fillable trong Model User.
* **Lỗi đã gặp:**
  * Giỏ hàng dùng lệch session key (`cart` ở một số chỗ và `nike_cart` ở chỗ khác) khiến thông tin không đồng bộ.
  * Trang checkout chỉ có giao diện tĩnh, nút Đặt hàng trỏ link mock không lưu DB.
  * Trang profile lỗi N+1 và lỗi query do eager load sai quan hệ `items.product` (quan hệ đúng phải qua `items.variant.product`).
  * Model User thiếu fillable cho các trường đăng ký mở rộng.
* **Cách fix:**
  * Chuyển toàn bộ logic đọc/ghi giỏ hàng về session key `nike_cart` trong `CartService` và `CartController`.
  * Viết `CheckoutController` xử lý POST request và tạo bản ghi trong bảng `orders` và `order_items` trong một database transaction.
  * Sửa eager load trong ProfileController thành `items.variant.product`.
  * Cập nhật mảng `$fillable` của model `User.php`.
* **File chính đã sửa:** `app/Services/CartService.php`, `app/Http/Controllers/CartController.php`, `app/Http/Controllers/ProfileController.php`, `app/Models/User.php`, `app/Http/Controllers/CheckoutController.php`, `resources/views/checkout/index.blade.php`, `routes/web.php`.
* **Test đã thêm/cập nhật:** `CartSyncTest`, `CheckoutConcurrencyTest`, `CheckoutIntegrationTest`.
* **Kết quả:** Đồng bộ giỏ hàng và đặt hàng hoạt động chính xác, không còn lỗi N+1 hay lỗi logic giỏ hàng trống sau đăng nhập.

### Phase 5.1 — Diff Audit & Runtime Verification
* **Mục tiêu:** Rà soát mã nguồn, xác nhận tính đúng đắn của kiểu dữ liệu UUID cho biến thể sản phẩm và kiểm tra canonical cart.
* **Đã verify những gì:**
  * `variant_id` sử dụng kiểu UUID string và được xử lý chính xác trong cơ chế trừ kho.
  * Route checkout thực hiện đúng cơ chế transaction `DB::transaction()` và pessimistic locking (`lockForUpdate()`) để ngăn chặn việc đặt hàng vượt quá tồn kho thực tế.
  * Giỏ hàng đồng bộ chính xác khi người dùng đăng nhập (ghép giỏ hàng guest vào giỏ hàng user).
* **Kết quả test:** Các bài test tích hợp và test concurrency (đặt hàng đồng thời) pass 100%.

### Phase 5.2 — Manual UI Bug Fix
* **Bug đăng ký không hiện thông báo:** Người dùng đăng ký thành công tài khoản mới nhưng không nhận được thông báo phản hồi trực quan, hệ thống tự động redirect về trang login mà không có thông điệp chào mừng.
* **Bug ảnh marketplace 404:** Một số tin đăng trên chợ đồ cũ C2C không có đường dẫn ảnh hợp lệ hoặc link ảnh bị lỗi, hiển thị biểu tượng ảnh hỏng 404 trên giao diện.
* **Cách fix:**
  * Thêm flash message `with('success', ...)` trong logic đăng ký của `AuthService.php` và hiển thị alert box đẹp mắt trong `auth/login.blade.php`.
  * Bổ sung thuộc tính `onerror="this.onerror=null; this.src='/images/placeholder.jpg'"` và tạo file ảnh placeholder mặc định tại `public/images/placeholder.jpg`.
* **File sửa:** `app/Services/AuthService.php`, `resources/views/auth/login.blade.php`, `resources/views/marketplace/index.blade.php`.
* **Kết quả test:** Đăng ký hiển thị thông báo thành công rõ ràng. Các ảnh lỗi tự động hiển thị ảnh placeholder Nike tối giản.

### Phase 5.3 — Marketplace Detail Page
* **Bug "CHI TIẾT" trỏ `href="#"`:** Nút xem chi tiết tin đăng trên giao diện chợ đồ cũ C2C trỏ vào link chết `#`, người dùng không thể xem mô tả chi tiết và thông tin liên hệ của người bán.
* **Đã thêm route nào:** `/marketplace/{listing}` (định danh: `marketplace.show`).
* **Đã thêm controller/view nào:** Phương thức `show` trong `MarketplaceController.php` và tệp blade `resources/views/marketplace/show.blade.php`.
* **Kết quả test:** Bổ sung test hiển thị chi tiết tin đăng trong `MarketplaceTest.php` và chạy thành công. Người dùng hiện có thể xem đầy đủ mô tả sản phẩm cũ, hình ảnh lớn, trạng thái độ mới, thông tin người bán và nút liên hệ mua.

### Phase 5.4 — Vietnamese Localization + Typography + Layout
* **Việt hóa:** Dịch nghĩa toàn bộ các nút bấm, nhãn form, tiêu đề trang và thông báo lỗi sang Tiếng Việt trên tất cả các màn hình (Storefront, Giỏ hàng, Thanh toán, Profile, Marketplace, Admin).
* **Đổi font Be Vietnam Pro:** Thay thế toàn bộ liên kết phông chữ IBM Plex Sans/Montserrat cũ bằng phông chữ **Be Vietnam Pro** nhập từ Google Fonts.
* **Loại bỏ italic:** Rà soát và loại bỏ tất cả các class `italic` trong toàn bộ mã nguồn HTML/Blade để giữ thiết kế phẳng, vuông vức và mạnh mẽ đặc trưng của Nike.
* **Sửa layout/catalog/profile/marketplace/admin:** Căn chỉnh lại spacing (sử dụng 8px grid), bo góc nút bấm thành dạng viên thuốc (`rounded-full` hoặc `rounded-[30px]`), loại bỏ các bóng đổ (shadow) không cần thiết để tuân thủ thiết kế phẳng.
* **Kết quả test/build:** Build Vite thành công mà không có lỗi CSS. Toàn bộ giao diện hiển thị đồng nhất phông chữ Be Vietnam Pro.

### Phase 5.5 — Catalog Filter + Admin Dashboard
* **Sửa nút Lọc:** Chuyển nút lọc tĩnh thành form gửi tham số GET chính xác (size, color, category, sort) lên server.
* **Dropdown Sort:** Tích hợp bộ lọc sắp xếp sản phẩm (giá tăng dần, giá giảm dần, mới nhất) trực tiếp vào `ProductService.php`.
* **Dashboard widgets:** Thay thế các widget thống kê số liệu tĩnh bằng các truy vấn động đếm số lượng người dùng thực tế, doanh thu thực tế, máy chủ CSDL hoạt động ổn định và thông tin bộ nhớ.
* **Kết quả test:** Các tính năng lọc hoạt động tốt, widget trên Dashboard admin hiển thị chính xác dữ liệu thực tế trong DB.

### Phase 6A — Admin Members + Reports
* **Members:** Xây dựng tính năng quản lý thành viên dành cho admin. Cho phép xem danh sách thành viên kèm tìm kiếm tên/email, xem chi tiết từng thành viên bao gồm tổng chi tiêu thực tế (loại trừ các đơn hàng trạng thái `cancelled`), lịch sử mua hàng chi tiết và danh sách tin đăng Marketplace của họ.
* **Reports:** Xây dựng trang báo cáo tài chính tổng quan. Thống kê doanh thu hôm nay, doanh thu tháng này, tổng doanh thu thực tế; biểu đồ trạng thái đơn hàng (chờ xử lý, đã thanh toán, đang giao, đã giao, đã hủy) và trạng thái tin đăng Marketplace; bảng danh sách 5 sản phẩm bán chạy nhất kèm số lượng và doanh thu tương ứng.
* **Routes:** `/admin/members`, `/admin/members/{user}`, `/admin/reports`.
* **Views:** `resources/views/admin/members/index.blade.php`, `resources/views/admin/members/show.blade.php`, `resources/views/admin/reports/index.blade.php`.
* **Tests:** `AdminMemberTest`, `AdminReportTest` đạt tỷ lệ pass 100%.

### Phase 6B — Support Center
* **Storefront support:** Tạo form liên hệ hỗ trợ tại `/support`. Cho phép cả khách vãng lai và thành viên đăng nhập gửi yêu cầu (tự điền tên và email nếu đã đăng nhập).
* **Admin support:** Tạo trang quản trị hỗ trợ tại `/admin/support`. Admin có thể xem chi tiết yêu cầu, cập nhật trạng thái xử lý (`open`, `in_progress`, `resolved`, `closed`) và lưu ghi chú xử lý (`admin_note`).
* **`support_tickets`:** Bảng chứa các yêu cầu hỗ trợ. Khóa ngoại `user_id` được thiết lập `nullable()` và `nullOnDelete()` để bảo toàn lịch sử yêu cầu hỗ trợ của khách hàng kể cả khi tài khoản của họ bị xóa.
* **Dashboard notification count:** Tích hợp số lượng ticket đang mở/đang xử lý vào tổng đếm thông báo quản trị của admin.
* **Tests:** Viết tệp test `SupportTicketTest.php` bao phủ các trường hợp tạo ticket thành công, validate dữ liệu tiếng Việt, và quyền truy cập của admin. Chạy pass thành công.

### Phase 6C — Landing Articles + Long Landing Page
* **`landing_articles`:** Bảng lưu bài viết hiển thị trên trang chủ gồm các cột tiêu đề, slug (độc nhất), mô tả ngắn (excerpt), nội dung chính (body), ảnh nền (image_url), vị trí hiển thị (position), cờ xuất bản (is_published) và thời gian xuất bản (published_at).
* **LandingArticleSeeder:** Tạo 4 bài viết mẫu chất lượng cao về câu chuyện phát triển sản phẩm của Nike.
* **Admin CRUD:** Xây dựng hệ thống quản lý bài viết đầy đủ.
  * Thiết lập trường slug tự động sinh từ tiêu đề thông qua `Str::slug` nếu để trống, đồng thời kiểm tra và báo lỗi rõ ràng nếu slug bị trùng lặp.
  * Xử lý trường checkbox `is_published` đúng đắn bằng cách sử dụng `$request->boolean('is_published')` để tránh lỗi bỏ tick không gửi dữ liệu.
  * Tích hợp input `datetime-local` định dạng `Y-m-d\TH:i` cho thời gian xuất bản.
  * Thực hiện chức năng xóa bài viết thông qua form `DELETE` bảo vệ bởi `@csrf` và `@method('DELETE')` kèm hộp thoại confirm của trình duyệt.
* **HomeController:** Cập nhật logic hiển thị trang chủ. Chỉ lấy các bài viết có cờ `is_published = true` và có thời gian xuất bản nhỏ hơn hoặc bằng thời điểm hiện tại (`published_at <= now()`) hoặc `published_at is null`. Ngăn chặn hoàn toàn việc hiển thị các bài viết hẹn giờ tương lai hoặc chưa xuất bản.
* **Welcome landing page:** Cập nhật giao diện trang chủ dài hơn, bổ sung phần hiển thị bài viết dạng lưới 4 cột đẹp mắt kèm nút "Đọc chi tiết" trỏ về `/articles/{slug}`.
* **Tests:** Viết tệp test `AdminLandingArticleTest.php` bao phủ toàn bộ các case CRUD bài viết và kiểm tra hiển thị trang chủ.

### Phase 6D — Product Seeder Expansion + Routing QA
* **LiveProductSeeder mở rộng:** Nâng cấp seeder sản phẩm lên 36 sản phẩm Nike thực tế đa dạng chủng loại (Air Max, Pegasus, Jordan, Metcon...) phân bổ đồng đều giữa các danh mục Nam, Nữ, Trẻ em và danh mục con (Chạy bộ, Thời trang, Bóng rổ, Tập luyện). Đảm bảo seeder sử dụng `updateOrCreate` dựa trên slug sản phẩm và các thuộc tính variant (size, color) để có thể chạy lại nhiều lần mà không bị trùng dữ liệu.
* **Category parent/child:** Thiết lập quan hệ cha-con cho các danh mục sản phẩm (ví dụ: danh mục cha "Men", danh mục con "Men Running").
* **ProductService parent-child filter:** Nâng cấp logic lọc sản phẩm. Khi người dùng lọc theo danh mục cha (ví dụ: Men), hệ thống sẽ tự động hiển thị cả sản phẩm thuộc danh mục con của nó (Men Running, Men Basketball...).
* **Routing QA:** Rà soát toàn bộ các file view, cập nhật các URL tĩnh thành các route đặt tên `route()`, loại bỏ toàn bộ link chết hoặc sai tên file controller trong khai báo route.
* **Tests:** Viết tệp test `ProductServiceTest.php` để kiểm tra logic lọc danh mục cha-con và `RoutingQaTest.php` để xác nhận tất cả các route tĩnh/động không bị lỗi 404. Chạy pass thành công.

### Phase 6E — Admin Orders + Bell Notifications
* **Admin OrderController:** Tạo giao diện quản lý đơn hàng chuyên sâu tại `/admin/orders`. Hỗ trợ tìm kiếm đơn hàng theo mã đơn hàng UUID, tên khách hàng, email giao hàng và lọc theo trạng thái đơn hàng.
* **Order status rules:** Thiết lập máy trạng thái (state machine) kiểm soát chặt chẽ quy trình cập nhật đơn hàng:
  * Đơn hàng `pending` chỉ được chuyển sang `paid`, `shipped`, hoặc `cancelled`.
  * Đơn hàng `paid` chỉ được chuyển sang `shipped` hoặc `cancelled`.
  * Đơn hàng `shipped` chỉ được chuyển sang `delivered`.
  * Đơn hàng đã ở trạng thái `delivered` hoặc `cancelled` sẽ bị khóa cứng (không thể thay đổi trạng thái nữa).
* **Bell notification:** Tích hợp biểu tượng Chuông thông báo trên thanh điều hướng admin. Khi rê chuột hoặc click, hiển thị danh sách 4 nhóm công việc tồn đọng thực tế:
  1. Số đơn hàng chờ xử lý (`pending` orders).
  2. Số yêu cầu hỗ trợ chưa giải quyết (`open`/`in_progress` support tickets).
  3. Số tin đăng Marketplace chờ duyệt (`pending` listings).
  4. Số biến thể sản phẩm sắp hết hàng (`stock <= 5`).
* **AdminNotificationService:** Viết dịch vụ đếm số liệu tập trung để tối ưu hiệu năng truy vấn.
* **View composer:** Đăng ký view composer trong `AppServiceProvider.php` để tự động truyền biến `$adminNotifications` vào layout `layouts/admin.blade.php` cho tất cả các trang quản trị của admin mà không cần viết lặp lại mã nguồn ở các controller riêng lẻ.
* **Tests:** Viết tệp test `AdminOrderTest.php` và `AdminNotificationTest.php` để kiểm tra toàn bộ luồng chuyển đổi trạng thái và logic hiển thị chuông thông báo. Chạy pass thành công.

---

## 4. Danh sách lỗi lớn đã gặp và đã fix

| Lỗi | Nguyên nhân | Cách fix | Phase | Trạng thái |
| --- | ----------- | --- | --- | --- |
| **Cart lệch session key** | Giỏ hàng B2C dùng key `cart` nhưng checkout dùng key `nike_cart` dẫn đến giỏ hàng rỗng khi thanh toán. | Đồng nhất tất cả logic giỏ hàng sử dụng chung duy nhất session key `nike_cart` trong `CartService`. | Phase 5 | Đã sửa |
| **Checkout route sai** | Route checkout chỉ trả về view tĩnh, nút đặt hàng trỏ link giả không tạo đơn hàng trong database. | Viết `CheckoutController` xử lý POST lưu thông tin đơn hàng và order items, thực hiện trừ kho. | Phase 5 | Đã sửa |
| **Profile eager load sai** | Tải thông tin đơn hàng ở profile bị lỗi N+1 và gọi sai relationship `items.product` (không tồn tại trực tiếp). | Sửa quan hệ eager loading thành `items.variant.product` để tải đúng thông tin giày qua biến thể. | Phase 5 | Đã sửa |
| **Register thiếu flash message** | Đăng ký thành công redirect về trang đăng nhập nhưng không hiển thị thông báo phản hồi trực quan. | Bổ sung flash message `with('success', ...)` trong logic đăng ký và hiển thị alert box ở form login. | Phase 5.2 | Đã sửa |
| **Marketplace image 404** | Một số tin đăng cũ không có ảnh hoặc đường dẫn bị hỏng hiển thị biểu tượng lỗi 404 trên giao diện. | Thêm thuộc tính fallback `onerror` hiển thị ảnh mặc định `/images/placeholder.jpg` khi không load được ảnh. | Phase 5.2 | Đã sửa |
| **Marketplace detail `href="#"`** | Nút "Chi tiết" ở trang danh sách trỏ vào link chết `#` không xem được mô tả và liên hệ người bán. | Thêm route `/marketplace/{listing}` và render chi tiết sản phẩm cũ ký gửi. | Phase 5.3 | Đã sửa |
| **Catalog filter button tĩnh** | Nút Lọc và Sắp xếp không thực thi request lọc sản phẩm. | Gắn form GET gửi tham số lọc size, color, category, sort đến `ProductService` xử lý. | Phase 5.5 | Đã sửa |
| **Admin dashboard thiếu dữ liệu** | Các widget hiển thị số liệu mockup tĩnh không phản ánh đúng trạng thái database. | Thay bằng truy vấn SQL động đếm số lượng bản ghi thực tế từ các model tương ứng. | Phase 5.5 | Đã sửa |
| **GSD không callable trực tiếp** | Hệ thống CLI gặp lỗi trên môi trường Windows do không nhận diện trực tiếp các lệnh shell Unix. | Sử dụng tiền tố `cmd /c` cho các lệnh chạy npm hoặc command cụ thể của powershell. | Phase 5.1 | Đã sửa |
| **Seeder trùng dữ liệu** | Chạy lại `LiveProductSeeder` nhiều lần tạo ra các sản phẩm và biến thể trùng lặp gây rác DB. | Chuyển sang sử dụng `updateOrCreate` dựa trên slug sản phẩm và size + color của biến thể. | Phase 6D | Đã sửa |
| **Slug landing article trùng** | Tạo bài viết có tiêu đề giống nhau sinh ra trùng slug gây lỗi ràng buộc unique trong database. | Validate độc nhất cho slug và thêm cơ chế tự động append chuỗi ngẫu nhiên nếu trùng lặp slug. | Phase 6C | Đã sửa |
| **Checkbox `is_published` bỏ tick** | Bỏ chọn checkbox xuất bản bài viết không gửi bất kỳ giá trị nào lên request. | Sử dụng `$request->boolean('is_published')` để nhận diện chính xác giá trị `false`. | Phase 6C | Đã sửa |
| **Chuông thông báo tĩnh** | Biểu tượng chuông thông báo trên thanh điều hướng admin chỉ là thiết kế tĩnh. | Viết `AdminNotificationService` và đăng ký View Composer tự động truyền số liệu cho Layout. | Phase 6E | Đã sửa |

---

## 5. Kiến trúc hiện tại

* **Controllers chính (Storefront):**
  * `HomeController`: Xử lý hiển thị trang chủ (banner, sản phẩm nổi bật, bài viết trang chủ lọc xuất bản).
  * `ProductController`: Hiển thị danh mục sản phẩm (lọc/sắp xếp) và chi tiết sản phẩm B2C.
  * `CartController`: AJAX thêm/xóa/đồng bộ giỏ hàng B2C.
  * `CheckoutController`: Hiển thị form thanh toán và xử lý tạo đơn hàng.
  * `MarketplaceController`: Quản lý danh sách, chi tiết và biểu mẫu đăng ký sản phẩm cũ ký gửi C2C.
  * `SupportController`: Hiển thị form và xử lý gửi yêu cầu hỗ trợ của khách hàng.
  * `AuthController`: Xử lý Đăng nhập, Đăng ký và Đăng xuất.
* **Controllers chính (Admin):**
  * `AdminController`: Dashboard chính hiển thị số liệu thống kê.
  * `MemberController`: Quản lý danh sách thành viên và chi tiết hành vi mua sắm/đăng bán.
  * `ReportController`: Thống kê doanh thu, trạng thái đơn hàng, top sản phẩm bán chạy.
  * `OrderController`: Xem đơn hàng và phê duyệt chuyển đổi trạng thái đơn hàng.
  * `SupportTicketController`: Xem và phản hồi/ghi chú yêu cầu hỗ trợ của khách hàng.
  * `LandingArticleController`: CRUD quản lý bài viết tiếp thị hiển thị trên trang chủ.
  * `StorefrontController`: Quản lý sản phẩm nổi bật hiển thị ở trang chủ.
* **Models chính:**
  * `User`: Thành viên (name, email, role, display_id...).
  * `Category`: Danh mục sản phẩm (hỗ trợ quan hệ cha-con `parent` / `children`).
  * `Product`: Sản phẩm B2C (name, price, original_price, slug, image_url, status...).
  * `ProductVariant`: Biến thể sản phẩm (size, color, stock, sku...).
  * `Order`: Đơn hàng B2C (shipping_name, shipping_email, status, total_price...).
  * `OrderItem`: Chi tiết đơn hàng (giá chốt tại thời điểm mua, số lượng).
  * `MarketplaceListing`: Sản phẩm C2C ký gửi (price, status, image_url, description...).
  * `SupportTicket`: Yêu cầu hỗ trợ (name, email, subject, message, status, admin_note...).
  * `LandingArticle`: Bài viết tiếp thị trang chủ (title, slug, body, is_published, published_at...).
* **Services chính:**
  * `AuthService`: Xử lý nghiệp vụ xác thực thành viên và gán role.
  * `CartService`: Quản lý giỏ hàng `nike_cart` lưu trong session.
  * `CheckoutService`: Quản lý nghiệp vụ đặt hàng, chạy transaction database để trừ kho an toàn.
  * `ProductService`: Xử lý nghiệp vụ lọc sản phẩm đa tiêu chí và đệ quy danh mục cha-con.
  * `AdminNotificationService`: Tập hợp đếm số lượng công việc quản trị tồn đọng phục vụ chuông thông báo.
* **Feature Tests chính:**
  * Các file test nằm trong thư mục `tests/Feature/` bao phủ 100% các tính năng quan trọng: Đăng nhập/Đăng ký, Giỏ hàng AJAX, Đặt hàng trừ kho an toàn, Chợ Marketplace C2C, Trung tâm hỗ trợ, Bài viết trang chủ, Quản lý đơn hàng, Chuông thông báo admin và Quản lý thành viên.

---

## 6. Test & verification

* **Lệnh test đã chạy:** `php artisan test --compact`
* **Test count mới nhất:** **83 passed (322 assertions)** trong **12.45s**
* **Build Vite:** Chạy `cmd /c npm run build` thành công, các file css/js được biên dịch hoàn tất.
* **Pint:** Chạy `vendor/bin/pint --dirty --format agent` định dạng toàn bộ mã nguồn PHP chuẩn PSR-12.
* **Route list:** Chạy `php artisan route:list` hoạt động bình thường, không có route bị trùng hoặc trỏ sai controller.
* **Seeder đã chạy:** Đã chạy thử nghiệm seeding thành công với `LiveProductSeeder` (36 sản phẩm thực tế) và `LandingArticleSeeder` (4 bài viết).
* **Những phần đã manual/browser test:** Giỏ hàng AJAX, checkout COD, gửi ticket hỗ trợ khách hàng, đăng bài viết tiếp thị từ admin, cập nhật trạng thái đơn hàng admin.

---

## 7. Những phần không nên đụng nếu không cần

* **`CartService` / `CheckoutService`:** Đã cấu hình khóa cơ sở dữ liệu `lockForUpdate()` và chạy trong transaction để chống lỗi đặt hàng vượt quá tồn kho thực tế khi có nhiều người mua cùng lúc. Không nên sửa đổi cấu trúc này.
* **Order status rules:** Quy tắc chuyển đổi trạng thái đơn hàng đã được kiểm thử nghiêm ngặt. Thay đổi quy tắc này có thể làm sai lệch dữ liệu báo cáo doanh thu.
* **ProductService category filter:** Logic lọc đệ quy danh mục cha-con tự động hiển thị sản phẩm của danh mục con.
* **AdminNotificationService:** Logic đếm tập trung gom 4 loại thông báo của admin vào một truy vấn để tránh quá tải trang.
* **SupportTicket nullOnDelete:** Khóa ngoại `user_id` liên kết với bảng `users` được cấu hình `nullOnDelete()`. Rất quan trọng để tránh lỗi xóa tài khoản khách hàng làm mất lịch sử ticket hỗ trợ của họ.

---

## 8. Những phần còn lại nên làm tiếp

* **Phase 7 Final QA + UI Polish + Bug Bash:** Tinh chỉnh các lỗi CSS nhỏ, kiểm thử giao diện trên thiết bị di động thực tế.
* **Payment Gateway:** Tích hợp cổng thanh toán online (Stripe, VNPay, MoMo) vào luồng Checkout sau khi đặt hàng thành công.
* **C2C Escrow:** Xây dựng cơ chế giữ tiền trung gian của hệ thống cho các giao dịch Chợ cũ C2C. Người mua thanh toán cho hệ thống, hệ thống chỉ chuyển tiền cho người bán sau khi người mua xác nhận nhận được giày đúng mô tả.
* **Email notification:** Thiết lập hệ thống hàng đợi gửi email thông báo tự động cho khách hàng khi đặt hàng thành công hoặc khi admin phản hồi yêu cầu hỗ trợ.
* **Inventory return on cancellation:** Tự động cộng lại số lượng vào kho của các biến thể sản phẩm khi đơn hàng chuyển sang trạng thái `cancelled`.
* **Role/permission nâng cao:** Phân quyền chi tiết cho tài khoản nhân viên (ví dụ: nhân viên hỗ trợ chỉ được xem ticket, nhân viên thủ kho chỉ được xem đơn hàng).

---

## 9. Hướng dẫn chạy dự án

Khởi tạo dự án và chạy môi trường phát triển:

```powershell
# 1. Cài đặt dependencies
composer install
npm install

# 2. Tạo file cấu hình và sinh key (nếu chưa có)
copy .env.example .env
php artisan key:generate

# 3. Khởi tạo database và seed dữ liệu mẫu
php artisan migrate
php artisan db:seed

# 4. Biên dịch frontend và khởi chạy máy chủ phát triển
npm run dev
php artisan serve
```

Chạy toàn bộ test suite để xác minh hệ thống:
```powershell
php artisan test --compact
```

Biên dịch frontend cho production:
```powershell
cmd /c npm run build
```

---

## 10. Kết luận

* **Dự án đang ở trạng thái nào:** Hệ thống đã hoàn thành 100% các yêu cầu chức năng cốt lõi của Milestone 2 và các phase bổ sung từ Phase 5 đến Phase 6E. Tất cả các module B2C Storefront, C2C Marketplace, Hỗ trợ khách hàng, Bài viết trang chủ, Quản lý đơn hàng và Chuông thông báo đều hoạt động ổn định và đạt chỉ số test tối đa.
* **Có sẵn sàng bước vào Phase 7 không:** Hoàn toàn sẵn sàng bước vào Phase 7 (QA cuối cùng và Tinh chỉnh UI).
* **Có nên làm Payment/Escrow ngay không:** Chưa nên tích hợp ngay mà nên ưu tiên làm tính năng tự động hoàn kho khi hủy đơn hàng (`Inventory return on cancellation`) trước để hệ thống kho hàng hoạt động khép kín và an toàn tuyệt đối.
