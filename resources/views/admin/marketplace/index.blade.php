@extends('layouts.admin')

@section('page_title', 'Kiểm duyệt Chợ C2C')

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

    <div class="type-card overflow-hidden rounded-xl">
        <div class="flex flex-col gap-2 border-b border-zinc-800 p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <h3 class="font-bold text-white">Tin đăng đang chờ duyệt</h3>
                <p class="mt-1 text-xs text-zinc-500">Duyệt cả tin tự nhập và tin liên kết catalog.</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-widest text-zinc-500">{{ $listings->total() }} tin chờ</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-900/60 text-[10px] uppercase tracking-widest text-zinc-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Sản phẩm</th>
                        <th class="px-6 py-4 font-medium">Người bán</th>
                        <th class="px-6 py-4 font-medium">Giá bán</th>
                        <th class="px-6 py-4 font-medium">Tình trạng</th>
                        <th class="px-6 py-4 font-medium">Nguồn</th>
                        <th class="px-6 py-4 text-right font-medium">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($listings as $listing)
                        <tr class="transition-colors hover:bg-zinc-900/35">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded bg-zinc-900">
                                        <img src="{{ $listing->display_image_url }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" class="h-full w-full object-cover" alt="">
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block truncate font-medium text-white">{{ $listing->display_name }}</span>
                                        <span class="block text-[10px] uppercase text-zinc-500">Size: {{ $listing->display_size }} | {{ $listing->display_color }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-zinc-300">{{ $listing->user->name }}</span>
                                <span class="block text-[10px] text-zinc-500">{{ $listing->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-white">
                                {{ number_format($listing->asking_price, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded bg-zinc-800 px-2 py-1 text-[10px] font-bold uppercase text-zinc-300">
                                    {{ $listing->condition_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-400">
                                {{ $listing->display_source }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.marketplace.show', $listing) }}" class="rounded-lg bg-zinc-800 p-2 text-zinc-300 transition hover:bg-zinc-700 hover:text-white" title="Xem chi tiết">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.marketplace.update', [$listing->id, 'active']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg bg-green-500/10 p-2 text-green-400 transition hover:bg-green-500 hover:text-white" title="Duyệt bài">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.marketplace.update', [$listing->id, 'rejected']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg bg-red-500/10 p-2 text-red-400 transition hover:bg-red-500 hover:text-white" title="Từ chối">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                                Hiện không có tin đăng nào đang chờ duyệt.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-800 p-6">
            {{ $listings->links() }}
        </div>
    </div>
</div>
@endsection
