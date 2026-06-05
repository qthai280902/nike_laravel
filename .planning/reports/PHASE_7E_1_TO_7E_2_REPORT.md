# PHASE 7E.1 TO 7E.2 REPORT — NIKE_TMDT_LARAVEL

## 1. Tổng quan

- Dự án: `nike_tmdt_laravel`, nền tảng E-commerce Hybrid B2C & C2C theo định hướng Nike Podium cho B2C và TypeUI dark mode cho Admin.
- Stack: Laravel 12.x, PHP 8.2, Blade, Vanilla JS Fetch API, Tailwind CSS v4, PHPUnit 11, Laravel Pint.
- Trạng thái trước Phase 7E: Milestone B2C đã ổn định, C2C marketplace lõi đã có nền tảng, các phần quan trọng như cart sync, anti-user-enumeration và checkout pessimistic locking cần được giữ nguyên.
- Mục tiêu Phase 7E: hoàn thiện các mảnh vận hành còn thiếu quanh review/profile/support/store, sau đó xử lý nghiệp vụ hoàn kho khi admin hủy đơn hàng.

## 2. Phase 7E.1 — Review Moderation + Profile/Stores/Support Polish

### 2.1. Mục tiêu

Phase 7E.1 tập trung vào việc làm chặt vòng đời review sản phẩm, bổ sung trải nghiệm profile cho user, khóa danh tính khi gửi support ticket, và polish các màn hình vận hành phụ trợ như stores, sidebar, dashboard, notification.

### 2.2. Vấn đề trước khi sửa

- Review chưa có hàng đợi kiểm duyệt đủ rõ cho admin.
- User chưa thấy lịch sử review của chính mình, đặc biệt lý do bị từ chối.
- Profile còn thiếu luồng edit/avatar upload.
- Support form cho user đã đăng nhập vẫn có thể nhập danh tính khác.
- Trang stores cần dữ liệu vị trí và link Google Maps.
- Admin dashboard/sidebar/notification chưa phản ánh pending reviews đủ trực quan.

### 2.3. Database changes

- Bổ sung metadata kiểm duyệt cho `product_reviews`: `rejection_reason`, `moderated_at`, `moderated_by_user_id`.
- Chuẩn hóa review status quanh các trạng thái: `pending`, `approved`, `hidden`, `rejected`.
- Bổ sung `avatar_path` cho `users` để lưu avatar upload trên public disk.

### 2.4. Admin review moderation

- Thêm khu vực admin quản lý review tại `/admin/reviews`.
- Hỗ trợ danh sách, lọc/tìm kiếm, xem chi tiết, approve, hide, reject và keep pending.
- Luồng reject bắt buộc nhập lý do để user có thông tin phản hồi.
- `ProductReview` được bổ sung scopes, label/badge accessor và relationship tới moderator.

### 2.5. User profile review history

- Profile có tab lịch sử review của user hiện tại.
- Mỗi review hiển thị sản phẩm, rating, title, comment, status, ngày gửi, ngày kiểm duyệt, moderator và lý do bị từ chối nếu có.
- Public storefront/profile chỉ hiển thị review đã approved.

### 2.6. Profile edit + avatar upload

- Bổ sung routes `profile.edit` và `profile.update`.
- User được sửa name và avatar, email giữ readonly.
- Avatar lưu qua public disk, có `storage:link` để public asset hoạt động.
- Avatar được hiển thị ở private profile, public profile và review cards; nếu chưa có avatar thì fallback initials.

### 2.7. Support locked identity

- Với user đã đăng nhập, form support khóa name/email ở trạng thái readonly.
- Backend luôn override name/email từ `auth()->user()` để tránh giả mạo danh tính qua request thủ công.
- Guest flow vẫn giữ nguyên để khách chưa đăng nhập có thể gửi support.

### 2.8. Store locations + Google Maps

