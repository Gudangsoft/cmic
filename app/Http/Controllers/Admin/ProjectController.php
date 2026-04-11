<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JenisProyek;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['jenisProyek', 'clientModel'])->latest()->get();
        $settings = \App\Models\Setting::pluck('value', 'key');

        $stats = [
            'total'       => $projects->count(),
            'aktif'       => $projects->where('is_active', true)->count(),
            'nonaktif'    => $projects->where('is_active', false)->count(),
            'with_image'  => $projects->whereNotNull('image')->count(),
            'with_gallery'=> $projects->filter(fn($p) => !empty($p->gallery))->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats', 'settings'));
    }

    public function updateIntro(Request $request)
    {
        $request->validate([
            'pengalaman_section_title'    => 'nullable|string|max:150',
            'pengalaman_section_subtitle' => 'nullable|string',
            'pengalaman_section_image'    => 'nullable|image|max:2048',
        ]);
        foreach (['pengalaman_section_title', 'pengalaman_section_subtitle'] as $key) {
            \App\Models\Setting::set($key, $request->input($key));
        }
        if ($request->hasFile('pengalaman_section_image')) {
            $old = \App\Models\Setting::get('pengalaman_section_image');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('pengalaman_section_image')->store('settings', 'public');
            \App\Models\Setting::set('pengalaman_section_image', $path);
        } elseif ($request->boolean('pengalaman_section_image_delete')) {
            $old = \App\Models\Setting::get('pengalaman_section_image');
            if ($old) Storage::disk('public')->delete($old);
            \App\Models\Setting::set('pengalaman_section_image', null);
        }
        return redirect()->route('admin.projects.index')->with('success', 'Pengaturan halaman Pengalaman berhasil disimpan.');
    }

    public function export()
    {
        $projects = Project::with(['jenisProyek', 'clientModel'])->latest()->get();

        $filename = 'proyek_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($projects) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['No', 'Nama Proyek', 'Pemberi Tugas/Klien', 'Lokasi', 'Tahun', 'Bidang/Jenis', 'Deskripsi', 'Status', 'Tanggal Dibuat']);
            foreach ($projects as $i => $p) {
                fputcsv($out, [
                    $i + 1,
                    $p->name,
                    $p->clientModel?->name ?? $p->client ?? '',
                    $p->location ?? '',
                    $p->year ?? '',
                    $p->jenisProyek?->nama ?? $p->category ?? '',
                    $p->description ?? '',
                    $p->is_active ? 'Aktif' : 'Nonaktif',
                    $p->created_at->format('d/m/Y'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $clients  = Client::active()->orderBy('name')->get(['id', 'name']);
        $jenisAll = JenisProyek::orderBy('urutan')->orderBy('id')->get(['id', 'nama', 'warna']);
        return view('admin.projects.form', [
            'project'  => new Project(),
            'clients'  => $clients,
            'jenisAll' => $jenisAll,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'client_id'       => 'nullable|exists:clients,id',
            'client'          => 'nullable|string|max:255',
            'location'        => 'nullable|string|max:255',
            'year'            => 'nullable|integer|min:1990|max:2099',
            'jenis_proyek_id' => 'nullable|exists:jenis_proyek,id',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
            'gallery_new'     => 'nullable|array|max:10',
            'gallery_new.*'   => 'image|max:2048',
        ]);

        // Auto-fill client text from client model if chosen
        if ($request->filled('client_id')) {
            $c = Client::find($request->client_id);
            if ($c) $validated['client'] = $c->name;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery_new')) {
            foreach ($request->file('gallery_new') as $file) {
                $gallery[] = $file->store('projects/gallery', 'public');
            }
        }
        $validated['gallery']   = $gallery ?: null;
        $validated['is_active'] = $request->boolean('is_active');
        Project::create($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(Project $project)
    {
        $clients  = Client::active()->orderBy('name')->get(['id', 'name']);
        $jenisAll = JenisProyek::orderBy('urutan')->orderBy('id')->get(['id', 'nama', 'warna']);
        return view('admin.projects.form', compact('project', 'clients', 'jenisAll'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'client_id'       => 'nullable|exists:clients,id',
            'client'          => 'nullable|string|max:255',
            'location'        => 'nullable|string|max:255',
            'year'            => 'nullable|integer|min:1990|max:2099',
            'jenis_proyek_id' => 'nullable|exists:jenis_proyek,id',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
            'gallery_new'     => 'nullable|array',
            'gallery_new.*'   => 'image|max:2048',
            'gallery_keep'    => 'nullable|array',
        ]);

        if ($request->filled('client_id')) {
            $c = Client::find($request->client_id);
            if ($c) $validated['client'] = $c->name;
        }

        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $kept = $request->input('gallery_keep', []);
        foreach ($project->gallery ?? [] as $old) {
            if (!in_array($old, $kept)) Storage::disk('public')->delete($old);
        }
        $gallery = $kept;
        if ($request->hasFile('gallery_new')) {
            $remaining = 10 - count($gallery);
            foreach (array_slice($request->file('gallery_new'), 0, max(0, $remaining)) as $file) {
                $gallery[] = $file->store('projects/gallery', 'public');
            }
        }
        $validated['gallery']   = count($gallery) ? $gallery : null;
        $validated['is_active'] = $request->boolean('is_active');
        $project->update($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) Storage::disk('public')->delete($project->image);
        foreach ($project->gallery ?? [] as $img) Storage::disk('public')->delete($img);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}

