<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Keunggulan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $services   = Service::active()->get();
        $keunggulan = Keunggulan::active()->get();
        return view('frontend.layanan', compact('services', 'keunggulan'));
    }
}
