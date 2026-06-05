# QA Phase 6 To 7 Report

Generated: 2026-06-05 23:57:15 +07:00

Scope: Phase 6 through Phase 7 only. This QA pass audited admin members, reports, support, landing articles, product data, admin orders, admin notifications, storefront theme, C2C marketplace, product reviews, profile/avatar/support history, store locations, and inventory return. No application source code was intentionally changed for this QA pass; this report is the intended artifact.

## Executive Result

Status: PASS WITH NON-BLOCKING NOTES

Release blockers: None found.

Ready for final bug-fix pass / demo: Yes, with a final bug-fix/data-hygiene pass recommended before a polished demo.

## Scope Covered

### Phase 6A - Admin Members + Reports

- Routes verified: `/admin/members`, `/admin/members/{user}`, `/admin/reports`.
- Source audit confirmed member search, member detail, non-cancelled spend calculation, reports revenue totals, order status breakdown, marketplace status breakdown, low-stock list, and top-selling products.
- Existing tests cover access protection, member list/search/detail, cancelled-order exclusion, and report dashboard statistics.
- Browser QA loaded members list/detail and reports as admin.

### Phase 6B - Support Center

- Routes verified: public `/support`, POST `/support`, admin `/admin/support`, `/admin/support/{ticket}`, PATCH `/admin/support/{ticket}`.
- Source audit confirmed guest support ticket flow, authenticated locked identity flow, admin status/note update, resolver metadata, and profile support history.
- Existing tests cover guest submit, authenticated identity override, readonly support fields, admin update, resolution metadata, reopening metadata, profile support history, and admin route protection.
- Browser QA confirmed authenticated support name/email fields are readonly and populated from `test@example.com`.

### Phase 6C - Landing Articles

- Routes verified: admin landing articles index/create/store/edit/update/delete and public article display through home/article routes.
- Source audit confirmed slug generation/unique validation, published checkbox handling, `published_at` handling, CRUD views, and home filtering for visible articles.
- DB probe found 4 landing articles total and 4 currently visible; no future/unpublished article in local data.
- Browser QA loaded admin landing article index/create and public home article surface.

### Phase 6D - Product Data / Category / Routing

- Routes verified: catalog listing/detail/search/category routes, store route, user public profile route.
- Source audit confirmed category parent filtering in `ProductService`, duplicate slug/SKU validation surfaces, product fallback image accessor, product review eager loading, and route coverage.
- DB probe found 84 products, 569 variants, 16 categories, no duplicate product slugs, no duplicate variant SKUs, and no active product missing category.
- Data-quality note: 8 active products currently have no variants. Detail routes still return 200, but those active products cannot be added to cart because no size/variant can be selected.

### Phase 6E - Admin Orders + Notifications

- Routes verified: `/admin/orders`, `/admin/orders/{order}`, PATCH status route, `/admin/dashboard`.
- Source audit confirmed strict order state transitions and integration with `InventoryReturnService` for cancelled orders.
- `AdminNotificationService` probe returned total count 5: pending orders 1, pending reviews 1, pending listings 2, low-stock variants 1, open support tickets 0.
- Browser QA opened admin notification bell; badge showed `5` and menu listed pending orders, pending product reviews, pending C2C listings, and low-stock variants.

### Phase 7A - Theme / Freeform C2C / UI

- Theme toggle was verified in Browser: dark preference set `data-theme="dark"` and reset to `data-theme-preference="system"`.
- C2C freeform source audit confirmed nullable `product_variant_id`, freeform fields, fallback display accessors, and pending status on create.
- DB probe found 6 freeform C2C listings and 3 uploaded C2C images.
- Browser QA loaded public marketplace, active listing detail, authenticated marketplace create, and owner C2C surface.

### Phase 7B - Product Detail / Reviews / Admin Product Management

- Product detail pages rendered for stocked and no-variant products.
- Source audit confirmed approved review display only, authenticated pending review submission, duplicate prevention, admin product index/create/show/edit/update, product story/highlights/care content, and fallback product images.
- Existing tests cover product detail content, fallback images, review public visibility, and admin product management.
- Browser QA loaded admin products index/create/show/edit.

