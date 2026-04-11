<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalItem;
use Illuminate\Http\Request;

class LegalItemController extends Controller
{
    public function index()
    {
        $items = LegalItem::orderBy('order')->get();
        return view('admin.legal-items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.legal-items.form', ['item' => new LegalItem()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'value' => 'required|string|max:255',
            'icon'  => 'nullable|string|max:100',
            'order' => 'integer|min:0',
        ]);

        $validated['icon']       = $validated['icon'] ?: 'fas fa-file-alt';
        $validated['is_visible'] = $request->boolean('is_visible');
        LegalItem::create($validated);

        return redirect()->route('admin.legal-items.index')->with('success', 'Data legalitas berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(LegalItem $legalItem)
    {
        return view('admin.legal-items.form', ['item' => $legalItem]);
    }

    public function update(Request $request, LegalItem $legalItem)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'value' => 'required|string|max:255',
            'icon'  => 'nullable|string|max:100',
            'order' => 'integer|min:0',
        ]);

        $validated['icon']       = $validated['icon'] ?: 'fas fa-file-alt';
        $validated['is_visible'] = $request->boolean('is_visible');
        $legalItem->update($validated);

        return redirect()->route('admin.legal-items.index')->with('success', 'Data legalitas berhasil diperbarui.');
    }

    public function destroy(LegalItem $legalItem)
    {
        $legalItem->delete();
        return redirect()->route('admin.legal-items.index')->with('success', 'Data legalitas berhasil dihapus.');
    }

    public function toggleVisibility(LegalItem $legalItem)
    {
        $legalItem->update(['is_visible' => !$legalItem->is_visible]);
        return response()->json(['is_visible' => $legalItem->is_visible]);
    }
}
