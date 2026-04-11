<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Project;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\TeamMember;
use App\Models\Menu;
use App\Models\Page;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'services'    => Service::count(),
            'projects'    => Project::count(),
            'clients'     => Client::count(),
            'contacts'    => Contact::count(),
            'unread'      => Contact::where('is_read', false)->count(),
            'galleries'   => Gallery::count(),
            'team'        => TeamMember::count(),
            'menus'       => Menu::count(),
            'pages'       => Page::count(),
        ];
        $recentContacts = Contact::latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentContacts'));
    }
}
