<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.about.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_about'      => 'nullable|string',
            'about_visi'         => 'nullable|string',
            'about_misi'         => 'nullable|string',
            'about_image'        => 'nullable|image|max:2048',
            'about_org_image'    => 'nullable|image|max:5120',
            'about_org_mode'     => 'nullable|in:image,manual',
            'about_org_template' => 'nullable|in:1,2,3,4,5',
            'org_nama'           => 'nullable|array',
            'org_jabatan'        => 'nullable|array',
            'org_level'          => 'nullable|array',
            'org_foto'           => 'nullable|array',
            'org_foto.*'         => 'nullable|image|max:2048',
            'org_foto_existing'  => 'nullable|array',
        ]);

        $textFields = [
            'company_about',
            'about_visi', 'about_misi',
        ];

        foreach ($textFields as $key) {
            Setting::set($key, $request->input($key));
        }

        if ($request->hasFile('about_image')) {
            $old = Setting::get('about_image');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('about_image', $request->file('about_image')->store('about', 'public'));
        }

        if ($request->hasFile('about_org_image')) {
            $old = Setting::get('about_org_image');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('about_org_image', $request->file('about_org_image')->store('about', 'public'));
        } elseif ($request->boolean('about_org_image_delete')) {
            $old = Setting::get('about_org_image');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('about_org_image', null);
        }

        // Org mode + manual data
        Setting::set('about_org_mode', $request->input('about_org_mode', 'image'));
        Setting::set('about_org_template', $request->input('about_org_template', '1'));
        $orgNames        = $request->input('org_nama', []);
        $orgJabatan      = $request->input('org_jabatan', []);
        $orgLevels       = $request->input('org_level', []);
        $orgFotoFiles    = $request->file('org_foto', []);
        $orgFotoExisting = $request->input('org_foto_existing', []);
        $orgData = [];
        foreach ($orgNames as $i => $nama) {
            if (trim($nama) !== '') {
                $fotoPath = $orgFotoExisting[$i] ?? null;
                if (!empty($orgFotoFiles[$i])) {
                    if ($fotoPath) Storage::disk('public')->delete($fotoPath);
                    $fotoPath = $orgFotoFiles[$i]->store('org-photos', 'public');
                }
                $orgData[] = [
                    'nama'    => trim($nama),
                    'jabatan' => trim($orgJabatan[$i] ?? ''),
                    'level'   => (int) ($orgLevels[$i] ?? 1),
                    'foto'    => $fotoPath ?: null,
                ];
            }
        }
        Setting::set('about_org_data', json_encode($orgData));

        return back()->with('success', 'Konten Tentang Kami berhasil disimpan.');
    }
}
