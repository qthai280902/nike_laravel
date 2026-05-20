@extends('layouts.app')

@section('title', 'Tìm Cửa Hàng | Nike Hybrid')

@section('content')
<section class="max-w-[1920px] mx-auto px-6 md:px-12 py-24 font-nike-body bg-white text-nike-black">
    {{-- Page Header --}}
    <div class="max-w-4xl mb-16">
        <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-6">
            CỬA HÀNG.
        </h1>
        <p class="text-lg text-nike-gray-500 uppercase tracking-tight font-medium">Tìm kiếm các địa điểm cửa hàng Nike chính hãng gần bạn nhất.</p>
    </div>

    {{-- Interactive Filter & Listings --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
        {{-- City Filter Sidebar --}}
        <div class="lg:col-span-1 border-b lg:border-b-0 lg:border-r border-nike-gray-200 pb-8 lg:pb-0 lg:pr-8">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-nike-gray-400 mb-6">Khu vực</h3>
            <div class="flex flex-row lg:flex-col gap-4 overflow-x-auto pb-4 lg:pb-0">
                <button onclick="filterStores('all')" id="btn-filter-all" class="store-filter-btn px-6 py-3 border-2 border-nike-black bg-nike-black text-white text-[11px] font-bold uppercase tracking-widest transition-all text-left w-full max-w-[200px] flex-shrink-0">
                    Tất cả cửa hàng
                </button>
                <button onclick="filterStores('hanoi')" id="btn-filter-hanoi" class="store-filter-btn px-6 py-3 border-2 border-nike-gray-200 text-nike-black hover:border-nike-black text-[11px] font-bold uppercase tracking-widest transition-all text-left w-full max-w-[200px] flex-shrink-0">
                    Hà Nội
                </button>
                <button onclick="filterStores('hcm')" id="btn-filter-hcm" class="store-filter-btn px-6 py-3 border-2 border-nike-gray-200 text-nike-black hover:border-nike-black text-[11px] font-bold uppercase tracking-widest transition-all text-left w-full max-w-[200px] flex-shrink-0">
                    TP. Hồ Chí Minh
                </button>
            </div>
        </div>

        {{-- Store Grid --}}
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="stores-grid">
                
                {{-- Store HANOI 1 --}}
                <div class="store-card p-8 border border-nike-gray-200 hover:shadow-xl transition-all" data-city="hanoi">
                    <span class="text-[9px] font-black uppercase tracking-widest bg-nike-gray-100 px-2 py-1 mb-6 inline-block">Hà Nội</span>
                    <h3 class="text-xl font-black uppercase mb-4">Nike Vincom Bà Triệu</h3>
                    <div class="space-y-3 text-xs text-nike-gray-500 font-medium">
                        <p class="flex items-start">
                            <span class="font-bold text-nike-black mr-2">Địa chỉ:</span>
                            Tầng 3, Vincom Center, 191 Bà Triệu, Q. Hai Bà Trưng
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Hotline:</span>
                            024 2220 0211
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Giờ mở cửa:</span>
                            09:30 - 22:00
                        </p>
                    </div>
                </div>

                {{-- Store HANOI 2 --}}
                <div class="store-card p-8 border border-nike-gray-200 hover:shadow-xl transition-all" data-city="hanoi">
                    <span class="text-[9px] font-black uppercase tracking-widest bg-nike-gray-100 px-2 py-1 mb-6 inline-block">Hà Nội</span>
                    <h3 class="text-xl font-black uppercase mb-4">Nike Tràng Tiền Plaza</h3>
                    <div class="space-y-3 text-xs text-nike-gray-500 font-medium">
                        <p class="flex items-start">
                            <span class="font-bold text-nike-black mr-2">Địa chỉ:</span>
                            Tầng 4, Tràng Tiền Plaza, 24 Hai Bà Trưng, Q. Hoàn Kiếm
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Hotline:</span>
                            024 3936 0500
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Giờ mở cửa:</span>
                            09:30 - 21:30
                        </p>
                    </div>
                </div>

                {{-- Store HCM 1 --}}
                <div class="store-card p-8 border border-nike-gray-200 hover:shadow-xl transition-all" data-city="hcm">
                    <span class="text-[9px] font-black uppercase tracking-widest bg-nike-gray-100 px-2 py-1 mb-6 inline-block">TP. Hồ Chí Minh</span>
                    <h3 class="text-xl font-black uppercase mb-4">Nike Vincom Đồng Khởi</h3>
                    <div class="space-y-3 text-xs text-nike-gray-500 font-medium">
                        <p class="flex items-start">
                            <span class="font-bold text-nike-black mr-2">Địa chỉ:</span>
                            Tầng trệt, Vincom Center, 72 Lê Thánh Tôn, Q. 1
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Hotline:</span>
                            028 3936 9018
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Giờ mở cửa:</span>
                            09:30 - 22:00
                        </p>
                    </div>
                </div>

                {{-- Store HCM 2 --}}
                <div class="store-card p-8 border border-nike-gray-200 hover:shadow-xl transition-all" data-city="hcm">
                    <span class="text-[9px] font-black uppercase tracking-widest bg-nike-gray-100 px-2 py-1 mb-6 inline-block">TP. Hồ Chí Minh</span>
                    <h3 class="text-xl font-black uppercase mb-4">Nike Crescent Mall</h3>
                    <div class="space-y-3 text-xs text-nike-gray-500 font-medium">
                        <p class="flex items-start">
                            <span class="font-bold text-nike-black mr-2">Địa chỉ:</span>
                            Tầng trệt, Crescent Mall, 101 Tôn Dật Tiên, Q. 7
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Hotline:</span>
                            028 5413 8622
                        </p>
                        <p>
                            <span class="font-bold text-nike-black mr-2">Giờ mở cửa:</span>
                            10:00 - 22:00
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    function filterStores(city) {
        // Toggle active button styles
        document.querySelectorAll('.store-filter-btn').forEach(btn => {
            btn.classList.remove('bg-nike-black', 'text-white', 'border-nike-black');
            btn.classList.add('border-nike-gray-200', 'text-nike-black');
        });

        const activeBtn = document.getElementById(`btn-filter-${city}`);
        activeBtn.classList.remove('border-nike-gray-200', 'text-nike-black');
        activeBtn.classList.add('bg-nike-black', 'text-white', 'border-nike-black');

        // Show/hide cards
        document.querySelectorAll('.store-card').forEach(card => {
            if (city === 'all' || card.getAttribute('data-city') === city) {
                card.classList.remove('hidden');
                card.style.opacity = '1';
            } else {
                card.classList.add('hidden');
                card.style.opacity = '0';
            }
        });
    }
</script>
@endsection
