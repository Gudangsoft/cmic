<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('admin.contacts.index', compact('contacts'));
    }

    public function export()
    {
        $contacts = Contact::latest()->get();

        $filename = 'pesan_kontak_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($contacts) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['No', 'Nama', 'Email', 'Telepon', 'Subjek', 'Pesan', 'Status', 'Tanggal Masuk']);
            foreach ($contacts as $i => $c) {
                fputcsv($out, [
                    $i + 1,
                    $c->name,
                    $c->email,
                    $c->phone ?? '',
                    $c->subject,
                    $c->message,
                    $c->is_read ? 'Dibaca' : 'Belum Dibaca',
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Pesan berhasil dihapus.');
    }

    public function create() {}
    public function store() {}
    public function edit(string $id) {}
    public function update() {}
}
