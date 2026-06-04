<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="title" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Tiêu đề *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}" 
                class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('title') border-red-500 @enderror" required>
            @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="slug" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Đường dẫn (Slug - Để trống tự sinh)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug ?? '') }}" 
                class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('slug') border-red-500 @enderror">
            @error('slug')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="image_url" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Đường dẫn ảnh (URL)</label>
            <input type="text" name="image_url" id="image_url" value="{{ old('image_url', $article->image_url ?? '') }}" 
                class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('image_url') border-red-500 @enderror">
            @error('image_url')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="position" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Thứ tự hiển thị (Vị trí) *</label>
            <input type="number" name="position" id="position" value="{{ old('position', $article->position ?? 0) }}" 
                class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('position') border-red-500 @enderror" required>
            @error('position')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="excerpt" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Mô tả ngắn (Tóm tắt)</label>
        <textarea name="excerpt" id="excerpt" rows="3" 
            class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
        @error('excerpt')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="body" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Nội dung chi tiết *</label>
        <textarea name="body" id="body" rows="10" 
            class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('body') border-red-500 @enderror" required>{{ old('body', $article->body ?? '') }}</textarea>
        @error('body')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <div class="flex items-center space-x-3">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" 
                class="w-4 h-4 rounded bg-zinc-950 border-zinc-800 text-white focus:ring-0 focus:ring-offset-0" 
                {{ old('is_published', $article->is_published ?? true) ? 'checked' : '' }}>
            <label for="is_published" class="text-xs font-bold uppercase text-zinc-400 tracking-wider">Xuất bản bài viết</label>
        </div>

        <div>
            <label for="published_at" class="block text-xs font-bold uppercase text-zinc-400 tracking-wider mb-2">Hẹn ngày xuất bản (Hành động: Xuất bản ngay nếu trống)</label>
            <input type="datetime-local" name="published_at" id="published_at" 
                value="{{ old('published_at', isset($article->published_at) ? $article->published_at->format('Y-m-d\TH:i') : '') }}" 
                class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-zinc-700 transition-all @error('published_at') border-red-500 @enderror">
            @error('published_at')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
