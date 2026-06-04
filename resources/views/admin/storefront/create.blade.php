@extends('layouts.admin')

@section('page_title', 'Thêm sản phẩm')

@section('admin_content')
<form method="POST" action="{{ route('admin.products.store') }}" class="space-y-6">
    @csrf

    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('admin.storefront.index') }}" class="text-xs font-bold uppercase tracking-widest text-zinc-500 transition hover:text-white">Quay lại danh sách</a>
        <button class="bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-black transition hover:bg-zinc-200">
            Lưu sản phẩm
        </button>
    </div>

    @if($errors->any())
        <div class="border border-red-500/20 bg-red-500/10 px-5 py-4 text-sm text-red-300">
            Vui lòng kiểm tra lại thông tin sản phẩm.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <section class="type-card space-y-5 p-6 xl:col-span-8">
            <div>
                <h3 class="font-bold text-white">Thông tin cơ bản</h3>
                <p class="mt-1 text-xs text-zinc-500">Dữ liệu này hiển thị ở catalog và trang chi tiết.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tên sản phẩm</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('name')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="slug" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Slug</label>
                    <input id="slug" name="slug" value="{{ old('slug') }}" placeholder="Tự tạo nếu bỏ trống" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('slug')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="category_id" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Danh mục</label>
                    <select id="category_id" name="category_id" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="price" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Giá bán</label>
                    <input id="price" name="price" type="number" min="0" value="{{ old('price') }}" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('price')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="original_price" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Giá gốc</label>
                    <input id="original_price" name="original_price" type="number" min="0" value="{{ old('original_price') }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('original_price')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trạng thái</label>
                    <select id="status" name="status" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}" @selected(old('status', 'active') === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="featured_position" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trưng bày</label>
                    <select id="featured_position" name="featured_position" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                        <option value="">Không</option>
                        <option value="hero" @selected(old('featured_position') === 'hero')>Hero</option>
                        <option value="secondary" @selected(old('featured_position') === 'secondary')>Phụ</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="image_url" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Ảnh sản phẩm</label>
                    <input id="image_url" name="image_url" value="{{ old('image_url', '/images/hero.png') }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('image_url')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Mô tả ngắn</label>
                    <textarea id="description" name="description" rows="3" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('description') }}</textarea>
                </div>
            </div>
        </section>

        <aside class="type-card space-y-5 p-6 xl:col-span-4">
            <div>
                <h3 class="font-bold text-white">Variant đầu tiên</h3>
                <p class="mt-1 text-xs text-zinc-500">Có thể chỉnh tồn kho sau khi tạo.</p>
            </div>

            <div>
                <label for="variant_sku" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">SKU</label>
                <input id="variant_sku" name="variant_sku" value="{{ old('variant_sku') }}" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                @error('variant_sku')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="variant_size" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Size</label>
                    <input id="variant_size" name="variant_size" value="{{ old('variant_size', 'US 9') }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                </div>
                <div>
                    <label for="variant_color" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Màu</label>
                    <input id="variant_color" name="variant_color" value="{{ old('variant_color', 'Đen/Trắng') }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="variant_stock" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tồn kho</label>
                    <input id="variant_stock" name="variant_stock" type="number" min="0" value="{{ old('variant_stock', 20) }}" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                </div>
                <div>
                    <label for="variant_price_override" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Giá riêng</label>
                    <input id="variant_price_override" name="variant_price_override" type="number" min="0" value="{{ old('variant_price_override') }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                </div>
            </div>
        </aside>
    </div>

    <section class="type-card space-y-5 p-6">
        <div>
            <h3 class="font-bold text-white">Nội dung trang chi tiết</h3>
            <p class="mt-1 text-xs text-zinc-500">Các khối này được render trực tiếp ở trang sản phẩm.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div>
                <label for="product_story" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Câu chuyện sản phẩm</label>
                <textarea id="product_story" name="product_story" rows="7" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('product_story') }}</textarea>
            </div>
            <div>
                <label for="highlights_text" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Điểm nổi bật</label>
                <textarea id="highlights_text" name="highlights_text" rows="7" placeholder="Mỗi dòng là một ý" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('highlights_text') }}</textarea>
            </div>
            <div>
                <label for="care_instructions" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Cách phối đồ / chăm sóc</label>
                <textarea id="care_instructions" name="care_instructions" rows="7" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('care_instructions') }}</textarea>
            </div>
        </div>
    </section>
</form>
@endsection
