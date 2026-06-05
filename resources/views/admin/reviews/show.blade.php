@extends('layouts.admin')

@section('page_title', 'Chi tiết đánh giá')

@section('admin_content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-lg border border-green-500/20 bg-green-500/10 px-5 py-4 text-sm font-medium text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-500/20 bg-red-500/10 px-5 py-4 text-sm font-medium text-red-400">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <a href="{{ route('admin.reviews.index', ['status' => $review->status]) }}" class="inline-flex items-center rounded-lg border border-zinc-800 bg-zinc-900 px-4 py-2 text-xs font-bold uppercase tracking-wider text-zinc-400 transition hover:text-white">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại hàng đợi
        </a>
        <span class="rounded px-3 py-2 text-[10px] font-bold uppercase tracking-widest {{ $review->status_badge_class }}">
            {{ $review->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <div class="space-y-6 xl:col-span-7">
            <div class="type-card overflow-hidden rounded-xl">
                <div class="flex flex-col gap-5 p-6 md:flex-row">
                    <div class="h-32 w-full shrink-0 overflow-hidden rounded-lg bg-zinc-950 md:w-32">
                        <img src="{{ $review->product?->image_url ?? asset('images/hero.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $review->product?->name }}" class="h-full w-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">{{ $review->product?->category?->name ?? 'Không có danh mục' }}</p>
                        <h3 class="mt-3 text-2xl font-bold uppercase leading-tight text-white">{{ $review->product?->name ?? 'Sản phẩm đã xóa' }}</h3>
                        @if($review->product)
                            <a href="{{ route('catalog.show', $review->product->slug) }}" target="_blank" rel="noopener" class="mt-4 inline-flex text-xs font-bold uppercase tracking-widest text-zinc-400 underline-offset-4 hover:text-white hover:underline">
                                Xem ngoài storefront
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="type-card rounded-xl p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Nội dung đánh giá</p>
                        <h3 class="mt-3 text-2xl font-bold uppercase text-white">{{ $review->title ?: 'Đánh giá sản phẩm' }}</h3>
                    </div>
                    <span class="rounded bg-white px-3 py-2 text-xs font-black text-black">{{ $review->rating }}/5</span>
                </div>
                <p class="mt-6 whitespace-pre-line rounded-lg border border-zinc-800 bg-zinc-950 p-5 text-sm leading-7 text-zinc-300">{{ $review->comment }}</p>
            </div>

            @if($review->rejection_reason)
                <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-6">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-red-400">Lý do từ chối</p>
                    <p class="mt-3 text-sm leading-6 text-red-100">{{ $review->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6 xl:col-span-5">
            <div class="type-card rounded-xl p-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Reviewer</h4>
                <div class="mt-4 flex items-center gap-3">
                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-zinc-800 text-white">
                        @if($review->user?->avatar_display_url)
                            <img src="{{ $review->user->avatar_display_url }}" alt="{{ $review->user->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="flex h-full w-full items-center justify-center text-xs font-bold uppercase">{{ $review->user?->initials ?? Str::upper(Str::substr($review->author_name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-white">{{ $review->author_name }}</p>
                        <p class="text-xs text-zinc-500">{{ $review->user?->name ?? 'Không liên kết user' }}</p>
                    </div>
                </div>
            </div>

            <div class="type-card rounded-xl p-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Thông tin kiểm duyệt</h4>
                <dl class="mt-5 grid grid-cols-1 gap-3 text-xs">
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Gửi lúc</dt>
                        <dd class="mt-1 font-bold text-white">{{ $review->created_at?->format('H:i d/m/Y') }}</dd>
                    </div>
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Duyệt bởi</dt>
                        <dd class="mt-1 font-bold text-white">{{ $review->moderator?->name ?? 'Chưa có' }}</dd>
                    </div>
                    <div class="rounded-lg bg-zinc-950 p-4">
                        <dt class="text-[10px] uppercase tracking-widest text-zinc-500">Duyệt lúc</dt>
                        <dd class="mt-1 font-bold text-white">{{ $review->moderated_at?->format('H:i d/m/Y') ?? 'Chưa có' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="type-card rounded-xl p-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Hành động</h4>
                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full rounded-lg bg-green-500 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-green-400">
                            Duyệt
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.hide', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full rounded-lg bg-zinc-700 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-zinc-600">
                            Ẩn
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.keep-pending', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full rounded-lg border border-zinc-700 px-4 py-3 text-xs font-bold uppercase tracking-widest text-zinc-300 transition hover:text-white">
                            Chờ lại
                        </button>
                    </form>
                </div>

                <form id="reject-review-form" action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="mt-5 space-y-3">
                    @csrf
                    @method('PATCH')
                    <label for="rejection_reason" class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Lý do từ chối</label>
                    <textarea id="rejection_reason" name="rejection_reason" required rows="5" maxlength="2000"
                        class="w-full rounded-lg border border-zinc-800 bg-zinc-950 p-4 text-sm leading-6 text-white outline-none transition focus:border-zinc-600"
                        placeholder="Nhập lý do để user xem trong hồ sơ cá nhân">{{ old('rejection_reason', $review->rejection_reason) }}</textarea>
                    <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-3 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-red-400">
                        Từ chối đánh giá
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
