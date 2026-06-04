# PHASE 7D — REAL REVIEWS + PROFILE ACTIVITY + C2C STATUS REPORT

## 1. Mục tiêu phase

Phase 7D tập trung vào việc hiện thực hóa toàn bộ trải nghiệm người dùng trên storefront và cải tiến công cụ quản trị Admin: tăng tính chân thực của dữ liệu đánh giá sản phẩm, liên kết tên người đánh giá đến hồ sơ công khai, tích hợp Lịch sử yêu cầu hỗ trợ (Support history) và Quản lý trạng thái bài đăng C2C Marketplace trực tiếp trong trang cá nhân (Profile Dashboard), xây dựng khối bộ lọc "Tin của bạn" trên chợ đồ cũ, và thiết lập trang xem trước tin đăng Marketplace (Admin C2C Preview) kèm nút duyệt trực quan dành cho admin.

Các công việc chính bao gồm:
* Thay thế các đánh giá sản phẩm giả lập/ẩn danh cũ bằng các đánh giá gắn với User thật trong hệ thống, kèm avatar đại diện và bình luận cụ thể cho từng dòng giày.
* Tích hợp tab Lịch sử Hỗ trợ tại trang Profile cá nhân, hiển thị đầy đủ thông tin chủ đề, trạng thái xử lý, phản hồi của admin, tên người xử lý và ngày giải quyết.
* Tích hợp tab Lịch sử Đăng bán tại trang Profile cá nhân, hiển thị tất cả tin đăng C2C của họ cùng trạng thái duyệt chi tiết (Chờ duyệt, Đã duyệt, Bị từ chối, Đã ẩn, Đã xóa).
* Thiết kế khu vực "Tin của bạn" trên trang chủ Marketplace để người dùng theo dõi nhanh trạng thái xuất bản tin rao vặt của mình.
* Xây dựng trang xem trước tin đăng C2C (`admin.marketplace.show`) dành cho quản trị viên, bổ sung nút biểu tượng Con mắt (Preview) để admin kiểm tra thông tin chi tiết của người bán trước khi đưa ra quyết định phê duyệt hoặc từ chối.

## 2. Vấn đề trước khi sửa

* **Đánh giá sản phẩm thiếu tính chân thực:** Các review cũ chỉ lưu chuỗi văn bản tên tác giả tĩnh (`author_name`), không liên kết với ID người dùng thật trong hệ thống, không hiển thị avatar đại diện và không thể nhấp vào để xem hồ sơ cá nhân.
* **Sản phẩm thiếu ảnh đại diện thực tế:** Một số dòng giày mới bổ sung chưa được ánh xạ chính xác sang các đường dẫn ảnh chất lượng cao hoặc các placeholder tương ứng, gây hiện tượng lỗi hiển thị ảnh.
* **Profile cá nhân thiếu thông tin tương tác:** Người dùng không có cách nào xem lại các yêu cầu hỗ trợ mình đã gửi, không biết yêu cầu đã được tiếp nhận hay chưa, và admin phản hồi thế nào.
* **Người dùng không biết trạng thái tin đăng C2C:** Khi đăng bán giày cũ, tin đăng ở trạng thái chờ duyệt hoặc bị từ chối sẽ biến mất hoàn toàn trên giao diện của người dùng. Họ không biết tin đang ở trạng thái nào hoặc có bị lỗi gì không.
* **Chợ đồ cũ hiển thị lẫn lộn tin chưa duyệt:** Nếu không có cơ chế lọc nghiêm ngặt, các tin đăng ở trạng thái chờ duyệt hoặc bị từ chối có thể bị hiển thị công khai trên chợ đồ cũ, gây mất uy tín cho website.
* **Admin duyệt tin C2C "mù":** Trên trang danh sách duyệt tin của admin, hệ thống chỉ hiển thị bảng văn bản ngắn. Admin muốn xem đầy đủ nội dung mô tả chi tiết, hình ảnh phóng to hoặc thông tin email liên hệ của người bán phải truy cập trực tiếp database vì thiếu giao diện xem trước.

## 3. Việc đã thực hiện

