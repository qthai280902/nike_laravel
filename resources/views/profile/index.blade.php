@extends('layouts.app')

@section('title', 'Hồ sơ thành viên | Nike Hybrid')

@section('content')
@php
    $avatarUrl = $user->avatar_display_url;
    $memberCode = $user->display_id ?? '#' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);
    $roleLabel = match ($user->role) {
        'customer' => 'Khách hàng',
        'seller' => 'Người bán',
        'admin' => 'Quản trị viên',
        default => $user->role,
    };
@endphp

<section class="mx-auto max-w-[1920px] px-6 py-16 md:px-12 md:py-24">
    <div class="mx-auto max-w-5xl">
        <div class="mb-12 flex flex-col gap-8 border border-nike-gray-100 bg-nike-snow p-6 md:flex-row md:items-center md:justify-between md:p-8">
            <div class="flex items-center gap-5">
                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-full bg-nike-black text-white">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <span class="flex h-full w-full items-center justify-center text-xl font-black uppercase">{{ $user->initials }}</span>
                    @endif
                </div>
                <div>
                    <p class="mb-2 text-[10px] font-black uppercase tracking-[0.24em] text-nike-gray-400">Hồ sơ thành viên</p>
                    <h1 class="text-4xl font-black uppercase leading-none tracking-tight text-nike-black md:text-6xl">{{ $user->name }}</h1>
                    <p class="mt-3 text-xs font-bold uppercase tracking-widest text-nike-gray-500">Chào mừng bạn trở lại Nike Hybrid</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="mb-1 text-[10px] font-black uppercase tracking-[0.3em] text-nike-gray-300">Mã thành viên</p>
                <p class="text-3xl font-black uppercase tracking-tight text-nike-black md:text-4xl">{{ $memberCode }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-10 flex gap-8 overflow-x-auto border-b border-nike-gray-200">
            <button onclick="switchTab('details')" id="tab-btn-details" class="tab-btn border-b-2 border-nike-black pb-4 text-sm font-bold uppercase tracking-widest transition-all">
                Thông tin
            </button>
            <button onclick="switchTab('orders')" id="tab-btn-orders" class="tab-btn border-b-2 border-transparent pb-4 text-sm font-bold uppercase tracking-widest text-nike-gray-400 transition-all hover:text-nike-black">
                Đơn hàng
            </button>
            <button onclick="switchTab('wishlist')" id="tab-btn-wishlist" class="tab-btn border-b-2 border-transparent pb-4 text-sm font-bold uppercase tracking-widest text-nike-gray-400 transition-all hover:text-nike-black">
                Yêu thích
            </button>
            <button onclick="switchTab('support')" id="tab-btn-support" class="tab-btn border-b-2 border-transparent pb-4 text-sm font-bold uppercase tracking-widest text-nike-gray-400 transition-all hover:text-nike-black">
                Hỗ trợ
            </button>
            <button onclick="switchTab('reviews')" id="tab-btn-reviews" class="tab-btn border-b-2 border-transparent pb-4 text-sm font-bold uppercase tracking-widest text-nike-gray-400 transition-all hover:text-nike-black">
                Đánh giá của tôi
            </button>
            <button onclick="switchTab('marketplace')" id="tab-btn-marketplace" class="tab-btn border-b-2 border-transparent pb-4 text-sm font-bold uppercase tracking-widest text-nike-gray-400 transition-all hover:text-nike-black">
                C2C
            </button>
        </div>

        <div id="tab-content-details" class="tab-content">
            <div class="space-y-10">
                <div>
                    <h2 class="mb-8 text-3xl font-bold uppercase">Thông tin cá nhân</h2>
                    <div class="grid grid-cols-1 gap-8 font-nike-body md:grid-cols-2">
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-nike-gray-500">Họ và tên</p>
                            <p class="text-lg font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-nike-gray-500">Địa chỉ email</p>
                            <p class="text-lg font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-nike-gray-500">Vai trò tài khoản</p>
                            <p class="text-lg font-bold uppercase text-nike-red">{{ $roleLabel }}</p>
                        </div>
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-nike-gray-500">Thành viên từ</p>
                            <p class="text-lg font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:flex-row">
                    @if($user->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-nike-black px-8 py-4 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-nike-gray-800">
                            Quản lý hệ thống
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full border border-nike-gray-300 bg-white px-8 py-4 text-xs font-bold uppercase tracking-widest text-nike-black transition hover:bg-nike-gray-100">
                        Chỉnh sửa hồ sơ
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-nike-red px-8 py-4 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-red-800 md:w-auto">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="tab-content-orders" class="tab-content hidden">
            <h2 class="mb-8 text-3xl font-bold uppercase">Đơn hàng của tôi</h2>
            @if($orders->isEmpty())
                <p class="text-nike-gray-500">Bạn chưa có đơn hàng nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left font-nike-body">
                        <thead>
                            <tr class="border-b border-nike-gray-200 bg-nike-gray-100 text-[10px] uppercase tracking-widest">
                                <th class="px-6 py-4 font-black">Mã đơn</th>
                                <th class="px-6 py-4 font-black">Ngày đặt</th>
                                <th class="px-6 py-4 font-black">Sản phẩm</th>
                                <th class="px-6 py-4 font-black">Tổng cộng</th>
                                <th class="px-6 py-4 font-black">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-nike-gray-100">
                            @foreach($orders as $order)
                                <tr class="transition-colors hover:bg-nike-snow">
                                    <td class="px-6 py-6 font-bold">#{{ $order->id }}</td>
                                    <td class="px-6 py-6 text-nike-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-6">
                                        <div class="flex -space-x-3 overflow-hidden">
                                            @foreach($order->items as $item)
                                                <img src="{{ $item->variant?->product?->image_url ?? asset('images/hero.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="Item" class="inline-block h-10 w-10 rounded-full bg-nike-gray-100 object-cover ring-2 ring-white">
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 font-black tracking-tight">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                    <td class="px-6 py-6">
                                        <span class="bg-nike-black px-3 py-1 text-[9px] font-black uppercase tracking-widest text-white">
                                            {{ match($order->status) { 'pending' => 'Đang xử lý', 'paid' => 'Đã thanh toán', 'shipped' => 'Đang giao', 'delivered' => 'Đã giao', 'cancelled' => 'Đã hủy', default => $order->status } }}
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
            <h2 class="mb-8 text-3xl font-bold uppercase">Danh sách yêu thích</h2>
            @if($wishlistProducts->isEmpty())
                <p id="empty-wishlist-msg" class="text-nike-gray-500">Danh sách yêu thích của bạn đang trống.</p>
            @else
                <div class="grid grid-cols-2 gap-6 md:grid-cols-3" id="wishlist-grid">
                    @foreach($wishlistProducts as $product)
                        <div id="wishlist-item-{{ $product->id }}" class="group relative border border-nike-gray-100 bg-nike-snow p-4 transition-all hover:shadow-xl">
                            <button onclick="openWishlistDeleteModal('{{ $product->id }}')" class="absolute right-2 top-2 z-10 rounded-full bg-white/80 p-1.5 text-nike-black shadow-sm backdrop-blur-sm transition-all hover:bg-nike-red hover:text-white" title="Xóa khỏi yêu thích">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <a href="{{ route('catalog.show', $product->slug) }}" class="block">
                                <div class="mb-4 aspect-square overflow-hidden bg-nike-gray-100">
                                    <img src="{{ $product->image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </div>
                                <p class="text-[11px] font-black uppercase tracking-tight">{{ $product->name }}</p>
                                <p class="text-[10px] uppercase text-nike-gray-500">{{ $product->category->name }}</p>
                                <p class="mt-2 text-[12px] font-black tracking-tight">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="tab-content-support" class="tab-content hidden">
            <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold uppercase">Lịch sử hỗ trợ</h2>
                    <p class="mt-2 text-sm font-medium text-nike-gray-500">Các yêu cầu bạn đã gửi khi đăng nhập.</p>
                </div>
                <a href="{{ route('support.create') }}" class="inline-flex rounded-full bg-nike-black px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white">
                    Gửi hỗ trợ
                </a>
            </div>

            <div class="space-y-4">
                @forelse($supportTickets as $ticket)
                    <article class="border border-nike-gray-150 bg-white p-5">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">#{{ $ticket->id }} · {{ $ticket->created_at->format('H:i d/m/Y') }}</p>
                                <h3 class="mt-2 text-base font-black uppercase text-nike-black">{{ $ticket->subject }}</h3>
                                <p class="mt-2 text-sm font-medium leading-6 text-nike-gray-600">{{ Str::limit($ticket->message, 180) }}</p>
                            </div>
                            <span class="shrink-0 bg-nike-black px-3 py-1.5 text-[9px] font-black uppercase tracking-widest text-white">{{ $ticket->status_label }}</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 gap-3 border-t border-nike-gray-100 pt-4 text-xs font-bold uppercase tracking-wider text-nike-gray-500 md:grid-cols-2">
                            <p>Hoàn tất: <span class="text-nike-black">{{ $ticket->resolved_at?->format('H:i d/m/Y') ?? 'Chưa xử lý xong' }}</span></p>
                            <p>Người xử lý: <span class="text-nike-black">{{ $ticket->resolver?->name ?? 'Chưa có' }}</span></p>
                        </div>
                        @if($ticket->admin_note)
                            <p class="mt-4 border border-nike-gray-100 bg-nike-snow p-4 text-sm font-medium leading-6 text-nike-gray-600">{{ $ticket->admin_note }}</p>
                        @endif
                    </article>
                @empty
                    <div class="border border-dashed border-nike-gray-200 bg-white p-8 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Bạn chưa có yêu cầu hỗ trợ nào.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div id="tab-content-reviews" class="tab-content hidden">
            <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold uppercase">Đánh giá của tôi</h2>
                    <p class="mt-2 text-sm font-medium text-nike-gray-500">Theo dõi trạng thái kiểm duyệt của các review đã gửi.</p>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($productReviews as $review)
                    <article class="border border-nike-gray-150 bg-white p-5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3 text-[10px] font-black uppercase tracking-widest">
                                    <span class="px-3 py-1.5 {{ $review->status_badge_class }}">{{ $review->status_label }}</span>
                                    <span class="border border-nike-gray-150 px-3 py-1.5 text-nike-gray-500">{{ $review->rating }}/5</span>
                                    <span class="border border-nike-gray-150 px-3 py-1.5 text-nike-gray-500">Gửi {{ $review->created_at?->format('d/m/Y') }}</span>
                                </div>
                                <a href="{{ $review->product ? route('catalog.show', $review->product->slug) : '#' }}" class="mt-4 inline-block text-sm font-black uppercase text-nike-black underline-offset-4 hover:underline">
                                    {{ $review->product?->name ?? 'Sản phẩm đã xóa' }}
                                </a>
                                <p class="mt-2 text-sm font-black uppercase text-nike-black">{{ $review->title ?: 'Đánh giá sản phẩm' }}</p>
                                <p class="mt-2 text-sm font-medium leading-6 text-nike-gray-600">{{ $review->comment }}</p>
                            </div>
                            <div class="shrink-0 text-left text-[10px] font-bold uppercase tracking-widest text-nike-gray-400 md:text-right">
                                <p>Duyệt lúc: <span class="text-nike-black">{{ $review->moderated_at?->format('d/m/Y') ?? 'Chưa có' }}</span></p>
                                <p class="mt-1">Admin: <span class="text-nike-black">{{ $review->moderator?->name ?? 'Chưa có' }}</span></p>
                            </div>
                        </div>
                        @if($review->rejection_reason)
                            <p class="mt-4 border border-red-100 bg-red-50 p-4 text-sm font-medium leading-6 text-red-800">{{ $review->rejection_reason }}</p>
                        @endif
                    </article>
                @empty
                    <div class="border border-dashed border-nike-gray-200 bg-white p-8 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Bạn chưa gửi đánh giá sản phẩm nào.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div id="tab-content-marketplace" class="tab-content hidden">
            <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold uppercase">Tin C2C của tôi</h2>
                    <p class="mt-2 text-sm font-medium text-nike-gray-500">Theo dõi trạng thái tin đăng marketplace của bạn.</p>
                </div>
                <a href="{{ route('marketplace.create') }}" class="inline-flex rounded-full bg-nike-black px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white">
                    Đăng bán
                </a>
            </div>

            <div class="space-y-4">
                @forelse($marketplaceListings as $listing)
                    <article class="flex flex-col gap-4 border border-nike-gray-150 bg-white p-4 md:flex-row md:items-center">
                        <div class="h-28 w-full shrink-0 overflow-hidden bg-nike-snow md:w-28">
                            <img src="{{ $listing->display_image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $listing->display_name }}" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">{{ $listing->display_source }}</p>
                                    <h3 class="mt-1 text-sm font-black uppercase text-nike-black">{{ $listing->display_name }}</h3>
                                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-nike-gray-500">Size {{ $listing->display_size }} · {{ $listing->display_color }}</p>
                                </div>
                                <p class="text-sm font-black text-nike-black">{{ number_format($listing->asking_price, 0, ',', '.') }}₫</p>
                            </div>
                            <div class="mt-4 flex flex-wrap items-center gap-3 text-[10px] font-black uppercase tracking-widest">
                                <span class="bg-nike-black px-3 py-1.5 text-white">{{ $listing->owner_status_label }}</span>
                                <span class="border border-nike-gray-150 px-3 py-1.5 text-nike-gray-500">Gửi {{ $listing->created_at->format('d/m/Y') }}</span>
                                <span class="border border-nike-gray-150 px-3 py-1.5 text-nike-gray-500">Cập nhật {{ $listing->status_changed_at?->format('d/m/Y') }}</span>
                                @if(! $listing->trashed())
                                    <a href="{{ route('marketplace.show', $listing) }}" class="px-3 py-1.5 text-nike-black underline underline-offset-4">Xem tin</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="border border-dashed border-nike-gray-200 bg-white p-8 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Bạn chưa có tin C2C nào.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="wishlist-delete-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeWishlistDeleteModal()"></div>
        <div class="relative w-full max-w-sm border border-nike-gray-200 bg-white p-8 text-center shadow-2xl">
            <h3 class="mb-4 text-sm font-bold uppercase tracking-widest text-nike-black">Xác nhận</h3>
            <p class="mb-8 text-xs font-medium uppercase tracking-tight text-nike-gray-500">Bạn muốn xóa sản phẩm này khỏi danh sách yêu thích?</p>
            <div class="flex gap-4">
                <button onclick="closeWishlistDeleteModal()" class="flex-1 border border-black py-3 text-[11px] font-bold uppercase tracking-widest text-black transition-colors hover:bg-nike-gray-50">
                    Hủy
                </button>
                <button onclick="confirmRemoveFromWishlist()" class="flex-1 bg-black py-3 text-[11px] font-bold uppercase tracking-widest text-white transition-colors hover:bg-nike-gray-800">
                    Xác nhận
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-nike-black');
            btn.classList.add('border-transparent', 'text-nike-gray-400');
        });

        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');

        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.remove('border-transparent', 'text-nike-gray-400');
        activeBtn.classList.add('border-nike-black');
    }

    let targetProductId = null;

    function openWishlistDeleteModal(productId) {
        targetProductId = productId;
        document.getElementById('wishlist-delete-modal').classList.remove('hidden');
        document.getElementById('wishlist-delete-modal').classList.add('flex');
    }

    function closeWishlistDeleteModal() {
        targetProductId = null;
        document.getElementById('wishlist-delete-modal').classList.add('hidden');
        document.getElementById('wishlist-delete-modal').classList.remove('flex');
    }

    async function confirmRemoveFromWishlist() {
        if (!targetProductId) {
            return;
        }

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
