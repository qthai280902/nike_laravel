<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị | Nike Hybrid</title>
    
    {{-- TypeUI Fonts: IBM Plex Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif !important;
        }
        .type-sidebar {
            background-color: #09090b;
            border-right: 1px solid #27272a;
        }
        .type-card {
            background-color: #18181b;
            border: 1px solid #27272a;
        }
        .type-nav-link:hover {
            background-color: #27272a;
        }
        .type-nav-link.active {
            background-color: #27272a;
            color: #ffffff;
        }
    </style>
</head>
<body class="bg-[#09090b] text-[#fafafa] antialiased overflow-x-hidden min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="type-sidebar w-64 flex-shrink-0 flex flex-col fixed inset-y-0 z-50">
        <div class="p-6 flex items-center space-x-3">
            <div class="w-8 h-8 bg-white rounded flex items-center justify-center">
                <svg class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 17V7l7 10V7" stroke="currentColor" stroke-width="2.4" stroke-linecap="square" stroke-linejoin="miter"/>
                    <path d="M15 17V7h4v10" stroke="currentColor" stroke-width="2.4" stroke-linecap="square" stroke-linejoin="miter"/>
                </svg>
            </div>
            <span class="text-lg font-bold tracking-tight">Nike Hybrid Admin</span>
        </div>

        <nav class="flex-grow px-4 space-y-1 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="type-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Bảng điều khiển
            </a>
            <a href="{{ route('admin.orders.index') }}" class="type-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Đơn hàng
            </a>
            <a href="{{ route('admin.storefront.index') }}" class="type-nav-link {{ request()->routeIs('admin.storefront.*') || request()->routeIs('admin.products.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Trưng bày
            </a>
            <a href="{{ route('admin.marketplace.index') }}" class="type-nav-link {{ request()->routeIs('admin.marketplace.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Duyệt Chợ C2C
            </a>
            <a href="{{ route('admin.members.index') }}" class="type-nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Thành viên
            </a>
            <a href="{{ route('admin.reports.index') }}" class="type-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Báo cáo
            </a>
            <a href="{{ route('admin.support.index') }}" class="type-nav-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Hỗ trợ
            </a>
            <a href="{{ route('admin.landing-articles.index') }}" class="type-nav-link {{ request()->routeIs('admin.landing-articles.*') ? 'active' : '' }} flex items-center px-4 py-2.5 text-sm font-medium text-zinc-400 rounded-lg transition-all">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m2 2a2 2 0 002-2V8a2 2 0 00-2-2h-2m-9 4h4m-4 4h4m-4 4h4"></path></svg>
                Bài viết trang chủ
            </a>
        </nav>

        <div class="p-4 border-t border-zinc-800">
            <a href="/" class="flex items-center px-4 py-2 text-xs font-medium text-zinc-500 hover:text-white transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại cửa hàng
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-grow ml-64 p-8">
        {{-- Topbar --}}
        <header class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">@yield('page_title', 'Dashboard')</h2>
                <p class="text-sm text-zinc-500">Xin chào, {{ auth()->user()->name }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative inline-block text-left" id="admin-notification-dropdown">
                    <button id="notification-bell-btn" class="relative p-2 bg-zinc-900 border border-zinc-800 rounded-full text-zinc-400 hover:text-white transition-all focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if($adminNotifications['total_count'] > 0)
                            <span class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500 text-[9px] font-extrabold text-white text-center leading-4">
                                {{ $adminNotifications['total_count'] }}
                            </span>
                        @endif
                    </button>

                    <div id="notification-menu" class="hidden absolute right-0 mt-2 w-72 bg-zinc-950 border border-zinc-850 rounded-xl shadow-2xl py-2 z-50">
                        <div class="px-4 py-2 border-b border-zinc-900 flex justify-between items-center">
                            <span class="font-bold text-xs text-white uppercase tracking-wider">Thông báo quản trị</span>
                            @if($adminNotifications['total_count'] > 0)
                                <span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-500 text-[10px] font-bold">
                                    Mới: {{ $adminNotifications['total_count'] }}
                                </span>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto divide-y divide-zinc-900 text-xs">
                            @if($adminNotifications['total_count'] > 0)
                                @if($adminNotifications['pending_orders_count'] > 0)
                                    <a href="{{ $adminNotifications['links']['pending_orders'] }}" class="flex items-center justify-between px-4 py-3 hover:bg-zinc-900/55 transition-colors">
                                        <span class="text-zinc-300">Đơn hàng chờ xử lý</span>
                                        <span class="px-2 py-0.5 rounded bg-yellow-500/15 text-yellow-500 font-bold font-mono">
                                            {{ $adminNotifications['pending_orders_count'] }}
                                        </span>
                                    </a>
                                @endif

                                @if($adminNotifications['open_support_tickets_count'] > 0)
                                    <a href="{{ $adminNotifications['links']['open_support'] }}" class="flex items-center justify-between px-4 py-3 hover:bg-zinc-900/55 transition-colors">
                                        <span class="text-zinc-300">Yêu cầu hỗ trợ đang mở</span>
                                        <span class="px-2 py-0.5 rounded bg-orange-500/15 text-orange-500 font-bold font-mono">
                                            {{ $adminNotifications['open_support_tickets_count'] }}
                                        </span>
                                    </a>
                                @endif

                                @if($adminNotifications['pending_listings_count'] > 0)
                                    <a href="{{ $adminNotifications['links']['pending_listings'] }}" class="flex items-center justify-between px-4 py-3 hover:bg-zinc-900/55 transition-colors">
                                        <span class="text-zinc-300">Tin C2C chờ duyệt</span>
                                        <span class="px-2 py-0.5 rounded bg-blue-500/15 text-blue-500 font-bold font-mono">
                                            {{ $adminNotifications['pending_listings_count'] }}
                                        </span>
                                    </a>
                                @endif

                                @if($adminNotifications['low_stock_count'] > 0)
                                    <a href="{{ $adminNotifications['links']['low_stock'] }}" class="flex items-center justify-between px-4 py-3 hover:bg-zinc-900/55 transition-colors">
                                        <span class="text-zinc-300 font-medium">Sản phẩm sắp hết hàng</span>
                                        <span class="px-2 py-0.5 rounded bg-red-500/15 text-red-500 font-bold font-mono">
                                            {{ $adminNotifications['low_stock_count'] }}
                                        </span>
                                    </a>
                                @endif
                            @else
                                <div class="px-4 py-6 text-center text-zinc-500">
                                    Không có thông báo mới
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="w-10 h-10 bg-gradient-to-tr from-zinc-700 to-zinc-900 rounded-full border border-zinc-700 flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Content Area --}}
        @yield('admin_content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bellBtn = document.getElementById('notification-bell-btn');
            const menu = document.getElementById('notification-menu');

            if (bellBtn && menu) {
                bellBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!menu.contains(e.target) && !bellBtn.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
