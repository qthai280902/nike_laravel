@extends('layouts.admin')

@section('page_title', 'Sửa sản phẩm')

@section('admin_content')
@php
    $highlightText = old('highlights_text', implode(PHP_EOL, $product->highlights ?? []));
@endphp

<form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
    @csrf
    @method('PATCH')

    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('admin.products.show', $product) }}" class="text-xs font-bold uppercase tracking-widest text-zinc-500 transition hover:text-white">Quay lại chi tiết</a>
        <button class="bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-black transition hover:bg-zinc-200">
            Lưu thay đổi
        </button>
    </div>

    @if($errors->any())
        <div class="border border-red-500/20 bg-red-500/10 px-5 py-4 text-sm text-red-300">
            Vui lòng kiểm tra lại thông tin sản phẩm.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <section class="type-card space-y-5 p-6 xl:col-span-7">
            <div>
                <h3 class="font-bold text-white">Thông tin cơ bản</h3>
                <p class="mt-1 text-xs text-zinc-500">Sửa dữ liệu catalog và trạng thái hiển thị.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tên sản phẩm</label>
                    <input id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('name')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="slug" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Slug</label>
                    <input id="slug" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('slug')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="category_id" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Danh mục</label>
                    <select id="category_id" name="category_id" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="price" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Giá bán</label>
                    <input id="price" name="price" type="number" min="0" value="{{ old('price', (int) $product->price) }}" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('price')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="original_price" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Giá gốc</label>
                    <input id="original_price" name="original_price" type="number" min="0" value="{{ old('original_price', $product->original_price ? (int) $product->original_price : null) }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('original_price')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="status" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trạng thái</label>
                    <select id="status" name="status" required class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                        @foreach($statusLabels as $status => $label)
                            <option value="{{ $status }}" @selected(old('status', $product->status) === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="featured_position" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trưng bày</label>
                    <select id="featured_position" name="featured_position" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                        <option value="">Không</option>
                        <option value="hero" @selected(old('featured_position', $product->featured_position) === 'hero')>Hero</option>
                        <option value="secondary" @selected(old('featured_position', $product->featured_position) === 'secondary')>Phụ</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="image_url" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Ảnh sản phẩm</label>
                    <input id="image_url" name="image_url" value="{{ old('image_url', $product->getRawOriginal('image_url') ?: '/images/hero.png') }}" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">
                    @error('image_url')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Mô tả ngắn</label>
                    <textarea id="description" name="description" rows="3" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </section>

        <aside class="type-card p-6 xl:col-span-5">
            <h3 class="font-bold text-white">Tồn kho biến thể</h3>
            <p class="mt-1 text-xs text-zinc-500">Cập nhật stock và giá riêng từng variant.</p>

            <div class="mt-5 space-y-3">
                @foreach($product->variants as $variant)
                    <div class="grid grid-cols-12 gap-3 border border-zinc-800 bg-zinc-950 p-4">
                        <div class="col-span-12 min-w-0 lg:col-span-6">
                            <p class="truncate font-mono text-xs text-white">{{ $variant->sku }}</p>
                            <p class="mt-1 text-[10px] uppercase tracking-widest text-zinc-500">Size {{ $variant->size ?? 'N/A' }} | {{ $variant->color ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-6 lg:col-span-3">
                            <label class="mb-1 block text-[10px] uppercase tracking-widest text-zinc-600">Stock</label>
                            <input name="variant_stock[{{ $variant->id }}]" type="number" min="0" value="{{ old('variant_stock.'.$variant->id, $variant->stock) }}" class="w-full border border-zinc-800 bg-zinc-900 px-3 py-2 text-sm text-white outline-none focus:border-zinc-600">
                        </div>
                        <div class="col-span-6 lg:col-span-3">
                            <label class="mb-1 block text-[10px] uppercase tracking-widest text-zinc-600">Giá riêng</label>
                            <input name="variant_price_override[{{ $variant->id }}]" type="number" min="0" value="{{ old('variant_price_override.'.$variant->id, $variant->price_override ? (int) $variant->price_override : null) }}" class="w-full border border-zinc-800 bg-zinc-900 px-3 py-2 text-sm text-white outline-none focus:border-zinc-600">
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>
    </div>

    <section class="type-card space-y-5 p-6">
        <div>
            <h3 class="font-bold text-white">Nội dung trang chi tiết</h3>
            <p class="mt-1 text-xs text-zinc-500">Không hard-code trong Blade, dữ liệu lưu trên product.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div>
                <label for="product_story" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Câu chuyện sản phẩm</label>
                <textarea id="product_story" name="product_story" rows="7" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('product_story', $product->product_story) }}</textarea>
            </div>
            <div>
                <label for="highlights_text" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Điểm nổi bật</label>
                <textarea id="highlights_text" name="highlights_text" rows="7" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ $highlightText }}</textarea>
            </div>
            <div>
                <label for="care_instructions" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Cách phối đồ / chăm sóc</label>
                <textarea id="care_instructions" name="care_instructions" rows="7" class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none focus:border-zinc-600">{{ old('care_instructions', $product->care_instructions) }}</textarea>
            </div>
        </div>
    </section>
</form>
@endsection
