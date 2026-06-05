# QA REPORT — PHASE 4 TO PHASE 5

## 1. Tổng quan

* Project: `nike_tmdt_laravel`
* Date: 2026-06-05 23:37:21 +07:00
* Scope: Phase 4 -> Phase 5 only: identity/auth baseline, wishlist/profile surface, marketplace baseline/detail, cart canonical `nike_cart`, B2C checkout, localization/font/layout, catalog filter/sort/search, admin dashboard baseline.
* Tester/Agent: Codex
* Environment: Local Laravel 12.57.0, PHP 8.2.12, MySQL connection, Vite/Tailwind build.
* QA mode: Source audit + existing automated tests + HTTP/service probes + in-app Browser QA + Chrome smoke.
* Có sửa code không: Không sửa logic app. Chỉ tạo report QA này.
* Có thêm test không: Không. Existing tests đã cover đủ scope Phase 4 -> 5.
* Có chạy migrate/fresh không: Không.

## 2. Source Audit

* Phase 4 modules found: Auth/Login/Register/Logout, role/admin middleware, dynamic auth nav, profile baseline, wishlist, marketplace baseline, admin dashboard access.
* Phase 5 modules found: `CartService` canonical session `nike_cart`, AJAX cart drawer/fragment, `CheckoutService`, real checkout page, order/order_items, stock deduction, profile order history, marketplace detail, marketplace active/public permissions, Vietnamese UI/font/layout normalization, catalog filter/sort/search, admin dashboard widgets.
* Routes audited:
  * cart: `POST /cart/add`, `POST /cart/remove`, `GET /cart/fragment`
  * checkout: `GET /checkout`, `POST /checkout`
  * marketplace: public index/detail, authenticated create/store/search/variants, admin marketplace queue/update
  * profile: index/edit/update
  * catalog: products, product detail, search suggestions
  * admin: dashboard and baseline admin sections
* Services audited: `CartService`, `CheckoutService`, `ProductService`, `MarketplaceService`, `AuthService` via source references.
* Models audited: `User`, `Product`, `ProductVariant`, `Order`, `OrderItem`, `MarketplaceListing`.
* Views audited: cart drawer/fragment, checkout, profile, marketplace index/create/show, catalog index/show, auth login/register, layouts app/admin, admin dashboard.
* Tests audited: `CheckoutIntegrationTest`, `CheckoutConcurrencyTest`, `CartSyncTest`, `MarketplaceTest`, `ProfileTest`, `RoutingQaTest`, `ProductServiceTest`, admin dashboard/marketplace/member/order tests.
* Reports read: `.planning/reports/20260427-phase-4-report.md`, `.planning/reports/PROJECT_PROGRESS_REPORT_PHASE_5_TO_6E.md`, `.planning/kiem-thu/QA_PHASE_1_TO_3_REPORT.md`.
* Scope uncertainty if any: Phase 4 report exists and describes Identity & Security. Prompt also asks Phase 4 marketplace/user surface baseline, so QA used current source to include wishlist, profile, marketplace baseline, product browsing, and admin baseline. Phase 6/7 deep features were not tested beyond avoiding regressions.

Key source conclusions:

* Phase 4 report exists: `20260427-phase-4-report.md`.
* Phase 4 in current source maps to auth, profile, wishlist, marketplace baseline, product browsing baseline, and admin access baseline.
* Phase 5 in current source maps to cart canonical, checkout, marketplace detail, localization/font/layout, catalog filter/sort, admin dashboard widgets.
* Cart session key is `nike_cart` in `app/Services/CartService.php`.
* Checkout stock deduction happens inside `CheckoutService::process()` with `DB::transaction()`, `lockForUpdate()`, `decrement('stock', qty)`, and order item price snapshot.
* Marketplace detail route is `GET /marketplace/{listing}` -> `MarketplaceController@show` -> `resources/views/marketplace/show.blade.php`.
* Marketplace detail permits active listings publicly and inactive listings only for owner; non-owner pending/rejected listing returns 404.
* Biggest pre-test risks: existing dirty worktree from earlier work; low-severity dead-link notes already known from Prompt 1/3; Browser QA creates local order/listing data.

## 3. Test Process / Quá trình kiểm thử

### 3.1. Audit process

