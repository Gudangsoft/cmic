<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('order')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        $clientTypes = ClientType::orderBy('order')->get();
        return view('admin.clients.form', ['client' => new Client(), 'clientTypes' => $clientTypes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_type_id' => 'nullable|exists:client_types,id',
            'name'    => 'required|string|max:255',
            'logo'    => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
            'order'   => 'integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clients', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        Client::create($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(Client $client)
    {
        $clientTypes = ClientType::orderBy('order')->get();
        return view('admin.clients.form', compact('client', 'clientTypes'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_type_id' => 'nullable|exists:client_types,id',
            'name'    => 'required|string|max:255',
            'logo'    => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
            'order'   => 'integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            if ($client->logo) Storage::disk('public')->delete($client->logo);
            $validated['logo'] = $request->file('logo')->store('clients', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $client->update($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        if ($client->logo) Storage::disk('public')->delete($client->logo);
        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil dihapus.');
    }

    public function updateLogo(Request $request, Client $client)
    {
        $request->validate(['logo' => 'required|image|max:2048']);

        if ($client->logo) Storage::disk('public')->delete($client->logo);
        $path = $request->file('logo')->store('clients', 'public');
        $client->update(['logo' => $path]);

        return response()->json(['url' => asset('storage/' . $path)]);
    }
}
