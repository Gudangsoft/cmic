<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\Setting;
use Illuminate\Http\Request;

class SdmController extends Controller
{
    public function index()
    {
        $sectionOrder = ['Direksi', 'Manajemen', 'Tenaga Ahli', 'Staf Pendukung'];
        $members = TeamMember::active()->get()
            ->groupBy('section')
            ->sortBy(function ($items, $section) use ($sectionOrder) {
                $idx = array_search($section, $sectionOrder);
                return $idx === false ? 99 : $idx;
            });
        $teamSettings = [
            'team_section_title'    => Setting::get('team_section_title', 'Tim Profesional Kami'),
            'team_section_subtitle' => Setting::get('team_section_subtitle', 'Didukung oleh tenaga ahli berpengalaman di bidangnya'),
        ];
        return view('frontend.sdm', compact('members', 'teamSettings'));
    }
}
