@extends('layouts.admin')

@section('page_title', 'Sửa bài viết Trang chủ')

@section('admin_content')
<div class="mb-6 flex items-center space-x-3 text-xs uppercase tracking-wider">
    <a href="{{ route('admin.landing-articles.index') }}" class="text-zinc-500 hover:text-white transition-all">Bài viết</a>
    <span class="text-zinc-700">/</span>
    <span class="text-white">Chỉnh sửa</span>
</div>

<div class="mb-6">
    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Chỉnh sửa bài viết</h3>
</div>

<div class="type-card rounded-xl p-6 mb-8">
    <form action="{{ route('admin.landing-articles.update', $landingArticle) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        @include('admin.landing_articles._form', ['article' => $landingArticle])

        <div class="pt-6 border-t border-zinc-800 flex justify-end space-x-3">
            <a href="{{ route('admin.landing-articles.index') }}" 
                class="bg-zinc-900 text-zinc-400 hover:bg-zinc-800 hover:text-white border border-zinc-800 px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all">
                Hủy bỏ
            </a>
            <button type="submit" 
                class="bg-white text-black hover:bg-zinc-200 px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all">
                Cập nhật bài viết
            </button>
        </div>
    </form>
</div>
@endsection
