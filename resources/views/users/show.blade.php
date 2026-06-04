@extends('layouts.app')

@section('title', $user->name.' | Nike Hybrid')

@section('content')
@php
    $avatarUrl = $user->avatar_display_url;
    $roleLabel = match ($user->role ?? 'customer') {
        'customer' => 'Khách hàng',
        'seller' => 'Người bán',
        'admin' => 'Quản trị viên',
        default => ucfirst($user->role ?? 'customer'),
    };
@endphp

<section class="bg-white px-6 py-12 md:px-12">
    <div class="mx-auto max-w-[1200px]">
        <div class="flex flex-col gap-6 border-b border-nike-gray-150 pb-8 md:flex-row md:items-center">
            <div class="h-24 w-24 shrink-0 overflow-hidden rounded-full bg-nike-black text-white">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                @else
                    <span class="flex h-full w-full items-center justify-center text-2xl font-black uppercase">{{ $user->initials }}</span>
                @endif
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-[0.24em] text-nike-gray-400">Hồ sơ công khai</p>
                <h1 class="mt-3 text-4xl font-black uppercase leading-none tracking-tight text-nike-black md:text-7xl">
                    {{ $user->name }}
                </h1>
                <div class="mt-6 flex flex-wrap gap-3 text-[10px] font-black uppercase tracking-widest text-nike-gray-500">
                    <span class="border border-nike-gray-150 px-4 py-2">{{ $roleLabel }}</span>
                    <span class="border border-nike-gray-150 px-4 py-2">Tham gia {{ $user->created_at?->format('m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 border-b border-nike-gray-150 py-8 md:grid-cols-2">
            <div class="bg-nike-snow p-6">
                <p class="text-4xl font-black text-nike-black">{{ $user->approved_reviews_count }}</p>
                <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Đánh giá đã duyệt</p>
            </div>
            <div class="bg-nike-snow p-6">
                <p class="text-4xl font-black text-nike-black">{{ $user->active_marketplace_listings_count }}</p>
                <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Tin C2C đang hiển thị</p>
            </div>
        </div>

        <div class="py-10">
            <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Review gần đây</p>
                    <h2 class="mt-2 text-3xl font-black uppercase leading-none tracking-tight text-nike-black">Nhận xét công khai</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse($recentReviews as $review)
                    <article class="border border-nike-gray-150 bg-white p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <a href="{{ route('catalog.show', $review->product->slug) }}" class="text-sm font-black uppercase leading-tight text-nike-black underline-offset-4 hover:underline">
                                    {{ $review->product->name }}
                                </a>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-nike-gray-400">
                                    {{ $review->created_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <span class="shrink-0 text-sm font-black text-nike-black">{{ $review->rating }}/5</span>
                        </div>
                        <p class="mt-4 text-sm font-black uppercase text-nike-black">{{ $review->title ?: 'Đánh giá sản phẩm' }}</p>
                        <p class="mt-2 text-sm font-medium leading-6 text-nike-gray-600">{{ $review->comment }}</p>
                    </article>
                @empty
                    <div class="col-span-full border border-dashed border-nike-gray-200 bg-white p-8 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Chưa có đánh giá công khai.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
