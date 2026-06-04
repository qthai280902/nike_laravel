@php
    $cartService = app(\App\Services\CartService::class);
    $cart = $cartService->items();
    $total = $cartService->subtotal();
    $count = $cartService->count();
@endphp

<div id="cart-content-wrapper" class="flex flex-col h-full">
    {{-- Cart Items List --}}
    <div id="cart-items-list" class="flex-grow overflow-y-auto px-6 py-6 space-y-6">
        @forelse($cart as $id => $item)
            <div class="flex space-x-4 animate-[fade-in_0.3s_ease-out] group">
                <div class="w-20 h-20 bg-nike-gray-100 flex-shrink-0 shadow-sm">
                    <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-grow relative">
                    <h4 class="font-bold text-[13px] text-nike-black uppercase pr-8 tracking-tighter leading-tight">{{ $item['product_name'] }}</h4>
                    <p class="text-nike-gray-500 text-[11px] font-medium">Màu sắc: {{ $item['color'] ?? '' }} | Kích cỡ: {{ $item['size'] ?? '' }} | Số lượng: {{ $item['qty'] }}</p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="font-bold text-sm">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}₫</span>
                        <button 
                            onclick="removeFromCart('{{ $id }}')"
                            class="text-[10px] uppercase font-black text-nike-gray-400 hover:text-nike-red tracking-widest transition-colors"
                        >
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-24 px-6 text-center">
                <svg class="w-12 h-12 text-nike-gray-200 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <p class="text-nike-gray-400 font-bold uppercase text-[10px] tracking-widest">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('catalog.index') }}" class="mt-6 bg-nike-black text-white px-8 py-3 rounded-full font-bold uppercase text-[10px] transition-transform hover:scale-105">Mua sắm ngay</a>
            </div>
        @endforelse
    </div>

    {{-- Footer --}}
    @if($count > 0)
    <div class="px-6 py-8 border-t border-nike-gray-200 bg-nike-snow">
        <div class="space-y-2 mb-8">
            <div class="flex justify-between text-xs">
                <span class="text-nike-gray-500 font-bold uppercase tracking-widest">Tạm tính</span>
                <span class="font-bold">{{ number_format($total, 0, ',', '.') }}₫</span>
            </div>
            <div class="flex justify-between text-xl font-black text-nike-black uppercase tracking-tighter border-t border-nike-gray-100 pt-4">
                <span>Tổng cộng</span>
                <span id="cart-total-price">{{ number_format($total, 0, ',', '.') }}₫</span>
            </div>
        </div>
        <x-pill-button href="/checkout" class="w-full py-5 text-center bg-nike-black text-white hover:bg-nike-gray-800 font-black uppercase text-xs tracking-widest">Tiếp tục thanh toán</x-pill-button>
    </div>
    @endif
</div>

<script>
    async function removeFromCart(variantId) {
        try {
            const response = await fetch("{{ route('cart.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ variant_id: variantId })
            });

            const data = await response.json();

            if (data.success) {
                // 1. CẬP NHẬT BADGE NGAY LẬP TỨC
                updateCartBadge(data.cart_count);
                
                // 2. HIỆN MODAL THÀNH CÔNG (DÙNG HELPER GLOBAL)
                if (typeof showSuccessModal === 'function') {
                    showSuccessModal('Đã xóa sản phẩm khỏi túi');
                }

                // 3. REFRESH FRAGMENT
                const fragResponse = await fetch("{{ route('cart.fragment') }}");
                const html = await fragResponse.text();
                document.getElementById('cart-fragment-container').innerHTML = html;
            }
        } catch (error) {
            console.error('Lỗi khi xóa:', error);
        }
    }

    function updateCartBadge(count) {
        const badge = document.getElementById('cart-count-badge');
        if (badge) {
            if (count > 0) {
                badge.innerText = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    // Tự động chạy khi fragment được nạp để đồng bộ badge ban đầu
    updateCartBadge({{ $count }});
</script>
