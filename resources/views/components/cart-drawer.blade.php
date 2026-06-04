<div id="cart-drawer-overlay" class="fixed inset-0 bg-black/50 z-[60] hidden opacity-0 transition-opacity duration-300" onclick="toggleCart()"></div>

<div id="cart-drawer" class="fixed top-0 right-0 h-full w-full md:w-[400px] bg-white z-[70] translate-x-full transition-transform duration-300 ease-in-out shadow-[-10px_0_30px_rgba(0,0,0,0.1)]">
    <div class="flex flex-col h-full h-screen">
        {{-- Header --}}
        <div class="px-6 py-6 border-b border-nike-gray-200 flex justify-between items-center bg-white">
            <h2 class="text-xl font-nike-display uppercase">Giỏ hàng</h2>
            <button onclick="toggleCart()" class="p-2 hover:bg-nike-gray-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Dynamic Content Wrapper --}}
        <div id="cart-fragment-container" class="flex-grow overflow-hidden">
            @include('components.cart-items-fragment')
        </div>
    </div>
</div>

<script>
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
</script>