- Trang `/stores` hiển thị các store cards gồm name, city, address, hotline, hours và link Google Maps.
- Dữ liệu hiện tại là demo theo nhóm vị trí trung tâm thương mại, chưa xác nhận là điểm vận hành thực tế.

### 2.9. Sidebar/dashboard/notification

- Admin sidebar có link quản lý đánh giá sản phẩm.
- Dashboard có quick action liên quan review.
- Notification bell hiển thị pending review count và link xử lý.

### 2.10. Files changed

- `app/Models/ProductReview.php`
- `app/Http/Controllers/Admin/ProductReviewController.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/SupportController.php`
- `app/Services/AdminNotificationService.php`
- `routes/web.php`
- Các view admin review.
- Các view profile.
- View support.
- View stores.
- Admin layout/dashboard.
- Migrations cho `product_reviews` và `users`.
- Tests: `AdminProductReviewTest`, `ProfileTest`, `StoreLocationTest`, và cập nhật `ProductReviewTest`, `ProductDetailTest`, `AdminNotificationTest`, `SupportTicketTest`.

### 2.11. Tests/build/browser verification

- `php artisan migrate --no-interaction`: passed.
- `php artisan storage:link --no-interaction`: link đã tồn tại.
- `vendor\bin\pint --dirty --format agent`: passed.
- `npm.cmd run build`: passed.
- `php artisan test --compact`: 121 passed / 527 assertions.
- Browser QA đã kiểm tra các màn hình: `/admin/reviews`, `/profile/edit`, `/support`, `/stores`.
- Ghi chú: PHPUnit có warning cũ về doc-comment metadata ở `CartSyncTest` và `CheckoutConcurrencyTest`, nhưng test vẫn pass.

### 2.12. Kết quả Phase 7E.1

Phase 7E.1 hoàn thành mục tiêu polish vận hành: review có moderation queue, user có lịch sử review và avatar/profile edit, support identity được khóa an toàn, stores có Google Maps, admin có sidebar/dashboard/notification phù hợp.

## 3. Phase 7E.2 — Inventory Return on Order Cancellation

### 3.1. Mục tiêu

Phase 7E.2 bổ sung nghiệp vụ hoàn kho khi admin hủy đơn hàng, đồng thời bảo vệ hệ thống khỏi lỗi hoàn kho lặp, hủy sai trạng thái hoặc hoàn kho nhầm khi variant không còn hợp lệ.

### 3.2. Vấn đề trước khi sửa

- Checkout đã trừ kho bằng pessimistic locking, nhưng khi admin hủy đơn chưa có cơ chế cộng lại tồn kho.
- Cần giữ luật trạng thái hiện có: chỉ `pending`/`paid` được chuyển sang `cancelled`, `shipped` không được hủy, `delivered` và `cancelled` là terminal.
- Cần chống double-restock nếu thao tác hủy hoặc service bị gọi lại.
- Cần xử lý đơn nhiều item và trường hợp variant bị xóa/missing.

### 3.3. Database changes

Bổ sung vào bảng `orders`:

- `inventory_returned_at`: nullable timestamp, thời điểm hoàn kho.
- `inventory_returned_by_user_id`: nullable foreign key tới `users`, `nullOnDelete`.
- `inventory_return_note`: nullable text, ghi chú số item đã hoàn và item bị bỏ qua.

`Order` model được cập nhật fillable, cast `inventory_returned_at` sang datetime và relationship `inventoryReturnedBy()`.

### 3.4. InventoryReturnService

Tạo `app/Services/InventoryReturnService.php` với method:

- `returnForCancelledOrder(Order $order, ?User $admin = null): void`

Logic chính:

- Chạy trong `DB::transaction`.
- Lock order bằng `lockForUpdate`.
- Bỏ qua nếu order không tồn tại, đã hoàn kho, hoặc status không phải `cancelled`.
- Load order items.
- Với từng item, bỏ qua nếu quantity không hợp lệ hoặc thiếu `product_variant_id`.
- Tìm variant còn tồn tại và lock bằng `lockForUpdate`, sau đó increment stock theo quantity.
- Ghi metadata hoàn kho vào order gồm thời điểm, admin thực hiện và note.