* Đã đọc file/report:
  * `.planning/reports/20260427-phase-4-report.md`
  * `.planning/reports/PROJECT_PROGRESS_REPORT_PHASE_5_TO_6E.md`
  * `.planning/kiem-thu/QA_PHASE_1_TO_3_REPORT.md`
  * `routes/web.php`
  * core services/controllers/models/views/tests listed in Section 2
* Kết luận sau audit:
  * Phase 4 source hiện tại gồm auth/security, wishlist/profile, marketplace baseline, product browsing/admin baseline.
  * Phase 5 source hiện tại gồm cart canonical, checkout thật, marketplace detail, localization/font/layout, catalog filter/sort/search, admin dashboard.
  * Không cần thêm test QA mới vì existing test suite đã có coverage trực tiếp cho checkout/cart/marketplace/profile/admin route access.

### 3.2. Command process

* Đã chạy command baseline đúng prompt:
  * `php artisan about`
  * `php artisan route:list`
  * `php artisan route:list --path=cart`
  * `php artisan route:list --path=checkout`
  * `php artisan route:list --path=marketplace`
  * `php artisan route:list --path=profile`
  * `php artisan route:list --path=catalog`
  * `php artisan test --compact`
  * `vendor\bin\pint --dirty --format agent`
  * `npm.cmd run build`
* Kết quả: tất cả pass.

### 3.3. Automated test process

* Đã chạy full suite hai lần trong phiên QA: baseline và verify cuối.
* Có thêm test mới không: Không.
* Test mới kiểm gì: Không áp dụng.

### 3.4. Browser QA process

* Browser/tool dùng:
  * In-app Browser for main Phase 4/5 UI flows.
  * Chrome extension for separate smoke pass because prompt explicitly requested `@chrome`.
  * `@Máy tính` / computer-use was requested but no callable computer-use plugin was available in this session.
* Pages checked:
  * `/`, `/catalog/products`, 3 product detail pages, cart drawer, `/checkout`, `/profile`, `/marketplace`, active marketplace detail, `/login`, `/register`, `/admin/dashboard`, `/marketplace/create`.
* Viewports checked:
  * 1366 x 768
  * 768 x 900
  * 390 x 844
* Flow checked:
  * guest redirects
  * customer login
  * wishlist add/remove
  * add cart
  * checkout success
  * empty checkout after cart clear
  * marketplace create pending listing
  * marketplace pending listing not public after logout
  * customer blocked from admin
  * admin dashboard access

### 3.5. Data used for QA

* User/customer/admin dùng để test:
  * Customer: `test@example.com` / `123456`
  * Admin: `admin@example.com` / `123456`
* Product/listing dùng để test:
  * Product detail: `nike-zoom-structure-515`, `nike-air-jordan-1-high-433`, `nike-precision-hero-651`
  * Checkout variant: `019dd4b5-ceb6-73be-bb39-d766f16f641c`
  * Active marketplace listing: `019e460a-4027-721a-9b4b-2ec06cd82edb`
  * Existing pending listing: `019e9211-fa35-718a-b959-f0232d4426cd`
* Order/checkout test data:
  * Browser checkout created order `019e98a3-b8b8-7235-91b4-56ac4e1d3fbb`
  * Shipping email: `qa-phase45@example.test`
* Có tạo dữ liệu QA local không:
  * Yes. One B2C order/order item was created.
  * Yes. One pending marketplace listing was created: `QA Phase45 Browser Pair 1780677328754`, id `019e98a3-c2c9-72f9-8932-b5dc980c7d37`.
  * No data was deleted.

## 4. Baseline Commands

| Command | Result | Evidence | Notes |
| ------- | ------ | -------- | ----- |
| `php artisan about` | PASS | Laravel 12.57.0, PHP 8.2.12, env local, debug enabled, DB mysql, session database, public/storage linked | No QA blocker |
| `php artisan route:list` | PASS | 71 routes | Includes `_boost/browser-logs` vendor route |
| `php artisan route:list --path=cart` | PASS | 3 routes | add/remove/fragment present |
| `php artisan route:list --path=checkout` | PASS | 2 routes | GET/POST checkout present |
| `php artisan route:list --path=marketplace` | PASS | 9 routes | public, auth, admin marketplace routes present |
| `php artisan route:list --path=profile` | PASS | 3 routes | index/edit/update present |
| `php artisan route:list --path=catalog` | PASS | 5 routes | categories/products/detail/reviews/search |
| `php artisan test --compact` | PASS | 137 tests, 616 assertions | Baseline pass |
| `vendor\bin\pint --dirty --format agent` | PASS | `{"tool":"pint","result":"passed"}` | No reported formatting changes |
| `npm.cmd run build` | PASS | Vite 7.3.2, 53 modules transformed, CSS/JS built | No build blocker |

