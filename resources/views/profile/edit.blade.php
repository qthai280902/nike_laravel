@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ | Nike Hybrid')

@section('content')
@php($avatarUrl = $user->avatar_display_url)

<section class="mx-auto max-w-[1200px] px-6 py-16 md:px-12 md:py-24">
    <div class="mb-10">
        <a href="{{ route('profile.index') }}" class="text-xs font-black uppercase tracking-widest text-nike-gray-500 underline-offset-4 hover:text-nike-black hover:underline">
            Quay lại hồ sơ
        </a>
        <h1 class="mt-6 text-4xl font-black uppercase leading-none tracking-tight text-nike-black md:text-7xl">
            Chỉnh sửa hồ sơ
        </h1>
    </div>

    @if($errors->any())
        <div class="mb-8 border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-800">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-10 lg:grid-cols-12">
        @csrf
        @method('PATCH')

        <div class="lg:col-span-4">
            <div class="border border-nike-gray-150 bg-nike-snow p-6">
                <div class="mx-auto h-40 w-40 overflow-hidden rounded-full bg-nike-black text-white">
                    @if($avatarUrl)
                        <img id="avatar-preview" src="{{ $avatarUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/hero.png') }}'" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <span id="avatar-fallback" class="flex h-full w-full items-center justify-center text-4xl font-black uppercase">{{ $user->initials }}</span>
                        <img id="avatar-preview" src="{{ asset('images/hero.png') }}" alt="{{ $user->name }}" class="hidden h-full w-full object-cover">
                    @endif
                </div>

                <label for="avatar_file" class="mt-8 flex cursor-pointer items-center justify-center rounded-full border border-nike-black bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-nike-black transition hover:bg-nike-black hover:text-white">
                    Chọn avatar
                </label>
                <input id="avatar_file" name="avatar_file" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only">
                <p class="mt-3 text-center text-[10px] font-bold uppercase tracking-widest text-nike-gray-400">JPG, PNG, WEBP tối đa 4MB</p>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-8">
            <div>
                <label for="name" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-500">Họ và tên</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                    class="w-full border border-nike-gray-200 bg-white px-5 py-4 text-lg font-bold text-nike-black outline-none transition focus:border-nike-black">
            </div>

            <div>
                <label for="email" class="mb-2 block text-[10px] font-black uppercase tracking-widest text-nike-gray-500">Email</label>
                <input id="email" type="email" value="{{ $user->email }}" readonly
                    class="w-full border border-nike-gray-200 bg-nike-snow px-5 py-4 text-lg font-bold text-nike-gray-500 outline-none">
            </div>

            <div class="flex flex-col gap-3 md:flex-row">
                <button type="submit" class="inline-flex justify-center rounded-full bg-nike-black px-8 py-4 text-xs font-black uppercase tracking-widest text-white transition hover:bg-nike-gray-800">
                    Lưu hồ sơ
                </button>
                <a href="{{ route('profile.index') }}" class="inline-flex justify-center rounded-full border border-nike-gray-300 bg-white px-8 py-4 text-xs font-black uppercase tracking-widest text-nike-black transition hover:bg-nike-gray-100">
                    Hủy
                </a>
            </div>
        </div>
    </form>
</section>

<script>
    document.getElementById('avatar_file')?.addEventListener('change', function () {
        const [file] = this.files;
        const preview = document.getElementById('avatar-preview');
        const fallback = document.getElementById('avatar-fallback');

        if (!file || !preview) {
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        fallback?.classList.add('hidden');
    });
</script>
@endsection
