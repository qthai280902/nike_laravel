@extends('layouts.app')

@section('title', 'Hồ sơ thành viên | Nike Hybrid')

@section('content')
<section class="max-w-[1920px] mx-auto px-6 md:px-12 py-24">
    <div class="max-w-4xl mx-auto">
        {{-- Profile Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-16 gap-6 bg-nike-snow p-8 border border-nike-gray-100">
            <div>
                <h1 class="text-nike-hero tracking-tighter leading-none mb-2">Hồ sơ.</h1>
                <p class="text-nike-gray-500 font-medium tracking-tight uppercase text-xs">Chào mừng bạn trở lại hệ thống Nike Hybrid</p>
            </div>
            <div class="text-right">
                <p class="text-nike-gray-300 font-nike-display text-[10px] uppercase tracking-[0.3em] font-black mb-1">Mã Thành Viên</p>
                <p class="text-nike-black font-black text-4xl uppercase tracking-tighter">
                    {{ $user->display_id ?? '#' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>

        {{-- Tab Menu --}}
        <div class="flex border-b border-nike-gray-200 mb-12 space-x-12 overflow-x-auto scrollbar-hide">
            <button onclick="switchTab('details')" id="tab-btn-details" class="tab-btn pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-nike-black transition-all">
                Thông tin chi tiết
            </button>
            <button onclick="switchTab('orders')" id="tab-btn-orders" class="tab-btn pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-nike-gray-400 hover:text-nike-black transition-all">
                Lịch sử mua hàng
            </button>
            <button onclick="switchTab('wishlist')" id="tab-btn-wishlist" class="tab-btn pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-nike-gray-400 hover:text-nike-black transition-all">
                Sản phẩm yêu thích
            </button>
        </div>

        {{-- Tab Contents --}}
        <div id="tab-content-details" class="tab-content">
            <div class="space-y-12">
                <div>
                    <h2 class="text-3xl font-bold uppercase mb-8">Thông tin cá nhân</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-nike-body">
                        <div>
                            <p class="text-nike-gray-500 uppercase text-[10px] font-bold tracking-widest mb-1">Họ và Tên</p>
                            <p class="text-lg font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-nike-gray-500 uppercase text-[10px] font-bold tracking-widest mb-1">Địa chỉ Email</p>
                            <p class="text-lg font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-nike-gray-500 uppercase text-[10px] font-bold tracking-widest mb-1">Vai trò tài khoản</p>
                            <p class="text-lg font-medium uppercase font-bold text-nike-red">{{ $user->role }}</p>
                        </div>
                        <div>
                            <p class="text-nike-gray-500 uppercase text-[10px] font-bold tracking-widest mb-1">Thành viên từ</p>
                            <p class="text-lg font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="bg-nike-black text-white py-4 px-8 uppercase font-bold text-xs tracking-widest text-center rounded-full hover:bg-nike-gray-800 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Quản lý hệ thống
                        </a>
                    @endif

                    <x-pill-button class="bg-white text-nike-black border border-nike-gray-300 hover:bg-nike-gray-100 py-4 px-8 uppercase font-bold text-xs tracking-widest">
                        Chỉnh sửa hồ sơ
                    </x-pill-button>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-pill-button type="submit" class="bg-nike-red text-white hover:bg-red-800 py-4 px-8 uppercase font-bold text-xs tracking-widest">
                            Đăng xuất
                        </x-pill-button>
                    </form>
                </div>
            </div>
        </div>

        <div id="tab-content-orders" class="tab-content hidden">
            <h2 class="text-3xl font-bold uppercase mb-8">Đơn hàng của tôi</h2>
            @if($orders->isEmpty())
                <p class="text-nike-gray-500">Bạn chưa có đơn hàng nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left font-nike-body border-collapse">
                        <thead>
                            <tr class="bg-nike-gray-100 uppercase text-[10px] tracking-widest border-b border-nike-gray-200">
                                <th class="px-6 py-4 font-black">Mã Đơn</th>
                                <th class="px-6 py-4 font-black">Ngày Đặt</th>
                                <th class="px-6 py-4 font-black">Sản phẩm</th>
                                <th class="px-6 py-4 font-black">Tổng cộng</th>
                                <th class="px-6 py-4 font-black">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-nike-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-nike-snow transition-colors">
                                    <td class="px-6 py-6 font-bold">#{{ $order->id }}</td>
                                    <td class="px-6 py-6 text-nike-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-6">
                                        <div class="flex -space-x-3 overflow-hidden">
                                            @foreach($order->items as $item)
                                                <img src="{{ $item->product->image_url }}" alt="Item" class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover bg-nike-gray-100">
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 font-black tracking-tighter">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                    <td class="px-6 py-6">
                                        <span class="px-3 py-1 bg-nike-black text-white text-[9px] font-black uppercase tracking-widest">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div id="tab-content-wishlist" class="tab-content hidden">
            <h2 class="text-3xl font-bold uppercase mb-8">Danh sách yêu thích</h2>
            @if($wishlistProducts->isEmpty())
                <p id="empty-wishlist-msg" class="text-nike-gray-500">Danh sách yêu thích của bạn đang trống.</p>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6" id="wishlist-grid">
                    @foreach($wishlistProducts as $product)
                        <div id="wishlist-item-{{ $product->id }}" class="group relative bg-nike-snow border border-nike-gray-100 p-4 hover:shadow-xl transition-all">
                            {{-- Delete Button --}}
                            <button onclick="openWishlistDeleteModal('{{ $product->id }}')" class="absolute top-2 right-2 z-10 bg-white/80 backdrop-blur-sm text-nike-black p-1.5 rounded-full hover:bg-nike-red hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <a href="{{ route('catalog.show', $product->slug) }}" class="block">
                                <div class="aspect-square bg-nike-gray-100 mb-4 overflow-hidden">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <p class="text-[11px] font-black uppercase tracking-tight">{{ $product->name }}</p>
                                <p class="text-[10px] text-nike-gray-500 uppercase">{{ $product->category->name }}</p>
                                <p class="text-[12px] font-black mt-2 tracking-tighter">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Custom Wishlist Deletion Modal --}}
    <div id="wishlist-delete-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeWishlistDeleteModal()"></div>
        <div class="relative bg-white p-8 max-w-sm w-full border border-nike-gray-200 shadow-2xl animate-[scale-in_0.2s_ease-out] text-center rounded-none">
            <h3 class="font-bold uppercase tracking-widest text-nike-black text-sm mb-4">Xác nhận</h3>
            <p class="text-nike-gray-500 font-medium text-xs uppercase tracking-tight mb-8">Bạn muốn xóa sản phẩm này khỏi danh sách yêu thích?</p>
            <div class="flex space-x-4">
                <button onclick="closeWishlistDeleteModal()" class="flex-1 border border-black text-black py-3 text-[11px] font-bold uppercase tracking-widest hover:bg-nike-gray-50 transition-colors rounded-none">
                    [ HỦY ]
                </button>
                <button onclick="confirmRemoveFromWishlist()" class="flex-1 bg-black text-white py-3 text-[11px] font-bold uppercase tracking-widest hover:bg-nike-gray-800 transition-colors rounded-none">
                    [ XÁC NHẬN ]
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    function switchTab(tabName) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        // Deactivate all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-nike-black');
            btn.classList.add('border-transparent', 'text-nike-gray-400');
        });

        // Show active content
        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
        // Activate active button
        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.remove('border-transparent', 'text-nike-gray-400');
        activeBtn.classList.add('border-nike-black');
    }

    let targetProductId = null;

    function openWishlistDeleteModal(productId) {
        targetProductId = productId;
        document.getElementById('wishlist-delete-modal').classList.remove('hidden');
    }

    function closeWishlistDeleteModal() {
        targetProductId = null;
        document.getElementById('wishlist-delete-modal').classList.add('hidden');
    }

    async function confirmRemoveFromWishlist() {
        if (!targetProductId) return;
        const productId = targetProductId;
        closeWishlistDeleteModal();

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
            
            if (data.status === 'removed') {
                const element = document.getElementById(`wishlist-item-${productId}`);
                if (element) {
                    element.style.opacity = '0';
                    element.style.transform = 'scale(0.9)';
                    
                    setTimeout(() => {
                        element.remove();
                        
                        // Check if wishlist is now empty
                        const grid = document.getElementById('wishlist-grid');
                        if (grid && grid.children.length === 0) {
                            grid.remove();
                            const container = document.getElementById('tab-content-wishlist');
                            const msg = document.createElement('p');
                            msg.id = 'empty-wishlist-msg';
                            msg.className = 'text-nike-gray-500';
                            msg.innerText = 'Danh sách yêu thích của bạn đang trống.';
                            container.appendChild(msg);
                        }
                    }, 300);
                }

                if (typeof showSuccessModal === 'function') {
                    showSuccessModal('Đã xóa khỏi yêu thích');
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>
@endsection
