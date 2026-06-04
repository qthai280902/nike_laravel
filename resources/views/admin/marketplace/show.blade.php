@extends('layouts.admin')

@section('page_title', 'Preview tin C2C')

@section('admin_content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-lg border border-green-500/20 bg-green-500/10 px-5 py-4 text-sm font-medium text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-500/20 bg-red-500/10 px-5 py-4 text-sm font-medium text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('admin.marketplace.index') }}" class="inline-flex items-center rounded-lg border border-zinc-800 bg-zinc-900 px-4 py-2 text-xs font-bold uppercase tracking-wider text-zinc-400 transition hover:text-white">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại hàng đợi
        </a>
        <span class="rounded bg-zinc-800 px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-zinc-300">
            {{ $listing->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <div class="type-card overflow-hidden rounded-xl">
                <div class="aspect-square bg-zinc-950">
                    <img src="{{ $listing->display_image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $listing->display_name }}" class="h-full w-full object-cover">
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-5">
            <div class="type-card rounded-xl p-6">
                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">{{ $listing->display_source }}</p>
                <h3 class="mt-3 text-2xl font-bold uppercase leading-tight text-white">{{ $listing->display_name }}</h3>
                <p class="mt-4 text-3xl font-bold text-white">{{ number_format($listing->asking_price, 0, ',', '.') }}₫</p>

                <dl class="mt-6 grid grid-cols-2 gap-4 text-xs">
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Size</dt>
                        <dd class="mt-1 font-bold text-white">{{ $listing->display_size }}</dd>
                    </div>
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Màu</dt>
                        <dd class="mt-1 font-bold text-white">{{ $listing->display_color }}</dd>
                    </div>
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Tình trạng</dt>
                        <dd class="mt-1 font-bold text-white">{{ $listing->condition_label }}</dd>
                    </div>
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Ngày gửi</dt>
                        <dd class="mt-1 font-bold text-white">{{ $listing->created_at->format('H:i d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="type-card rounded-xl p-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Người bán</h4>
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-zinc-800 text-xs font-bold text-white">
                        {{ $listing->user->initials }}
                    </div>
                    <div>
                        <p class="font-bold text-white">{{ $listing->user->name }}</p>
                        <p class="text-xs text-zinc-500">{{ $listing->user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="type-card rounded-xl p-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Mô tả từ người bán</h4>
                <p class="mt-4 whitespace-pre-line rounded-lg border border-zinc-800 bg-zinc-950 p-4 text-sm leading-6 text-zinc-300">
                    {{ $listing->seller_description ?: 'Người bán chưa bổ sung mô tả chi tiết.' }}
                </p>
            </div>

            @if($listing->status === \App\Enums\MarketplaceListingStatus::Pending)
                <div class="grid grid-cols-2 gap-3">
                    <form action="{{ route('admin.marketplace.update', [$listing->id, 'active']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full rounded-lg bg-green-500 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-green-400">
                            Duyệt tin
                        </button>
                    </form>
                    <form action="{{ route('admin.marketplace.update', [$listing->id, 'rejected']) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-red-400">
                            Từ chối
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
