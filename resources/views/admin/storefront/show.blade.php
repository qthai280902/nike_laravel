@extends('layouts.admin')

@section('page_title', 'Chi tiết sản phẩm')

@section('admin_content')
@php
    $statusLabels = [
        'active' => 'Đang bán',
        'inactive' => 'Tạm ẩn',
        'archived' => 'Lưu trữ',
    ];
    $reviewCount = (int) ($product->approved_reviews_count ?? $product->approvedReviews->count());
    $averageRating = $product->approved_reviews_avg_rating ? number_format((float) $product->approved_reviews_avg_rating, 1) : null;
@endphp

<div class="space-y-6">
    @if(session('success'))
        <div class="border border-green-500/20 bg-green-500/10 px-5 py-4 text-sm font-medium text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('admin.storefront.index') }}" class="text-xs font-bold uppercase tracking-widest text-zinc-500 transition hover:text-white">Quay lại danh sách</a>
        <a href="{{ route('admin.products.edit', $product) }}" class="bg-white px-5 py-3 text-xs font-black uppercase tracking-widest text-black transition hover:bg-zinc-200">Sửa sản phẩm</a>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <section class="type-card overflow-hidden xl:col-span-4">
            <div class="aspect-square bg-zinc-950">
                <img src="{{ $product->image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $product->name }}" class="h-full w-full object-cover">
            </div>
            <div class="space-y-3 p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">{{ $product->category?->name ?? 'Chưa phân loại' }}</p>
                <h3 class="text-2xl font-black uppercase leading-tight text-white">{{ $product->name }}</h3>
                <p class="break-all text-xs text-zinc-500">{{ $product->slug }}</p>
                <div class="flex flex-wrap gap-2 pt-2">
                    <span class="bg-zinc-950 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-zinc-300">{{ $statusLabels[$product->status] ?? $product->status }}</span>
                    @if($product->featured_position)
                        <span class="bg-white px-3 py-1 text-[10px] font-black uppercase tracking-widest text-black">{{ $product->featured_position }}</span>
                    @endif
                </div>
                <p class="pt-2 text-xl font-black text-white">{{ number_format($product->price, 0, ',', '.') }}₫</p>
            </div>
        </section>

        <section class="space-y-6 xl:col-span-8">
            <div class="type-card p-6">
                <h3 class="font-bold text-white">Nội dung trang chi tiết</h3>
                <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Câu chuyện sản phẩm</p>
                        <p class="mt-3 text-sm leading-6 text-zinc-300">{{ $product->product_story ?: $product->description }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Điểm nổi bật</p>
                        <ul class="mt-3 space-y-2 text-sm text-zinc-300">
                            @forelse(($product->highlights ?? []) as $highlight)
                                <li>{{ $highlight }}</li>
                            @empty
                                <li>Chưa có điểm nổi bật.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Cách phối đồ / chăm sóc</p>
                        <p class="mt-3 text-sm leading-6 text-zinc-300">{{ $product->care_instructions ?: 'Chưa có hướng dẫn.' }}</p>
                    </div>
                </div>
            </div>

            <div class="type-card overflow-hidden">
                <div class="border-b border-zinc-800 p-5">
                    <h3 class="font-bold text-white">Biến thể và tồn kho</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-900/60 text-[10px] uppercase tracking-widest text-zinc-500">
                            <tr>
                                <th class="px-5 py-4 font-medium">SKU</th>
                                <th class="px-5 py-4 font-medium">Size</th>
                                <th class="px-5 py-4 font-medium">Màu</th>
                                <th class="px-5 py-4 font-medium">Tồn kho</th>
                                <th class="px-5 py-4 font-medium">Giá riêng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800">
                            @foreach($product->variants as $variant)
                                <tr>
                                    <td class="px-5 py-4 font-mono text-xs text-white">{{ $variant->sku }}</td>
                                    <td class="px-5 py-4 text-zinc-300">{{ $variant->size ?? 'Không có' }}</td>
                                    <td class="px-5 py-4 text-zinc-300">{{ $variant->color ?? 'Không có' }}</td>
                                    <td class="px-5 py-4 font-bold text-white">{{ $variant->stock }}</td>
                                    <td class="px-5 py-4 text-zinc-300">{{ $variant->price_override ? number_format($variant->price_override, 0, ',', '.').'₫' : 'Theo sản phẩm' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="type-card p-6">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-white">Review đã duyệt</h3>
                        <p class="mt-1 text-xs text-zinc-500">{{ $reviewCount }} đánh giá | Điểm trung bình {{ $averageRating ? $averageRating.'/5' : 'chưa có' }}</p>
                    </div>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-3 lg:grid-cols-2">
                    @forelse($product->approvedReviews as $review)
                        <article class="border border-zinc-800 bg-zinc-950 p-4">
                            <div class="flex justify-between gap-4">
                                <p class="font-bold text-white">{{ $review->title ?: 'Đánh giá sản phẩm' }}</p>
                                <span class="text-sm font-black text-white">{{ $review->rating }}/5</span>
                            </div>
                            <p class="mt-1 text-[10px] uppercase tracking-widest text-zinc-500">{{ $review->author_name }}</p>
                            <p class="mt-3 text-sm leading-6 text-zinc-300">{{ $review->comment }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-500">Chưa có review đã duyệt.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
