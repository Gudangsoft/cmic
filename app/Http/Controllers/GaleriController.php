<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index()
    {
        $all = Gallery::active()->orderBy('album')->orderBy('title')->orderBy('order')->get();
        // 2-level: album (L1) → title/project (L2) → photos
        $albumGroups = $all->groupBy(fn($g) => $g->album ?: '')->map(fn($g) => $g->groupBy('title'));
        return view('frontend.galeri', compact('albumGroups'));
    }
}
