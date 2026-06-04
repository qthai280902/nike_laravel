<!DOCTYPE html>
<html lang="vi" data-theme="light" data-theme-preference="system">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nike Hybrid | Bản Sắc Thể Thao')</title>
    <!-- Be Vietnam Pro Font -->
    <script>
        (() => {
            try {
                const key = 'nike-storefront-theme';
                const preference = localStorage.getItem(key) || 'system';
                const isSystemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.dataset.theme = preference === 'system' ? (isSystemDark ? 'dark' : 'light') : preference;
                document.documentElement.dataset.themePreference = preference;
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
                document.documentElement.dataset.themePreference = 'system';
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-nike-black antialiased overflow-x-hidden font-nike-body transition-colors duration-200">

    {{-- Global Navigation --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-nike-gray-100">
        {{-- Utility Nav --}}
        <div class="bg-nike-gray-100 py-1.5 px-6 md:px-12 flex justify-end items-center space-x-6 text-[10px] font-bold uppercase tracking-widest">
            <a href="{{ route('story') }}" class="hover:text-nike-gray-500 {{ request()->routeIs('story') ? 'text-nike-black underline' : '' }}">Câu chuyện</a>
            <span class="text-nike-gray-300">|</span>
            <a href="{{ route('stores') }}" class="hover:text-nike-gray-500 {{ request()->routeIs('stores') ? 'text-nike-black underline' : '' }}">Tìm cửa hàng</a>
            <span class="text-nike-gray-300">|</span>
            <a href="{{ route('support.create') }}" class="hover:text-nike-gray-500 {{ request()->routeIs('support.create') ? 'text-nike-black underline' : '' }}">Hỗ trợ</a>
        </div>

        {{-- Main Nav --}}
        <nav class="flex justify-between items-center py-4 px-6 md:px-12 relative">
            {{-- Logo --}}
            <a href="/" class="z-50 hover:opacity-70 transition-opacity">
                <svg class="w-16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 8.719L7.836 14.303C6.74 14.768 5.818 15 5.075 15c-.836 0-1.445-.295-1.819-.884-.485-.76-.273-1.982.559-3.272.494-.754 1.122-1.446 1.734-2.108-.144.234-1.415 2.349-.025 3.345.275.2.666.298 1.147.298.386 0 .829-.063 1.316-.19L21 8.719z"></path>
                </svg>
            </a>

            {{-- Center Links: PILL SHAPE NAVIGATION --}}
            <div class="hidden md:flex items-center space-x-1">
                @php
                    $currentCategory = request()->get('category');
                    $isHome = request()->routeIs('home') || request()->is('/');
                    $isSale = request()->routeIs('catalog.sale');
                    $isMarketplace = request()->routeIs('marketplace.*');
                    $isCatalogIndex = request()->routeIs('catalog.index') && ! $currentCategory;
                    $isCatalogShow = request()->routeIs('catalog.show');
                    $isCatalog = ($isCatalogIndex || $isCatalogShow) && ! $isSale;
                    $isMen = request()->routeIs('catalog.index') && $currentCategory === 'men';
                    $isWomen = request()->routeIs('catalog.index') && $currentCategory === 'women';
                    $pillClass = "px-5 py-2 rounded-[30px] text-[13px] font-bold uppercase tracking-tight transition-all whitespace-nowrap";
                    $activePill = "bg-nike-black text-white shadow-lg";
                    $inactivePill = "text-nike-black hover:bg-nike-gray-100";
                @endphp
                
                <a href="/" class="{{ $pillClass }} {{ $isHome ? $activePill : $inactivePill }}">Trang chủ</a>
                <a href="{{ route('catalog.index') }}" class="{{ $pillClass }} {{ $isCatalog ? $activePill : $inactivePill }}">Cửa hàng</a>
                <a href="{{ route('catalog.index', ['category' => 'men']) }}" class="{{ $pillClass }} {{ $isMen ? $activePill : $inactivePill }}">Nam</a>
                <a href="{{ route('catalog.index', ['category' => 'women']) }}" class="{{ $pillClass }} {{ $isWomen ? $activePill : $inactivePill }}">Nữ</a>
                <a href="{{ route('marketplace.index') }}" class="{{ $pillClass }} {{ $isMarketplace ? $activePill : $inactivePill }}">Chợ đồ cũ</a>
                <a href="{{ route('catalog.sale') }}" class="{{ $pillClass }} {{ $isSale ? 'bg-nike-red text-white' : 'text-nike-red hover:bg-red-50' }}">Sale</a>
            </div>

            {{-- Right Interaction Cluster --}}
            <div class="flex items-center space-x-6">
                {{-- Search Box --}}
                <div class="relative hidden lg:block group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="w-4 h-4 text-nike-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input id="global-search-input" type="text" placeholder="Tìm kiếm" autocomplete="off"
                        class="bg-nike-gray-100 border-none rounded-full py-2 pl-11 pr-5 text-sm focus:ring-2 focus:ring-nike-gray-200 w-48 transition-all focus:w-64 font-medium">
                    <div id="search-suggestions" class="absolute top-12 left-0 right-0 bg-white border border-nike-gray-100 shadow-2xl z-50 hidden">
                        <div id="suggestions-list" class="divide-y divide-nike-gray-50"></div>
                    </div>
                </div>

                <div class="relative" data-theme-menu>
                    <button type="button" data-theme-menu-button class="theme-toggle-button flex h-9 w-9 items-center justify-center rounded-full border border-nike-gray-200 bg-white text-nike-black transition hover:border-nike-black focus:outline-none focus:ring-2 focus:ring-nike-gray-200" aria-label="Đổi giao diện" aria-expanded="false">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m8.66-13.66-.7.7M4.04 19.96l-.7.7M21 12h-1M4 12H3m16.96 7.96-.7-.7M4.04 4.04l-.7-.7M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                    <div data-theme-menu-panel class="absolute right-0 top-12 z-50 hidden w-44 rounded-md border border-nike-gray-150 bg-white p-2 shadow-2xl">
                        <button type="button" data-theme-option="light" class="w-full rounded px-3 py-2 text-left text-xs font-bold uppercase tracking-widest text-nike-black transition hover:bg-nike-gray-100">Sáng</button>
                        <button type="button" data-theme-option="dark" class="w-full rounded px-3 py-2 text-left text-xs font-bold uppercase tracking-widest text-nike-black transition hover:bg-nike-gray-100">Tối</button>
                        <button type="button" data-theme-option="system" class="w-full rounded px-3 py-2 text-left text-xs font-bold uppercase tracking-widest text-nike-black transition hover:bg-nike-gray-100">Hệ thống</button>
                    </div>
                </div>

                {{-- Auth Toggles --}}
                @guest
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-[11px] font-black uppercase tracking-tighter hover:opacity-70">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="text-[11px] font-black uppercase tracking-tighter hover:opacity-70">Đăng ký</a>
                    </div>
                @else
                    <a href="{{ route('profile.index') }}" class="text-[11px] font-black uppercase hover:text-nike-gray-500 bg-nike-snow px-3 py-1.5 rounded-md border border-nike-gray-100">
                        {{ strtoupper(explode(' ', auth()->user()->name)[0]) }}
                    </a>
                @endguest

                <button onclick="toggleCart()" class="relative text-nike-black">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span id="cart-count-badge" class="absolute -top-1 -right-1 bg-nike-red text-white text-[9px] w-4 h-4 flex items-center justify-center rounded-full font-black hidden">0</span>
                </button>
            </div>
        </nav>
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-black text-white text-center py-3 text-[11px] font-bold uppercase tracking-[0.2em] animate-pulse">
                {{ session('success') }}
            </div>
        @endif
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <div id="generic-success-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-md"></div>
        <div class="relative bg-white p-12 flex flex-col items-center animate-[scale-in_0.3s_ease-out]">
            <svg class="w-20 h-20 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p id="success-modal-text" class="font-bold uppercase text-lg tracking-widest text-nike-black">Thành công</p>
        </div>
    </div>

    <x-cart-drawer />

    <script>
        // --- SEARCH ENGINE ---
        let searchTimeout;
        const searchInput = document.getElementById('global-search-input');
        const suggestionsBox = document.getElementById('search-suggestions');
        const suggestionsList = document.getElementById('suggestions-list');

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsBox.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/catalog/search/suggestions?q=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (data.length > 0) {
                        suggestionsList.innerHTML = data.map(item => `
                            <a href="/catalog/products/${item.slug}" class="flex items-center p-4 hover:bg-nike-gray-50 transition-colors group">
                                <div class="w-12 h-12 bg-nike-gray-100 flex-shrink-0">
                                    <img src="${item.image}" onerror="this.onerror=null; this.src='/images/hero.png'" class="w-full h-full object-cover">
                                </div>
                                <div class="ml-4">
                                    <p class="text-[13px] font-bold uppercase tracking-tight  group-hover:text-nike-gray-500">${item.name}</p>
                                    <p class="text-[10px] text-nike-gray-400 uppercase font-medium">${item.category}</p>
                                </div>
                            </a>
                        `).join('');
                        suggestionsBox.classList.remove('hidden');
                    } else {
                        suggestionsBox.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Search error:', error);
                }
            }, 300);
        });

        // Close search on click outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('hidden');
            }
        });

        function toggleCart() {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('cart-drawer-overlay');
            if (drawer.classList.contains('translate-x-full')) {
                drawer.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            } else {
                drawer.classList.add('translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function showSuccessModal(text) {
            const modal = document.getElementById('generic-success-modal');
            const label = document.getElementById('success-modal-text');
            label.innerText = text;
            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.add('hidden'), 1000);
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-count-badge');
            if (badge) {
                if (count > 0) {
                    badge.innerText = count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>
