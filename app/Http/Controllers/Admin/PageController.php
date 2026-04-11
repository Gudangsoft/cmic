<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('order')->orderBy('title')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug',
            'content'          => 'nullable|string',
            'banner_image'     => 'nullable|image|max:2048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'order'            => 'required|integer|min:0',
            'is_active'        => 'boolean',
        ]);

        $data['slug']      = $data['slug'] ? Str::slug($data['slug']) : Page::generateUniqueSlug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('pages', 'public');
        }

        Page::create($data);
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content'          => 'nullable|string',
            'banner_image'     => 'nullable|image|max:2048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'order'            => 'required|integer|min:0',
            'is_active'        => 'boolean',
        ]);

        $data['slug']      = $data['slug'] ? Str::slug($data['slug']) : Page::generateUniqueSlug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('banner_image')) {
            if ($page->banner_image) Storage::disk('public')->delete($page->banner_image);
            $data['banner_image'] = $request->file('banner_image')->store('pages', 'public');
        }

        $page->update($data);
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        if ($page->banner_image) Storage::disk('public')->delete($page->banner_image);
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil dihapus.');
    }
}
