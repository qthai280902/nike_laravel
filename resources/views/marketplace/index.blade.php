@extends('layouts.app')

@section('title', 'Chợ đồ cũ | Nike Hybrid')

@section('content')
<section class="bg-white px-6 py-10 md:px-12 md:py-14">
    <div class="mx-auto max-w-[1440px]">
        <div class="mb-10 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <p class="mb-3 text-xs font-black uppercase tracking-[0.24em] text-nike-gray-400">Chợ C2C</p>
                <h1 class="text-4xl font-black uppercase leading-none tracking-tight text-nike-black md:text-6xl">Chợ đồ cũ</h1>
                <p class="mt-4 text-sm font-medium leading-relaxed text-nike-gray-500 md:text-base">
                    Tìm những đôi giày đã qua sử dụng từ cộng đồng. Tin đăng được kiểm duyệt trước khi hiển thị.
                </p>
            </div>

            <a href="{{ route('marketplace.create') }}" class="inline-flex items-center justify-center rounded-full bg-nike-black px-8 py-4 text-xs font-black uppercase tracking-widest text-white transition hover:bg-nike-gray-800">
                Đăng bán
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 border border-green-200 bg-green-50 px-5 py-4 text-xs font-bold uppercase tracking-widest text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($listings as $listing)
                <article class="group flex h-full flex-col border border-nike-gray-150 bg-white">
                    <a href="{{ route('marketplace.show', $listing) }}" class="block">
                        <div class="relative aspect-square overflow-hidden bg-nike-snow">
                            <img src="{{ $listing->display_image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $listing->display_name }}">
                            <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                                <span class="bg-white/95 px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.18em] text-nike-black shadow-sm">
                                    {{ $listing->condition_label }}
                                </span>
                                <span class="bg-nike-black px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.18em] text-white shadow-sm">
                                    {{ $listing->display_source }}
                                </span>
                            </div>
                        </div>
                    </a>

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">{{ $listing->display_brand }}</p>
                                <h2 class="mt-1 line-clamp-2 text-sm font-black uppercase leading-tight text-nike-black">
                                    {{ $listing->display_name }}
                                </h2>
                            </div>
                            <p class="shrink-0 text-sm font-black text-nike-black">{{ number_format($listing->asking_price, 0, ',', '.') }}₫</p>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 text-[11px] font-bold uppercase tracking-wider text-nike-gray-500">
                            <div class="border border-nike-gray-100 px-3 py-2">
                                <span class="block text-nike-gray-400">Size</span>
                                <span class="text-nike-black">{{ $listing->display_size }}</span>
                            </div>
                            <div class="border border-nike-gray-100 px-3 py-2">
                                <span class="block text-nike-gray-400">Màu</span>
                                <span class="text-nike-black">{{ $listing->display_color }}</span>
                            </div>
                        </div>

                        @if($listing->seller_description)
                            <p class="mt-4 line-clamp-2 text-xs font-medium leading-relaxed text-nike-gray-500">
                                {{ Str::limit($listing->seller_description, 110) }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between border-t border-nike-gray-100 pt-4">
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-nike-gray-100 text-[10px] font-black text-nike-black">
                                    {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                </span>
                                <span class="truncate text-[10px] font-black uppercase tracking-widest text-nike-gray-400">{{ $listing->user->name }}</span>
                            </div>
                            <a href="{{ route('marketplace.show', $listing) }}" class="shrink-0 text-[10px] font-black uppercase tracking-widest text-nike-black underline">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full border border-dashed border-nike-gray-200 bg-nike-snow px-6 py-20 text-center">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-nike-gray-400">Chưa có tin đăng nào</p>
                    <h2 class="mt-3 text-2xl font-black uppercase tracking-tight text-nike-black">Hãy là người đầu tiên mở chợ</h2>
                    <a href="{{ route('marketplace.create') }}" class="mt-6 inline-flex rounded-full bg-nike-black px-7 py-3 text-xs font-black uppercase tracking-widest text-white">
                        Đăng bán ngay
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $listings->links() }}
        </div>
    </div>
</section>
@endsection
