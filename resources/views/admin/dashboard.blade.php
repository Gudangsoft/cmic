@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#0057A8;"><i class="fas fa-cogs"></i></div>
                <div><div class="stat-num">{{ $stats['services'] }}</div><div class="stat-lbl">Layanan</div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#10b981;"><i class="fas fa-briefcase"></i></div>
                <div><div class="stat-num">{{ $stats['projects'] }}</div><div class="stat-lbl">Proyek</div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#8b5cf6;"><i class="fas fa-handshake"></i></div>
                <div><div class="stat-num">{{ $stats['clients'] }}</div><div class="stat-lbl">Klien</div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#F5C518;"><i class="fas fa-users" style="color:#333;"></i></div>
                <div><div class="stat-num">{{ $stats['team'] }}</div><div class="stat-lbl">Tim SDM</div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#0ea5e9;"><i class="fas fa-photo-video"></i></div>
                <div><div class="stat-num">{{ $stats['galleries'] }}</div><div class="stat-lbl">Galeri</div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#ef4444;"><i class="fas fa-envelope"></i></div>
                <div><div class="stat-num">{{ $stats['unread'] }}</div><div class="stat-lbl">Pesan Baru</div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:#6366f1;"><i class="fas fa-bars"></i></div>
                <div><div class="stat-num">{{ $stats['menus'] }}</div><div class="stat-lbl">Menu Nav</div></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Quick Access --}}
    <div class="col-lg-5">
        <div class="card card-table h-100">
            <div class="cht-header"><i class="fas fa-bolt me-2"></i>Akses Cepat</div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-6"><a href="{{ route('admin.sliders.create') }}" class="btn btn-outline-primary btn-sm w-100 py-2"><i class="fas fa-plus me-1"></i>Slider</a></div>
                    <div class="col-6"><a href="{{ route('admin.services.create') }}" class="btn btn-outline-success btn-sm w-100 py-2"><i class="fas fa-plus me-1"></i>Layanan</a></div>
                    <div class="col-6"><a href="{{ route('admin.projects.create') }}" class="btn btn-outline-warning btn-sm w-100 py-2"><i class="fas fa-plus me-1"></i>Proyek</a></div>
                    <div class="col-6"><a href="{{ route('admin.galleries.create') }}" class="btn btn-outline-info btn-sm w-100 py-2"><i class="fas fa-plus me-1"></i>Foto Galeri</a></div>
                    <div class="col-6"><a href="{{ route('admin.team.create') }}" class="btn btn-outline-secondary btn-sm w-100 py-2"><i class="fas fa-plus me-1"></i>Anggota Tim</a></div>
                    <div class="col-6"><a href="{{ route('admin.clients.create') }}" class="btn btn-sm w-100 py-2" style="border:1px solid #8b5cf6;color:#8b5cf6;"><i class="fas fa-plus me-1"></i>Klien</a></div>
                    <div class="col-6"><a href="{{ route('admin.menus.index') }}" class="btn btn-sm w-100 py-2" style="border:1px solid #6366f1;color:#6366f1;"><i class="fas fa-bars me-1"></i>Kelola Menu</a></div>
                </div>
                <hr class="my-3">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <a href="{{ route('admin.sliders.index') }}" class="text-decoration-none">
                            <div class="p-2 rounded-3" style="background:#f0f4f8;">
                                <i class="fas fa-images fa-lg" style="color:#0057A8;"></i>
                                <div style="font-size:11px;color:#64748b;margin-top:4px;">Slider</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('admin.contacts.index') }}" class="text-decoration-none">
                            <div class="p-2 rounded-3 position-relative" style="background:#fef2f2;">
                                <i class="fas fa-envelope fa-lg" style="color:#ef4444;"></i>
                                @if($stats['unread'] > 0)<span class="position-absolute top-0 end-0 badge rounded-pill bg-danger" style="font-size:9px;transform:translate(4px,-4px);">{{ $stats['unread'] }}</span>@endif
                                <div style="font-size:11px;color:#64748b;margin-top:4px;">Pesan</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('admin.settings.index') }}" class="text-decoration-none">
                            <div class="p-2 rounded-3" style="background:#f0fdf4;">
                                <i class="fas fa-cog fa-lg" style="color:#10b981;"></i>
                                <div style="font-size:11px;color:#64748b;margin-top:4px;">Setting</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('admin.menus.index') }}" class="text-decoration-none">
                            <div class="p-2 rounded-3" style="background:#eef2ff;">
                                <i class="fas fa-bars fa-lg" style="color:#6366f1;"></i>
                                <div style="font-size:11px;color:#64748b;margin-top:4px;">Menu Nav</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Messages --}}
    <div class="col-lg-7">
        <div class="card card-table h-100">
            <div class="cht-header d-flex justify-content-between align-items-center" style="background:#003A78;">
                <span><i class="fas fa-envelope-open-text me-2"></i>Pesan Terbaru</span>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:none;font-size:12px;">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="card-body p-0">
                @forelse($recentContacts as $contact)
                <a href="{{ route('admin.contacts.show', $contact) }}" class="d-flex align-items-start p-3 border-bottom text-decoration-none {{ !$contact->is_read ? '' : '' }}" style="transition:background .15s;">
                    <div style="width:38px;height:38px;background:{{ !$contact->is_read ? '#0057A8':'#94a3b8' }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-user text-white" style="font-size:14px;"></i>
                    </div>
                    <div class="ms-3 flex-grow-1 min-w-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-{{ !$contact->is_read ? 'semibold':'normal' }}" style="font-size:13.5px;color:#1e293b;">{{ $contact->name }}</span>
                            <small style="font-size:11px;color:#94a3b8;white-space:nowrap;margin-left:8px;">{{ $contact->created_at->diffForHumans() }}</small>
                        </div>
                        <div style="font-size:12.5px;color:#64748b;" class="text-truncate">{{ $contact->subject }}</div>
                    </div>
                    @if(!$contact->is_read)<span class="ms-2 mt-1 flex-shrink-0" style="width:8px;height:8px;border-radius:50%;background:#0057A8;display:inline-block;"></span>@endif
                </a>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-2x mb-3 d-block" style="color:#cbd5e1;"></i>
                    Belum ada pesan masuk.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