### Phase 7C - Navbar / C2C Upload / Review Submit / Public Reviewer Profile

- Source and test audit confirmed navbar active state, C2C image upload validation/storage, review form submission to pending status, and public user profile hiding email/private review statuses.
- Browser QA loaded `/users/1`, product detail review area, and authenticated marketplace create with file input present.
- Live file upload was not repeated through Browser because existing PHPUnit coverage already verifies upload storage and no extra QA data was needed.

### Phase 7D - Reviews / Profile / Support / C2C Status / Admin Preview

- Profile page loaded with order, support, review, wishlist, and C2C sections.
- Source and tests confirm profile review history includes approved/pending/hidden/rejected statuses and rejection reason, support history is scoped to current user, C2C owner statuses include hidden/deleted, and inactive listing detail is owner-only.
- Browser QA loaded admin marketplace preview and admin reviews queue/detail.

### Phase 7E.1 - Review Moderation / Profile Edit / Avatar / Support Identity / Stores

- Source and test audit confirmed admin review queue, approve/hide/reject/keep-pending actions, required rejection reason, moderator metadata, profile edit/avatar upload, locked support identity, and stores Google Maps links.
- DB probe found 212 approved reviews, 1 pending review, 1 rejected review, 1 uploaded user avatar, and public storage path exists.
- Browser QA loaded `/profile/edit`, `/admin/reviews`, `/admin/reviews/{review}`, `/stores`, and confirmed support readonly identity.

### Phase 7E.2 - Inventory Return

- Source and test audit confirmed `InventoryReturnService` uses transaction + row locks, returns inventory once, skips invalid/missing variants, and records return metadata.
- DB probe found 6 orders, including 2 cancelled orders with `inventory_returned_at`.
- Existing tests cover pending/paid cancellation restock, once-only restock, shipped/delivered/cancelled locks, multi-item restock, and deleted variant skip.

### Phase 7E.3 - Final QA Regression

- Prior Phase 7E.3 report was audited.
- Regression notes from QA 1/3 and QA 2/3 were rechecked.
- Browser and Chrome smoke were repeated against the current local app without changing application logic.

## Automated Verification

| Command | Result |
| --- | --- |
| `php artisan about` | Passed. Laravel 12.57.0, PHP 8.2.12, local env, app URL `localhost:8000`, `public/storage` linked. |
| `php artisan route:list` | Passed. 71 routes loaded. |
| `php artisan route:list --path=admin` | Passed. 33 admin routes. |
| `php artisan route:list --path=support` | Passed. 5 support routes. |
| `php artisan route:list --path=profile` | Passed. 3 profile routes. |
| `php artisan route:list --path=marketplace` | Passed. 9 marketplace routes. |
| `php artisan route:list --path=reviews` | Passed. 7 review routes. |
| `php artisan route:list --path=stores` | Passed. 1 stores route. |
| `php artisan route:list --path=users` | Passed. 1 users route. |
| `php artisan test --compact` | Passed. 137 tests, 616 assertions. |
| `vendor\bin\pint --dirty --format agent` | Passed. |
| `npm.cmd run build` | Passed. Vite transformed 53 modules and built CSS/JS assets. |

Additional read-only probes:

- `/up` returned 200 after starting local `php artisan serve` on `127.0.0.1:8000`.
- Public pages returned expected HTTP/browser states: `/`, catalog, product detail, marketplace, active C2C detail, support, stores, and `/users/1` loaded.
- Pending C2C detail `/marketplace/019e9211-fa35-718a-b959-f0232d4426cd` returned 404 for guest, as expected.
- Guest auth/admin pages redirected to `/login`: `/admin/dashboard`, `/profile`, `/checkout`, `/marketplace/create`.

## Data Snapshot

- Users: 20.
- Products: 84.
- Variants: 569.
- Categories: 16.
- Orders: 6.
- Order statuses: cancelled 2, delivered 2, pending 1, shipped 1.
- Orders with inventory returned: 2.
- Support ticket statuses: resolved 3.
- Review statuses: approved 212, pending 1, rejected 1.
- Marketplace statuses: active 5, pending 2.
- Landing articles: 4 total, 4 visible.
- Low-stock variants: 1.
- Duplicate product slugs: 0.
- Duplicate variant SKUs: 0.
- Active products missing category: 0.
- Active products missing variants: 8.
- Freeform C2C listings: 6.
- C2C listings with uploaded image path: 3.
- Users with uploaded avatar path: 1.
- Public storage path exists: true.

