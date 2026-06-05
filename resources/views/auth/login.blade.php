<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Đăng nhập | Nike Hybrid</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white">
        <div class="flex min-h-screen">
            {{-- Left Side: Branding/Image --}}
            <div class="hidden lg:flex w-1/2 bg-nike-black items-center justify-center p-24">
                <div class="max-w-md">
                    <svg class="w-32 h-32 fill-white mb-12" viewBox="0 0 24 24"><path d="M21 8.75c0 0-4.5 6-12.5 6s-6.5-2-6.5-2 1.5 4 7.5 4 11.5-8 11.5-8z"/></svg>
                    <h1 class="text-6xl font-nike-display text-white uppercase leading-tight mb-8">Trở thành thành viên để nhận ưu đãi tốt hơn.</h1>
                    <p class="text-nike-gray-500 font-nike-body text-lg">Vận động, mua sắm và kỷ niệm với những sản phẩm tốt nhất từ Nike.</p>
                </div>
            </div>

            {{-- Right Side: Form --}}
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-24">
                <div class="w-full max-w-sm">
                    <h2 class="text-3xl font-nike-display uppercase mb-10">Nhập email của bạn để tham gia hoặc đăng nhập.</h2>
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-nike-black text-white text-xs font-bold uppercase tracking-widest rounded flex items-center space-x-3">
                            <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div>
                            <input type="text" name="email" placeholder="Email hoặc Tên đăng nhập" required autofocus class="w-full bg-transparent border-b border-nike-gray-200 py-4 focus:border-nike-black focus:outline-none font-nike-body transition-colors">
                            @error('email') <p class="text-nike-red text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <input type="password" name="password" placeholder="Mật khẩu" required class="w-full bg-transparent border-b border-nike-gray-200 py-4 focus:border-nike-black focus:outline-none font-nike-body transition-colors">
                        </div>

                        <div class="flex items-center justify-between text-xs text-nike-gray-500 font-nike-body">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="mr-2 accent-nike-black"> Duy trì đăng nhập
                            </label>
                            <span>Quên mật khẩu?</span>
                        </div>

                        <p class="text-[10px] text-nike-gray-500 leading-relaxed py-4">
                            Bằng cách tiếp tục, tôi đồng ý với Chính sách Bảo mật và Điều khoản Sử dụng của Nike.
                        </p>

                        <button type="submit" class="w-full bg-nike-black text-white py-4 rounded-full font-bold uppercase text-xs tracking-widest hover:bg-nike-gray-800 transition-colors">
                            Đăng nhập
                        </button>
                    </form>

                    <p class="mt-12 text-sm text-nike-gray-500 font-nike-body text-center">
                        Chưa là thành viên? <a href="{{ route('register') }}" class="text-nike-black font-medium underline">Tham gia ngay.</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
