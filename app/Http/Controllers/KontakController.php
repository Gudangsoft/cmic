<?php

namespace App\Http\Controllers;

use App\Mail\NewContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KontakController extends Controller
{
    public function index()
    {
        return view('frontend.kontak');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        $adminEmail = config('mail.notify_to');
        if ($adminEmail) {
            Mail::to($adminEmail)->queue(new NewContactMail($contact));
        }

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
