@extends('layouts.app')

@section('title', $article->title . ' | Nike Hybrid')

@section('content')
<article class="py-20 px-6 md:px-12 max-w-4xl mx-auto">
    {{-- Breadcrumbs & Metadata --}}
    <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-widest text-nike-gray-500 mb-6">
        <a href="/" class="hover:text-nike-black transition-colors">Trang chủ</a>
        <span>/</span>
        <span class="text-nike-black">Bài viết</span>
    </div>

    {{-- Title --}}
    <h1 class="text-3xl md:text-5xl font-nike-display uppercase tracking-tighter leading-tight text-nike-black mb-6">
        {{ $article->title }}
    </h1>

    {{-- Publish Date --}}
    <div class="flex items-center space-x-4 text-xs font-medium text-nike-gray-500 mb-10">
        <span>Đăng lúc: {{ $article->published_at ? $article->published_at->format('H:i d/m/Y') : 'Ngay bây giờ' }}</span>
        <span>•</span>
        <span>Bởi Nike Hybrid Team</span>
    </div>

    {{-- Featured Image --}}
    @if ($article->image_url)
        <div class="aspect-[16/9] w-full bg-nike-gray-100 overflow-hidden mb-12 rounded-2xl shadow-sm">
            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" 
                class="w-full h-full object-cover" 
                onerror="this.onerror=null; this.src='/images/hero.png'">
        </div>
    @endif

    {{-- Excerpt / Short intro --}}
    @if ($article->excerpt)
        <p class="text-lg md:text-xl font-medium text-nike-gray-600 leading-relaxed mb-10 pb-8 border-b border-nike-gray-100">
            {{ $article->excerpt }}
        </p>
    @endif

    {{-- Body Content --}}
    <div class="prose max-w-none text-nike-black leading-relaxed text-base space-y-6">
        {!! nl2br(e($article->body)) !!}
    </div>

    {{-- Back to Home --}}
    <div class="mt-16 pt-8 border-t border-nike-gray-100">
        <a href="/" class="inline-flex items-center space-x-2 bg-nike-black text-white hover:bg-nike-gray-500 px-6 py-3 rounded-full font-bold text-xs uppercase tracking-wider transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Quay lại trang chủ</span>
        </a>
    </div>
</article>
@endsection
