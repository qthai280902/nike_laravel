@extends('layouts.admin')

@section('page_title', 'Hệ thống Quản trị')

@section('admin_content')
@php
    $stats = [
        ['label' => 'Tổng Doanh Thu', 'value' => number_format($totalRevenue, 0, ',', '.').'₫', 'meta' => 'Trực tiếp', 'tone' => 'text-green-400 bg-green-500/10'],
        ['label' => 'Đơn Hàng Mới', 'value' => $newOrdersCount, 'meta' => 'Chờ xử lý', 'tone' => 'text-yellow-400 bg-yellow-500/10'],
        ['label' => 'Sản Phẩm Quản Lý', 'value' => $productsCount, 'meta' => 'Mẫu', 'tone' => 'text-zinc-300 bg-zinc-500/10'],
        ['label' => 'Thành Viên', 'value' => $newMembersCount, 'meta' => 'Người dùng', 'tone' => 'text-green-400 bg-green-500/10'],
        ['label' => 'Hỗ Trợ Đang Mở', 'value' => $openTicketsCount, 'meta' => 'Ticket', 'tone' => 'text-yellow-400 bg-yellow-500/10'],
        ['label' => 'Đánh Giá Chờ Duyệt', 'value' => $pendingProductReviewsCount, 'meta' => 'Review', 'tone' => 'text-blue-400 bg-blue-500/10'],
        ['label' => 'Bài Viết Đã Đăng', 'value' => $publishedArticlesCount, 'meta' => 'Bài viết', 'tone' => 'text-green-400 bg-green-500/10'],
    ];

    $orderStatusLabels = [
        'pending' => ['Đang chờ', 'text-yellow-400 bg-yellow-500/10'],
        'paid' => ['Đã thanh toán', 'text-green-400 bg-green-500/10'],
        'shipped' => ['Đang giao', 'text-blue-400 bg-blue-500/10'],
        'delivered' => ['Đã giao', 'text-zinc-300 bg-zinc-500/10'],
        'cancelled' => ['Đã hủy', 'text-red-400 bg-red-500/10'],
    ];

    $quickActions = [
        ['label' => 'Đơn hàng', 'route' => route('admin.orders.index')],
        ['label' => 'Trưng bày', 'route' => route('admin.storefront.index')],
        ['label' => 'Duyệt C2C', 'route' => route('admin.marketplace.index')],
        ['label' => 'Duyệt review', 'route' => route('admin.reviews.index')],
        ['label' => 'Thành viên', 'route' => route('admin.members.index')],
        ['label' => 'Báo cáo', 'route' => route('admin.reports.index')],
        ['label' => 'Hỗ trợ', 'route' => route('admin.support.index')],
        ['label' => 'Bài viết', 'route' => route('admin.landing-articles.index')],
        ['label' => 'Cửa hàng', 'route' => route('home')],
    ];
@endphp