### 3.1. Hệ thống Đánh giá thực tế (Real Product Reviews)
* Nâng cấp cơ sở dữ liệu để liên kết cột `user_id` với bảng `users`. Cập nhật seeder khởi tạo 6 tài khoản người dùng thực tế đại diện cho các nhóm khách hàng khác nhau.
* Mỗi sản phẩm catalog được gán ngẫu nhiên 3 đánh giá có nội dung bằng tiếng Việt tự nhiên và sát với thực tế sản phẩm (ví dụ: đánh giá Jordan 1 đi êm chân nhưng hơi chật size, Air Max đế cao thích hợp chạy bộ...).
* Hiển thị avatar đại diện của người đánh giá trên PDP, liên kết tên của họ tới trang hồ sơ công khai `/users/{user}`.

### 3.2. Cập nhật Trang cá nhân công khai (Public Profile Update)
* Giao diện hồ sơ công khai `/users/{user}` hiển thị thống kê tổng số tin đăng đã duyệt, số review đã viết, danh sách các tin rao vặt đang bán công khai. Hệ thống ẩn địa chỉ email và avatar thực tế để bảo vệ thông tin cá nhân.

### 3.3. Tối ưu ảnh sản phẩm chất lượng cao (Product Image Update)
* Cập nhật các đường dẫn ảnh sang ảnh chính thức từ CDN của Nike đối với các sản phẩm tiêu biểu, kết hợp với bộ placeholder SVG được tối ưu hóa độ phân giải hiển thị, loại bỏ hoàn toàn các lỗi hiển thị ảnh hỏng.

### 3.4. Tích hợp Lịch sử Hỗ trợ vào Profile (Profile Support History)
* Thiết lập tab "Hỗ trợ" tại `/profile`. Sử dụng câu lệnh eager loading tải quan hệ `resolver` (Người giải quyết) để hiển thị chi tiết: tiêu đề ticket, ngày gửi, trạng thái tiếng Việt, ghi chú của quản trị viên (`admin_note`), và tên của quản trị viên đã xử lý yêu cầu kèm thời gian giải quyết thực tế.

### 3.5. Theo dõi Trạng thái tin đăng Marketplace trong Profile (Profile C2C Listing Status)
* Thiết lập tab "C2C Marketplace" tại `/profile`. Hiển thị bảng danh sách toàn bộ tin rao bán của người dùng (sử dụng `withTrashed()` để lấy cả tin đã xóa mềm).
* Ánh xạ trạng thái tin đăng sang nhãn tiếng Việt rõ ràng:
  * `pending` -> Chờ duyệt (nhãn màu xám)
  * `active` -> Đã duyệt (nhãn màu xanh lá)
  * `rejected` -> Bị từ chối (nhãn màu đỏ)
  * `hidden` -> Đã ẩn (nhãn màu cam)
  * `deleted` -> Đã xóa (nhãn màu đỏ đậm)

### 3.6. Khu vực "Tin đăng của bạn" trên Marketplace (Marketplace Private Listings Block)
* Tích hợp khối "Tin đăng của bạn" ở đầu trang danh sách Marketplace `/marketplace` dành riêng cho người dùng đã đăng nhập.
* Khu vực này hiển thị các tin đăng của chính tài khoản đó ở mọi trạng thái (bao gồm cả tin chờ duyệt hoặc bị từ chối) để họ tiện theo dõi, trong khi người dùng khác chỉ nhìn thấy các tin ở trạng thái `active` trên lưới công khai phía dưới.

### 3.7. Giao diện Xem trước và Duyệt tin C2C dành cho Admin (Admin C2C Preview)
* Xây dựng route `/admin/marketplace/{listing}` trỏ về hàm `marketplaceShow` trong `AdminController.php`.
* Thiết kế tệp view `admin/marketplace/show.blade.php` hiển thị đầy đủ: ảnh giày cỡ lớn, thông tin chi tiết người bán (tên và email), xuất xứ tin đăng (Catalog hay Tự nhập), tên giày, thương hiệu, size, màu sắc, tình trạng và mô tả chi tiết từ người bán.
* Tích hợp trực tiếp hai nút bấm "Phê duyệt" (Approve) và "Từ chối" (Reject) trên trang xem trước để admin thực hiện duyệt ngay lập tức sau khi kiểm tra xong thông tin.
* Thêm nút bấm hình Con mắt (Preview) trên bảng danh sách chờ duyệt của admin để liên kết nhanh đến trang xem trước này.

## 4. Thay đổi Database (Database Changes)

