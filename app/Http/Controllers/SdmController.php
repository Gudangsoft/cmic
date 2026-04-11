<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\Setting;
use Illuminate\Http\Request;

class SdmController extends Controller
{
    public function index()
    {
        $members = TeamMember::active()->get()->groupBy('section');
        $teamSettings = [
            'team_section_title'    => Setting::get('team_section_title', 'Tim Profesional Kami'),
            'team_section_subtitle' => Setting::get('team_section_subtitle', 'Didukung oleh tenaga ahli berpengalaman di bidangnya'),
        ];
        return view('frontend.sdm', compact('members', 'teamSettings'));
    }
}
