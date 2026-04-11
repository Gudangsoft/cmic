<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $members  = TeamMember::orderBy('order')->get();
        $settings = \App\Models\Setting::pluck('value', 'key');
        return view('admin.team.index', compact('members', 'settings'));
    }

    public function updateIntro(Request $request)
    {
        $request->validate([
            'team_section_title'    => 'nullable|string|max:150',
            'team_section_subtitle' => 'nullable|string|max:255',
        ]);
        foreach (['team_section_title', 'team_section_subtitle'] as $key) {
            \App\Models\Setting::set($key, $request->input($key));
        }
        return redirect()->route('admin.team.index')->with('success', 'Pengaturan section SDM berhasil disimpan.');
    }

    public function create()
    {
        return view('admin.team.form', ['member' => new TeamMember()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'section'     => 'required|string|max:100',
            'position'    => 'required|string|max:255',
            'education'   => 'nullable|string|max:255',
            'expertise'   => 'nullable|string',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
            'order'       => 'integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        TeamMember::create($validated);
        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(TeamMember $team)
    {
        return view('admin.team.form', ['member' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'section'     => 'required|string|max:100',
            'position'    => 'required|string|max:255',
            'education'   => 'nullable|string|max:255',
            'expertise'   => 'nullable|string',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
            'order'       => 'integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            if ($team->photo) Storage::disk('public')->delete($team->photo);
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $team->update($validated);
        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $team)
    {
        if ($team->photo) Storage::disk('public')->delete($team->photo);
        $team->delete();
        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
