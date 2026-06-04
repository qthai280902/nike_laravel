@extends('layouts.app')

@section('title', (request()->routeIs('catalog.sale') ? 'Giảm Giá' : 'Sản Phẩm') . ' | Nike Hybrid')

@section('content')
<section class="max-w-[1920px] mx-auto px-6 md:px-12 py-12">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div>
            <h1 class="text-4xl font-nike-display uppercase tracking-tighter ">
                @if(request()->routeIs('catalog.sale'))
                    Khuyến mãi.
                @elseif(request()->get('category'))
                    {{ match(request()->get('category')) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', default => ucfirst(request()->get('category')) } }}.
                @else
                    Mọi Sản Phẩm.
                @endif
            </h1>
            <p class="text-nike-gray-500 font-medium text-sm mt-1 font-nike-body">Khám phá phong cách và hiệu suất đỉnh cao.</p>
        </div>
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <button id="filter-toggle-btn" class="flex items-center space-x-2 px-4 py-2 border border-nike-gray-200 rounded-full hover:border-nike-black transition-colors focus:outline-none">
                <span class="text-xs font-bold uppercase tracking-widest ">Lọc</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4.5h18m-18 5h18m-18 5h18m-18 5h18"></path></svg>
            </button>
            <div class="relative">
                <select onchange="window.location.href=this.value" class="appearance-none bg-white border border-nike-gray-200 px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest pr-10 focus:ring-0 focus:border-nike-black cursor-pointer">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                </select>
            </div>
        </div>
    </div>

    <div class="flex gap-12">
        {{-- Sidebar Filters (Standard Nike Layout) --}}
        <aside id="filter-sidebar" class="hidden lg:block w-64 flex-shrink-0 space-y-10 transition-all duration-300">
            <div>
                <h3 class="text-[13px] font-black uppercase mb-6 tracking-tighter ">Danh mục</h3>
                <ul class="space-y-3">
                    @foreach($categories as $cat)
                        <li><a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="text-sm font-medium hover:text-nike-gray-500 {{ request('category') == $cat->slug ? 'font-bold underline' : '' }}">{{ match(strtolower($cat->name)) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', 'lifestyle' => 'Phong cách sống', 'running' => 'Chạy bộ', 'basketball' => 'Bóng rổ', 'training' => 'Tập luyện', 'yoga' => 'Yoga', 'shoes' => 'Giày', 'clothing' => 'Quần áo', 'accessories' => 'Phụ kiện', default => $cat->name } }}</a></li>
                    @endforeach
                    <li><a href="{{ route('catalog.sale') }}" class="text-sm font-bold text-nike-red hover:text-red-700  {{ request()->routeIs('catalog.sale') ? 'underline' : '' }}">Đang Giảm Giá</a></li>
                </ul>
            </div>
        </aside>

        {{-- Product Grid --}}
        <div class="flex-grow">
            <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-y-12 gap-x-4 transition-all duration-300">
                @forelse($products as $product)
                    <div class="group">
                        <a href="{{ route('catalog.show', $product->slug) }}">
                            <div class="aspect-square bg-nike-gray-100 overflow-hidden mb-4 no-border-radius shadow-sm">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='/images/hero.png'" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="space-y-1">
                               @if($product->original_price && $product->price < $product->original_price)
                                     <span class="text-nike-red text-[11px] font-black uppercase tracking-tighter">Đang Giảm Giá</span>
                               @endif
                                <h3 class="font-bold text-nike-black uppercase text-[13px] tracking-tight group-hover:text-nike-gray-500 transition-colors  leading-none">{{ $product->name }}</h3>
                                <p class="text-nike-gray-500 text-[12px] font-medium leading-none">{{ match(strtolower($product->category->name)) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', 'lifestyle' => 'Phong cách sống', 'running' => 'Chạy bộ', 'basketball' => 'Bóng rổ', 'training' => 'Tập luyện', 'yoga' => 'Yoga', 'shoes' => 'Giày', 'clothing' => 'Quần áo', 'accessories' => 'Phụ kiện', default => $product->category->name } }}</p>
                                
                                <div class="pt-2 flex items-center space-x-2 font-nike-body">
                                    <span class="font-bold text-sm tracking-tight">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    @if($product->original_price && $product->price < $product->original_price)
                                        <span class="text-nike-gray-400 text-xs line-through">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-32 text-center">
                        <p class="text-nike-gray-400 font-bold uppercase tracking-widest text-[11px]">Không tìm thấy sản phẩm nào.</p>
                        <a href="{{ route('catalog.index') }}" class="mt-4 inline-block text-nike-black font-black uppercase underline text-xs">Quay lại tất cả</a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-20">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    {{-- Mobile Filter Drawer --}}
    <div id="mobile-filter-drawer" class="fixed inset-0 z-50 invisible" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div id="mobile-filter-overlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300"></div>
        
        <!-- Drawer Panel -->
        <div id="mobile-filter-panel" class="absolute inset-y-0 left-0 max-w-xs w-full bg-white shadow-xl transform -translate-x-full transition-transform duration-300 flex flex-col z-10">
            <div class="flex items-center justify-between px-6 py-5 border-b border-nike-gray-200">
                <h2 class="text-lg font-bold uppercase tracking-wider font-nike-display">Bộ lọc</h2>
                <button id="mobile-filter-close-btn" class="p-1 text-nike-black hover:text-nike-gray-500 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="flex-grow overflow-y-auto px-6 py-6 space-y-8">
                <div>
                    <h3 class="text-[13px] font-black uppercase mb-4 tracking-tighter">Danh mục</h3>
                    <ul class="space-y-4">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="text-sm font-medium hover:text-nike-gray-500 {{ request('category') == $cat->slug ? 'font-bold underline' : '' }}">
                                    {{ match(strtolower($cat->name)) { 'men' => 'Nam', 'women' => 'Nữ', 'kids' => 'Trẻ em', 'lifestyle' => 'Phong cách sống', 'running' => 'Chạy bộ', 'basketball' => 'Bóng rổ', 'training' => 'Tập luyện', 'yoga' => 'Yoga', 'shoes' => 'Giày', 'clothing' => 'Quần áo', 'accessories' => 'Phụ kiện', default => $cat->name } }}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('catalog.sale') }}" class="text-sm font-bold text-nike-red hover:text-red-700 {{ request()->routeIs('catalog.sale') ? 'underline' : '' }}">
                                Đang Giảm Giá
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('filter-toggle-btn');
        const sidebar = document.getElementById('filter-sidebar');
        const grid = document.getElementById('product-grid');
        
        // Mobile Drawer Elements
        const mobileDrawer = document.getElementById('mobile-filter-drawer');
        const mobileOverlay = document.getElementById('mobile-filter-overlay');
        const mobilePanel = document.getElementById('mobile-filter-panel');
        const mobileCloseBtn = document.getElementById('mobile-filter-close-btn');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                if (window.innerWidth >= 1024) {
                    // Desktop toggle: Toggle the sidebar visibility
                    const isHidden = sidebar.classList.contains('lg:hidden');
                    if (isHidden) {
                        sidebar.classList.remove('lg:hidden');
                        grid.classList.remove('lg:grid-cols-3', 'xl:grid-cols-4');
                        grid.classList.add('xl:grid-cols-3');
                    } else {
                        sidebar.classList.add('lg:hidden');
                        grid.classList.remove('xl:grid-cols-3');
                        grid.classList.add('lg:grid-cols-3', 'xl:grid-cols-4');
                    }
                } else {
                    // Mobile toggle: Show the drawer
                    mobileDrawer.classList.remove('invisible');
                    setTimeout(() => {
                        mobileOverlay.classList.remove('opacity-0');
                        mobileOverlay.classList.add('opacity-100');
                        mobilePanel.classList.remove('-translate-x-full');
                        mobilePanel.classList.add('translate-x-0');
                    }, 50);
                    document.body.classList.add('overflow-hidden');
                }
            });
        }

        function closeMobileDrawer() {
            mobileOverlay.classList.remove('opacity-100');
            mobileOverlay.classList.add('opacity-0');
            mobilePanel.classList.remove('translate-x-0');
            mobilePanel.classList.add('-translate-x-full');
            
            setTimeout(() => {
                mobileDrawer.classList.add('invisible');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        if (mobileCloseBtn) {
            mobileCloseBtn.addEventListener('click', closeMobileDrawer);
        }
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileDrawer);
        }
    });
</script>
@endsection
