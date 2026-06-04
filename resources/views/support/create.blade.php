@extends('layouts.app')

@section('title', 'Trung tâm Hỗ trợ | Nike Hybrid')

@section('content')
<div class="py-12 bg-nike-gray-100 min-h-[80vh]">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Breadcrumb / Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold uppercase tracking-tight text-nike-black font-nike-display">
                Trung tâm Hỗ trợ
            </h1>
            <p class="text-sm text-nike-gray-500 mt-2 font-nike-body">
                Gửi yêu cầu hỗ trợ cho chúng tôi. Đội ngũ Nike Hybrid sẽ phản hồi bạn trong thời gian sớm nhất.
            </p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-950 p-4 mb-6 rounded shadow-sm font-nike-body">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-950 p-4 mb-6 rounded shadow-sm font-nike-body">
                <div class="flex mb-2">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold">Đã xảy ra lỗi nhập liệu:</p>
                    </div>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-8">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Support Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-nike-gray-200 p-8">
            <form action="{{ route('support.store') }}" method="POST" class="space-y-6 font-nike-body">
                @csrf

                <!-- Name & Email fields in a grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-nike-black mb-2">
                            Họ và tên <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                            class="w-full px-4 py-3 rounded-lg border border-nike-gray-300 focus:outline-none focus:border-nike-black focus:ring-1 focus:ring-nike-black text-sm transition-colors @error('name') border-rose-500 @enderror"
                            placeholder="Nhập họ và tên của bạn"
                            value="{{ old('name', auth()->user()->name ?? '') }}" required>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-nike-black mb-2">
                            Địa chỉ Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" 
                            class="w-full px-4 py-3 rounded-lg border border-nike-gray-300 focus:outline-none focus:border-nike-black focus:ring-1 focus:ring-nike-black text-sm transition-colors @error('email') border-rose-500 @enderror"
                            placeholder="username@domain.com"
                            value="{{ old('email', auth()->user()->email ?? '') }}" required>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Subject field -->
                <div>
                    <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-nike-black mb-2">
                        Tiêu đề yêu cầu <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" 
                        class="w-full px-4 py-3 rounded-lg border border-nike-gray-300 focus:outline-none focus:border-nike-black focus:ring-1 focus:ring-nike-black text-sm transition-colors @error('subject') border-rose-500 @enderror"
                        placeholder="Ví dụ: Lỗi thanh toán, Tư vấn kích cỡ giày..."
                        value="{{ old('subject') }}" required>
                    @error('subject')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message field -->
                <div>
                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-nike-black mb-2">
                        Nội dung chi tiết <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="6" 
                        class="w-full px-4 py-3 rounded-lg border border-nike-gray-300 focus:outline-none focus:border-nike-black focus:ring-1 focus:ring-nike-black text-sm transition-colors @error('message') border-rose-500 @enderror"
                        placeholder="Mô tả cụ thể vấn đề hoặc câu hỏi của bạn..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-nike-black text-white hover:bg-nike-gray-800 font-bold uppercase py-4 rounded-full transition-colors text-sm tracking-widest shadow-sm">
                        Gửi yêu cầu hỗ trợ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
