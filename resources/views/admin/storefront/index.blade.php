@extends('layouts.admin')

@section('page_title', 'Quản lý sản phẩm')

@section('admin_content')
@php
    $statusTone = [
        'active' => 'bg-green-500/10 text-green-400',
        'inactive' => 'bg-yellow-500/10 text-yellow-400',
        'archived' => 'bg-zinc-700 text-zinc-300',
    ];
@endphp

<div class="space-y-6">
    @if(session('success'))
        <div class="border border-green-500/20 bg-green-500/10 px-5 py-4 text-sm font-medium text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.storefront.index') }}" class="type-card grid grid-cols-1 gap-4 p-5 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <label for="search" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tìm sản phẩm / slug / SKU</label>
            <input id="search" name="search" value="{{ request('search') }}" type="search" placeholder="Air Max, dunk-low, NK-..."
                class="w-full border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-zinc-600">
        </div>

        <div class="lg:col-span-2">
            <label for="category_id" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Danh mục</label>
            <select id="category_id" name="category_id" class="w-full border border-zinc-800 bg-zinc-950 px-3 py-3 text-sm text-white outline-none focus:border-zinc-600">
                <option value="">Tất cả</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-2">
            <label for="status" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trạng thái</label>
            <select id="status" name="status" class="w-full border border-zinc-800 bg-zinc-950 px-3 py-3 text-sm text-white outline-none focus:border-zinc-600">
                <option value="">Tất cả</option>
                @foreach($statusLabels as $status => $label)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-2">
            <label for="stock" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tồn kho</label>
            <select id="stock" name="stock" class="w-full border border-zinc-800 bg-zinc-950 px-3 py-3 text-sm text-white outline-none focus:border-zinc-600">
                <option value="">Tất cả</option>
                <option value="available" @selected(request('stock') === 'available')>Còn hàng tốt</option>
                <option value="low" @selected(request('stock') === 'low')>Sắp hết</option>
                <option value="out" @selected(request('stock') === 'out')>Hết hàng</option>
            </select>
        </div>

        <div class="flex items-end gap-2 lg:col-span-2">
            <button class="flex-1 bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-black transition hover:bg-zinc-200">
                Lọc
            </button>
            <a href="{{ route('admin.storefront.index') }}" class="border border-zinc-800 px-4 py-3 text-xs font-black uppercase tracking-widest text-zinc-400 transition hover:border-zinc-600 hover:text-white">
                Xóa
            </a>
        </div>
    </form>

    <div class="flex items-center justify-between gap-4">
        <p class="text-xs font-medium text-zinc-500">
            Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} trong {{ $products->total() }} sản phẩm.
        </p>
        <a href="{{ route('admin.products.create') }}" class="bg-white px-5 py-3 text-xs font-black uppercase tracking-widest text-black transition hover:bg-zinc-200">
            Thêm sản phẩm
        </a>
    </div>

    <div class="type-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-900/60 text-[10px] uppercase tracking-widest text-zinc-500">
                    <tr>
                        <th class="px-5 py-4 font-medium">Sản phẩm</th>
                        <th class="px-5 py-4 font-medium">Danh mục</th>
                        <th class="px-5 py-4 font-medium">Tồn kho</th>
                        <th class="px-5 py-4 font-medium">Trạng thái</th>
                        <th class="px-5 py-4 font-medium">Trưng bày</th>
                        <th class="px-5 py-4 font-medium text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($products as $product)
                        @php
                            $firstVariant = $product->variants->first();
                            $totalStock = (int) ($product->total_stock ?? 0);
                        @endphp
                        <tr class="transition hover:bg-zinc-900/35">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 shrink-0 overflow-hidden bg-zinc-900">
                                        <img src="{{ $product->image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" class="h-full w-full object-cover" alt="{{ $product->name }}">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-bold uppercase text-white">{{ $product->name }}</p>
                                        <p class="mt-1 truncate text-[10px] uppercase tracking-wider text-zinc-500">{{ $product->slug }}</p>
                                        <p class="mt-1 truncate text-[10px] text-zinc-600">{{ $firstVariant?->sku ?? 'Chưa có SKU' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-zinc-400">{{ $product->category?->name ?? 'Chưa phân loại' }}</td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-white">{{ $totalStock }}</p>
                                <p class="mt-1 text-[10px] uppercase tracking-widest text-zinc-500">{{ $product->variants_count }} biến thể</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-1 text-[10px] font-bold uppercase {{ $statusTone[$product->status] ?? 'bg-zinc-700 text-zinc-300' }}">
                                    {{ $statusLabels[$product->status] ?? $product->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <form action="{{ route('admin.storefront.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="featured_position" onchange="this.form.submit()" class="border border-zinc-800 bg-zinc-950 px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-zinc-300 outline-none focus:border-zinc-600">
                                        <option value="">Không</option>
                                        <option value="hero" @selected($product->featured_position === 'hero')>Hero</option>
                                        <option value="secondary" @selected($product->featured_position === 'secondary')>Phụ</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}" class="border border-zinc-800 px-3 py-2 text-[10px] font-black uppercase tracking-widest text-zinc-300 transition hover:border-zinc-600 hover:text-white">Xem</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="bg-white px-3 py-2 text-[10px] font-black uppercase tracking-widest text-black transition hover:bg-zinc-200">Sửa</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-zinc-500">Không tìm thấy sản phẩm phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-800 px-5 py-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
