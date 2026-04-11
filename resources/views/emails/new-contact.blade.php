<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Website CMIC</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #0057A8; padding: 28px 32px; }
        .header h2 { color: #fff; margin: 0; font-size: 20px; }
        .body { padding: 28px 32px; }
        .label { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
        .value { font-size: 15px; color: #222; margin-bottom: 18px; font-weight: 500; }
        .message-box { background: #f8fafc; border-left: 4px solid #0057A8; padding: 14px 18px; border-radius: 4px; color: #444; font-size: 14px; line-height: 1.7; white-space: pre-line; }
        .footer { background: #f4f6f8; padding: 16px 32px; font-size: 12px; color: #94a3b8; text-align: center; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 22px; background: #0057A8; color: #fff !important; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>📩 Pesan Baru dari Website</h2>
    </div>
    <div class="body">
        <p style="color:#555;margin-top:0;">Ada pesan baru yang masuk melalui formulir kontak website CMIC.</p>

        <div class="label">Nama Pengirim</div>
        <div class="value">{{ $contact->name }}</div>

        <div class="label">Email</div>
        <div class="value">{{ $contact->email }}</div>

        @if($contact->phone)
        <div class="label">Telepon</div>
        <div class="value">{{ $contact->phone }}</div>
        @endif

        <div class="label">Subjek</div>
        <div class="value">{{ $contact->subject }}</div>

        <div class="label">Pesan</div>
        <div class="message-box">{{ $contact->message }}</div>

        <div style="margin-top:4px;font-size:12px;color:#94a3b8;">
            Diterima pada: {{ $contact->created_at->format('d F Y, H:i') }} WIB
        </div>

        <a href="{{ config('app.url') }}/admin/contacts" class="btn">Lihat di Admin Panel</a>
    </div>
    <div class="footer">
        Email ini dikirim otomatis oleh sistem &mdash; PT. Citra Muda Indo Consultant
    </div>
</div>
</body>
</html>
