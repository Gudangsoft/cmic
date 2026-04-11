<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->orderBy('order')->get();
        return view('admin.menus.form', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'      => 'required|string|max:100',
            'url'        => 'nullable|string|max:255',
            'route_name' => 'nullable|string|max:100',
            'icon'       => 'nullable|string|max:100',
            'parent_id'  => 'nullable|exists:menus,id',
            'order'      => 'required|integer|min:0',
            'target'     => 'required|in:_self,_blank',
            'is_active'  => 'boolean',
        ]);

        Menu::create([
            'label'      => $request->label,
            'url'        => $request->url,
            'route_name' => $request->route_name,
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id,
            'order'      => $request->order,
            'target'     => $request->target,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('order')->get();
        return view('admin.menus.form', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'label'      => 'required|string|max:100',
            'url'        => 'nullable|string|max:255',
            'route_name' => 'nullable|string|max:100',
            'icon'       => 'nullable|string|max:100',
            'parent_id'  => 'nullable|exists:menus,id',
            'order'      => 'required|integer|min:0',
            'target'     => 'required|in:_self,_blank',
            'is_active'  => 'boolean',
        ]);

        $menu->update([
            'label'      => $request->label,
            'url'        => $request->url,
            'route_name' => $request->route_name,
            'icon'       => $request->icon,
            'parent_id'  => $request->parent_id,
            'order'      => $request->order,
            'target'     => $request->target,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        // Set children parent_id to null before deleting
        Menu::where('parent_id', $menu->id)->update(['parent_id' => null]);
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