Seed/demo accounts used:

- Customer: `test@example.com / 123456`.
- Admin: `admin@example.com / 123456`.

Key sample records:

- Product: `nike-zoom-structure-515`, variant `019dd4b5-ceb2-7317-a752-e80afadebb21`.
- Active marketplace listing: `019e460a-4027-721a-9b4b-2ec06cd82edb`.
- Pending marketplace listing: `019e9211-fa35-718a-b959-f0232d4426cd`.
- Pending product review: `019e91f0-5ba9-736a-a2e6-71f8cea22eb9`.
- Pending order: `019e98a3-b8b8-7235-91b4-56ac4e1d3fbb`.

## Browser QA

### In-App Browser

Total checks: 109 page/viewport checks.

Viewports:

- 1366 x 768.
- 1024 x 768.
- 768 x 844.
- 425 x 844.
- 390 x 844.
- 375 x 812.

Guest/public pages checked:

- `/`
- `/login`
- `/register`
- `/catalog/products`
- `/catalog/products?category=men`
- `/catalog/products?sort=price_asc`
- `/discount-sale`
- `/catalog/products/nike-zoom-structure-515`
- `/catalog/products/nike-pegasus-41`
- `/marketplace`
- `/marketplace/019e460a-4027-721a-9b4b-2ec06cd82edb`
- `/marketplace/019e9211-fa35-718a-b959-f0232d4426cd`
- `/support`
- `/stores`
- `/users/1`

Customer pages/interactions checked:

- Logged in as `test@example.com`.
- `/profile`
- `/profile/edit`
- `/support`
- `/marketplace`
- `/marketplace/create`
- Product detail add-to-cart with variant `019dd4b5-ceb2-7317-a752-e80afadebb21`.
- Checkout page opened from real session cart without submitting an order.
- Support identity readonly fields: name `Nguyễn Quốc Thái`, email `test@example.com`.
- Theme toggle dark/system.

Admin pages/interactions checked:

- Logged in as `admin@example.com`.
- `/admin/dashboard`
- `/admin/members`
- `/admin/members/1`
- `/admin/reports`
- `/admin/support`
- `/admin/support/3`
- `/admin/orders`
- `/admin/orders/019e98a3-b8b8-7235-91b4-56ac4e1d3fbb`
- `/admin/products`
- `/admin/products/create`
- `/admin/products/019dd4b5-cea7-712c-863d-4d7c6dbdf470`
- `/admin/products/019dd4b5-cea7-712c-863d-4d7c6dbdf470/edit`
- `/admin/marketplace`
- `/admin/marketplace/019e9211-fa35-718a-b959-f0232d4426cd`
- `/admin/reviews`
- `/admin/reviews/019e91f0-5ba9-736a-a2e6-71f8cea22eb9`
- `/admin/landing-articles`
- `/admin/landing-articles/create`
- Notification bell opened successfully with badge `5`.

In-app Browser results:

- No app console errors found on checked pages.
- No visible broken images found on checked pages.
- No incoherent visible horizontal overflow found on checked responsive pages.
- Pending marketplace listing returned 404 for guest, as expected.
- Register page still has 2 visible `href="#"` legal links.

### Chrome

Chrome extension smoke passed. Chrome documentation endpoint returned `Packaged browser documentation directory is missing`, but the Chrome automation backend worked.

Checked pages:

- `/`
- `/catalog/products`
- `/marketplace`
- `/marketplace/019e460a-4027-721a-9b4b-2ec06cd82edb`
- `/support`
- `/stores`

Results:

