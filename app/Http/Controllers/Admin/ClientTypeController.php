<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientType;
use Illuminate\Http\Request;

class ClientTypeController extends Controller
{
    public function index()
    {
        $clientTypes = ClientType::withCount('clients')->orderBy('order')->get();
        return view('admin.client-types.index', compact('clientTypes'));
    }

    public function create()
    {
        return view('admin.client-types.form', ['clientType' => new ClientType()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:client_types,name',
            'order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        ClientType::create($validated);

        return redirect()->route('admin.client-types.index')->with('success', 'Jenis klien berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(ClientType $clientType)
    {
        return view('admin.client-types.form', compact('clientType'));
    }

    public function update(Request $request, ClientType $clientType)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:client_types,name,' . $clientType->id,
            'order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $clientType->update($validated);

        return redirect()->route('admin.client-types.index')->with('success', 'Jenis klien berhasil diperbarui.');
    }

    public function destroy(ClientType $clientType)
    {
        $clientType->delete();
        return redirect()->route('admin.client-types.index')->with('success', 'Jenis klien berhasil dihapus.');
    }
}