## 5. Phase 4 Testcases — Core Commerce / Marketplace Baseline / User Surface

| Testcase | Result | Evidence | Notes |
| -------- | ------ | -------- | ----- |
| TC-4.1 Wishlist module | PASS | route `wishlist.toggle`, table `wishlists`, profile wishlist, PDP `#wishlist-btn`; Browser add -> `Đã Yêu Thích`, remove -> `Yêu Thích` | Unique pivot exists |
| TC-4.2 Marketplace index baseline | PASS | `/marketplace` HTTP 200, Browser h1 `Chợ đồ cũ`, 5 active cards in Chrome smoke, no broken images | Active feed only |
| TC-4.3 Marketplace create access | PASS | Guest `/marketplace/create` redirects `/login`; customer Browser page 200 with h1 `Đăng bán đôi giày của bạn` | Form layout did not crash |
| TC-4.4 Marketplace store baseline | PASS | Browser submitted `QA Phase45 Browser Pair 1780677328754`; listing created as `pending`; not visible to public after logout | No upload/freeform deep Phase 7C testing |
| TC-4.5 Marketplace permissions | PASS | Guest auth routes redirect login; customer admin marketplace/dashboard returns 404; admin dashboard 200 | Existing tests also cover admin marketplace |
| TC-4.6 Profile surface baseline | PASS | Customer `/profile` 200 after checkout, shows profile/order areas; no broken images/console errors | Browser also checked profile responsive |
| TC-4.7 Admin/dashboard baseline | PASS | Admin `/admin/dashboard` Browser 200, h2 `Hệ thống Quản trị`, no console errors | Customer blocked |
| TC-4.8 Product browsing nâng cao | PASS | Search suggestions JSON 200; sale/category HTTP 200; ProductService sort asc/desc verified true | No 500 |

## 6. Phase 5 Testcases — Cart / Checkout / Marketplace Detail / Localization

| Testcase | Result | Evidence | Notes |
| -------- | ------ | -------- | ----- |
| TC-5.1 CartService canonical | PASS | `CartService::$sessionKey = 'nike_cart'`; helpers `items()`, `count()`, `subtotal()` exist | No important legacy `session('cart')` usage found |
| TC-5.2 Add to cart from product detail | PASS | Browser selected variant `019dd4b5-ceb6-73be-bb39-d766f16f641c`, badge `1`, drawer opened, item `Nike Zoom Structure` | No JS error |
| TC-5.3 Cart drawer fragment | PASS | Drawer showed product/size/color/qty/price; existing tests cover remove; Prompt 1/3 already browser-tested remove | This run used add + checkout clear path |
| TC-5.4 Checkout requires auth | PASS | Guest `/checkout` HTTP 302 to `/login`; authenticated customer with cart got checkout 200 | Browser checkout page loaded |
| TC-5.5 Checkout empty cart | PASS | After successful checkout cart cleared; customer `/checkout` redirected to catalog with empty-cart behavior | Existing test also covers empty cart POST |
| TC-5.6 Checkout successful flow | PASS | Browser created order `019e98a3-b8b8-7235-91b4-56ac4e1d3fbb`; stock 89 -> 88; cart count returned 0; profile success | COD only |
| TC-5.7 Checkout insufficient stock | PASS | Existing `CheckoutIntegrationTest::test_checkout_fails_if_stock_is_insufficient` passed | No browser mutation needed |
| TC-5.8 Profile order history after checkout | PASS | Browser redirected to `/profile`; DB latest order has item `Nike Zoom Structure`; profile page no crash | Existing test asserts profile shows order total |
| TC-5.9 Checkout price snapshot | PASS | `order_items.price` created as `2100000.00`; `CheckoutConcurrencyTest` asserts snapshot remains after product price change | Source stores item price |
| TC-5.3.1 Marketplace index detail links | PASS | Marketplace cards link to `route('marketplace.show', listing)`; active detail clicked/opened by direct route; no `href="#"` in marketplace index scan | No dead CTA in marketplace index |
| TC-5.3.2 Active listing detail | PASS | Active listing detail HTTP 200 and Browser/Chrome h1 `Nike Air Force 1 '07` | No broken image |
| TC-5.3.3 Inactive listing public access | PASS | Pending listing `/marketplace/019e9211-fa35-718a-b959-f0232d4426cd` returned 404 as guest | Owner exception exists in controller |
| TC-5.3.4 Null-safe listing detail | PASS | Existing tests cover active freeform and catalog-linked listings; model accessors fallback optional fields | No crash found |
| TC-5.4 Localization/font/layout | PASS WITH LOW NOTES | Main visible UI is Vietnamese, Be Vietnam Pro configured, no horizontal overflow in Browser/Chrome | Low notes below |
| TC-5.5 Catalog filter/admin dashboard | PASS | category/sale/search HTTP 200; ProductService sort asc/desc true; admin dashboard Browser/Chrome no errors | No blocker |

