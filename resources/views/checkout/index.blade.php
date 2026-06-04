@extends('layouts.app')

@section('title', 'Checkout | Nike Hybrid')

@section('content')
<section class="max-w-6xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-nike-display uppercase mb-12">Thanh toán</h1>

    @if(session('error'))
        <div class="bg-nike-red text-white p-4 uppercase font-bold text-xs tracking-widest mb-8">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" class="flex flex-col lg:flex-row gap-16">
        @csrf
        {{-- Shipping Details --}}
        <div class="flex-grow space-y-8">
            <div>
                <h2 class="text-xl font-nike-display uppercase mb-6">Địa chỉ giao hàng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Họ và tên" class="bg-nike-gray-100 rounded-lg p-4 border-none w-full">
                        @error('name') <p class="text-nike-red text-[11px] mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" placeholder="Email" class="bg-nike-gray-100 rounded-lg p-4 border-none w-full">
                        @error('email') <p class="text-nike-red text-[11px] mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Số điện thoại" class="bg-nike-gray-100 rounded-lg p-4 border-none w-full">
                        @error('phone') <p class="text-nike-red text-[11px] mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="col-span-2">
                        <textarea name="address" placeholder="Địa chỉ nhà" class="bg-nike-gray-100 rounded-lg p-4 border-none w-full h-32">{{ old('address') }}</textarea>
                        @error('address') <p class="text-nike-red text-[11px] mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-nike-display uppercase mb-6">Phương thức thanh toán</h2>
                <div class="space-y-3">
                    <label class="flex items-center p-4 bg-nike-gray-100 rounded-lg cursor-pointer border border-transparent hover:border-nike-black transition-all">
                        <input type="radio" name="payment_method" value="cod" checked class="w-5 h-5 mr-4 accent-nike-black">
                        <span class="font-nike-body">Thanh toán khi nhận hàng (COD)</span>
                    </label>
                    <label class="flex items-center p-4 bg-nike-gray-100 rounded-lg opacity-50 cursor-not-allowed">
                        <input type="radio" name="payment_method" value="stripe" disabled class="w-5 h-5 mr-4">
                        <span class="font-nike-body">Thẻ tín dụng (Sắp ra mắt)</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="w-full lg:w-[400px] flex-shrink-0">
            <div class="bg-nike-snow p-8 rounded-2xl sticky top-24">
                <h2 class="text-xl font-nike-display uppercase mb-8">Tóm tắt đơn hàng</h2>
                
                {{-- Dynamic Items list --}}
                <div class="space-y-4 mb-6 border-b border-nike-gray-200 pb-6 max-h-60 overflow-y-auto">
                    @foreach($cartItems as $item)
                        <div class="flex items-center justify-between text-xs font-nike-body">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-nike-gray-100 flex-shrink-0">
                                    <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-[11px] uppercase truncate max-w-[150px]">{{ $item['product_name'] }}</p>
                                    <p class="text-nike-gray-500 text-[10px]">{{ $item['variant_name'] }} x {{ $item['qty'] }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-right">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}₫</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-sm font-nike-body text-nike-gray-500">
                        <span>Tạm tính</span>
                        <span>{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="flex justify-between text-sm font-nike-body text-nike-gray-500">
                        <span>Phí vận chuyển (dự kiến)</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-nike-gray-200 font-nike-body font-medium">
                        <span>Tổng cộng</span>
                        <span class="font-black text-lg text-nike-black">{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                    </div>
                </div>
                
                <button type="submit" class="w-full py-5 text-sm bg-nike-black text-white hover:bg-nike-gray-800 transition-colors uppercase font-bold tracking-wider text-center rounded-full">Đặt hàng</button>
                
                <p class="mt-6 text-xs text-nike-gray-500 leading-relaxed">
                    Khi đặt hàng, bạn đồng ý với Điều khoản Sử dụng và Chính sách Bảo mật của Nike. Đơn hàng phụ thuộc vào tình trạng tồn kho.
                </p>
            </div>
        </div>
    </form>
</section>
@endsection
