<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services   = Service::orderBy('order')->get();
        $keunggulan = \App\Models\Keunggulan::orderBy('order')->get();
        $settings   = \App\Models\Setting::pluck('value', 'key');
        $stats = [
            'total'        => $services->count(),
            'active'       => $services->where('is_active', true)->count(),
            'inactive'     => $services->where('is_active', false)->count(),
            'with_image'   => $services->filter(fn($s) => !empty($s->image))->count(),
            'with_gallery' => $services->filter(fn($s) => !empty($s->gallery) && count($s->gallery) > 0)->count(),
            'with_icon'    => $services->filter(fn($s) => !empty($s->icon))->count(),
        ];
        return view('admin.services.index', compact('services', 'settings', 'stats', 'keunggulan'));
    }

    public function updateIntro(Request $request)
    {
        $request->validate([
            'services_intro'            => 'nullable|string',
            'services_section_title'    => 'nullable|string|max:150',
            'services_section_subtitle' => 'nullable|string|max:200',
            'services_section_image'    => 'nullable|image|max:2048',
        ]);
        foreach (['services_intro', 'services_section_title', 'services_section_subtitle'] as $key) {
            \App\Models\Setting::set($key, $request->input($key));
        }
        // Handle image upload / delete
        if ($request->hasFile('services_section_image')) {
            $old = \App\Models\Setting::get('services_section_image');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('services_section_image')->store('settings', 'public');
            \App\Models\Setting::set('services_section_image', $path);
        } elseif ($request->boolean('services_section_image_delete')) {
            $old = \App\Models\Setting::get('services_section_image');
            if ($old) Storage::disk('public')->delete($old);
            \App\Models\Setting::set('services_section_image', null);
        }
        return redirect()->route('admin.services.index')->with('success', 'Deskripsi pembuka berhasil disimpan.');
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'icon'         => 'nullable|string|max:100',
            'image'        => 'nullable|image|max:2048',
            'gallery_new'  => 'nullable|array',
            'gallery_new.*'=> 'image|max:2048',
            'order'        => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery_new')) {
            foreach ($request->file('gallery_new') as $file) {
                $gallery[] = $file->store('services/gallery', 'public');
            }
        }
        $validated['gallery']   = $gallery ?: null;
        $validated['is_active'] = $request->boolean('is_active');
        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'icon'         => 'nullable|string|max:100',
            'image'        => 'nullable|image|max:2048',
            'gallery_new'  => 'nullable|array',
            'gallery_new.*'=> 'image|max:2048',
            'gallery_keep' => 'nullable|array',
            'order'        => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Kept existing + new uploads
        $kept = $request->input('gallery_keep', []);
        // Delete removed images
        foreach ($service->gallery ?? [] as $old) {
            if (!in_array($old, $kept)) Storage::disk('public')->delete($old);
        }
        $gallery = $kept;
        if ($request->hasFile('gallery_new')) {
            foreach ($request->file('gallery_new') as $file) {
                $gallery[] = $file->store('services/gallery', 'public');
            }
        }
        $validated['gallery']   = count($gallery) ? $gallery : null;
        $validated['is_active'] = $request->boolean('is_active');
        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) Storage::disk('public')->delete($service->image);
        foreach ($service->gallery ?? [] as $img) Storage::disk('public')->delete($img);
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
