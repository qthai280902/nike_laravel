@extends('layouts.app')

@section('title', 'Câu chuyện Nike | Nike Hybrid')

@section('content')
<section class="max-w-[1920px] mx-auto bg-black text-white font-nike-body">
    {{-- Hero Banner --}}
    <div class="px-6 md:px-12 py-32 border-b border-zinc-800 relative overflow-hidden flex flex-col justify-end min-h-[70vh]">
        <div class="relative z-10 max-w-4xl">
            <span class="text-xs font-black tracking-[0.4em] uppercase text-zinc-500 mb-6 block animate-[fade-in-up_0.6s_forwards]">Di sản & Khát vọng</span>
            <h1 class="text-7xl md:text-9xl font-black tracking-tighter leading-none mb-12 uppercase animate-[fade-in-up_0.8s_forwards_0.2s]">
                CÂU CHUYỆN.
            </h1>
            <p class="text-2xl md:text-3xl font-medium tracking-tight leading-relaxed max-w-2xl text-zinc-300 animate-[fade-in-up_1s_forwards_0.4s]">
                "Nếu bạn có một cơ thể, bạn là một vận động viên."
            </p>
        </div>
        <div class="absolute right-0 bottom-0 translate-y-1/4 translate-x-1/4 opacity-[0.03] pointer-events-none">
            <svg class="w-[900px] h-[900px]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 8.719L7.836 14.303C6.74 14.768 5.818 15 5.075 15c-.836 0-1.445-.295-1.819-.884-.485-.76-.273-1.982.559-3.272.494-.754 1.122-1.446 1.734-2.108-.144.234-1.415 2.349-.025 3.345.275.2.666.298 1.147.298.386 0 .829-.063 1.316-.19L21 8.719z"></path>
            </svg>
        </div>
    </div>

    {{-- Timeline and Mission Grid --}}
    <div class="px-6 md:px-12 py-32 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 items-start">
            <div class="lg:col-span-1 lg:sticky lg:top-24">
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-6 text-white">Hành trình<br>Vượt giới hạn.</h2>
                <p class="text-zinc-400 font-medium leading-relaxed">
                    Từ nguồn cảm hứng nhỏ nhoi của khuôn bánh waffle cho tới những đôi giày phá vỡ các kỷ lục marathon thế giới, Nike luôn đồng hành cùng các vận động viên để biến điều không thể thành có thể.
                </p>
            </div>
            
            <div class="lg:col-span-2 space-y-24">
                {{-- Milestone 1 --}}
                <div class="border-t border-zinc-800 pt-12 flex flex-col md:flex-row gap-8">
                    <span class="text-5xl font-black text-zinc-800 tracking-tighter md:w-32 flex-shrink-0">1964</span>
                    <div>
                        <h3 class="text-2xl font-bold uppercase tracking-tight text-white mb-4">Blue Ribbon Sports được thành lập</h3>
                        <p class="text-zinc-400 leading-relaxed">
                            Khởi đầu bởi Bill Bowerman và Phil Knight với mục tiêu cung cấp những đôi giày chạy bộ chất lượng cao từ Nhật Bản. Niềm đam mê chạy bộ và cải tiến đã đặt những viên gạch nền móng đầu tiên cho đế chế Nike sau này.
                        </p>
                    </div>
                </div>

                {{-- Milestone 2 --}}
                <div class="border-t border-zinc-800 pt-12 flex flex-col md:flex-row gap-8">
                    <span class="text-5xl font-black text-zinc-800 tracking-tighter md:w-32 flex-shrink-0">1971</span>
                    <div>
                        <h3 class="text-2xl font-bold uppercase tracking-tight text-white mb-4">Sự trỗi dậy của Swoosh</h3>
                        <p class="text-zinc-400 leading-relaxed">
                            Biểu tượng Swoosh huyền thoại được thiết kế bởi Carolyn Davidson chính thức được ra mắt, thể hiện tốc độ, chuyển động và đôi cánh của nữ thần chiến thắng Nike trong thần thoại Hy Lạp.
                        </p>
                    </div>
                </div>

                {{-- Milestone 3 --}}
                <div class="border-t border-zinc-800 pt-12 flex flex-col md:flex-row gap-8">
                    <span class="text-5xl font-black text-zinc-800 tracking-tighter md:w-32 flex-shrink-0">1988</span>
                    <div>
                        <h3 class="text-2xl font-bold uppercase tracking-tight text-white mb-4">Khẩu hiệu "Just Do It" ra đời</h3>
                        <p class="text-zinc-400 leading-relaxed">
                            Chỉ với ba chữ đơn giản nhưng đầy năng lượng, "Just Do It" không chỉ là một chiến dịch quảng cáo, nó đã trở thành kim chỉ nam hành động của cả một thế hệ yêu thích vận động và thể thao toàn cầu.
                        </p>
                    </div>
                </div>

                {{-- Milestone 4 --}}
                <div class="border-t border-zinc-800 pt-12 flex flex-col md:flex-row gap-8">
                    <span class="text-5xl font-black text-zinc-800 tracking-tighter md:w-32 flex-shrink-0">Hiện tại</span>
                    <div>
                        <h3 class="text-2xl font-bold uppercase tracking-tight text-white mb-4">Hệ sinh thái Nike Hybrid</h3>
                        <p class="text-zinc-400 leading-relaxed">
                            Nơi tiếp nối di sản lâu đời của Nike với mô hình phân phối thương mại điện tử đột phá, kết nối hoàn hảo luồng mua sắm chính hãng B2C và thị trường đồ cũ C2C an toàn và tin cậy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
