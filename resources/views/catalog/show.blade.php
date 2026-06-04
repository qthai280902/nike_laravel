@extends('layouts.app')

@section('title', $product->name . ' | Nike Hybrid')

@section('content')
    @php
        $fallbackImage = asset('images/hero.png');
        $highlights = collect($product->highlights ?? [])->filter()->values();
        $reviewCount = (int) ($product->approved_reviews_count ?? $product->approvedReviews->count());
        $averageRating = $product->approved_reviews_avg_rating ? number_format((float) $product->approved_reviews_avg_rating, 1) : null;
    @endphp

    </div>
</div>

{{-- SIZE GUIDE MODAL --}}
<div id="size-guide-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="toggleSizeGuide()"></div>
    <div class="relative bg-white p-8 md:p-12 max-w-2xl w-full mx-4 shadow-2xl animate-[scale-in_0.3s_ease-out]">
        <button onclick="toggleSizeGuide()" class="absolute top-6 right-6 p-2 hover:bg-nike-gray-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <h2 class="text-3xl font-nike-display uppercase mb-8">Bảng Kích Cỡ Giày Nike</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left font-nike-body border-collapse">
                <thead>
                    <tr class="bg-nike-gray-100 uppercase text-xs tracking-tighter border-b border-nike-gray-200">
                        <th class="px-6 py-4 font-bold ">US (Mỹ)</th>
                        <th class="px-6 py-4 font-bold ">EU (Châu Âu)</th>
                        <th class="px-6 py-4 font-bold ">CM (Chiều dài chân)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-nike-gray-200">
                    <tr class="hover:bg-nike-snow">
                        <td class="px-6 py-4">7</td><td class="px-6 py-4">40</td><td class="px-6 py-4">25 cm</td>
                    </tr>
                    <tr class="hover:bg-nike-snow">
                        <td class="px-6 py-4">8</td><td class="px-6 py-4">41</td><td class="px-6 py-4">26 cm</td>
                    </tr>
                    <tr class="hover:bg-nike-snow">
                        <td class="px-6 py-4">9</td><td class="px-6 py-4">42.5</td><td class="px-6 py-4">27 cm</td>
                    </tr>
                    <tr class="hover:bg-nike-snow">
                        <td class="px-6 py-4">10</td><td class="px-6 py-4">44</td><td class="px-6 py-4">28 cm</td>
                    </tr>
                    <tr class="hover:bg-nike-snow">
                        <td class="px-6 py-4">11</td><td class="px-6 py-4">45</td><td class="px-6 py-4">29 cm</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <p class="mt-8 text-nike-gray-500 text-sm leading-relaxed ">
            * Lưu ý: Đây là bảng kích cỡ tiêu chuẩn của Nike. Nếu chân bạn rộng hoặc mu bàn chân cao, bạn nên cân nhắc tăng 0.5 size.
        </p>
    </div>
</div>