* **`2026_06_04_092915_add_avatar_url_to_users_table.php`:**
  * Bổ sung cột `avatar_url` (string, nullable) lưu liên kết ảnh đại diện của thành viên.
* **`2026_06_04_092915_add_resolution_fields_to_support_tickets_table.php`:**
  * Bổ sung cột `resolved_at` (timestamp, nullable) lưu thời điểm quản trị viên nhấn xử lý ticket.
  * Bổ sung cột `resolved_by_user_id` (khóa ngoại liên kết bảng `users`, null on delete) lưu định danh của quản trị viên chịu trách nhiệm xử lý ticket hỗ trợ.

## 5. Tính chân thực của Đánh giá sản phẩm (Product Reviews Realism)

* **Vấn đề của dữ liệu cũ:** Review chỉ là những chuỗi văn bản tĩnh được seeder tự sinh ngẫu nhiên, không liên kết khóa ngoại với người dùng nên không thể kiểm soát luồng phân quyền và không có avatar trực quan.
* **Giải pháp hiện tại:**
  * Tạo 6 tài khoản người dùng demo cố định với họ tên thật: Lan Anh, Minh Khôi, Gia Hân, Quốc Bảo, Hoàng Vy, Tuấn Minh.
  * Ánh xạ các review mẫu về 6 tài khoản này. Seeder sử dụng `updateOrCreate()` trên cặp `(product_id, user_id)` để đảm bảo tính đồng bộ dữ liệu.
  * Gắn avatar tự sinh dựa trên chữ cái đầu của tên (ví dụ: Lan Anh -> LA) hiển thị dạng khối SVG tròn tối giản cực kỳ hiện đại.
  * Khi click vào tên người đánh giá, hệ thống điều hướng đến trang hồ sơ công khai `/users/{user}` hiển thị các đóng góp khác của họ cho cộng đồng, tăng độ tin cậy của đánh giá sản phẩm.

## 6. Lịch sử Hỗ trợ trên Trang cá nhân (Profile Support History)

* **Luồng hoạt động:**
  * Khi người dùng truy cập trang `/profile`, controller nạp danh sách support ticket thông qua câu lệnh: `$user->supportTickets()->with('resolver')->latest()->get()`.
  * Trên tab Hỗ trợ, hiển thị bảng danh sách các ticket: Tiêu đề, Trạng thái xử lý, Ghi chú phản hồi từ admin (`admin_note`), Tên admin xử lý (lấy từ quan hệ `resolver->name`), và Ngày giải quyết (`resolved_at->format('d/m/Y')`).
  * Ở phía quản trị: Khi admin cập nhật trạng thái ticket về `resolved` hoặc `closed`, controller tự động ghi đè thời gian hiện tại vào cột `resolved_at` và gán ID của admin đăng nhập vào cột `resolved_by_user_id`. Nếu admin chuyển trạng thái về `open` hoặc `in_progress` để xử lý lại, hệ thống tự động xóa sạch thông tin giải quyết này (`resolved_at = null`, `resolved_by_user_id = null`).

## 7. Trạng thái Tin đăng C2C của Thành viên (C2C Listing Status Mapping)

* **Hệ thống ánh xạ trạng thái:**
  * C2C tin đăng hỗ trợ 6 trạng thái chính trong cơ sở dữ liệu: `pending` (Chờ duyệt), `active` (Đã duyệt), `rejected` (Bị từ chối), `sold` (Đã bán), `hidden` (Đã ẩn), và `deleted` (Đã xóa).
  * Trong tab C2C Marketplace tại trang Profile cá nhân, danh sách tin đăng được hiển thị chi tiết kèm theo nhãn màu trạng thái và ngày cập nhật tương ứng. Người dùng cũng có thể xem chi tiết tin đăng chờ duyệt của mình thông qua route xem chi tiết.
  * Trang chợ chính thức `/marketplace` sử dụng scope `active()` để lọc bỏ toàn bộ tin đăng đang ở trạng thái chờ duyệt, bị từ chối hoặc đã xóa mềm, bảo vệ tính trung thực của thông tin hiển thị với khách vãng lai.

## 8. Xem trước và Duyệt tin từ Admin (Admin C2C Preview)

