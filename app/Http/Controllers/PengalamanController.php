<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\JenisProyek;
use App\Models\Setting;
use Illuminate\Http\Request;

class PengalamanController extends Controller
{
    public function index()
    {
        $jenisProyeks     = JenisProyek::active()->withCount('activeProjects')->get();
        $pengalamanSettings = [
            'pengalaman_section_title'    => Setting::get('pengalaman_section_title', 'Bidang Pengalaman Kami'),
            'pengalaman_section_subtitle' => Setting::get('pengalaman_section_subtitle', 'Pilih bidang pengalaman di bawah ini untuk melihat daftar pekerjaan yang telah kami kerjakan.'),
            'pengalaman_section_image'    => Setting::get('pengalaman_section_image'),
            'company_logo'                => Setting::get('company_logo'),
        ];
        return view('frontend.pengalaman', compact('jenisProyeks', 'pengalamanSettings'));
    }

    public function showJenis(JenisProyek $jenisProyek)
    {
        abort_unless($jenisProyek->is_active, 404);

        $allJenis = JenisProyek::active()->withCount('activeProjects')->get();

        $projects = Project::where('jenis_proyek_id', $jenisProyek->id)
            ->where('is_active', true)
            ->with('clientModel')
            ->orderByDesc('year')
            ->latest()
            ->get();

        // Bangun galeri dari semua foto proyek di bidang ini
        $allPhotos = collect();
        foreach ($projects as $p) {
            if ($p->image) $allPhotos->push(['src' => asset('storage/' . $p->image), 'caption' => $p->name]);
            foreach ($p->gallery ?? [] as $g) {
                $allPhotos->push(['src' => asset('storage/' . $g), 'caption' => $p->name]);
            }
        }
        $allPhotosSlice = $allPhotos->take(12)->values();

        return view('frontend.pengalaman_jenis', compact('jenisProyek', 'allJenis', 'projects', 'allPhotos', 'allPhotosSlice'));
    }

    public function show(Project $project)
    {
        abort_unless($project->is_active, 404);
        $project->load('jenisProyek', 'clientModel');
        $related = Project::active()
            ->where('id', '!=', $project->id)
            ->where(function($q) use ($project) {
                if ($project->jenis_proyek_id) {
                    $q->where('jenis_proyek_id', $project->jenis_proyek_id);
                } else {
                    $q->where('category', $project->category);
                }
            })
            ->limit(3)
            ->get();
        return view('frontend.pengalaman_detail', compact('project', 'related'));
    }

    public function showItem(\App\Models\PengalamanProyek $pengalamanProyek)
    {
        // Setelah migrasi, semua item sudah memiliki project_id — redirect ke halaman proyek
        if ($pengalamanProyek->project_id) {
            return redirect()->route('pengalaman.show', $pengalamanProyek->project_id, 301);
        }

        abort_unless($pengalamanProyek->is_active, 404);
        $pengalamanProyek->load('jenis');
        $allJenis = JenisProyek::active()->get();
        $related  = \App\Models\PengalamanProyek::active()
            ->where('jenis_proyek_id', $pengalamanProyek->jenis_proyek_id)
            ->where('id', '!=', $pengalamanProyek->id)
            ->limit(6)->get();
        return view('frontend.pengalaman_item', compact('pengalamanProyek', 'allJenis', 'related'));
    }
}
