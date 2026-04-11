<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisProyek;
use App\Models\PengalamanProyek;
use Illuminate\Http\Request;

class JenisProyekController extends Controller
{
    public function index()
    {
        $jenis = JenisProyek::withCount(['pengalamans', 'pengalamanAktif'])->orderBy('urutan')->orderBy('id')->get();

        $stats = [
            'total'          => $jenis->count(),
            'aktif'          => $jenis->where('is_active', true)->count(),
            'nonaktif'       => $jenis->where('is_active', false)->count(),
            'total_item'     => PengalamanProyek::count(),
            'total_aktif_item' => PengalamanProyek::where('is_active', true)->count(),
        ];

        return view('admin.jenis-proyek.index', compact('jenis', 'stats'));
    }

    public function create()
    {
        return view('admin.jenis-proyek.form', ['item' => new JenisProyek()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:200',
            'warna'     => 'required|string|max:30',
            'ikon'      => 'nullable|string|max:80',
            'urutan'    => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['urutan']     = $request->input('urutan', 0);
        $validated['ikon']       = $request->input('ikon', 'fas fa-folder');

        JenisProyek::create($validated);

        return redirect()->route('admin.jenis-proyek.index')->with('success', 'Jenis proyek berhasil ditambahkan.');
    }

    public function edit(JenisProyek $jenisProyek)
    {
        $pengalamans = $jenisProyek->pengalamans()->get();
        return view('admin.jenis-proyek.form', ['item' => $jenisProyek, 'pengalamans' => $pengalamans]);
    }

    public function update(Request $request, JenisProyek $jenisProyek)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:200',
            'warna'     => 'required|string|max:30',
            'ikon'      => 'nullable|string|max:80',
            'urutan'    => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['urutan']    = $request->input('urutan', 0);
        $validated['ikon']      = $request->input('ikon', 'fas fa-folder');

        $jenisProyek->update($validated);

        return redirect()->route('admin.jenis-proyek.index')->with('success', 'Jenis proyek berhasil diperbarui.');
    }

    public function destroy(JenisProyek $jenisProyek)
    {
        $jenisProyek->delete();
        return redirect()->route('admin.jenis-proyek.index')->with('success', 'Jenis proyek dihapus.');
    }

    public function toggleActive(JenisProyek $jenisProyek)
    {
        $jenisProyek->update(['is_active' => !$jenisProyek->is_active]);
        return response()->json(['is_active' => $jenisProyek->is_active]);
    }
}
