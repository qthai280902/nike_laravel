@extends('layouts.admin')

@section('page_title', 'Chi tiết Thành viên')

@section('admin_content')
<div class="space-y-8">
    {{-- Back Link --}}
    <div>
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center text-sm text-zinc-400 hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Quay lại danh sách thành viên
        </a>
    </div>

    {{-- Member Overview Card --}}
    <div class="type-card rounded-lg p-6 border border-zinc-800 bg-[#18181b] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gradient-to-tr from-zinc-700 to-zinc-900 rounded-full border border-zinc-700 flex items-center justify-center text-xl font-bold text-white">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                <p class="text-zinc-400 text-sm">{{ $user->email }}</p>
                <div class="flex items-center space-x-2 mt-1">
                    @if($user->role === 'admin')
                        <span class="px-2 py-0.5 rounded bg-red-950 text-red-400 text-xs font-semibold border border-red-900/50">Quản trị viên</span>
                    @elseif($user->role === 'seller')
                        <span class="px-2 py-0.5 rounded bg-blue-950 text-blue-400 text-xs font-semibold border border-blue-900/50">Người bán</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 text-xs font-semibold border border-zinc-700/50">Khách hàng</span>
                    @endif
                    <span class="text-zinc-500 text-xs">Tham gia: {{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="border-t md:border-t-0 md:border-l border-zinc-800 pt-4 md:pt-0 md:pl-8 flex flex-col justify-center">
            <span class="text-xs text-zinc-500 uppercase font-bold tracking-wider">Tổng chi tiêu thực tế</span>
            <span class="text-2xl font-black text-white mt-1">
                {{ number_format($totalSpent, 0, ',', '.') }}đ
            </span>
            <span class="text-zinc-500 text-xs mt-1">Dựa trên các đơn hàng thành công</span>
        </div>
    </div>

    {{-- Tabs / Grid Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Orders (Left 2 cols) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800 bg-zinc-950/30 flex justify-between items-center">
                    <h4 class="font-bold text-white text-base">Đơn hàng đã mua ({{ $user->orders->count() }})</h4>
                </div>
                
                <div class="p-6">
                    @if($user->orders->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-zinc-800 text-xs font-bold uppercase text-zinc-500">
                                        <th class="pb-3">Mã đơn hàng</th>
                                        <th class="pb-3">Ngày đặt</th>
                                        <th class="pb-3">Tổng cộng</th>
                                        <th class="pb-3">Phương thức</th>
                                        <th class="pb-3 text-right">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800/50 text-sm text-zinc-300">
                                    @foreach($user->orders as $order)
                                        <tr>
                                            <td class="py-3 font-mono text-zinc-400 text-xs">
                                                #{{ substr($order->id, 0, 8) }}
                                            </td>
                                            <td class="py-3">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-3 font-semibold text-white">
                                                {{ number_format($order->total_price, 0, ',', '.') }}đ
                                            </td>
                                            <td class="py-3 uppercase text-xs font-bold text-zinc-400">
                                                {{ $order->payment_method === 'cod' ? 'COD' : $order->payment_method }}
                                            </td>
                                            <td class="py-3 text-right">
                                                @php
                                                    $statusLabel = match($order->status) {
                                                        'pending' => 'Đang xử lý',
                                                        'paid' => 'Đã thanh toán',
                                                        'shipped' => 'Đang giao',
                                                        'delivered' => 'Đã giao',
                                                        'cancelled' => 'Đã hủy',
                                                        default => $order->status
                                                    };
                                                    $statusClass = match($order->status) {
                                                        'delivered' => 'bg-green-950 text-green-400 border-green-900/50',
                                                        'cancelled' => 'bg-red-950 text-red-400 border-red-900/50',
                                                        'pending' => 'bg-yellow-950 text-yellow-400 border-yellow-900/50',
                                                        default => 'bg-zinc-800 text-zinc-300 border-zinc-700/50'
                                                    };
                                                @endphp
                                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium border {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center text-zinc-500">
                            <svg class="w-8 h-8 mx-auto text-zinc-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span>Thành viên này chưa thực hiện đơn hàng nào.</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- C2C Listings --}}
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800 bg-zinc-950/30">
                    <h4 class="font-bold text-white text-base">Tin đăng bán chợ đồ cũ C2C ({{ $user->marketplaceListings->count() }})</h4>
                </div>
                
                <div class="p-6">
                    @if($user->marketplaceListings->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-zinc-800 text-xs font-bold uppercase text-zinc-500">
                                        <th class="pb-3">Sản phẩm</th>
                                        <th class="pb-3">Độ mới</th>
                                        <th class="pb-3">Giá yêu cầu</th>
                                        <th class="pb-3 text-right">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800/50 text-sm text-zinc-300">
                                    @foreach($user->marketplaceListings as $listing)
                                        <tr>
                                            <td class="py-3">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 rounded bg-zinc-800 overflow-hidden flex-shrink-0">
                                                        <img src="{{ $listing->display_image_url }}"
                                                             onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'"
                                                             class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-white text-xs uppercase tracking-tight">
                                                            {{ $listing->display_name }}
                                                        </p>
                                                        <p class="text-[10px] text-zinc-400">
                                                            Size: {{ $listing->display_size }} | {{ $listing->display_color }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-zinc-400">
                                                {{ $listing->condition_label }}
                                            </td>
                                            <td class="py-3 font-semibold text-white">
                                                {{ number_format($listing->asking_price, 0, ',', '.') }}đ
                                            </td>
                                            <td class="py-3 text-right">
                                                @php
                                                    $listingStatus = $listing->status instanceof \App\Enums\MarketplaceListingStatus 
                                                        ? $listing->status->value 
                                                        : (string) $listing->status;

                                                    $listingLabel = match($listingStatus) {
                                                        'pending' => 'Chờ duyệt',
                                                        'active' => 'Đang hiển thị',
                                                        'rejected' => 'Bị từ chối',
                                                        'sold' => 'Đã bán',
                                                        default => $listingStatus
                                                    };
                                                    $listingClass = match($listingStatus) {
                                                        'active' => 'bg-green-950 text-green-400 border-green-900/50',
                                                        'pending' => 'bg-yellow-950 text-yellow-400 border-yellow-900/50',
                                                        'rejected' => 'bg-red-950 text-red-400 border-red-900/50',
                                                        'sold' => 'bg-blue-950 text-blue-400 border-blue-900/50',
                                                        default => 'bg-zinc-800 text-zinc-300 border-zinc-700/50'
                                                    };
                                                @endphp
                                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium border {{ $listingClass }}">
                                                    {{ $listingLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center text-zinc-500">
                            <svg class="w-8 h-8 mx-auto text-zinc-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Thành viên này chưa đăng tin rao bán đồ cũ nào.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Wishlist (Right 1 col) --}}
        <div class="space-y-6">
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800 bg-zinc-950/30">
                    <h4 class="font-bold text-white text-base">Danh sách yêu thích ({{ $user->wishlistProducts->count() }})</h4>
                </div>
                
                <div class="p-6">
                    @if($user->wishlistProducts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($user->wishlistProducts as $product)
                                <div class="flex items-center space-x-3 p-2 hover:bg-zinc-900 rounded-lg transition-all">
                                    <div class="w-12 h-12 bg-zinc-800 overflow-hidden rounded flex-shrink-0">
                                        <img src="{{ $product->image_url }}" 
                                             onerror="this.onerror=null; this.src='/images/hero.png'"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="font-bold text-white text-xs uppercase tracking-tight line-clamp-1">
                                            {{ $product->name }}
                                        </h5>
                                        <p class="text-[10px] text-zinc-400 uppercase font-medium">
                                            {{ match(strtolower($product->category->name)) { 
                                                'men' => 'Nam', 
                                                'women' => 'Nữ', 
                                                'kids' => 'Trẻ em', 
                                                'lifestyle' => 'Phong cách sống', 
                                                'running' => 'Chạy bộ', 
                                                'basketball' => 'Bóng rổ', 
                                                'training' => 'Tập luyện', 
                                                'yoga' => 'Yoga', 
                                                'shoes' => 'Giày', 
                                                'clothing' => 'Quần áo', 
                                                'accessories' => 'Phụ kiện', 
                                                default => $product->category->name 
                                            } }}
                                        </p>
                                        <p class="font-semibold text-white text-xs mt-0.5">
                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-zinc-500">
                            <svg class="w-8 h-8 mx-auto text-zinc-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>Chưa yêu thích sản phẩm nào.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
