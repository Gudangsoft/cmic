<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegalItem;
use App\Models\Keunggulan;

class TentangController extends Controller
{
    public function index()
    {
        $legalItems      = LegalItem::visible()->get();
        $keunggulanItems = Keunggulan::active()->get();
        return view('frontend.tentang', compact('legalItems', 'keunggulanItems'));
    }
}