### 3.5. Admin order status integration

- `Admin\OrderController` inject `InventoryReturnService`.
- `show` eager load thêm `inventoryReturnedBy`.
- `updateStatus` giữ rules trạng thái hiện có.
- Khi status mới là `cancelled`, controller update status rồi gọi service hoàn kho.
- Flash message phân biệt hủy thành công đã hoàn kho và trường hợp đã hoàn kho trước đó.

### 3.6. Admin order UI

- `resources/views/admin/orders/show.blade.php` hiển thị card "Trạng thái hoàn kho".
- Card cho biết `Đã hoàn kho`, `Chưa hoàn kho`, hoặc `Không áp dụng`.
- Khi đã hoàn kho, UI hiển thị thời gian, admin thực hiện và note.
- `resources/views/admin/orders/index.blade.php` hiển thị badge `Đã hoàn kho` cho cancelled order đã được hoàn kho.
- `resources/views/admin/dashboard.blade.php` hiển thị badge tương ứng ở recent orders.

### 3.7. Double-restock prevention

- Dùng cặp cơ chế `inventory_returned_at` + row lock trên order để ngăn hoàn kho lặp.
- Nếu service bị gọi lại sau khi order đã có `inventory_returned_at`, service return sớm và không increment stock lần nữa.
- Browser QA đã xác nhận gọi service lần hai không làm stock tăng thêm.

### 3.8. Edge cases handled

- Pending order hủy thì hoàn kho.
- Paid order hủy thì hoàn kho.
- Shipped order không được hủy.
- Delivered order không được đổi trạng thái.
- Cancelled order không được đổi trạng thái.
- Multi-item order hoàn kho đúng từng variant.
- Variant bị soft-delete/deleted/missing được skip và ghi note, không cộng nhầm stock.
- Item quantity không hợp lệ hoặc thiếu variant id được skip an toàn.

### 3.9. Files changed

