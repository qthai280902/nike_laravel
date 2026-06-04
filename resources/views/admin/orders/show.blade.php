@extends('layouts.admin')

@section('page_title', 'Chi tiết Đơn hàng')

@section('admin_content')
<div class="space-y-6">
    {{-- Top Action Bar --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-zinc-400 hover:text-white transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại danh sách
        </a>
        <div class="text-sm text-zinc-500">
            Mã đơn hàng: <span class="font-mono text-zinc-300">{{ $order->id }}</span>
        </div>
    </div>

    {{-- Alert Sessions --}}
    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-lg text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-lg text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-zinc-800 border border-zinc-700 text-zinc-300 p-4 rounded-lg text-sm font-bold">
            {{ session('info') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Order Details & Customer Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items Card --}}
            <div class="type-card rounded-xl overflow-hidden">
                <div class="p-6 border-b border-zinc-800">
                    <h4 class="font-bold text-white">Sản phẩm đã đặt</h4>
                </div>
                <div class="divide-y divide-zinc-800">
                    @foreach($order->items as $item)
                        <div class="p-6 flex items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded bg-zinc-900 border border-zinc-800 overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->variant->product->image_url ?? '/images/hero.png' }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-white uppercase tracking-tight">{{ $item->variant->product->name ?? 'Sản phẩm không tồn tại' }}</h5>
                                    <p class="text-xs text-zinc-400 mt-1">
                                        Size: {{ $item->variant->size }} | Màu: {{ $item->variant->color }}
                                    </p>
                                    <p class="text-xs text-zinc-500 mt-0.5">
                                        SKU: {{ $item->variant->sku }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-white">
                                    {{ number_format($item->price, 0, ',', '.') }}₫
                                </div>
                                <div class="text-xs text-zinc-500 mt-1">
                                    Số lượng: {{ $item->quantity }}
                                </div>
                                <div class="text-sm font-bold text-white mt-1 border-t border-zinc-800 pt-1">
                                    Tổng: {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-6 bg-zinc-900/30 border-t border-zinc-800 flex justify-between items-center">
                    <span class="text-zinc-400 text-sm font-medium">Tổng giá trị sản phẩm</span>
                    <span class="text-xl font-bold text-white">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
                </div>
            </div>

            {{-- Shipping & Customer Info Card --}}
            <div class="type-card p-6 rounded-xl space-y-4">
                <h4 class="font-bold text-white border-b border-zinc-800 pb-3">Thông tin giao hàng</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-zinc-500 text-xs uppercase tracking-wider">Người nhận</p>
                        <p class="text-white font-medium mt-1">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-xs uppercase tracking-wider">Email liên hệ</p>
                        <p class="text-white font-medium mt-1">{{ $order->shipping_email }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-xs uppercase tracking-wider">Số điện thoại</p>
                        <p class="text-white font-medium mt-1">{{ $order->shipping_phone }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-xs uppercase tracking-wider">Hình thức thanh toán</p>
                        <p class="text-white font-bold mt-1 uppercase text-xs">{{ $order->payment_method }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-zinc-500 text-xs uppercase tracking-wider">Địa chỉ giao hàng</p>
                        <p class="text-white font-medium mt-1 bg-zinc-950 border border-zinc-850 p-3 rounded-lg leading-relaxed">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Status Management --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="type-card p-6 rounded-xl space-y-4">
                <h4 class="font-bold text-white border-b border-zinc-800 pb-3">Trạng thái đơn hàng</h4>
                
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-wider">Trạng thái hiện tại</p>
                    <div class="mt-2">
                        @if($order->status === 'pending')
                            <span class="px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-500 text-xs font-bold uppercase">Chờ xử lý</span>
                        @elseif($order->status === 'paid')
                            <span class="px-3 py-1 rounded-full bg-green-500/10 text-green-500 text-xs font-bold uppercase">Đã thanh toán</span>
                        @elseif($order->status === 'shipped')
                            <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-xs font-bold uppercase">Đang giao</span>
                        @elseif($order->status === 'delivered')
                            <span class="px-3 py-1 rounded-full bg-zinc-500/10 text-zinc-500 text-xs font-bold uppercase">Đã giao</span>
                        @elseif($order->status === 'cancelled')
                            <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-xs font-bold uppercase">Đã hủy</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-zinc-500/10 text-zinc-500 text-xs font-bold uppercase">{{ $order->status }}</span>
                        @endif
                    </div>
                </div>

                {{-- Status Update Form --}}
                @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-3 pt-3 border-t border-zinc-800">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label for="status" class="block text-zinc-400 text-xs font-medium mb-2">Cập nhật trạng thái mới</label>
                            <select name="status" id="status" class="w-full bg-[#09090b] border border-zinc-800 rounded-lg px-3 py-2 text-sm text-zinc-300 focus:outline-none focus:border-zinc-700 transition-colors">
                                @if($order->status === 'pending')
                                    <option value="paid">Đã thanh toán (paid)</option>
                                    <option value="shipped">Đang giao hàng (shipped)</option>
                                    <option value="cancelled">Hủy đơn hàng (cancelled)</option>
                                @elseif($order->status === 'paid')
                                    <option value="shipped">Đang giao hàng (shipped)</option>
                                    <option value="cancelled">Hủy đơn hàng (cancelled)</option>
                                @elseif($order->status === 'shipped')
                                    <option value="delivered">Đã giao hàng (delivered)</option>
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-white text-black hover:bg-zinc-200 py-2.5 rounded-lg text-sm font-bold transition-all uppercase tracking-wider">Cập nhật</button>
                    </form>
                @else
                    <div class="bg-zinc-950/50 border border-zinc-800 p-4 rounded-lg text-center text-xs text-zinc-500 leading-relaxed">
                        Đơn hàng đã ở trạng thái kết thúc (đã giao hoặc đã hủy) và không thể thay đổi trạng thái thêm.
                    </div>
                @endif
            </div>

            {{-- Customer Account Card --}}
            <div class="type-card p-6 rounded-xl space-y-4">
                <h4 class="font-bold text-white border-b border-zinc-800 pb-3">Tài khoản đặt hàng</h4>
                @if($order->user)
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-tr from-zinc-700 to-zinc-900 rounded-full border border-zinc-700 flex items-center justify-center text-xs font-bold text-white">
                            {{ strtoupper(substr($order->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $order->user->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $order->user->email }}</div>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('admin.members.show', $order->user->id) }}" class="block text-center text-xs bg-zinc-900 border border-zinc-800 hover:border-zinc-750 hover:bg-zinc-800 text-zinc-300 hover:text-white py-2 rounded font-bold transition-all uppercase tracking-wider">
                            Xem hồ sơ thành viên
                        </a>
                    </div>
                @else
                    <div class="text-xs text-zinc-500">
                        Khách đặt hàng không đăng ký tài khoản (Guest Checkout).
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
