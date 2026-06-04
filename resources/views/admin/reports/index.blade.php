@extends('layouts.admin')

@section('page_title', 'Báo cáo Hệ thống')

@section('admin_content')
<div class="space-y-8">
    {{-- 1. Revenue Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="type-card rounded-lg p-6 border border-zinc-800 bg-[#18181b]">
            <span class="text-xs text-zinc-500 uppercase font-bold tracking-wider">Tổng doanh thu thực tế</span>
            <div class="text-3xl font-black text-white mt-1">
                {{ number_format($totalRevenue, 0, ',', '.') }}đ
            </div>
            <span class="text-zinc-500 text-xs mt-1 block">Không tính đơn hàng đã hủy</span>
        </div>
        <div class="type-card rounded-lg p-6 border border-zinc-800 bg-[#18181b]">
            <span class="text-xs text-zinc-500 uppercase font-bold tracking-wider">Doanh thu hôm nay</span>
            <div class="text-3xl font-black text-white mt-1">
                {{ number_format($revenueToday, 0, ',', '.') }}đ
            </div>
            <span class="text-zinc-500 text-xs mt-1 block">Doanh số ghi nhận trong ngày</span>
        </div>
        <div class="type-card rounded-lg p-6 border border-zinc-800 bg-[#18181b]">
            <span class="text-xs text-zinc-500 uppercase font-bold tracking-wider">Doanh thu tháng này</span>
            <div class="text-3xl font-black text-white mt-1">
                {{ number_format($revenueThisMonth, 0, ',', '.') }}đ
            </div>
            <span class="text-zinc-500 text-xs mt-1 block">Tổng doanh số của tháng hiện tại</span>
        </div>
    </div>

    {{-- 2. General Stats Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Orders & Marketplace --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Breakdown --}}
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] p-6">
                <h4 class="font-bold text-white text-base mb-4">Thống kê Đơn hàng (Tổng số: {{ $totalOrders }})</h4>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Chờ xử lý</span>
                        <div class="text-xl font-bold text-yellow-500 mt-1">{{ $orderStats['pending'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Đã thanh toán</span>
                        <div class="text-xl font-bold text-blue-500 mt-1">{{ $orderStats['paid'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Đang giao</span>
                        <div class="text-xl font-bold text-purple-500 mt-1">{{ $orderStats['shipped'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Đã giao</span>
                        <div class="text-xl font-bold text-green-500 mt-1">{{ $orderStats['delivered'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center col-span-2 md:col-span-1">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Đã hủy</span>
                        <div class="text-xl font-bold text-red-500 mt-1">{{ $orderStats['cancelled'] }}</div>
                    </div>
                </div>
            </div>

            {{-- Marketplace C2C Breakdown --}}
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] p-6">
                <h4 class="font-bold text-white text-base mb-4">Trạng thái Chợ đồ cũ C2C</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Chờ duyệt tin</span>
                        <div class="text-xl font-bold text-yellow-500 mt-1">{{ $marketplaceStats['pending'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Đang hiển thị</span>
                        <div class="text-xl font-bold text-green-500 mt-1">{{ $marketplaceStats['active'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Bị từ chối</span>
                        <div class="text-xl font-bold text-red-500 mt-1">{{ $marketplaceStats['rejected'] }}</div>
                    </div>
                    <div class="bg-zinc-950 p-4 rounded-lg border border-zinc-800 text-center">
                        <span class="text-xs text-zinc-500 font-bold uppercase">Đã bán</span>
                        <div class="text-xl font-bold text-blue-500 mt-1">{{ $marketplaceStats['sold'] }}</div>
                    </div>
                </div>
            </div>

            {{-- Top Selling Products --}}
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800 bg-zinc-950/30">
                    <h4 class="font-bold text-white text-base">Top 5 Sản phẩm bán chạy nhất</h4>
                </div>
                <div class="p-6">
                    @if($topSelling->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-zinc-800 text-xs font-bold uppercase text-zinc-500">
                                        <th class="pb-3">Sản phẩm</th>
                                        <th class="pb-3">Size/Màu</th>
                                        <th class="pb-3 text-center">Số lượng bán</th>
                                        <th class="pb-3 text-right">Doanh thu thu về</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800/50 text-sm text-zinc-300">
                                    @foreach($topSelling as $item)
                                        @if($item->variant)
                                            <tr>
                                                <td class="py-3">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 rounded bg-zinc-800 overflow-hidden flex-shrink-0">
                                                            <img src="{{ $item->variant->product->image_url }}" 
                                                                 onerror="this.onerror=null; this.src='/images/hero.png'"
                                                                 class="w-full h-full object-cover">
                                                        </div>
                                                        <div class="font-bold text-white text-xs uppercase tracking-tight">
                                                            {{ $item->variant->product->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-zinc-400">
                                                    {{ $item->variant->size }} | {{ $item->variant->color }}
                                                </td>
                                                <td class="py-3 text-center font-semibold text-white">
                                                    {{ $item->total_qty }}
                                                </td>
                                                <td class="py-3 text-right font-semibold text-white">
                                                    {{ number_format($item->total_revenue, 0, ',', '.') }}đ
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="4" class="py-3 text-zinc-500">Sản phẩm hoặc biến thể đã bị xóa.</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center text-zinc-500">
                            <span>Chưa có dữ liệu bán hàng.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Members & Stock --}}
        <div class="space-y-6">
            {{-- Member Stats --}}
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] p-6">
                <h4 class="font-bold text-white text-base mb-4">Thành viên</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-800">
                        <span class="text-sm text-zinc-400">Tổng số thành viên:</span>
                        <span class="text-lg font-bold text-white">{{ $totalMembers }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-zinc-400">Đăng ký mới tháng này:</span>
                        <span class="text-lg font-bold text-white">{{ $newMembersThisMonth }}</span>
                    </div>
                </div>
            </div>

            {{-- Low Stock Alert --}}
            <div class="type-card rounded-lg border border-zinc-800 bg-[#18181b] overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-800 bg-zinc-950/30 flex justify-between items-center">
                    <h4 class="font-bold text-white text-base">Cảnh báo tồn kho (Sắp hết)</h4>
                    <span class="text-xs bg-red-950 text-red-400 border border-red-900/50 px-2 py-0.5 rounded font-bold">Stock &lt;= 5</span>
                </div>
                <div class="p-6">
                    @if($lowStockProducts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($lowStockProducts as $variant)
                                <div class="flex justify-between items-center p-2 hover:bg-zinc-900 rounded-lg transition-all">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded bg-zinc-800 overflow-hidden flex-shrink-0">
                                            <img src="{{ $variant->product->image_url }}" 
                                                 onerror="this.onerror=null; this.src='/images/hero.png'"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-white text-xs uppercase tracking-tight line-clamp-1">
                                                {{ $variant->product->name }}
                                            </p>
                                            <p class="text-[9px] text-zinc-400">
                                                {{ $variant->size }} | {{ $variant->color }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-0.5 rounded {{ $variant->stock == 0 ? 'bg-red-950 text-red-500 font-black' : 'bg-yellow-950 text-yellow-500 font-bold' }}">
                                        Còn {{ $variant->stock }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-zinc-500">
                            <span>Không có sản phẩm nào sắp hết hàng.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
