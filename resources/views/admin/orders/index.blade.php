@extends('layouts.admin')

@section('page_title', 'Quản lý Đơn hàng')

@section('admin_content')
<div class="space-y-6">
    {{-- Search and Filter Bar --}}
    <div class="type-card p-6 rounded-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã đơn, tên hoặc email..." class="w-full bg-[#09090b] border border-zinc-800 rounded-lg pl-10 pr-4 py-2 text-sm text-zinc-300 placeholder-zinc-500 focus:outline-none focus:border-zinc-700 transition-colors">
            </div>

            <div class="w-full sm:w-48">
                <select name="status" class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-3 py-2 text-sm text-zinc-300 focus:outline-none focus:border-zinc-700 transition-colors">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Đang giao</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Đã giao</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-white text-black hover:bg-zinc-200 px-4 py-2 rounded-lg text-sm font-bold transition-all uppercase tracking-wider">Lọc</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.orders.index') }}" class="bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-zinc-400 hover:text-white px-4 py-2 rounded-lg text-sm font-bold transition-all uppercase tracking-wider">Xóa bộ lọc</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="type-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-900/50 text-zinc-500 uppercase text-[10px] tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4 font-medium">Mã Đơn Hàng</th>
                        <th class="px-6 py-4 font-medium">Khách Hàng</th>
                        <th class="px-6 py-4 font-medium">Phương Thức</th>
                        <th class="px-6 py-4 font-medium">Tổng Tiền</th>
                        <th class="px-6 py-4 font-medium">Trạng Thái</th>
                        <th class="px-6 py-4 font-medium">Ngày Đặt</th>
                        <th class="px-6 py-4 font-medium">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-zinc-900/30 transition-all">
                            <td class="px-6 py-4 font-bold text-white tracking-wider">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                    #{{ substr($order->id, 0, 8) }}...
                                </a>
                            </td>
                            <td class="px-6 py-4 text-zinc-400">
                                <div class="font-medium text-white">{{ $order->shipping_name }}</div>
                                <div class="text-xs text-zinc-500">{{ $order->shipping_email }}</div>
                            </td>
                            <td class="px-6 py-4 text-zinc-400 text-xs font-bold uppercase">
                                {{ strtoupper($order->payment_method) }}
                            </td>
                            <td class="px-6 py-4 text-white font-bold">
                                {{ number_format($order->total_price, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-4">
                                @if($order->status === 'pending')
                                    <span class="px-2 py-0.5 rounded bg-yellow-500/10 text-yellow-500 text-[10px] font-bold uppercase">Chờ xử lý</span>
                                @elseif($order->status === 'paid')
                                    <span class="px-2 py-0.5 rounded bg-green-500/10 text-green-500 text-[10px] font-bold uppercase">Đã thanh toán</span>
                                @elseif($order->status === 'shipped')
                                    <span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-500 text-[10px] font-bold uppercase">Đang giao</span>
                                @elseif($order->status === 'delivered')
                                    <span class="px-2 py-0.5 rounded bg-zinc-500/10 text-zinc-500 text-[10px] font-bold uppercase">Đã giao</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-2 py-0.5 rounded bg-red-500/10 text-red-500 text-[10px] font-bold uppercase">Đã hủy</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-zinc-500/10 text-zinc-500 text-[10px] font-bold uppercase">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-zinc-400 text-xs">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center p-2 bg-zinc-900 border border-zinc-800 rounded text-zinc-400 hover:text-white transition-all hover:border-zinc-700" title="Xem chi tiết">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-zinc-500">Không tìm thấy đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-6 border-t border-zinc-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
