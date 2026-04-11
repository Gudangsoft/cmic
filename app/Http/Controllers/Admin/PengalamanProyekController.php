<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisProyek;
use App\Models\PengalamanProyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengalamanProyekController extends Controller
{
    public function index(Request $request)
    {
        $jenisId  = $request->query('jenis');
        $jenisAll = JenisProyek::orderBy('urutan')->orderBy('id')->get();
        $settings = \App\Models\Setting::pluck('value', 'key');

        $query = PengalamanProyek::with('jenis')->orderBy('jenis_proyek_id')->orderBy('urutan')->orderBy('id');
        if ($jenisId) {
            $query->where('jenis_proyek_id', $jenisId);
        }
        $items = $query->get();

        $stats = [
            'total'    => PengalamanProyek::count(),
            'aktif'    => PengalamanProyek::where('is_active', true)->count(),
            'nonaktif' => PengalamanProyek::where('is_active', false)->count(),
        ];

        return view('admin.pengalaman-proyek.index', compact('items', 'jenisAll', 'jenisId', 'stats', 'settings'));
    }

    public function updateIntro(Request $request)
    {
        $request->validate([
            'pengalaman_section_title'    => 'nullable|string|max:150',
            'pengalaman_section_subtitle' => 'nullable|string|max:255',
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
        return redirect()->route('admin.pengalaman-proyek.index')->with('success', 'Pengaturan halaman Pengalaman berhasil disimpan.');
    }

    public function create(Request $request)
    {
        $jenisAll  = JenisProyek::orderBy('urutan')->orderBy('id')->get();
        $jenisId   = $request->query('jenis');
        $projectAll = \App\Models\Project::active()->orderBy('name')->get(['id','name']);
        return view('admin.pengalaman-proyek.form', [
            'item'       => new PengalamanProyek(),
            'jenisAll'   => $jenisAll,
            'jenisId'    => $jenisId,
            'projectAll' => $projectAll,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_proyek_id' => 'required|exists:jenis_proyek,id',
            'project_id'      => 'nullable|exists:projects,id',
            'nama'            => 'required|string|max:400',
            'deskripsi'       => 'nullable|string',
            'gambar'          => 'nullable|image|max:2048',
            'galeri_new'      => 'nullable|array|max:10',
            'galeri_new.*'    => 'image|max:2048',
            'lokasi'          => 'nullable|string|max:255',
            'tahun'           => 'nullable|integer|min:1990|max:2099',
            'pemberi_tugas'   => 'nullable|string|max:255',
            'urutan'          => 'nullable|integer|min:0',
            'is_active'       => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('pengalaman', 'public');
        }
        $galeri = [];
        if ($request->hasFile('galeri_new')) {
            foreach ($request->file('galeri_new') as $file) {
                $galeri[] = $file->store('pengalaman/galeri', 'public');
            }
        }
        $validated['galeri']    = $galeri ?: null;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['urutan']    = $request->input('urutan', 0);

        PengalamanProyek::create($validated);

        $jenisId = $request->input('jenis_proyek_id');
        return redirect()->route('admin.pengalaman-proyek.index', ['jenis' => $jenisId])
            ->with('success', 'Item pengalaman berhasil ditambahkan.');
    }

    public function edit(PengalamanProyek $pengalamanProyek)
    {
        $jenisAll   = JenisProyek::orderBy('urutan')->orderBy('id')->get();
        $projectAll = \App\Models\Project::active()->orderBy('name')->get(['id','name']);
        return view('admin.pengalaman-proyek.form', [
            'item'       => $pengalamanProyek,
            'jenisAll'   => $jenisAll,
            'jenisId'    => $pengalamanProyek->jenis_proyek_id,
            'projectAll' => $projectAll,
        ]);
    }

    public function update(Request $request, PengalamanProyek $pengalamanProyek)
    {
        $validated = $request->validate([
            'jenis_proyek_id' => 'required|exists:jenis_proyek,id',
            'project_id'      => 'nullable|exists:projects,id',
            'nama'            => 'required|string|max:400',
            'deskripsi'       => 'nullable|string',
            'gambar'          => 'nullable|image|max:2048',
            'galeri_new'      => 'nullable|array',
            'galeri_new.*'    => 'image|max:2048',
            'galeri_keep'     => 'nullable|array',
            'lokasi'          => 'nullable|string|max:255',
            'tahun'           => 'nullable|integer|min:1990|max:2099',
            'pemberi_tugas'   => 'nullable|string|max:255',
            'urutan'          => 'nullable|integer|min:0',
            'is_active'       => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            if ($pengalamanProyek->gambar) Storage::disk('public')->delete($pengalamanProyek->gambar);
            $validated['gambar'] = $request->file('gambar')->store('pengalaman', 'public');
        }
        $kept = $request->input('galeri_keep', []);
        foreach ($pengalamanProyek->galeri ?? [] as $old) {
            if (!in_array($old, $kept)) Storage::disk('public')->delete($old);
        }
        $galeri = $kept;
        if ($request->hasFile('galeri_new')) {
            foreach (array_slice($request->file('galeri_new'), 0, max(0, 10 - count($galeri))) as $file) {
                $galeri[] = $file->store('pengalaman/galeri', 'public');
            }
        }
        $validated['galeri']    = count($galeri) ? $galeri : null;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['urutan']    = $request->input('urutan', 0);

        $pengalamanProyek->update($validated);

        return redirect()->route('admin.pengalaman-proyek.index', ['jenis' => $pengalamanProyek->jenis_proyek_id])
            ->with('success', 'Item pengalaman berhasil diperbarui.');
    }

    public function destroy(PengalamanProyek $pengalamanProyek)
    {
        $jenisId = $pengalamanProyek->jenis_proyek_id;
        if ($pengalamanProyek->gambar) Storage::disk('public')->delete($pengalamanProyek->gambar);
        foreach ($pengalamanProyek->galeri ?? [] as $img) Storage::disk('public')->delete($img);
        $pengalamanProyek->delete();
        return redirect()->route('admin.pengalaman-proyek.index', ['jenis' => $jenisId])
            ->with('success', 'Item pengalaman dihapus.');
    }

    public function toggleActive(PengalamanProyek $pengalamanProyek)
    {
        $pengalamanProyek->update(['is_active' => !$pengalamanProyek->is_active]);
        return response()->json(['is_active' => $pengalamanProyek->is_active]);
    }

    /**
     * Quick-store via AJAX (inline from JenisProyek manage page)
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'jenis_proyek_id' => 'required|exists:jenis_proyek,id',
            'nama'            => 'required|string|max:400',
        ]);

        $item = PengalamanProyek::create([
            'jenis_proyek_id' => $validated['jenis_proyek_id'],
            'nama'            => $validated['nama'],
            'urutan'          => PengalamanProyek::where('jenis_proyek_id', $validated['jenis_proyek_id'])->max('urutan') + 1,
            'is_active'       => true,
        ]);

        $item->load('jenis');
        return response()->json(['success' => true, 'item' => $item]);
    }

    /**
     * Quick-delete via AJAX
     */
    public function quickDestroy(PengalamanProyek $pengalamanProyek)
    {
        $pengalamanProyek->delete();
        return response()->json(['success' => true]);
    }
}
