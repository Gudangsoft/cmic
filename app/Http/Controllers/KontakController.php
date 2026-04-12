<?php

namespace App\Http\Controllers;

use App\Mail\NewContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class KontakController extends Controller
{
    public function index()
    {
        return view('frontend.kontak');
    }

    public function store(Request $request)
    {
        // --- SPAM CHECK 1: Honeypot ---
        if (!empty($request->input('website_url'))) {
            // Silent redirect — don't reveal the check to bots
            return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
        }

        // --- SPAM CHECK 2: Time token (must take at least 3 seconds to fill) ---
        $formToken = $request->input('form_token');
        if ($formToken) {
            $decoded = base64_decode($formToken);
            $parts   = explode('|', $decoded);
            if (count($parts) === 2) {
                $submittedAt = (int) $parts[0];
                $elapsed     = time() - $submittedAt;
                if ($elapsed < 3) {
                    return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
                }
            }
        }

        // --- SPAM CHECK 3: Duplicate submission (same email + subject in last 10 minutes) ---
        $recentDuplicate = Contact::where('email', $request->input('email'))
            ->where('subject', $request->input('subject'))
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if ($recentDuplicate) {
            return back()->withErrors(['message' => 'Pesan serupa sudah kami terima. Mohon tunggu beberapa saat sebelum mengirim kembali.'])->withInput();
        }

        // --- VALIDATION ---
        $validated = $request->validate([
            'name'    => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\p{L}\s\'\-\.]+$/u'],
            'email'   => ['required', 'email:rfc,dns', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'subject' => ['required', 'string', 'min:5', 'max:255'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ], [
            'name.regex'     => 'Nama hanya boleh berisi huruf, spasi, tanda kutip, dan titik.',
            'name.min'       => 'Nama minimal 2 karakter.',
            'email.dns'      => 'Gunakan alamat email yang valid.',
            'phone.regex'    => 'Nomor telepon hanya boleh berisi angka, spasi, +, -, dan kurung.',
            'subject.min'    => 'Subjek minimal 5 karakter.',
            'message.min'    => 'Pesan terlalu singkat, minimal 20 karakter.',
            'message.max'    => 'Pesan terlalu panjang, maksimal 5000 karakter.',
        ]);

        $contact = Contact::create($validated);

        $adminEmail = config('mail.notify_to');
        if ($adminEmail) {
            Mail::to($adminEmail)->queue(new NewContactMail($contact));
        }

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
