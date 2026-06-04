<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LandingArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $articles = LandingArticle::orderBy('position', 'asc')
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return view('admin.landing_articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.landing_articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $slug = $request->input('slug');
        if (empty($slug)) {
            $slug = Str::slug($request->input('title'));
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_articles,slug',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'image_url' => 'nullable|string|max:255',
            'position' => 'required|integer',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'slug.required' => 'Đường dẫn (slug) không được để trống.',
            'slug.unique' => 'Đường dẫn (slug) này đã tồn tại.',
            'body.required' => 'Nội dung không được để trống.',
            'position.required' => 'Vị trí hiển thị không được để trống.',
            'position.integer' => 'Vị trí hiển thị phải là số nguyên.',
        ]);

        $isPublished = $request->boolean('is_published');
        $publishedAt = $request->input('published_at');

        if ($isPublished && empty($publishedAt)) {
            $publishedAt = now();
        } elseif (! $isPublished && empty($publishedAt)) {
            $publishedAt = null;
        }

        LandingArticle::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'image_url' => $validated['image_url'] ?? null,
            'position' => $validated['position'],
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.landing-articles.index')
            ->with('success', 'Tạo bài viết trang chủ mới thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LandingArticle $landingArticle): View
    {
        return view('admin.landing_articles.edit', compact('landingArticle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LandingArticle $landingArticle): RedirectResponse
    {
        $slug = $request->input('slug');
        if (empty($slug)) {
            $slug = Str::slug($request->input('title'));
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_articles,slug,'.$landingArticle->id,
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'image_url' => 'nullable|string|max:255',
            'position' => 'required|integer',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'slug.required' => 'Đường dẫn (slug) không được để trống.',
            'slug.unique' => 'Đường dẫn (slug) này đã tồn tại.',
            'body.required' => 'Nội dung không được để trống.',
            'position.required' => 'Vị trí hiển thị không được để trống.',
            'position.integer' => 'Vị trí hiển thị phải là số nguyên.',
        ]);

        $isPublished = $request->boolean('is_published');
        $publishedAt = $request->input('published_at');

        if ($isPublished && empty($publishedAt)) {
            $publishedAt = now();
        } elseif (! $isPublished && empty($publishedAt)) {
            $publishedAt = null;
        }

        $landingArticle->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'image_url' => $validated['image_url'] ?? null,
            'position' => $validated['position'],
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.landing-articles.index')
            ->with('success', 'Cập nhật bài viết trang chủ thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LandingArticle $landingArticle): RedirectResponse
    {
        $landingArticle->delete();

        return redirect()->route('admin.landing-articles.index')
            ->with('success', 'Xóa bài viết thành công.');
    }
}
