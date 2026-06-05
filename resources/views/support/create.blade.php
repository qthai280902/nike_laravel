@extends('layouts.app')

@section('title', 'Trung tâm hỗ trợ | Nike Hybrid')

@section('content')
@php($authUser = auth()->user())

<div class="min-h-[80vh] bg-nike-gray-100 py-12">
    <div class="mx-auto max-w-2xl px-4">
        <div class="mb-8">
            <h1 class="font-nike-display text-3xl font-extrabold uppercase tracking-tight text-nike-black">
                Trung tâm hỗ trợ
            </h1>
            <p class="font-nike-body mt-2 text-sm text-nike-gray-500">
                Gửi yêu cầu hỗ trợ cho đội ngũ Nike Hybrid.
            </p>
        </div>

        @if (session('success'))
            <div class="font-nike-body mb-6 border-l-4 border-emerald-500 bg-emerald-50 p-4 text-emerald-950 shadow-sm">
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="font-nike-body mb-6 border-l-4 border-rose-500 bg-rose-50 p-4 text-rose-950 shadow-sm">
                <p class="text-sm font-semibold">Đã xảy ra lỗi nhập liệu:</p>
                <ul class="ml-5 mt-2 list-disc space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="border border-nike-gray-200 bg-white p-8 shadow-sm">
            <form action="{{ route('support.store') }}" method="POST" class="font-nike-body space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-xs font-bold uppercase tracking-wider text-nike-black">
                            Họ và tên <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            class="w-full border border-nike-gray-300 px-4 py-3 text-sm transition-colors focus:border-nike-black focus:outline-none focus:ring-1 focus:ring-nike-black {{ $authUser ? 'bg-nike-gray-100 text-nike-gray-500' : '' }} @error('name') border-rose-500 @enderror"
                            placeholder="Nhập họ và tên của bạn"
                            value="{{ $authUser ? $authUser->name : old('name') }}"
                            {{ $authUser ? 'readonly' : '' }}
                            required>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-nike-black">
                            Địa chỉ email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            class="w-full border border-nike-gray-300 px-4 py-3 text-sm transition-colors focus:border-nike-black focus:outline-none focus:ring-1 focus:ring-nike-black {{ $authUser ? 'bg-nike-gray-100 text-nike-gray-500' : '' }} @error('email') border-rose-500 @enderror"
                            placeholder="username@domain.com"
                            value="{{ $authUser ? $authUser->email : old('email') }}"
                            {{ $authUser ? 'readonly' : '' }}
                            required>
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($authUser)
                    <p class="border border-nike-gray-200 bg-nike-snow px-4 py-3 text-xs font-bold uppercase tracking-wider text-nike-gray-500">
                        Thông tin liên hệ được lấy từ tài khoản của bạn.
                    </p>
                @endif

                <div>
                    <label for="subject" class="mb-2 block text-xs font-bold uppercase tracking-wider text-nike-black">
                        Tiêu đề yêu cầu <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject"
                        class="w-full border border-nike-gray-300 px-4 py-3 text-sm transition-colors focus:border-nike-black focus:outline-none focus:ring-1 focus:ring-nike-black @error('subject') border-rose-500 @enderror"
                        placeholder="Ví dụ: Lỗi thanh toán, tư vấn kích cỡ giày"
                        value="{{ old('subject') }}"
                        required>
                    @error('subject')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="mb-2 block text-xs font-bold uppercase tracking-wider text-nike-black">
                        Nội dung chi tiết <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="6"
                        class="w-full border border-nike-gray-300 px-4 py-3 text-sm transition-colors focus:border-nike-black focus:outline-none focus:ring-1 focus:ring-nike-black @error('message') border-rose-500 @enderror"
                        placeholder="Mô tả cụ thể vấn đề hoặc câu hỏi của bạn"
                        required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full rounded-full bg-nike-black py-4 text-sm font-bold uppercase tracking-widest text-white shadow-sm transition-colors hover:bg-nike-gray-800">
                        Gửi yêu cầu hỗ trợ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
