<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class KlienController extends Controller
{
    public function index()
    {
        $clients = Client::active()->get();
        $projectCounts = Project::where('is_active', true)
            ->selectRaw('client as client_name, count(*) as total')
            ->whereNotNull('client')
            ->groupBy('client')
            ->pluck('total', 'client_name');
        return view('frontend.klien', compact('clients', 'projectCounts'));
    }

    public function show(Client $client)
    {
        abort_unless($client->is_active, 404);
        $projects = Project::where('is_active', true)
            ->where('client', $client->name)
            ->latest()
            ->get();
        return view('frontend.klien_detail', compact('client', 'projects'));
    }
}
