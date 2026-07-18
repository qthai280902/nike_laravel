# Nike Hybrid: Retail Storefront & Marketplace

<p align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg" width="200" alt="Nike Logo">
</p>

<p align="center">
  
</p>

---

## 🚀 Giới thiệu (Overview)

**Nike Hybrid** là một nền tảng thương mại điện tử hiện đại, kết hợp mô hình B2C (Bán lẻ chính hãng) truyền thống sự linh hoạt của mô hình C2C (Marketplace dành cho cộng đồng Sneakerhead). 

Dự án tuân thủ nghiêm ngặt **Nền tảng Thiết kế Cốt lõi (Podium CDS)** của Nike: tối giản, sử dụng bảng màu đơn sắc (Monochromatic) đen trắng, kích thước chữ lớn (Typography 96px) và nhấn mạnh vào hình ảnh sản phẩm.

## ✨ Tính năng nổi bật (Key Features)

### 1. Trải nghiệm Bán lẻ Cao cấp (Premium B2C Experience)
- **Sale Cathedral (Thánh đường Giảm giá):** Cơ chế định giá thông minh tự động nổi bật các sản phẩm có `original_price` và `price`, hiển thị nhãn giảm giá Đỏ chuẩn Nike.
- **AJAX Cart Engine:** Hệ thống quản lý giỏ hàng Zero-Reload. Thêm, xóa sản phẩm và đồng bộ số lượng (`Badge Count`) tức thì bằng cách sử dụng công nghệ HTML Fragment Injection.
- **Real-time Search:** Thanh tìm kiếm tích hợp tính năng Debounce, hiển thị hình ảnh và thông tin Gợi ý (Suggestions) ngay khi người dùng gõ từ khóa.

### 2. Hệ sinh thái Thành viên (Membership System)
- **Đăng nhập linh hoạt:** Đăng nhập thông qua cả hai hình thức: `Email` hoặc `Họ và Tên`.
- **Định danh hiển thị (Display_ID):** Hệ thống cấp phát mã số duy nhất 6 chữ số (Ví dụ: `#829301`) cho từng User, kích thích tính độc bản cộng đồng.
- **Quản lý Profile:** Trang tiểu sử thông tin được tối ưu hóa hiển thị chuẩn xác và đẳng cấp.

### 3. Giao diện Đỉnh cao (Podium UI)
- **Pill-Shape Navigation:** Menu điều hướng theo thiết kế lưới thông minh với khung hình viên thuốc (pill).
- **Montserrat Typography:** Hệ thống phông chữ 100% được đồng hóa sang Montserrat (Weights 400-800).
- **Trang Câu chuyện (Narrative - About Us):** Phong cách dàn trang chữ khổng lồ, truyền tải sứ mệnh mang độ tin cậy đến người tiêu dùng.

## 🛠️ Công nghệ sử dụng (Technology Stack)

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Tailwind CSS v4, Blade Templates, Vanilla JS (Fetch API)
- **Database:** MySQL
- **Tooling:** Vite, Laravel Pint, PHPUnit

## ⚙️ Hướng dẫn Cài đặt (Installation & Setup)

1. **Clone repository:**
   ```bash
   git clone <repository_url>
   cd nike_tmdt_laravel
   ```

2. **Cài đặt các gói phụ thuộc (Dependencies):**
   ```bash
   composer install
   npm install
   ```

3. **Thiết lập Environment:**
   Sao chép `.env.example` thành `.env` và cấu hình Database:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Biên dịch Frontend (Vite):**
   ```bash
   npm run build
   # Hoặc chạy môi trường dev: npm run dev
   ```

5. **Khởi tạo và làm đầy (Seed) Database:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Khởi chạy ứng dụng:**
   ```bash
   php artisan serve
   ```

## 📈 Lộ trình phát triển (Roadmap)

- **Milestone 1:** Hoàn thiện hạ tầng B2C, UI/UX, Đăng nhập và Giỏ hàng nội tại (Đã hoàn thành).
- **Milestone 2:** Xây dựng C2C Marketplace, Cổng thông tin cho Nhà bán hàng (Sellers), Hệ thống đăng bán sản phẩm cá nhân.
- **Milestone 3:** Tích hợp Cổng thanh toán, Hệ thống Đặt hàng và Vận chuyển. 

## ⚖️ Giấy phép

Dự án này là mã nguồn mở có sẵn dưới [MIT license](https://opensource.org/licenses/MIT).
