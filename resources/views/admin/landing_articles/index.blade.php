@extends('layouts.admin')

@section('page_title', 'Bài viết Trang chủ')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Danh sách bài viết</h3>
    <a href="{{ route('admin.landing-articles.create') }}" 
        class="bg-white text-black hover:bg-zinc-200 px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all">
        Thêm bài viết
    </a>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-500 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="type-card rounded-xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-900/50 text-zinc-500 uppercase text-[10px] tracking-widest border-b border-zinc-800">
                <tr>
                    <th class="px-6 py-4 font-medium">Vị trí</th>
                    <th class="px-6 py-4 font-medium">Ảnh</th>
                    <th class="px-6 py-4 font-medium">Tiêu đề</th>
                    <th class="px-6 py-4 font-medium">Đường dẫn (Slug)</th>
                    <th class="px-6 py-4 font-medium">Trạng thái</th>
                    <th class="px-6 py-4 font-medium">Ngày xuất bản</th>
                    <th class="px-6 py-4 font-medium text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @forelse ($articles as $article)
                    <tr class="hover:bg-zinc-900/30 transition-colors">
                        <td class="px-6 py-4 font-bold text-white text-xs">
                            {{ $article->position }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-12 h-8 rounded bg-zinc-900 overflow-hidden flex-shrink-0">
                                @if ($article->image_url)
                                    <img src="{{ $article->image_url }}" alt="" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/images/hero.png'">
                                @else
                                    <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-[10px] text-zinc-500">
                                        Không ảnh
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-white text-xs">
                            {{ $article->title }}
                        </td>
                        <td class="px-6 py-4 text-zinc-400 text-xs">
                            {{ $article->slug }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($article->is_published)
                                <span class="px-2 py-0.5 rounded bg-green-500/10 text-green-500 text-[10px] font-bold uppercase">
                                    Đã xuất bản
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-zinc-500/20 text-zinc-400 text-[10px] font-bold uppercase">
                                    Bản nháp
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-zinc-400 text-xs">
                            {{ $article->published_at ? $article->published_at->format('H:i d/m/Y') : 'Chưa đặt' }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end items-center space-x-2">
                            <a href="{{ route('admin.landing-articles.edit', $article) }}" 
                                class="inline-flex items-center justify-center p-2 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 hover:bg-zinc-800 text-zinc-300 hover:text-white rounded-lg transition-all"
                                title="Sửa bài viết">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <form action="{{ route('admin.landing-articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="inline-flex items-center justify-center p-2 bg-zinc-900 border border-zinc-800 hover:border-red-950 hover:bg-red-950/20 text-zinc-400 hover:text-red-500 rounded-lg transition-all"
                                    title="Xóa bài viết">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-zinc-500">
                            Không tìm thấy bài viết nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($articles->hasPages())
    <div class="mt-4">
        {{ $articles->links() }}
    </div>
@endif
@endsection