<section class="max-w-[1920px] mx-auto px-6 md:px-12 py-12">
    <div class="flex flex-col lg:flex-row gap-16">
        {{-- Product Image Gallery --}}
        <div class="w-full lg:w-2/3 space-y-4">
            <div class="bg-nike-gray-100 aspect-square overflow-hidden no-border-radius">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='{{ $fallbackImage }}'" class="w-full h-full object-contain p-6">
            </div>
        </div>

        {{-- Product Info --}}
        <div class="w-full lg:w-1/3">
            <div class="sticky top-28">
                <h1 class="text-3xl font-nike-display uppercase leading-tight mb-2">{{ $product->name }}</h1>
                <p class="text-nike-gray-500 font-nike-body mb-6">{{ match(strtolower($product->category->name)) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', 'lifestyle' => 'Phong cách sống', 'running' => 'Chạy bộ', 'basketball' => 'Bóng rổ', 'training' => 'Tập luyện', 'yoga' => 'Yoga', 'shoes' => 'Giày', 'clothing' => 'Quần áo', 'accessories' => 'Phụ kiện', default => $product->category->name } }}</p>
                <p class="text-xl font-nike-body font-medium mb-12">{{ number_format($product->price, 0, ',', '.') }}₫</p>

                {{-- Sizing Section --}}
                <div class="mb-12">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-nike-body font-medium uppercase text-sm">Chọn kích cỡ (US)</span>
                        <button onclick="toggleSizeGuide()" class="text-nike-gray-500 text-sm underline hover:text-nike-black transition-colors">Bảng kích cỡ</button>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach($product->variants as $variant)
                            <label class="relative group cursor-pointer">
                                <input type="radio" name="variant_id" value="{{ $variant->id }}" data-stock="{{ $variant->stock }}" class="peer absolute opacity-0 w-full h-full cursor-pointer">
                                <div class="border border-nike-gray-200 py-3 rounded-md font-nike-body text-center transition-all peer-checked:border-nike-black peer-checked:bg-nike-black peer-checked:text-white hover:border-nike-black">
                                    {{ str_replace('US ', '', $variant->size) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div id="stock-display" class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400 ">
                        Chọn size để xem tồn kho
                    </div>
                    <p id="size-error" class="text-nike-red text-xs mt-2 hidden text-center ">Vui lòng chọn size trước khi thêm vào giỏ.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3">
                    <x-pill-button 
                        onclick="handleAjaxAddToCart()"
                        id="add-to-cart-btn"
                        class="w-full py-5 text-lg bg-nike-black text-white hover:bg-nike-gray-800"
                    >
                        Thêm Vào Giỏ
                    </x-pill-button>
                    
                    @auth
                        @php
                            $isWishlisted = auth()->user()->wishlistProducts()->where('product_id', $product->id)->exists();
                        @endphp
                        <button 
                            onclick="toggleWishlist('{{ $product->id }}')" 
                            id="wishlist-btn"
                            class="w-full py-5 border border-nike-gray-200 rounded-full font-nike-body font-medium flex items-center justify-center hover:border-nike-black transition-colors"
                        >
                            <span id="wishlist-text">{{ $isWishlisted ? 'Đã Yêu Thích' : 'Yêu Thích' }}</span>
                            <svg id="wishlist-icon" class="w-5 h-5 ml-2 transition-all {{ $isWishlisted ? 'fill-nike-red stroke-nike-red' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="w-full py-5 border border-nike-gray-200 rounded-full font-nike-body font-medium flex items-center justify-center hover:border-nike-black transition-colors">
                            Yêu Thích
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </a>
                    @endauth
                </div>

                <div class="mt-12 prose prose-sm font-nike-body text-nike-gray-500 leading-relaxed border-t border-nike-gray-100 pt-8 uppercase text-[12px] tracking-widest font-bold ">
                    <p>{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-[1440px] px-6 pb-12 md:px-12">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <article class="border-t border-nike-black pt-6">
            <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Câu chuyện sản phẩm</p>
            <h2 class="mt-3 text-2xl font-black uppercase leading-none tracking-tight text-nike-black">Từ catalog cửa hàng</h2>
            <p class="mt-5 text-sm font-medium leading-7 text-nike-gray-600">
                {{ $product->product_story ?: $product->description }}
            </p>
        </article>

        <article class="border-t border-nike-black pt-6">
            <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Điểm nổi bật</p>
            <h2 class="mt-3 text-2xl font-black uppercase leading-none tracking-tight text-nike-black">Chi tiết đáng chú ý</h2>
            <ul class="mt-5 space-y-3 text-sm font-bold leading-6 text-nike-black">
                @forelse($highlights as $highlight)
                    <li class="flex gap-3">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 bg-nike-black"></span>
                        <span>{{ $highlight }}</span>
                    </li>
                @empty
                    <li>{{ $product->description }}</li>
                @endforelse
            </ul>
        </article>

        <article class="border-t border-nike-black pt-6">
            <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Cách phối đồ / chăm sóc</p>
            <h2 class="mt-3 text-2xl font-black uppercase leading-none tracking-tight text-nike-black">Giữ sản phẩm bền đẹp</h2>
            <p class="mt-5 text-sm font-medium leading-7 text-nike-gray-600">
                {{ $product->care_instructions ?: 'Lau sạch nhẹ nhàng sau khi dùng, để khô tự nhiên và tránh nhiệt cao để giữ form sản phẩm.' }}
            </p>
        </article>
    </div>
</section>

<section class="border-t border-nike-gray-100 bg-nike-snow px-6 py-12 md:px-12">
    <div class="mx-auto max-w-[1440px]">
        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Đánh giá sản phẩm</p>
                <h2 class="mt-2 text-3xl font-black uppercase leading-none tracking-tight text-nike-black">
                    Nhận xét từ khách hàng
                </h2>
            </div>
            <div class="text-left md:text-right">
                <p class="text-2xl font-black text-nike-black">{{ $averageRating ? $averageRating.'/5' : 'Chưa có' }}</p>
                <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">{{ $reviewCount }} đánh giá đã duyệt</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @forelse($product->approvedReviews as $review)
                <article class="border border-nike-gray-150 bg-white p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-black uppercase leading-tight text-nike-black">{{ $review->title ?: 'Đánh giá sản phẩm' }}</p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-nike-gray-400">{{ $review->author_name }}</p>
                        </div>
                        <span class="shrink-0 text-sm font-black text-nike-black">{{ $review->rating }}/5</span>
                    </div>
                    <p class="mt-4 text-sm font-medium leading-6 text-nike-gray-600">{{ $review->comment }}</p>
                </article>
            @empty
                <div class="col-span-full border border-dashed border-nike-gray-200 bg-white p-8 text-center">
                    <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Sản phẩm này chưa có đánh giá đã duyệt.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<script>
    function toggleSizeGuide() {
        const modal = document.getElementById('size-guide-modal');
        modal.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    // --- DYNAMIC STOCK DISPLAY ---
    document.querySelectorAll('input[name="variant_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const stock = this.getAttribute('data-stock');
            const display = document.getElementById('stock-display');
            display.innerText = `Tồn kho: ${stock}`;
            display.classList.remove('text-nike-gray-400');
            display.classList.add('text-nike-black');
        });
    });

    // --- WISHLIST TOGGLE ---
    async function toggleWishlist(productId) {
        try {
            const response = await fetch("{{ route('wishlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            });

            const data = await response.json();
            const btnText = document.getElementById('wishlist-text');
            const icon = document.getElementById('wishlist-icon');

            if (data.status === 'added') {
                btnText.innerText = 'Đã Yêu Thích';
                icon.classList.add('fill-nike-red', 'stroke-nike-red');
                showSuccessModal('Đã thêm vào yêu thích');
            } else {
                btnText.innerText = 'Yêu Thích';
                icon.classList.remove('fill-nike-red', 'stroke-nike-red');
                showSuccessModal('Đã xóa khỏi yêu thích');
            }
        } catch (error) {
            console.error('Wishlist error:', error);
        }
    }

    async function handleAjaxAddToCart() {
        const selectedVariant = document.querySelector('input[name="variant_id"]:checked');
        const errorMsg = document.getElementById('size-error');
        const successModal = document.getElementById('add-success-modal');

        if (!selectedVariant) {
            errorMsg.classList.remove('hidden');
            return;
        }
        errorMsg.classList.add('hidden');

        const btn = document.getElementById('add-to-cart-btn');
        btn.disabled = true;
        
        try {
            const response = await fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    variant_id: selectedVariant.value,
                    quantity: 1
                })
            });

            if (response.ok) {
                const data = await response.json();
                
                // 1. HIỆN MODAL THÀNH CÔNG
                if (typeof showSuccessModal === 'function') {
                    showSuccessModal('Sản phẩm đã được thêm vào giỏ');
                }
                
                // 2. GIỮ ĐÚNG 1 GIÂY (1000MS)
                setTimeout(async () => {
                    // 3. CẬP NHẬT BADGE
                    if (typeof updateCartBadge === 'function') {
                        updateCartBadge(data.cart_count);
                    }
                    
                    // 4. FETCH FRAGMENT MỚI
                    const fragmentResponse = await fetch("{{ route('cart.fragment') }}");
                    const fragmentHtml = await fragmentResponse.text();
                    document.getElementById('cart-fragment-container').innerHTML = fragmentHtml;
                    
                    if (typeof toggleCart === 'function') {
                        toggleCart();
                    }
                }, 1000);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            btn.disabled = false;
        }
    }
</script>

<style>
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endsection
