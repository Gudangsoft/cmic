<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Service;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->get();
        $services = Service::active()->take(6)->get();
        $clients = Client::active()->get();
        $projectCount = Project::where('is_active', true)->count();
        $serviceCount = Service::where('is_active', true)->count();
        $clientCount = Client::where('is_active', true)->count();

        return view('frontend.home', compact('sliders', 'services', 'clients', 'projectCount', 'serviceCount', 'clientCount'));
    }
}