- Home title: `Nike Hybrid | Bản Sắc Thể Thao`.
- Catalog title: `Sản Phẩm | Nike Hybrid`.
- Marketplace title: `Chợ đồ cũ | Nike Hybrid`.
- Active listing title: `Nike Air Force 1 '07 | Nike Chợ đồ cũ`.
- Support title: `Trung tâm hỗ trợ | Nike Hybrid`.
- Stores title: `Tìm cửa hàng | Nike Hybrid`.
- No Chrome smoke failures.
- No visible broken images.
- No horizontal overflow.
- No app console errors.
- Chrome automation tab finalized with `keep: []`.

### Computer Use

`@Máy tính` / Computer Use was requested. The Computer Use helper was reachable and `list_apps` returned 40 app entries. No Windows desktop app workflow was needed for this Laravel web QA because Browser and Chrome covered the requested web surfaces.

## Findings

### QA-67-01 - Medium - Active product data contains products without variants

Scope: database/local seeded data.

The read-only DB probe found 8 products with `status = active` and no related variants:

- `nike-pegasus-41`
- `nike-dri-fit-dna-basketball-shorts`
- `nike-sportswear-club-fleece-sweatshirt-women`
- `nike-sportswear-club-fleece-hoodie-kids`
- `nike-dri-fit-tee-kids`
- `nike-sportswear-futura-t-shirt`
- `nike-dri-fit-challenger-shorts`
- `air-jordan-1-low`

Browser/HTTP checks confirmed the detail route still returns 200, so this is not a crash blocker. It is still a purchase-readiness issue because active catalog items with no variants cannot be added to cart through the size/variant selector.

Status: Reported only; not fixed per QA prompt.

### QA-67-02 - Low - Static dead links on register legal text

File: `resources/views/auth/register.blade.php:49`

The register page still contains two static `href="#"` links for privacy policy and terms. In-app Browser QA detected both as visible dead-link hygiene issues.

Status: Reported only; carried over from QA 1/3 and QA 2/3.

### QA-67-03 - Low - Conditional profile review fallback can render `href="#"`

File: `resources/views/profile/index.blade.php:233`

The profile review card uses `route('catalog.show', ...)` when a review has a product, but falls back to `#` when the product is missing. This did not surface as a live browser issue with current user data, but the conditional risk remains in source.

Status: Reported only; carried over from QA 1/3 and QA 2/3.

### QA-67-04 - Low - Checkout page title remains English

File: `resources/views/checkout/index.blade.php:3`

The checkout Blade title remains `Checkout | Nike Hybrid`. This is cosmetic/localization debt and does not block checkout behavior.

Status: Reported only; carried over from QA 2/3.

## Non-Blockers / Context

- Payment Gateway is not implemented, as expected by scope.
- C2C Escrow is not implemented, as expected by scope.
- Email notifications are not implemented, as expected by scope.
- `inventory_movements` table/log is not implemented, as expected by scope.
- No new PHPUnit QA test was added because the existing 137-test suite already covers Phase 6 and Phase 7 behaviors with focused feature tests.
- No migrations, destructive database operations, dependency changes, or app feature changes were performed.
- The working tree already contained unrelated modified files before this QA report. They were not reverted or normalized.

## Regression Notes From QA 1/3 + QA 2/3

- Register legal `href="#"`: still present and confirmed by Browser QA.
- Profile review conditional `href="#"`: still present in source as a conditional fallback.
- Checkout title English: still present in source.

## Bug Severity Summary

- Critical: None.
- High: None.
- Medium: Active product data contains 8 active products with no variants.
- Low: Register legal dead links, conditional profile review fallback `href="#"`, checkout title English.
- Notes: Missing Payment Gateway, C2C Escrow, Email Notification, and inventory movement logs are out of current scope and were not treated as bugs.

## Blockers

- Blocking issues: None found.
- Non-blocking issues: 1 medium data-quality issue and 3 low cleanup/localization/link-hygiene issues.

## Final Verdict

Phase 6 through Phase 7 QA passed with non-blocking notes. The application is ready to proceed to a final bug-fix/data-hygiene pass and can be demoed using the stable seeded flows, especially `test@example.com`, `admin@example.com`, stocked catalog products such as `nike-zoom-structure-515`, active C2C listing `019e460a-4027-721a-9b4b-2ec06cd82edb`, and existing admin queues.
