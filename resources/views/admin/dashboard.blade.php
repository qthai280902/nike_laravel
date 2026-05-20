@extends('layouts.admin')

@section('page_title', 'Hệ thống Quản trị')

@section('admin_content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    {{-- Stats Cards --}}
    <div class="type-card p-6 rounded-xl">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-4">Tổng Doanh Thu</p>
        <div class="flex items-end justify-between">
            <h3 class="text-2xl font-bold">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
            <span class="text-xs font-bold text-green-500 bg-green-500/10 px-2 py-1 rounded">Live</span>
        </div>
    </div>

    <div class="type-card p-6 rounded-xl">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-4">Đơn Hàng Mới</p>
        <div class="flex items-end justify-between">
            <h3 class="text-2xl font-bold">{{ $newOrdersCount }}</h3>
            <span class="text-xs font-bold text-green-500 bg-green-500/10 px-2 py-1 rounded">Pending</span>
        </div>
    </div>

    <div class="type-card p-6 rounded-xl">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-4">Sản Phẩm Quản Lý</p>
        <div class="flex items-end justify-between">
            <h3 class="text-2xl font-bold">{{ $productsCount }}</h3>
            <span class="text-xs font-bold text-zinc-500 bg-zinc-500/10 px-2 py-1 rounded">Styles</span>
        </div>
    </div>

    <div class="type-card p-6 rounded-xl">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-4">Thành Viên</p>
        <div class="flex items-end justify-between">
            <h3 class="text-2xl font-bold">{{ $newMembersCount }}</h3>
            <span class="text-xs font-bold text-green-500 bg-green-500/10 px-2 py-1 rounded">Users</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 type-card rounded-xl overflow-hidden">
        <div class="p-6 border-b border-zinc-800 flex justify-between items-center">
            <h4 class="font-bold">Đơn hàng gần đây</h4>
            <button class="text-xs text-zinc-500 hover:text-white transition-all">Xem tất cả</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-900/50 text-zinc-500 uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4 font-medium">Mã Đơn</th>
                        <th class="px-6 py-4 font-medium">Khách Hàng</th>
                        <th class="px-6 py-4 font-medium">Tổng Tiền</th>
                        <th class="px-6 py-4 font-medium">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-zinc-900/30">
                        <td class="px-6 py-4 font-medium text-white">#{{ substr($order->id, 0, 8) }}...</td>
                        <td class="px-6 py-4 text-zinc-400">{{ $order->user ? $order->user->name : $order->shipping_name }}</td>
                        <td class="px-6 py-4 text-white">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                        <td class="px-6 py-4">
                            @if($order->status === 'pending')
                                <span class="px-2 py-0.5 rounded bg-yellow-500/10 text-yellow-500 text-[10px] font-bold uppercase">Pending</span>
                            @elseif($order->status === 'paid')
                                <span class="px-2 py-0.5 rounded bg-green-500/10 text-green-500 text-[10px] font-bold uppercase">Paid</span>
                            @elseif($order->status === 'shipped')
                                <span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-500 text-[10px] font-bold uppercase">Shipped</span>
                            @elseif($order->status === 'delivered')
                                <span class="px-2 py-0.5 rounded bg-zinc-500/10 text-zinc-500 text-[10px] font-bold uppercase">Delivered</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-red-500/10 text-red-500 text-[10px] font-bold uppercase">{{ $order->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-zinc-500 italic">Không có đơn hàng nào gần đây.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- System Status --}}
    <div class="type-card p-6 rounded-xl">
        <h4 class="font-bold mb-6">Trạng thái hệ thống</h4>
        <div class="space-y-6">
            <div>
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-zinc-500">Database Server</span>
                    <span class="text-green-500">Healthy</span>
                </div>
                <div class="w-full h-1 bg-zinc-800 rounded-full overflow-hidden">
                    <div class="w-full h-full bg-green-500"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-zinc-500">Storage Usage</span>
                    <span class="text-zinc-300">68%</span>
                </div>
                <div class="w-full h-1 bg-zinc-800 rounded-full overflow-hidden">
                    <div class="w-[68%] h-full bg-zinc-400"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
