<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keunggulan;
use Illuminate\Http\Request;

class KeunggulanController extends Controller
{
    public function index()
    {
        $items = Keunggulan::orderBy('order')->get();
        $stats = [
            'total'    => $items->count(),
            'active'   => $items->where('is_active', true)->count(),
            'inactive' => $items->where('is_active', false)->count(),
        ];
        return view('admin.keunggulan.index', compact('items', 'stats'));
    }

    public function create()
    {
        return view('admin.keunggulan.form', ['item' => new Keunggulan()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'       => 'required|string|max:100',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'order'       => 'integer|min:0',
        ]);

        Keunggulan::create([
            'label'       => $request->label,
            'icon'        => $request->input('icon', 'fas fa-star') ?: 'fas fa-star',
            'description' => $request->description,
            'order'       => $request->input('order', 0),
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.keunggulan.index')
            ->with('success', 'Keunggulan berhasil ditambahkan.');
    }

    public function edit(Keunggulan $keunggulan)
    {
        return view('admin.keunggulan.form', ['item' => $keunggulan]);
    }

    public function update(Request $request, Keunggulan $keunggulan)
    {
        $request->validate([
            'label'       => 'required|string|max:100',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'order'       => 'integer|min:0',
        ]);

        $keunggulan->update([
            'label'       => $request->label,
            'icon'        => $request->input('icon', 'fas fa-star') ?: 'fas fa-star',
            'description' => $request->description,
            'order'       => $request->input('order', 0),
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.keunggulan.index')
            ->with('success', 'Keunggulan berhasil diperbarui.');
    }

    public function destroy(Keunggulan $keunggulan)
    {
        $keunggulan->delete();
        return back()->with('success', 'Keunggulan berhasil dihapus.');
    }

    public function toggleActive(Keunggulan $keunggulan)
    {
        $keunggulan->update(['is_active' => !$keunggulan->is_active]);
        return response()->json(['is_active' => $keunggulan->is_active]);
    }
}