<div class="space-y-8">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @foreach($stats as $stat)
            <div class="type-card flex min-h-[132px] flex-col justify-between rounded-xl p-5">
                <p class="text-xs font-medium uppercase tracking-widest text-zinc-500">{{ $stat['label'] }}</p>
                <div class="mt-6 flex items-end justify-between gap-4">
                    <h3 class="break-words text-2xl font-bold leading-tight text-white">{{ $stat['value'] }}</h3>
                    <span class="shrink-0 rounded px-2 py-1 text-[10px] font-bold uppercase {{ $stat['tone'] }}">{{ $stat['meta'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <div class="type-card overflow-hidden rounded-xl xl:col-span-8">
            <div class="flex items-center justify-between border-b border-zinc-800 p-5">
                <div>
                    <h4 class="font-bold text-white">Đơn hàng gần đây</h4>
                    <p class="mt-1 text-xs text-zinc-500">5 đơn mới nhất trong hệ thống.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold uppercase tracking-widest text-zinc-500 transition hover:text-white">Xem tất cả</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-zinc-900/60 text-[10px] uppercase tracking-widest text-zinc-500">
                        <tr>
                            <th class="px-5 py-4 font-medium">Mã đơn</th>
                            <th class="px-5 py-4 font-medium">Khách hàng</th>
                            <th class="px-5 py-4 font-medium">Tổng tiền</th>
                            <th class="px-5 py-4 font-medium">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse($recentOrders as $order)
                            @php($statusMeta = $orderStatusLabels[$order->status] ?? [$order->status, 'text-red-400 bg-red-500/10'])
                            <tr class="transition hover:bg-zinc-900/35">
                                <td class="px-5 py-4 font-medium text-white">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">#{{ substr($order->id, 0, 8) }}...</a>
                                </td>
                                <td class="px-5 py-4 text-zinc-400">{{ $order->user ? $order->user->name : $order->shipping_name }}</td>
                                <td class="px-5 py-4 font-bold text-white">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                <td class="px-5 py-4">
                                    <span class="rounded px-2 py-1 text-[10px] font-bold uppercase {{ $statusMeta[1] }}">{{ $statusMeta[0] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-zinc-500">Không có đơn hàng nào gần đây.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6 xl:col-span-4">
            <div class="type-card rounded-xl p-5">
                <h4 class="font-bold text-white">Thống kê Hoạt động</h4>
                <div class="mt-5 space-y-5">
                    <div>
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trạng thái Đơn hàng</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            @foreach($orderStatusLabels as $status => $meta)
                                <div class="flex justify-between rounded border border-zinc-800 bg-zinc-950 p-2">
                                    <span class="text-zinc-500">{{ $meta[0] }}</span>
                                    <span class="font-bold text-white">{{ $orderStats[$status] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Chợ đồ cũ C2C</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex justify-between rounded border border-zinc-800 bg-zinc-950 p-2"><span class="text-zinc-500">Chờ duyệt</span><span class="font-bold text-yellow-400">{{ $marketplaceStats['pending'] }}</span></div>
                            <div class="flex justify-between rounded border border-zinc-800 bg-zinc-950 p-2"><span class="text-zinc-500">Đang hiển thị</span><span class="font-bold text-green-400">{{ $marketplaceStats['active'] }}</span></div>
                            <div class="flex justify-between rounded border border-zinc-800 bg-zinc-950 p-2"><span class="text-zinc-500">Từ chối</span><span class="font-bold text-red-400">{{ $marketplaceStats['rejected'] }}</span></div>
                            <div class="flex justify-between rounded border border-zinc-800 bg-zinc-950 p-2"><span class="text-zinc-500">Đã bán</span><span class="font-bold text-zinc-300">{{ $marketplaceStats['sold'] }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="type-card rounded-xl p-5">
                <h4 class="font-bold text-white">Lối tắt quản trị nhanh</h4>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach($quickActions as $action)
                        <a href="{{ $action['route'] }}" class="flex items-center justify-between rounded border border-zinc-800 bg-zinc-950 px-3 py-2 text-xs font-medium text-zinc-300 transition hover:border-zinc-700 hover:bg-zinc-900 hover:text-white">
                            <span>{{ $action['label'] }}</span>
                            <span class="text-zinc-600">›</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="type-card overflow-hidden rounded-xl">
            <div class="flex items-center justify-between border-b border-zinc-800 p-5">
                <div>
                    <h4 class="font-bold text-white">Tin C2C chờ duyệt</h4>
                    <p class="mt-1 text-xs text-zinc-500">{{ $pendingListingsCount }} tin cần xem xét.</p>
                </div>
                <a href="{{ route('admin.marketplace.index') }}" class="text-xs font-bold uppercase tracking-widest text-zinc-500 transition hover:text-white">Duyệt tất cả</a>
            </div>

            <div class="divide-y divide-zinc-800">
                @forelse($pendingListings as $listing)
                    <div class="flex items-center gap-4 p-5">
                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded bg-zinc-900">
                            <img src="{{ $listing->display_image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-white">{{ $listing->display_name }}</p>
                            <p class="mt-1 text-[10px] uppercase tracking-wider text-zinc-500">Size: {{ $listing->display_size }} | {{ $listing->display_color }} | {{ $listing->display_source }}</p>
                            <p class="mt-1 text-[10px] text-zinc-500">{{ $listing->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-white">{{ number_format($listing->asking_price, 0, ',', '.') }}₫</p>
                            <a href="{{ route('admin.marketplace.index') }}" class="mt-2 inline-block rounded bg-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black transition hover:bg-zinc-200">Xem</a>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-zinc-500">Không có tin đăng nào đang chờ duyệt.</div>
                @endforelse
            </div>
        </div>

        <div id="low-stock-section" class="type-card overflow-hidden rounded-xl">
            <div class="border-b border-zinc-800 p-5">
                <h4 class="font-bold text-red-400">Sản phẩm sắp hết hàng</h4>
                <p class="mt-1 text-xs text-zinc-500">Các biến thể có tồn kho nhỏ hơn hoặc bằng 5.</p>
            </div>

            <div class="divide-y divide-zinc-800">
                @forelse($lowStockVariants as $variant)
                    <div class="grid grid-cols-12 items-center gap-3 p-5">
                        <div class="col-span-7 min-w-0">
                            <p class="truncate text-sm font-bold uppercase text-white">{{ $variant->product->name }}</p>
                            <p class="mt-1 text-[10px] uppercase tracking-wider text-zinc-500">{{ $variant->sku }} | Size: {{ $variant->size }} | {{ $variant->color }}</p>
                        </div>
                        <div class="col-span-2 text-right text-sm font-bold text-white">{{ $variant->stock }}</div>
                        <div class="col-span-3 text-right">
                            @if($variant->stock == 0)
                                <span class="rounded bg-red-500/20 px-2 py-1 text-[10px] font-bold uppercase text-red-400">Hết hàng</span>
                            @else
                                <span class="rounded bg-orange-500/20 px-2 py-1 text-[10px] font-bold uppercase text-orange-400">Sắp hết</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-zinc-500">Tất cả sản phẩm đều đủ hàng tồn kho.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
