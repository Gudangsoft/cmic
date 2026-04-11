@extends('layouts.admin')
@section('title','Detail Pesan') @section('page-title','Detail Pesan Masuk')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Pesan Masuk</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="fcard-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-envelope-open-text me-2"></i>Isi Pesan</span>
                @if(!$contact->is_read)
                <span class="badge" style="background:rgba(245,197,24,.9);color:#333;font-size:12px;">Belum Dibaca</span>
                @else
                <span class="badge bg-success">Sudah Dibaca</span>
                @endif
            </div>
            <div class="fcard-body">
                <h5 class="fw-bold mb-1" style="color:#1e293b;">{{ $contact->subject }}</h5>
                <div style="font-size:12.5px;color:#94a3b8;margin-bottom:20px;">
                    <i class="fas fa-clock me-1"></i>{{ $contact->created_at->format('d F Y, H:i') }} WIB
                    &mdash; {{ $contact->created_at->diffForHumans() }}
                </div>
                <div class="p-4 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;line-height:1.9;font-size:14.5px;color:#374151;">
                    {!! nl2br(e($contact->message)) !!}
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject) }}" class="btn btn-primary">
                        <i class="fas fa-reply me-2"></i>Balas via Email
                    </a>
                    @if($contact->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                    @endif
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary ms-auto">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" id="msgDel">@csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(document.getElementById('msgDel'),'Hapus pesan ini permanen?')">
                            <i class="fas fa-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card form-card">
            <div class="fcard-header"><i class="fas fa-user-circle me-2"></i>Info Pengirim</div>
            <div class="fcard-body">
                <div class="text-center mb-4">
                    <div style="width:72px;height:72px;border-radius:50%;background:#0057A8;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;color:#fff;font-weight:700;">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-0">{{ $contact->name }}</h6>
                </div>
                <ul class="list-group list-group-flush" style="font-size:13.5px;">
                    <li class="list-group-item px-0 py-2 d-flex gap-2">
                        <i class="fas fa-envelope text-primary mt-1" style="width:16px;"></i>
                        <div>
                            <div class="text-muted" style="font-size:11px;">Email</div>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </div>
                    </li>
                    <li class="list-group-item px-0 py-2 d-flex gap-2">
                        <i class="fas fa-phone text-success mt-1" style="width:16px;"></i>
                        <div>
                            <div class="text-muted" style="font-size:11px;">Telepon</div>
                            <span>{{ $contact->phone ?? '—' }}</span>
                        </div>
                    </li>
                    <li class="list-group-item px-0 py-2 d-flex gap-2">
                        <i class="fas fa-tag text-warning mt-1" style="width:16px;"></i>
                        <div>
                            <div class="text-muted" style="font-size:11px;">Subjek</div>
                            <span>{{ $contact->subject }}</span>
                        </div>
                    </li>
                    <li class="list-group-item px-0 py-2 d-flex gap-2">
                        <i class="fas fa-calendar text-info mt-1" style="width:16px;"></i>
                        <div>
                            <div class="text-muted" style="font-size:11px;">Diterima</div>
                            <span>{{ $contact->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