* **Thiết lập luồng duyệt:**
  * Admin truy cập danh sách duyệt tin tại `/admin/marketplace`. Bảng hiển thị các tin đăng đang chờ duyệt với nút bấm hình Con mắt ở cột thao tác.
  * Khi click vào con mắt, hệ thống hiển thị trang `/admin/marketplace/{listing}` hiển thị toàn bộ chi tiết tin rao vặt.
  * Admin kiểm tra nội dung và click:
    * **Phê duyệt:** Gửi request PATCH đến route `/admin/marketplace/{listing}/active`, chuyển trạng thái tin sang `active`, tin đăng lập tức xuất hiện công khai trên chợ.
    * **Từ chối:** Gửi request PATCH đến route `/admin/marketplace/{listing}/rejected`, chuyển trạng thái tin sang `rejected`, tin đăng bị ẩn khỏi chợ công khai nhưng vẫn hiển thị nhãn "Bị từ chối" kèm lý do trong trang cá nhân của người bán.

## 9. Danh sách các file thay đổi (Files changed)

* [User.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Models/User.php): Khai báo quan hệ `productReviews`, `supportTickets`, `marketplaceListings`, thêm các thuộc tính ảo lấy avatar đại diện và chữ cái viết tắt.
* [SupportTicket.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Models/SupportTicket.php): Khai báo thuộc tính resolution, định nghĩa quan hệ `resolver()` trỏ về bảng User, viết nhãn trạng thái tiếng Việt.
* [MarketplaceListing.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Models/MarketplaceListing.php): Thêm trường `image_path` vào fillable, viết accessor xác định nhãn trạng thái duyệt C2C dành cho chủ tin đăng.
* [SupportTicketController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/Admin/SupportTicketController.php): Cập nhật thông tin admin xử lý và ngày giải quyết khi thay đổi trạng thái ticket hỗ trợ.
* [AdminController.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/app/Http/Controllers/Admin/AdminController.php): Hoàn thiện hàm hiển thị danh sách tin đăng chờ duyệt và cập nhật phê duyệt/từ chối từ trang xem trước.
* [LiveProductSeeder.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/database/seeders/LiveProductSeeder.php): Khởi tạo 6 tài khoản người dùng thật, gán đánh giá và avatar viết tắt.
* [profile/index.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/profile/index.blade.php): Thiết kế lại trang cá nhân dạng tab: Đơn hàng, Yêu thích, Tin Marketplace (hiển thị trạng thái duyệt), Hỗ trợ (hiển thị ghi chú của admin và người xử lý).
* [admin/marketplace/index.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/admin/marketplace/index.blade.php): Thêm nút Con mắt liên kết trang xem trước.
* [admin/marketplace/show.blade.php](file:///c:/Users/thaib/du_an_code/nike_tmdt_laravel/resources/views/admin/marketplace/show.blade.php): Trang xem trước chi tiết tin đăng C2C dành cho admin kèm hai nút phê duyệt/từ chối.

## 10. Kết quả Test / Build

* **Chạy thử nghiệm test suite:**
  * Tệp `tests/Feature/SupportTicketTest.php` kiểm tra luồng tạo ticket, admin xem chi tiết, cập nhật trạng thái lưu metadata admin xử lý, thu hồi thông tin xử lý khi mở lại ticket, và kiểm tra trang profile khách hàng hiển thị đúng lịch sử hỗ trợ của riêng họ.
  * Tệp `tests/Feature/AdminMarketplaceTest.php` kiểm tra nút Con mắt xem trước xuất hiện trên hàng chờ duyệt, kiểm tra quyền truy cập của admin vào trang xem trước, và kiểm tra luồng duyệt phê duyệt/từ chối hoạt động bình thường trên trang xem trước.
  * Kết quả: **Tất cả các bài test tương ứng passed** thành công.
* **Định dạng code (Pint):** Chạy `vendor/bin/pint --dirty --format agent` hoàn tất.
* **Biên dịch Frontend (npm build):** Hoạt động ổn định.

## 11. Kết quả cuối cùng

Phase 7D đạt trạng thái **PASS / Hoàn thành**.
* **Rủi ro còn lại:** Hiện tại hệ thống chưa gửi email hoặc thông báo chuông trực tiếp cho người bán khi tin đăng C2C của họ bị từ chối hoặc phê duyệt. Người dùng phải tự truy cập trang cá nhân để xem trạng thái cập nhật. Cần tích hợp Notification Channel ở các phase tiếp theo.
