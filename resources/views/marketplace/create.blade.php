@extends('layouts.app')

@section('title', 'Đăng bán giày | Nike Chợ đồ cũ')

@section('content')
<section class="bg-white px-6 py-10 md:px-12 md:py-14">
    <div class="mx-auto max-w-[1440px]">
        <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <p class="mb-3 text-xs font-black uppercase tracking-[0.24em] text-nike-gray-400">Chợ đồ cũ C2C</p>
                <h1 class="text-4xl font-black uppercase leading-none tracking-tight text-nike-black md:text-6xl">
                    Đăng bán đôi giày của bạn
                </h1>
                <p class="mt-4 max-w-2xl text-sm font-medium leading-relaxed text-nike-gray-500 md:text-base">
                    Nhập trực tiếp thông tin sản phẩm. Bạn không cần chọn mẫu từ catalog cửa hàng.
                </p>
            </div>

            <a href="{{ route('marketplace.index') }}" class="inline-flex items-center justify-center rounded-full border border-nike-gray-200 px-6 py-3 text-xs font-black uppercase tracking-widest text-nike-black transition hover:border-nike-black">
                Quay lại chợ
            </a>
        </div>

        @if($errors->any())
            <div class="mb-8 border border-red-200 bg-red-50 px-5 py-4 text-xs font-bold uppercase tracking-widest text-nike-red">
                Vui lòng kiểm tra lại thông tin đăng bán.
            </div>
        @endif

        <form action="{{ route('marketplace.store') }}" method="POST" class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            @csrf

            <div class="space-y-8 lg:col-span-8">
                <div class="border border-nike-gray-150 bg-nike-snow p-5 md:p-6">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-sm font-black uppercase tracking-widest text-nike-black">Chọn nhanh từ catalog</h2>
                            <p class="mt-2 text-xs font-medium leading-relaxed text-nike-gray-500">
                                Không bắt buộc. Dùng mục này nếu đôi giày của bạn trùng với sản phẩm trong cửa hàng.
                            </p>
                        </div>
                        <button type="button" id="clear-catalog-btn" class="hidden shrink-0 rounded-full border border-nike-gray-200 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-nike-black transition hover:border-nike-black">
                            Bỏ chọn
                        </button>
                    </div>

                    <div class="relative" id="catalog-search-container">
                        <input type="text" id="product-search" placeholder="Gõ Air Max, Dunk, Pegasus..."
                            class="w-full border border-nike-gray-200 bg-white px-4 py-3 text-sm font-bold text-nike-black outline-none transition placeholder:text-nike-gray-300 focus:border-nike-black">
                        <div id="search-results" class="absolute left-0 right-0 top-full z-40 mt-2 hidden border border-nike-gray-150 bg-white shadow-2xl"></div>
                    </div>

                    <input type="hidden" name="product_variant_id" id="variant-input" value="{{ old('product_variant_id') }}">

                    <div id="catalog-selection" class="mt-5 hidden border-t border-nike-gray-150 pt-5">
                        <div class="flex items-start gap-4">
                            <div class="h-20 w-20 shrink-0 overflow-hidden bg-white">
                                <img id="catalog-preview-img" src="{{ asset('images/hero.png') }}" alt="" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Đã chọn catalog</p>
                                <h3 id="catalog-preview-name" class="mt-1 text-sm font-black uppercase leading-tight text-nike-black"></h3>
                                <p id="catalog-preview-variant" class="mt-2 text-xs font-bold uppercase tracking-wider text-nike-gray-500">Chọn size hoặc nhập thủ công bên dưới.</p>
                            </div>
                        </div>

                        <div id="variant-grid" class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-6"></div>
                    </div>

                    @error('product_variant_id')
                        <p class="mt-3 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="product_name" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Tên giày</label>
                        <input id="product_name" name="product_name" type="text" required value="{{ old('product_name') }}" placeholder="Ví dụ: Nike Air Max 90"
                            class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-2xl font-black uppercase tracking-tight text-nike-black outline-none transition placeholder:text-nike-gray-300 focus:border-nike-black">
                        @error('product_name')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="brand" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Thương hiệu</label>
                        <input id="brand" name="brand" type="text" value="{{ old('brand', 'Nike') }}" placeholder="Nike"
                            class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-sm font-bold text-nike-black outline-none transition focus:border-nike-black">
                        @error('brand')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image_url" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Ảnh sản phẩm URL</label>
                        <input id="image_url" name="image_url" type="url" value="{{ old('image_url') }}" placeholder="https://..."
                            class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-sm font-bold text-nike-black outline-none transition focus:border-nike-black">
                        @error('image_url')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="size" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Size</label>
                        <input id="size" name="size" type="text" required value="{{ old('size') }}" placeholder="US 9, EU 42..."
                            class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-sm font-bold text-nike-black outline-none transition focus:border-nike-black">
                        @error('size')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="color" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Màu sắc</label>
                        <input id="color" name="color" type="text" required value="{{ old('color') }}" placeholder="Trắng/Đen"
                            class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-sm font-bold text-nike-black outline-none transition focus:border-nike-black">
                        @error('color')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asking_price" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Giá bán mong muốn</label>
                        <input id="asking_price" name="asking_price" type="number" min="0" required value="{{ old('asking_price') }}" placeholder="2500000"
                            class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-2xl font-black tracking-tight text-nike-black outline-none transition focus:border-nike-black">
                        @error('asking_price')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="condition" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Tình trạng</label>
                        <select id="condition" name="condition" required class="w-full border-b border-nike-gray-200 bg-transparent py-4 text-sm font-black uppercase tracking-widest text-nike-black outline-none transition focus:border-nike-black">
                            <option value="new_with_box" @selected(old('condition') === 'new_with_box')>Mới nguyên hộp</option>
                            <option value="like_new" @selected(old('condition') === 'like_new')>Như mới</option>
                            <option value="good" @selected(old('condition', 'good') === 'good')>Tốt</option>
                            <option value="fair" @selected(old('condition') === 'fair')>Đã qua sử dụng</option>
                        </select>
                        @error('condition')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="seller_description" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Mô tả</label>
                        <textarea id="seller_description" name="seller_description" rows="5" required placeholder="Mô tả tình trạng đế, upper, hộp, lịch sử sử dụng..."
                            class="w-full border border-nike-gray-200 bg-nike-snow p-5 text-sm font-medium leading-relaxed text-nike-black outline-none transition focus:border-nike-black">{{ old('seller_description') }}</textarea>
                        @error('seller_description')
                            <p class="mt-2 text-xs font-bold uppercase tracking-widest text-nike-red">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-nike-black px-8 py-5 text-xs font-black uppercase tracking-[0.24em] text-white transition hover:bg-nike-gray-800 md:w-auto">
                    Đăng tin kiểm duyệt
                </button>
            </div>

            <aside class="lg:col-span-4">
                <div class="sticky top-28 border border-nike-gray-150 bg-white p-5">
                    <p class="mb-4 text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Xem trước tin đăng</p>
                    <div class="aspect-square overflow-hidden bg-nike-snow">
                        <img id="listing-preview-img" src="{{ old('image_url') ?: asset('images/hero.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="" class="h-full w-full object-cover">
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p id="listing-preview-brand" class="text-[10px] font-black uppercase tracking-widest text-nike-gray-400">{{ old('brand', 'Nike') }}</p>
                                <h3 id="listing-preview-name" class="mt-1 text-lg font-black uppercase leading-tight text-nike-black">{{ old('product_name', 'Tên đôi giày') }}</h3>
                            </div>
                            <p id="listing-preview-price" class="shrink-0 text-sm font-black text-nike-black">0₫</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 border-t border-nike-gray-150 pt-4 text-xs font-bold uppercase tracking-wider">
                            <div>
                                <span class="block text-nike-gray-400">Size</span>
                                <span id="listing-preview-size" class="text-nike-black">{{ old('size', 'Chưa nhập') }}</span>
                            </div>
                            <div>
                                <span class="block text-nike-gray-400">Màu</span>
                                <span id="listing-preview-color" class="text-nike-black">{{ old('color', 'Chưa nhập') }}</span>
                            </div>
                        </div>

                        <p class="border-t border-nike-gray-150 pt-4 text-xs font-medium leading-relaxed text-nike-gray-500">
                            Tin đăng sẽ hiển thị sau khi admin kiểm duyệt. Hệ thống chưa hỗ trợ thanh toán C2C hoặc escrow trong phase này.
                        </p>
                    </div>
                </div>
            </aside>
        </form>
    </div>