## 7. Checkout QA Details

* Cart canonical: `nike_cart` via `CartService::$sessionKey`.
* Cart drawer: AJAX add worked in Browser; drawer opened with item, subtotal, total.
* Checkout auth: Guest `/checkout` redirects to `/login`.
* Checkout empty cart: After order success and cart clear, `/checkout` redirects to catalog.
* Checkout success:
  * Orders before Browser checkout: 5
  * Orders after Browser checkout: 6
  * Order items before: 7
  * Order items after: 8
  * Created order: `019e98a3-b8b8-7235-91b4-56ac4e1d3fbb`
* Stock deduction:
  * Variant `019dd4b5-ceb6-73be-bb39-d766f16f641c`
  * Stock before: 89
  * Stock after: 88
* Insufficient stock: Covered by `CheckoutIntegrationTest`; full suite passed.
* Profile order history: Profile loaded after checkout and latest order item references `Nike Zoom Structure`.
* Price snapshot: Latest order item price `2100000.00`; dedicated test also covers unchanged snapshot after product price mutation.
* Issues found: No checkout blocker. Low localization note: browser title still `Checkout | Nike Hybrid`.

## 8. Marketplace QA Details

* Marketplace index: `/marketplace` 200; shows active listings; no broken images; Chrome saw 5 article cards.
* Marketplace create: guest redirects login; customer form 200; browser-created freeform listing submitted.
* Marketplace permissions: customer admin dashboard 404; guest admin dashboard redirect login; admin dashboard 200.
* Detail route: `/marketplace/{listing}` exists and routes to `MarketplaceController@show`.
* Active listing: `019e460a-4027-721a-9b4b-2ec06cd82edb` 200.
* Inactive listing: `019e9211-fa35-718a-b959-f0232d4426cd` 404 for guest/public.
* Null-safe behavior: source accessors and tests cover freeform/catalog-linked/optional fields.
* Issues found: No marketplace blocker.

## 9. Localization / Font / Layout QA

* Vietnamese coverage: Main visible storefront/admin/customer pages are Vietnamese. English product/brand names and terms like Nike/COD/US size are acceptable.
* Font: `resources/css/app.css` imports Be Vietnam Pro and maps `--font-sans`, `--font-nike-display`, and `--font-nike-body` to Be Vietnam Pro; admin layout also loads Be Vietnam Pro.
* Italic check: No `italic` class or `font-style` issue found in app UI. `<i>` only appears in `resources/views/vendor/pagination/semantic-ui.blade.php` as legacy icon markup, not Phase 4/5 UI text.
* Layout notes: Browser and Chrome found no horizontal overflow on checked pages.
* Responsive notes: In-app Browser checked 1366, 768, and 390 widths. No broken images or overflow found.
* Issues found:
  * Low: register legal text still has two `href="#"` links.
  * Low: profile review card can conditionally render `href="#"` if a review product is missing.
  * Note: checkout browser title is English (`Checkout | Nike Hybrid`) while visible UI is Vietnamese.

## 10. Browser QA

* Pages checked:
  * `/`
  * `/catalog/products`
  * `/catalog/products/nike-zoom-structure-515`
  * `/catalog/products/nike-air-jordan-1-high-433`
  * `/catalog/products/nike-precision-hero-651`
  * cart drawer
  * `/checkout`
  * `/profile`
  * `/marketplace`
  * `/marketplace/019e460a-4027-721a-9b4b-2ec06cd82edb`
  * `/login`
  * `/register`
  * `/admin/dashboard`
  * `/marketplace/create`