- `app/Http/Controllers/Admin/OrderController.php`
- `app/Models/Order.php`
- `app/Services/InventoryReturnService.php`
- `database/migrations/2026_06_05_080435_add_inventory_return_fields_to_orders_table.php`
- `resources/views/admin/orders/show.blade.php`
- `resources/views/admin/orders/index.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `tests/Feature/AdminOrderInventoryReturnTest.php`

### 3.10. Tests/build/browser verification

- `php artisan migrate --no-interaction`: passed.
- `php artisan test --compact --filter=AdminOrderInventoryReturnTest`: 8 passed / 47 assertions.
- `php artisan test --compact --filter=AdminOrderTest`: 8 passed / 45 assertions.
- `php artisan test --compact --filter=CheckoutIntegrationTest`: 3 passed / 32 assertions.
- `php artisan test --compact --filter=CheckoutConcurrencyTest`: 2 passed / 7 assertions.
- `php artisan route:list --path=admin`: 33 routes.
- `vendor\bin\pint --dirty --format agent`: passed.
- `npm.cmd run build`: passed.
- `php artisan test --compact`: 129 passed / 574 assertions.
- Browser QA: tạo đơn QA, hủy từ admin, stock tăng từ 11 lên 14 với quantity 3, UI hiển thị `cancelled`, `Đã hoàn kho`, admin thực hiện và form update bị khóa. Gọi service lần hai stock vẫn giữ 14. Delivered order không có option cancel và stock giữ nguyên.
- Ghi chú: có tạo 2 đơn QA local để kiểm tra thủ công trong database local.

### 3.11. Kết quả Phase 7E.2

Phase 7E.2 hoàn thành nghiệp vụ hoàn kho khi hủy đơn: stock được trả lại đúng, không hoàn lặp, giữ nguyên khóa trạng thái terminal, không phá checkout locking hiện có và có UI admin để kiểm tra trạng thái hoàn kho.

## 4. Tổng hợp test result mới nhất

- `AdminProductReviewTest`: đã được chạy trong Phase 7E.1 và pass trong full suite.
- `ProductReviewTest`: đã được chạy/cập nhật trong Phase 7E.1 và pass trong full suite.
- `ProductDetailTest`: đã được chạy/cập nhật trong Phase 7E.1 và pass trong full suite.
- `AdminNotificationTest`: đã được chạy/cập nhật trong Phase 7E.1 và pass trong full suite.
- `ProfileTest`: đã được chạy trong Phase 7E.1 và pass trong full suite.
- `SupportTicketTest`: đã được chạy/cập nhật trong Phase 7E.1 và pass trong full suite.
- `AdminOrderInventoryReturnTest`: 8 passed / 47 assertions.
- `AdminOrderTest`: 8 passed / 45 assertions.
- `CheckoutIntegrationTest`: 3 passed / 32 assertions.
- `CheckoutConcurrencyTest`: 2 passed / 7 assertions.
- Full suite mới nhất sau Phase 7E.2: 129 passed / 574 assertions.
- Pint mới nhất: `vendor\bin\pint --dirty --format agent` passed.
- Build mới nhất: `npm.cmd run build` passed.

## 5. Những lỗi/rủi ro đã xử lý

| Vấn đề | Phase | Cách xử lý | Trạng thái |
| --- | --- | --- | --- |
| Pending review cần admin duyệt. | 7E.1 | Tạo admin review moderation queue, filter/search/show và approve/hide/reject/keep-pending. | Đã xử lý |
| Review rejected cần lý do cho user. | 7E.1 | Bổ sung `rejection_reason`, bắt buộc reason khi reject, hiển thị trong profile review history. | Đã xử lý |
| User cần avatar/profile edit. | 7E.1 | Thêm profile edit/update, upload avatar public disk, fallback initials. | Đã xử lý |
| Support identity không được giả mạo. | 7E.1 | Form readonly với auth user và backend override name/email từ authenticated user. | Đã xử lý |
| Stores cần Google Maps link. | 7E.1 | Bổ sung `/stores` với store cards và Google Maps link. | Đã xử lý |
| Hủy đơn cần hoàn kho. | 7E.2 | Thêm `InventoryReturnService`, gọi khi admin chuyển order sang `cancelled`. | Đã xử lý |
| Double-restock. | 7E.2 | Dùng `inventory_returned_at` kết hợp `lockForUpdate` để return sớm nếu đã hoàn kho. | Đã xử lý |
| Delivered/cancelled terminal lock. | 7E.2 | Giữ và test rule không cho đổi trạng thái từ `delivered` hoặc `cancelled`. | Đã xử lý |
| Multi-item order restock. | 7E.2 | Service lặp qua toàn bộ order items và increment từng variant theo quantity. | Đã xử lý |
| Deleted/missing variant handling. | 7E.2 | Variant không còn hợp lệ được skip và ghi vào `inventory_return_note`. | Đã xử lý |

## 6. Những phần chưa làm

- Payment Gateway chưa làm.
- C2C Escrow chưa làm.
- Email notification chưa làm.
- `inventory_movements` chưa có.
- Có thể dọn PHPUnit doc-comment warnings cũ sau.
- Có thể làm Phase 7E.3 Final QA + Release/Demo Readiness.

## 7. Khuyến nghị tiếp theo

Nên làm Phase 7E.3 trước khi mở thêm nghiệp vụ lớn: final QA toàn web, responsive test, route/permission audit, console/network audit, cập nhật report và chuẩn bị demo readiness.

Không nên nhảy sang Payment Gateway hoặc C2C Escrow trước khi QA cuối, vì hai mảng đó sẽ tăng độ phức tạp vận hành và dễ che lấp lỗi regression còn sót từ B2C/C2C lõi.
