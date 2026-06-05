@extends('layouts.app')

@section('title', 'Tìm cửa hàng | Nike Hybrid')

@section('content')
@php
    $stores = [
        [
            'city_key' => 'hanoi',
            'city' => 'Hà Nội',
            'name' => 'Nike Vincom Bà Triệu',
            'address' => 'Vincom Center Bà Triệu, 191 Bà Triệu, Hai Bà Trưng, Hà Nội',
            'hotline' => '024 2220 0211',
            'hours' => '09:30 - 22:00',
            'maps_query' => 'Nike Vincom Ba Trieu 191 Ba Trieu Ha Noi',
        ],
        [
            'city_key' => 'hanoi',
            'city' => 'Hà Nội',
            'name' => 'Nike Tràng Tiền Plaza',
            'address' => 'Tràng Tiền Plaza, 24 Hai Bà Trưng, Hoàn Kiếm, Hà Nội',
            'hotline' => '024 3936 0500',
            'hours' => '09:30 - 21:30',
            'maps_query' => 'Nike Trang Tien Plaza 24 Hai Ba Trung Hoan Kiem Ha Noi',
        ],
        [
            'city_key' => 'hcm',
            'city' => 'TP. Hồ Chí Minh',
            'name' => 'Nike Vincom Đồng Khởi',
            'address' => 'Vincom Center Đồng Khởi, 72 Lê Thánh Tôn, Quận 1, TP. Hồ Chí Minh',
            'hotline' => '028 3936 9018',
            'hours' => '09:30 - 22:00',
            'maps_query' => 'Nike Vincom Dong Khoi 72 Le Thanh Ton Ho Chi Minh',
        ],
        [
            'city_key' => 'hcm',
            'city' => 'TP. Hồ Chí Minh',
            'name' => 'Nike Crescent Mall',
            'address' => 'Crescent Mall, 101 Tôn Dật Tiên, Quận 7, TP. Hồ Chí Minh',
            'hotline' => '028 5413 8622',
            'hours' => '10:00 - 22:00',
            'maps_query' => 'Nike Crescent Mall 101 Ton Dat Tien Quan 7 Ho Chi Minh',
        ],
        [
            'city_key' => 'danang',
            'city' => 'Đà Nẵng',
            'name' => 'Nike Vincom Đà Nẵng',
            'address' => 'Vincom Plaza, 910A Ngô Quyền, Sơn Trà, Đà Nẵng',
            'hotline' => '0236 3666 888',
            'hours' => '09:30 - 22:00',
            'maps_query' => 'Nike Vincom Plaza Da Nang 910A Ngo Quyen Son Tra',
        ],
    ];

    $filters = [
        'all' => 'Tất cả cửa hàng',
        'hanoi' => 'Hà Nội',
        'hcm' => 'TP. Hồ Chí Minh',
        'danang' => 'Đà Nẵng',
    ];
@endphp

<section class="mx-auto max-w-[1920px] bg-white px-6 py-20 text-nike-black md:px-12 md:py-24">
    <div class="mb-14 max-w-4xl">
        <h1 class="text-6xl font-black uppercase leading-none tracking-tight md:text-8xl">
            Cửa hàng
        </h1>
        <p class="mt-6 text-base font-medium uppercase tracking-tight text-nike-gray-500">
            Tìm địa điểm Nike Hybrid theo khu vực và mở đường đi bằng Google Maps.
        </p>
    </div>

    <div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-4">
        <aside class="border-b border-nike-gray-200 pb-8 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-8">
            <h2 class="mb-6 text-xs font-black uppercase tracking-[0.2em] text-nike-gray-400">Khu vực</h2>
            <div class="flex gap-4 overflow-x-auto pb-4 lg:flex-col lg:pb-0">
                @foreach($filters as $filterKey => $filterLabel)
                    <button onclick="filterStores('{{ $filterKey }}')" id="btn-filter-{{ $filterKey }}"
                        class="store-filter-btn w-full max-w-[220px] shrink-0 border-2 px-6 py-3 text-left text-[11px] font-bold uppercase tracking-widest transition-all {{ $filterKey === 'all' ? 'border-nike-black bg-nike-black text-white' : 'border-nike-gray-200 text-nike-black hover:border-nike-black' }}">
                        {{ $filterLabel }}
                    </button>
                @endforeach
            </div>
        </aside>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2" id="stores-grid">
                @foreach($stores as $store)
                    <article class="store-card border border-nike-gray-200 bg-white p-6 transition-all hover:shadow-xl md:p-8" data-city="{{ $store['city_key'] }}">
                        <span class="mb-6 inline-block bg-nike-gray-100 px-2 py-1 text-[9px] font-black uppercase tracking-widest">
                            {{ $store['city'] }}
                        </span>
                        <h3 class="text-xl font-black uppercase leading-tight">{{ $store['name'] }}</h3>
                        <div class="mt-5 space-y-3 text-xs font-medium leading-6 text-nike-gray-500">
                            <p><span class="mr-2 font-bold text-nike-black">Địa chỉ:</span>{{ $store['address'] }}</p>
                            <p><span class="mr-2 font-bold text-nike-black">Hotline:</span>{{ $store['hotline'] }}</p>
                            <p><span class="mr-2 font-bold text-nike-black">Giờ mở cửa:</span>{{ $store['hours'] }}</p>
                        </div>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($store['maps_query']) }}" target="_blank" rel="noopener"
                            class="mt-6 inline-flex rounded-full bg-nike-black px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white transition hover:bg-nike-gray-800">
                            Mở Google Maps
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    function filterStores(city) {
        document.querySelectorAll('.store-filter-btn').forEach(button => {
            button.classList.remove('bg-nike-black', 'text-white', 'border-nike-black');
            button.classList.add('border-nike-gray-200', 'text-nike-black');
        });

        const activeButton = document.getElementById(`btn-filter-${city}`);
        activeButton.classList.remove('border-nike-gray-200', 'text-nike-black');
        activeButton.classList.add('bg-nike-black', 'text-white', 'border-nike-black');

        document.querySelectorAll('.store-card').forEach(card => {
            if (city === 'all' || card.dataset.city === city) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }
</script>
@endsection