* Console errors: 0 on checked in-app Browser pages/flows; 0 in Chrome smoke.
* Network 404/500: Expected 404 for customer admin and public inactive marketplace listing only. No unexpected 500 found.
* Broken images: None detected on checked pages.
* Responsive notes: No horizontal overflow at 1366, 768, 390.
* Checkout browser flow: PASS, created order and profile success.
* Cart browser flow: PASS, add-to-cart drawer opened and checkout cleared cart.
* Marketplace browser flow: PASS, active detail opened, create form submitted pending listing, pending listing not public after logout.
* Admin dashboard browser flow: PASS, admin dashboard loaded; customer blocked.
* Issues found: Same low notes in Sections 12-13.

## 11. Automated Tests

* Existing tests run: Full PHPUnit suite.
* New QA tests added if any: None.
* New test file path if any: None.
* Full suite result: 137 passed, 616 assertions.
* Pint: Passed with `vendor\bin\pint --dirty --format agent`.
* Build: Passed with `npm.cmd run build`; Vite transformed 53 modules.
* Route list result:
  * all routes: 71
  * cart: 3
  * checkout: 2
  * marketplace: 9
  * profile: 3
  * catalog: 5

## 12. Bug còn tồn đọng

| ID | Severity | Bug còn tồn đọng | Page/Module | Reproduction | Expected | Actual | Recommendation | Blocking? |
| -- | -------- | ---------------- | ----------- | ------------ | -------- | ------ | -------------- | --------- |
| QA-45-01 | Low | Register legal links are dead links | `resources/views/auth/register.blade.php:49`, `/register` | Open `/register`; inspect/click `Chính sách Bảo mật` or `Điều khoản Sử dụng` | Links should route to real policy/terms pages or be plain non-link text | Both anchors use `href="#"` | Add real legal routes/pages later or replace anchors with text until legal pages exist | No |
| QA-45-02 | Low | Profile review fallback can render `href="#"` | `resources/views/profile/index.blade.php:233` | Render a profile review whose product relation is missing/null | Link should be hidden, disabled, or route to a safe fallback | Blade fallback uses `#` | Replace fallback with non-clickable label or guarded conditional rendering | No |
| QA-45-03 | Note | Checkout page title remains English | `resources/views/checkout/index.blade.php:3`, `/checkout` | Open checkout with cart and read browser title | Page title should be Vietnamese like `Thanh toán | Nike Hybrid` | Title is `Checkout | Nike Hybrid`; visible UI is Vietnamese | Localize title in a later approved bug-fix pass | No |

## 13. Issues Found / Notes

| Severity | Issue/Note | Page/Module | Evidence | Recommendation |
| -------- | ---------- | ----------- | -------- | -------------- |
| Low | Static legal dead links | Register | Browser dead-link detection: `Chính sách Bảo mật`, `Điều khoản Sử dụng`; source line 49 | Fix when legal pages/routes are available |
| Low | Conditional profile dead link | Profile reviews | Source fallback `: '#'` at `profile/index.blade.php:233` | Guard link rendering |
| Note | Checkout page title English | Checkout | Browser title `Checkout | Nike Hybrid` | Rename title to Vietnamese |
| Note | Laravel Boost MCP unavailable in this session | QA tooling | Tool discovery did not expose `search-docs`, `database-query`, `get-absolute-url`, `browser-logs` | No app impact; used Artisan/source/HTTP/Browser/Chrome |
| Note | `@Máy tính` unavailable | Browser tooling | No callable computer-use plugin was exposed | Browser and Chrome were used |
| Note | Existing dirty worktree predated this QA | Repo state | `git status` had modified app/test files before this report | Not modified/reverted in this prompt |

## 14. Final Verdict

* Phase 4: PASS WITH LOW-SEVERITY NOTES.
* Phase 5: PASS WITH LOW-SEVERITY NOTES.
* Overall status: PASS. No release blocker found in Phase 4 -> Phase 5 scope.
* Ready to continue QA prompt 3/3: Yes.
* Blockers: None.
* Non-blocking bugs: 2 Low, 1 Note.
* Recommended follow-up: In a separately approved fix pass, replace register/profile `href="#"` fallbacks and localize the checkout document title.