</section>

<script>
(() => {
    const searchUrl = @json(route('marketplace.search'));
    const variantUrlTemplate = @json(route('marketplace.products.variants', ['product' => '__PRODUCT__']));
    const placeholderImage = @json(asset('images/hero.png'));

    const productInput = document.getElementById('product_name');
    const brandInput = document.getElementById('brand');
    const sizeInput = document.getElementById('size');
    const colorInput = document.getElementById('color');
    const imageInput = document.getElementById('image_url');
    const priceInput = document.getElementById('asking_price');
    const variantInput = document.getElementById('variant-input');
    const searchInput = document.getElementById('product-search');
    const resultsBox = document.getElementById('search-results');
    const selectionBox = document.getElementById('catalog-selection');
    const variantGrid = document.getElementById('variant-grid');
    const clearCatalogBtn = document.getElementById('clear-catalog-btn');

    let searchTimeout;
    let currentProducts = [];

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatCurrency(value) {
        const number = Number(value || 0);
        return `${number.toLocaleString('vi-VN')}₫`;
    }

    function updatePreview() {
        document.getElementById('listing-preview-name').innerText = productInput.value || 'Tên đôi giày';
        document.getElementById('listing-preview-brand').innerText = brandInput.value || 'Nike';
        document.getElementById('listing-preview-size').innerText = sizeInput.value || 'Chưa nhập';
        document.getElementById('listing-preview-color').innerText = colorInput.value || 'Chưa nhập';
        document.getElementById('listing-preview-price').innerText = formatCurrency(priceInput.value);
        document.getElementById('listing-preview-img').src = imageInput.value || placeholderImage;
    }

    function renderProducts(products) {
        if (products.length === 0) {
            resultsBox.innerHTML = '<div class="p-5 text-center text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Không tìm thấy sản phẩm phù hợp</div>';
            resultsBox.classList.remove('hidden');
            return;
        }

        resultsBox.innerHTML = products.map((product, index) => `
            <button type="button" data-product-index="${index}" class="catalog-result flex w-full items-center gap-4 border-b border-nike-gray-100 p-4 text-left transition hover:bg-nike-snow">
                <span class="h-14 w-14 shrink-0 overflow-hidden bg-nike-snow">
                    <img src="${escapeHtml(product.image_url || placeholderImage)}" onerror="this.onerror=null; this.src='${placeholderImage}'" alt="" class="h-full w-full object-cover">
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-black uppercase text-nike-black">${escapeHtml(product.name)}</span>
                    <span class="mt-1 block text-[10px] font-bold uppercase tracking-widest text-nike-gray-400">${escapeHtml(product.category || 'Catalog')}</span>
                </span>
            </button>
        `).join('');

        document.querySelectorAll('.catalog-result').forEach(button => {
            button.addEventListener('click', () => selectProduct(Number(button.dataset.productIndex)));
        });

        resultsBox.classList.remove('hidden');
    }

    async function fetchVariants(product) {
        variantGrid.innerHTML = '<div class="col-span-full text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Đang tải size catalog...</div>';
        const response = await fetch(variantUrlTemplate.replace('__PRODUCT__', product.id), {
            headers: { 'Accept': 'application/json' }
        });
        const payload = await response.json();
        renderVariants(payload.data ?? []);
    }

    function renderVariants(variants) {
        if (variants.length === 0) {
            variantGrid.innerHTML = '<div class="col-span-full text-[10px] font-black uppercase tracking-widest text-nike-gray-400">Không có size catalog, hãy nhập thủ công.</div>';
            return;
        }

        variantGrid.innerHTML = variants.map(variant => `
            <button type="button" data-id="${escapeHtml(variant.id)}" data-size="${escapeHtml(variant.size)}" data-color="${escapeHtml(variant.color)}"
                class="variant-choice border border-nike-gray-200 px-3 py-3 text-[10px] font-black uppercase tracking-widest transition hover:border-nike-black">
                ${escapeHtml(variant.size)}
            </button>
        `).join('');

        document.querySelectorAll('.variant-choice').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.variant-choice').forEach(item => item.classList.remove('bg-nike-black', 'text-white', 'border-nike-black'));
                button.classList.add('bg-nike-black', 'text-white', 'border-nike-black');
                variantInput.value = button.dataset.id;
                sizeInput.value = button.dataset.size;
                colorInput.value = button.dataset.color || colorInput.value;
                document.getElementById('catalog-preview-variant').innerText = `Size: ${button.dataset.size} | Màu: ${button.dataset.color || 'Chưa rõ'}`;
                updatePreview();
            });
        });
    }

    async function selectProduct(index) {
        const product = currentProducts[index];
        resultsBox.classList.add('hidden');
        selectionBox.classList.remove('hidden');
        clearCatalogBtn.classList.remove('hidden');
        searchInput.value = product.name;
        productInput.value = product.name;
        brandInput.value = 'Nike';
        imageInput.value = product.image_url || '';
        variantInput.value = '';

        document.getElementById('catalog-preview-name').innerText = product.name;
        document.getElementById('catalog-preview-img').src = product.image_url || placeholderImage;
        document.getElementById('catalog-preview-variant').innerText = 'Chọn size hoặc nhập thủ công bên dưới.';

        updatePreview();
        await fetchVariants(product);
    }

    function clearCatalogSelection() {
        variantInput.value = '';
        searchInput.value = '';
        selectionBox.classList.add('hidden');
        clearCatalogBtn.classList.add('hidden');
        variantGrid.innerHTML = '';
        document.getElementById('catalog-preview-variant').innerText = 'Chọn size hoặc nhập thủ công bên dưới.';
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            resultsBox.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const payload = await response.json();
            currentProducts = payload.data ?? [];
            renderProducts(currentProducts);
        }, 300);
    });

    [productInput, brandInput, sizeInput, colorInput, imageInput, priceInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    clearCatalogBtn.addEventListener('click', clearCatalogSelection);

    document.addEventListener('click', function(event) {
        if (!document.getElementById('catalog-search-container').contains(event.target)) {
            resultsBox.classList.add('hidden');
        }
    });

    updatePreview();
})();
</script>
@endsection
