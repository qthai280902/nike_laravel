@extends('layouts.app')

@section('title', 'Nike Hybrid | Feel the Future')

@section('content')
    @php
        $heroImage = $heroProduct?->image_url ?? asset('images/hero.png');
        $heroTitle = $heroProduct?->name ?? 'FEEL THE FUTURE.';
        $heroCategory = $heroProduct?->category?->name ?? 'Nike Air Max Pulse';
        $heroDescription = $heroProduct
            ? Str::limit($heroProduct->description, 120)
            : 'Một trang chủ Nike tập trung vào catalog thật, hình ảnh sắc nét và giao diện đơn sắc gọn gàng.';
        $heroHref = $heroProduct ? route('catalog.show', $heroProduct->slug) : route('catalog.index');
    @endphp

    <section class="relative min-h-[90vh] flex items-center overflow-hidden bg-nike-black">
        <img src="{{ $heroImage }}" class="absolute inset-0 w-full h-full object-cover opacity-85" alt="{{ $heroTitle }}">

        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

        <div class="relative z-10 px-6 md:px-12 max-w-[1920px] mx-auto w-full">
            <div class="max-w-3xl">
                <p class="font-bold text-white mb-4 uppercase tracking-[0.2em] animate-[fade-in-up_0.6s_forwards] text-sm font-nike-body">
                    {{ $heroCategory }}
                </p>
                <h1 class="text-6xl md:text-nike-hero text-white mb-8 animate-[fade-in-up_0.8s_forwards_0.2s] tracking-tighter leading-[0.9] font-nike-display uppercase">
                    {{ $heroTitle }}
                </h1>
                <p class="text-white/80 font-nike-body text-lg md:text-xl mb-10 max-w-xl animate-[fade-in-up_1s_forwards_0.3s] leading-relaxed">
                    {{ $heroDescription }}
                </p>
                <div class="flex space-x-3 animate-[fade-in-up_1.2s_forwards_0.4s]">
                    <a href="{{ $heroHref }}" class="bg-white text-nike-black px-12 py-5 rounded-[40px] font-bold uppercase transition-colors hover:bg-nike-gray-200 inline-block text-base tracking-tight">
                        Mua ngay
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if($secondaryProducts->isNotEmpty())
        <section class="py-24 px-6 md:px-12 max-w-[1920px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
                <h2 class="text-4xl md:text-nike-section font-nike-display uppercase tracking-tighter leading-none">
                    Sản phẩm nổi bật
                </h2>
                <a href="{{ route('catalog.index') }}" class="text-nike-black font-bold uppercase underline tracking-widest text-xs hover:text-nike-gray-500 transition-colors">
                    Xem tất cả
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($secondaryProducts as $product)
                    <article class="group cursor-pointer">
                        <a href="{{ route('catalog.show', $product->slug) }}" class="block">
                            <div class="aspect-square bg-nike-gray-100 overflow-hidden mb-6">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="flex justify-between items-start gap-6">
                                <div class="space-y-1">
                                    <h3 class="font-bold text-nike-black uppercase text-sm group-hover:text-nike-gray-500 transition-colors leading-tight">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-nike-gray-500 text-sm font-medium">
                                        {{ $product->category->name }}
                                    </p>
                                </div>
                                <span class="text-sm font-bold whitespace-nowrap">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
