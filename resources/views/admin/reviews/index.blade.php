@extends('layouts.admin')

@section('page_title', 'Kiểm duyệt đánh giá')

@section('admin_content')
@php
    $statusLabels = [
        'all' => 'Tất cả',
        'pending' => 'Chờ duyệt',
        'approved' => 'Đã duyệt',
        'hidden' => 'Đang ẩn',
        'rejected' => 'Từ chối',
    ];
@endphp

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

    <div class="type-card rounded-xl p-5">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0 flex-1">
                <label for="q" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Tìm kiếm</label>
                <input id="q" name="q" value="{{ $search }}" placeholder="Tên sản phẩm, slug, reviewer, tiêu đề, nội dung"
                    class="w-full rounded-lg border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm text-white outline-none transition focus:border-zinc-600">
            </div>
            <div>
                <label for="status" class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-zinc-500">Trạng thái</label>
                <select id="status" name="status" class="w-full rounded-lg border border-zinc-800 bg-zinc-950 px-4 py-3 text-sm font-bold text-white outline-none transition focus:border-zinc-600 lg:w-48">
                    <option value="all" @selected($status === 'all')>Tất cả</option>
                    @foreach($statuses as $statusKey)
                        <option value="{{ $statusKey }}" @selected($status === $statusKey)>{{ $statusLabels[$statusKey] ?? $statusKey }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-white px-6 py-3 text-xs font-bold uppercase tracking-widest text-black transition hover:bg-zinc-200">
                Lọc
            </button>
        </form>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.reviews.index', ['status' => 'all', 'q' => $search]) }}" class="rounded-lg border px-4 py-2 text-[10px] font-bold uppercase tracking-widest transition {{ $status === 'all' ? 'border-white bg-white text-black' : 'border-zinc-800 text-zinc-400 hover:text-white' }}">
            Tất cả
        </a>
        @foreach($statuses as $statusKey)
            <a href="{{ route('admin.reviews.index', ['status' => $statusKey, 'q' => $search]) }}" class="rounded-lg border px-4 py-2 text-[10px] font-bold uppercase tracking-widest transition {{ $status === $statusKey ? 'border-white bg-white text-black' : 'border-zinc-800 text-zinc-400 hover:text-white' }}">
                {{ $statusLabels[$statusKey] ?? $statusKey }}
            </a>
        @endforeach
    </div>

    <div class="type-card overflow-hidden rounded-xl">
        <div class="flex flex-col gap-2 border-b border-zinc-800 p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <h3 class="font-bold text-white">Hàng đợi đánh giá sản phẩm</h3>
                <p class="mt-1 text-xs text-zinc-500">Chỉ đánh giá đã duyệt mới được xuất hiện ngoài storefront.</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-widest text-zinc-500">{{ $reviews->total() }} đánh giá</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-900/60 text-[10px] uppercase tracking-widest text-zinc-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Sản phẩm</th>
                        <th class="px-6 py-4 font-medium">Reviewer</th>
                        <th class="px-6 py-4 font-medium">Nội dung</th>
                        <th class="px-6 py-4 font-medium">Trạng thái</th>
                        <th class="px-6 py-4 text-right font-medium">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($reviews as $review)
                        <tr class="transition-colors hover:bg-zinc-900/35">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded bg-zinc-900">
                                        <img src="{{ $review->product?->image_url ?? asset('images/hero.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="" class="h-full w-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block truncate font-medium text-white">{{ $review->product?->name ?? 'Sản phẩm đã xóa' }}</span>
                                        <span class="block text-[10px] uppercase text-zinc-500">{{ $review->product?->category?->name ?? 'Không có danh mục' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-zinc-300">{{ $review->author_name }}</span>
                                <span class="block text-[10px] text-zinc-500">{{ $review->user?->name ?? 'Không liên kết user' }}</span>
                            </td>
                            <td class="max-w-md px-6 py-4">
                                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-zinc-500">
                                    <span>{{ $review->rating }}/5</span>
                                    <span>{{ $review->created_at?->format('H:i d/m/Y') }}</span>
                                </div>
                                <p class="mt-2 font-bold text-white">{{ $review->title ?: 'Đánh giá sản phẩm' }}</p>
                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-zinc-400">{{ $review->comment }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded px-2 py-1 text-[10px] font-bold uppercase {{ $review->status_badge_class }}">
                                    {{ $review->status_label }}
                                </span>
                                @if($review->moderator)
                                    <span class="mt-2 block text-[10px] text-zinc-500">{{ $review->moderator->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="rounded-lg bg-zinc-800 p-2 text-zinc-300 transition hover:bg-zinc-700 hover:text-white" title="Xem chi tiết">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($review->status !== \App\Models\ProductReview::STATUS_APPROVED)
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg bg-green-500/10 p-2 text-green-400 transition hover:bg-green-500 hover:text-white" title="Duyệt">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($review->status !== \App\Models\ProductReview::STATUS_HIDDEN)
                                        <form action="{{ route('admin.reviews.hide', $review) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg bg-zinc-700/50 p-2 text-zinc-300 transition hover:bg-zinc-600 hover:text-white" title="Ẩn">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.382-3.568m2.98-1.82A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.969 9.969 0 01-4.132 5.411M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l8 8M4 4l16 16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.reviews.show', $review) }}#reject-review-form" class="rounded-lg bg-red-500/10 p-2 text-red-400 transition hover:bg-red-500 hover:text-white" title="Từ chối">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                                Không có đánh giá nào khớp bộ lọc hiện tại.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-800 p-6">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
