<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\JenisProyek;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries    = Gallery::orderBy('album')->orderBy('title')->orderBy('order')->get();
        $projects     = Project::where('is_active', true)->orderBy('name')->get(['id', 'name', 'image', 'gallery']);
        $jenisOptions = JenisProyek::orderBy('urutan')->orderBy('nama')->pluck('nama');
        return view('admin.galleries.index', compact('galleries', 'projects', 'jenisOptions'));
    }

    public function create()
    {
        $projects     = Project::where('is_active', true)->orderBy('name')->get(['id', 'name', 'image', 'gallery']);
        $albums       = Gallery::whereNotNull('album')->distinct()->orderBy('album')->pluck('album');
        $jenisOptions = JenisProyek::orderBy('urutan')->orderBy('nama')->pluck('nama');
        return view('admin.galleries.form', ['gallery' => new Gallery(), 'projects' => $projects, 'albums' => $albums, 'jenisOptions' => $jenisOptions]);
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'image');
        $rules = [
            'album'       => 'nullable|string|max:255',
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'order'       => 'integer|min:0',
            'type'        => 'required|in:image,video',
        ];
        if ($type === 'video') {
            $rules['video_url'] = 'required|url|max:500';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120';
        }
        $validated = $request->validate($rules);

        // Multi-photo pick from projects
        if ($type === 'image' && $request->has('selected_photos')) {
            $photos = array_filter((array) $request->input('selected_photos', []));
            if (count($photos)) {
                $imported = 0;
                foreach ($photos as $imgPath) {
                    $path = ltrim(str_replace(['..', '\\'], ['', '/'], $imgPath), '/');
                    if (!Storage::disk('public')->exists($path)) continue;
                    Gallery::create([
                        'album'       => $validated['album'] ?: null,
                        'title'       => $validated['title'],
                        'category'    => $validated['category'] ?? null,
                        'description' => $validated['description'] ?? null,
                        'type'        => 'image',
                        'image'       => $path,
                        'order'       => $validated['order'] ?? 0,
                        'is_active'   => $request->boolean('is_active'),
                    ]);
                    $imported++;
                }
                return redirect()->route('admin.galleries.index')
                    ->with('success', "{$imported} foto berhasil ditambahkan ke sub-album \"{$validated['title']}\".");
            }
        }

        if ($type === 'image') {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('galleries', 'public');
            } elseif ($request->filled('image_from_project')) {
                $path = ltrim(str_replace(['..', '\\'], ['', '/'], $request->input('image_from_project')), '/');
                $validated['image'] = Storage::disk('public')->exists($path) ? $path : null;
            } else {
                $validated['image'] = null;
            }
        } else {
            $validated['image'] = null;
        }
        $validated['is_active'] = $request->boolean('is_active');
        Gallery::create($validated);
        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(Gallery $gallery)
    {
        $projects     = Project::where('is_active', true)->orderBy('name')->get(['id', 'name', 'image', 'gallery']);
        $albums       = Gallery::whereNotNull('album')->distinct()->orderBy('album')->pluck('album');
        $jenisOptions = JenisProyek::orderBy('urutan')->orderBy('nama')->pluck('nama');
        return view('admin.galleries.form', compact('gallery', 'projects', 'albums', 'jenisOptions'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $type = $request->input('type', $gallery->type ?? 'image');
        $rules = [
            'album'       => 'nullable|string|max:255',
            'title'       => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'order'       => 'integer|min:0',
            'type'        => 'required|in:image,video',
        ];
        if ($type === 'image') {
            $rules['image'] = 'nullable|image|max:5120';
        } else {
            $rules['video_url'] = 'required|url|max:500';
        }
        $validated = $request->validate($rules);

        if ($type === 'image' && $request->hasFile('image')) {
            if ($gallery->image && str_starts_with($gallery->image, 'galleries/')) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $request->file('image')->store('galleries', 'public');
        } elseif ($type === 'image' && $request->filled('image_from_project')) {
            $path = ltrim(str_replace(['..', '\\'], ['', '/'], $request->input('image_from_project')), '/');
            if (Storage::disk('public')->exists($path)) {
                if ($gallery->image && str_starts_with($gallery->image, 'galleries/')) {
                    Storage::disk('public')->delete($gallery->image);
                }
                $validated['image'] = $path;
            }
        } elseif ($type === 'video') {
            if ($gallery->type === 'image' && $gallery->image && str_starts_with($gallery->image, 'galleries/')) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $gallery->update($validated);
        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && str_starts_with($gallery->image, 'galleries/')) {
            Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('success', 'Item galeri berhasil dihapus.');
    }

    public function importFromProject(Request $request)
    {
        $request->validate([
            'albums'             => 'required|array|min:1',
            'albums.*.album'     => 'required|string|max:255',
            'albums.*.title'     => 'required|string|max:255',
            'albums.*.category'  => 'nullable|string|max:100',
            'albums.*.paths'     => 'required|array|min:1',
            'albums.*.paths.*'   => 'required|string',
        ]);

        $imported = 0;
        foreach ($request->albums as $albumData) {
            foreach ($albumData['paths'] as $path) {
                $path = ltrim(str_replace(['..', '\\'], ['', '/'], $path), '/');
                if (!Storage::disk('public')->exists($path)) continue;
                Gallery::create([
                    'album'     => $albumData['album'],
                    'title'     => $albumData['title'],
                    'category'  => $albumData['category'] ?? null,
                    'type'      => 'image',
                    'image'     => $path,
                    'order'     => 0,
                    'is_active' => true,
                ]);
                $imported++;
            }
        }
        $albumCount = count($request->albums);
        return back()->with('success', $albumCount . ' sub-album (' . $imported . ' foto) berhasil diimpor.');
    }

    public function renameAlbum(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string|max:255',
            'new_name' => 'required|string|max:255',
        ]);
        Gallery::where('album', $request->old_name)->update(['album' => $request->new_name]);
        return back()->with('success', 'Album "' . $request->old_name . '" berhasil diubah menjadi "' . $request->new_name . '".');
    }

    public function destroyAlbum(Request $request)
    {
        $request->validate(['album_name' => 'required|string|max:255']);
        $items = Gallery::where('album', $request->album_name)->get();
        foreach ($items as $item) {
            if ($item->image && str_starts_with($item->image, 'galleries/')) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
        }
        return back()->with('success', 'Album "' . $request->album_name . '" dan semua isinya berhasil dihapus.');
    }
}