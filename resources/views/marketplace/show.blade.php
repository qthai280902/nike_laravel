@extends('layouts.app')

@section('title', $listing->display_name . ' | Nike Chợ đồ cũ')

@section('content')
<section class="min-h-screen bg-white px-6 py-10 md:px-12 md:py-14">
    <div class="mx-auto max-w-[1280px]">
        <div class="mb-8">
            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center text-xs font-black uppercase tracking-widest text-nike-gray-500 transition hover:text-nike-black">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại chợ đồ cũ
            </a>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <div class="relative aspect-square overflow-hidden border border-nike-gray-150 bg-nike-snow">
                    <img src="{{ $listing->display_image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" class="h-full w-full object-cover" alt="{{ $listing->display_name }}">
                    <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                        <span class="bg-white/95 px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-nike-black shadow-sm">
                            {{ $listing->condition_label }}
                        </span>
                        <span class="bg-nike-black px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-white shadow-sm">
                            {{ $listing->display_source }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-between lg:col-span-5">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-nike-gray-400">{{ $listing->display_brand }}</p>
                    <h1 class="mt-3 text-4xl font-black uppercase leading-none tracking-tight text-nike-black md:text-5xl">
                        {{ $listing->display_name }}
                    </h1>
                    <p class="mt-5 text-3xl font-black tracking-tight text-nike-black">
                        {{ number_format($listing->asking_price, 0, ',', '.') }}₫
                    </p>

                    <div class="mt-8 grid grid-cols-2 gap-3 border-y border-nike-gray-150 py-6 text-xs font-bold uppercase tracking-wider">
                        <div>
                            <span class="block text-nike-gray-400">Size</span>
                            <span class="mt-1 block text-nike-black">{{ $listing->display_size }}</span>
                        </div>
                        <div>
                            <span class="block text-nike-gray-400">Màu sắc</span>
                            <span class="mt-1 block text-nike-black">{{ $listing->display_color }}</span>
                        </div>
                        <div>
                            <span class="block text-nike-gray-400">Tình trạng</span>
                            <span class="mt-1 block text-nike-black">{{ $listing->condition_label }}</span>
                        </div>
                        <div>
                            <span class="block text-nike-gray-400">Trạng thái</span>
                            <span class="mt-1 block text-nike-black">{{ $listing->status_label }}</span>
                        </div>
                    </div>

                    <div class="mt-6 border border-nike-gray-150 bg-nike-snow p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Người bán</p>
                        <div class="mt-3 flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-nike-gray-200 text-sm font-black text-nike-black">
                                {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-black uppercase tracking-tight text-nike-black">{{ $listing->user->name }}</p>
                                <p class="text-xs font-medium text-nike-gray-500">Tin đăng từ cộng đồng. Trạng thái hiện tại: {{ $listing->status_label }}.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xs font-black uppercase tracking-widest text-nike-black">Mô tả từ người bán</h2>
                        <p class="mt-3 whitespace-pre-line border border-nike-gray-150 bg-white p-5 text-sm font-medium leading-relaxed text-nike-gray-600">
                            {{ $listing->seller_description ?: 'Người bán chưa bổ sung mô tả chi tiết.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <button type="button" disabled class="w-full rounded-full bg-nike-black/10 px-6 py-5 text-xs font-black uppercase tracking-[0.24em] text-nike-gray-500">
                        Liên hệ người bán đang phát triển
                    </button>
                    <p class="text-center text-[10px] font-bold uppercase tracking-wider text-nike-gray-400">
                        Phase này chưa bao gồm thanh toán C2C hoặc escrow.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
