@extends('layouts.app')

@section('title', 'Nike Hybrid | Bản Sắc Thể Thao')

@section('content')
    @php
        $heroImage = $heroProduct?->image_url ?? asset('images/hero.png');
        $heroTitle = $heroProduct?->name ?? 'Sẵn sàng bứt phá';
        $heroCategoryName = $heroProduct?->category?->name;
        $heroCategory = $heroCategoryName
            ? (match(strtolower($heroCategoryName)) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', 'lifestyle' => 'Phong cách sống', 'running' => 'Chạy bộ', 'basketball' => 'Bóng rổ', 'training' => 'Tập luyện', 'yoga' => 'Yoga', 'shoes' => 'Giày', 'clothing' => 'Quần áo', 'accessories' => 'Phụ kiện', default => $heroCategoryName })
            : 'Bộ sưu tập nổi bật';
        $heroDescription = $heroProduct
            ? 'Mẫu nổi bật trong catalog Nike Hybrid, chọn lọc từ dữ liệu sản phẩm thật của cửa hàng.'
            : 'Trang chủ tập trung vào catalog giày, chợ đồ cũ C2C và trải nghiệm mua sắm gọn gàng.';
        $heroHref = $heroProduct ? route('catalog.show', $heroProduct->slug) : route('catalog.index');
    @endphp

    <section class="relative flex min-h-[68vh] items-end overflow-hidden bg-nike-black">
        <img src="{{ $heroImage }}" class="absolute inset-0 h-full w-full object-cover opacity-85" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $heroTitle }}">
        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>

        <div class="relative z-10 mx-auto w-full max-w-[1440px] px-6 pb-12 pt-28 md:px-12 md:pb-16">
            <div class="max-w-3xl">
                <p class="mb-4 text-xs font-black uppercase tracking-[0.26em] text-white/80">{{ $heroCategory }}</p>
                <h1 class="text-5xl font-black uppercase leading-none tracking-tight text-white md:text-7xl">
                    {{ $heroTitle }}
                </h1>
                <p class="mt-5 max-w-xl text-sm font-medium leading-relaxed text-white/80 md:text-base">
                    {{ $heroDescription }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ $heroHref }}" class="rounded-full bg-white px-8 py-4 text-xs font-black uppercase tracking-widest text-nike-black transition hover:bg-nike-gray-200">
                        Xem sản phẩm
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="rounded-full border border-white/40 px-8 py-4 text-xs font-black uppercase tracking-widest text-white transition hover:bg-white hover:text-nike-black">
                        Chợ đồ cũ
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-nike-gray-100 bg-nike-snow px-6 py-12 md:px-12">
        <div class="mx-auto grid max-w-[1440px] grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <p class="mb-3 text-xs font-black uppercase tracking-widest text-nike-red">Nike Hybrid</p>
                <h2 class="text-3xl font-black uppercase leading-none tracking-tight text-nike-black md:text-4xl">
                    Mua mới và trao đổi giày cũ trong một hệ sinh thái
                </h2>
            </div>
            <div class="grid gap-4 md:grid-cols-2 lg:col-span-2">
                <article class="border border-nike-gray-150 bg-white p-6">
                    <h3 class="text-sm font-black uppercase tracking-widest text-nike-black">Cửa hàng B2C</h3>
                    <p class="mt-3 text-sm font-medium leading-relaxed text-nike-gray-500">
                        Catalog giày Nike mới, rõ giá, rõ size và đồng bộ với giỏ hàng, đơn hàng hiện có.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="mt-5 inline-flex text-xs font-black uppercase tracking-widest text-nike-black underline">Khám phá cửa hàng</a>
                </article>
                <article class="border border-nike-gray-150 bg-white p-6">
                    <h3 class="text-sm font-black uppercase tracking-widest text-nike-black">Chợ C2C</h3>
                    <p class="mt-3 text-sm font-medium leading-relaxed text-nike-gray-500">
                        Người dùng đăng bán giày đã qua sử dụng, admin kiểm duyệt trước khi hiển thị công khai.
                    </p>
                    <a href="{{ route('marketplace.create') }}" class="mt-5 inline-flex text-xs font-black uppercase tracking-widest text-nike-black underline">Đăng bán giày</a>
                </article>
            </div>
        </div>
    </section>

    @if($secondaryProducts->isNotEmpty())
        <section class="mx-auto max-w-[1440px] px-6 py-12 md:px-12">
            <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-nike-gray-400">Đề xuất hàng đầu</p>
                    <h2 class="mt-2 text-3xl font-black uppercase leading-none tracking-tight text-nike-black md:text-4xl">
                        Sản phẩm nổi bật
                    </h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="text-xs font-black uppercase tracking-widest text-nike-black underline">Xem tất cả</a>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach($secondaryProducts as $product)
                    <article class="group">
                        <a href="{{ route('catalog.show', $product->slug) }}" class="block">
                            <div class="aspect-square overflow-hidden bg-nike-gray-100">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                            <div class="mt-4 flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-black uppercase leading-tight text-nike-black">{{ $product->name }}</h3>
                                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-nike-gray-400">
                                        {{ match(strtolower($product->category->name)) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', 'lifestyle' => 'Phong cách sống', 'running' => 'Chạy bộ', 'basketball' => 'Bóng rổ', 'training' => 'Tập luyện', 'yoga' => 'Yoga', 'shoes' => 'Giày', 'clothing' => 'Quần áo', 'accessories' => 'Phụ kiện', default => $product->category->name } }}
                                    </p>
                                </div>
                                <span class="shrink-0 text-sm font-black text-nike-black">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-nike-black px-6 py-12 text-white md:px-12">
        <div class="mx-auto flex max-w-[1440px] flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-white/50">Chợ C2C</p>
                <h2 class="mt-3 text-3xl font-black uppercase leading-none tracking-tight md:text-5xl">
                    Bán đôi giày cũ của bạn
                </h2>
                <p class="mt-4 text-sm font-medium leading-relaxed text-zinc-400">
                    Đăng tin trực tiếp, mô tả rõ tình trạng và chờ admin duyệt trước khi xuất hiện trong chợ.
                </p>
            </div>
            <a href="{{ route('marketplace.create') }}" class="inline-flex shrink-0 justify-center rounded-full bg-white px-8 py-4 text-xs font-black uppercase tracking-widest text-nike-black transition hover:bg-zinc-200">
                Đăng bán ngay
            </a>
        </div>
    </section>

    @if(isset($landingArticles) && $landingArticles->isNotEmpty())
        <section class="border-t border-nike-gray-100 bg-white px-6 py-12 md:px-12">
            <div class="mx-auto max-w-[1440px]">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-nike-red">Cảm hứng</p>
                        <h2 class="mt-2 text-3xl font-black uppercase leading-none tracking-tight text-nike-black">Bài viết mới</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    @foreach($landingArticles as $article)
                        <article class="border border-nike-gray-150 bg-nike-snow">
                            <div class="aspect-[16/9] overflow-hidden bg-nike-gray-100">
                                @if ($article->image_url)
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-full w-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs font-black uppercase tracking-widest text-nike-gray-400">Nike Story</div>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">
                                    {{ $article->published_at ? $article->published_at->format('d/m/Y') : 'Bản tin' }}
                                </p>
                                <h3 class="mt-3 line-clamp-2 text-base font-black uppercase leading-tight text-nike-black">{{ $article->title }}</h3>
                                @if ($article->excerpt)
                                    <p class="mt-3 line-clamp-2 text-xs font-medium leading-relaxed text-nike-gray-500">{{ $article->excerpt }}</p>
                                @endif
                                <a href="{{ route('articles.show', $article->slug) }}" class="mt-5 inline-flex text-xs font-black uppercase tracking-widest text-nike-black underline">
                                    Đọc chi tiết
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-t border-nike-gray-100 bg-nike-snow px-6 py-10 text-center md:px-12">
        <div class="mx-auto max-w-xl">
            <h3 class="text-xl font-black uppercase tracking-tight text-nike-black">Bạn cần hỗ trợ?</h3>
            <p class="mt-3 text-xs font-medium leading-relaxed text-nike-gray-500">Gửi câu hỏi về sản phẩm, đơn hàng hoặc tin đăng C2C cho đội ngũ hỗ trợ.</p>
            <a href="{{ route('support.create') }}" class="mt-5 inline-flex rounded-full bg-nike-black px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-nike-gray-800">
                Liên hệ hỗ trợ
            </a>
        </div>
    </section>
@endsection
