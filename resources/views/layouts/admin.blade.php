<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — CMIC Admin</title>
    @if(!empty($siteSettings['company_favicon']))
        <link rel="icon" href="{{ asset('storage/'.($siteSettings['company_favicon'])) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --blue: {{ $siteSettings['theme_color_primary'] ?? '#0057A8' }};
            --dark-blue: {{ $siteSettings['theme_color_secondary'] ?? '#003A78' }};
            --yellow: {{ $siteSettings['theme_color_accent'] ?? '#F5C518' }};
            --sidebar-w:265px; --topbar-h:60px;
        }
        *, body { font-family:'Poppins',sans-serif; }
        body { background:#eef2f7; }
        .sidebar { width:var(--sidebar-w); height:100vh; background:linear-gradient(180deg,#003A78 0%,#001f4d 100%); position:fixed; left:0; top:0; z-index:1030; display:flex; flex-direction:column; transition:transform .3s ease; overflow:hidden; }
        .sidebar-header { padding:18px 20px 16px; background:rgba(0,0,0,.25); border-bottom:1px solid rgba(255,255,255,.08); flex-shrink:0; }
        .sidebar-header .brand-name { color:var(--yellow); font-weight:700; font-size:15px; line-height:1.2; }
        .sidebar-header .brand-sub { color:rgba(255,255,255,.45); font-size:10.5px; margin-top:3px; }
        .sidebar-body { flex:1; overflow-y:auto; padding-bottom:12px; }
        .sidebar-body::-webkit-scrollbar { width:4px; }
        .sidebar-body::-webkit-scrollbar-thumb { background:rgba(255,255,255,.15); border-radius:2px; }
        .nav-section { color:rgba(255,255,255,.35); font-size:10px; text-transform:uppercase; letter-spacing:1.2px; padding:14px 20px 5px; font-weight:600; }
        .sidebar-link { display:flex; align-items:center; gap:10px; color:rgba(255,255,255,.7); padding:10px 20px; font-size:13.5px; text-decoration:none; border-left:3px solid transparent; transition:all .2s; }
        .sidebar-link i { width:18px; text-align:center; font-size:14px; }
        .sidebar-link:hover { color:#fff; background:rgba(255,255,255,.07); border-left-color:rgba(245,197,24,.5); }
        .sidebar-link.active { color:#fff; background:rgba(255,255,255,.1); border-left-color:var(--yellow); font-weight:500; }
        .sidebar-link .badge-pill { margin-left:auto; background:var(--yellow); color:#333; font-weight:700; font-size:10px; padding:2px 7px; border-radius:20px; }
        .sidebar-link .arrow { margin-left:auto; font-size:10px; transition:transform .2s; }
        .sidebar-group { overflow:hidden; }
        .sidebar-group-toggle { cursor:pointer; }
        .sidebar-group-toggle.open .arrow { transform:rotate(90deg); }
        .sidebar-children { display:none; background:rgba(0,0,0,.18); }
        .sidebar-children.show { display:block; }
        .sidebar-children .sidebar-link { font-size:12.5px; padding:8px 12px 8px 42px; border-left:3px solid transparent; color:rgba(255,255,255,.6); }
        .sidebar-children .sidebar-link i { font-size:12px; }
        .sidebar-children .sidebar-link:hover { color:#fff; background:rgba(255,255,255,.06); border-left-color:rgba(245,197,24,.4); }
        .sidebar-children .sidebar-link.active { color:#fff; background:rgba(255,255,255,.09); border-left-color:var(--yellow); font-weight:500; }
        .sidebar-footer { flex-shrink:0; padding:14px 20px; border-top:1px solid rgba(255,255,255,.08); background:rgba(0,0,0,.2); }
        .sidebar-footer .user-info { display:flex; align-items:center; gap:10px; }
        .sidebar-footer .avatar { width:34px; height:34px; border-radius:50%; background:var(--yellow); color:#333; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; flex-shrink:0; }
        .sidebar-footer .user-name { color:#fff; font-size:13px; font-weight:500; }
        .sidebar-footer .user-role { color:rgba(255,255,255,.4); font-size:11px; }
        .main-wrapper { margin-left:var(--sidebar-w); min-height:100vh; display:flex; flex-direction:column; }
        .topbar { height:var(--topbar-h); background:#fff; box-shadow:0 1px 0 #e2e8f0,0 2px 8px rgba(0,0,0,.05); display:flex; align-items:center; justify-content:space-between; padding:0 24px; position:sticky; top:0; z-index:100; }
        .topbar-left { display:flex; align-items:center; gap:12px; }
        .topbar .page-title { font-weight:600; color:var(--dark-blue); margin:0; font-size:16px; }
        .topbar-right { display:flex; align-items:center; gap:8px; }
        .breadcrumb-bar { background:transparent; padding:8px 24px 0; }
        .breadcrumb { margin:0; font-size:12px; }
        .breadcrumb-item a { color:var(--blue); text-decoration:none; }
        .breadcrumb-item.active { color:#888; }
        .breadcrumb-item+.breadcrumb-item::before { color:#bbb; }
        .content-area { padding:16px 24px 32px; flex:1; }
        .stat-card { border:none; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); transition:transform .2s,box-shadow .2s; }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.11); }
        .stat-card .stat-icon { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; color:#fff; }
        .stat-card .stat-num { font-size:1.9rem; font-weight:700; line-height:1; }
        .stat-card .stat-lbl { font-size:12.5px; color:#888; margin-top:3px; }
        .card-table { border:none; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); }
        .card-table .cht-header { background:var(--blue); color:#fff; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; font-weight:600; font-size:14px; }
        .card-table .cht-header .add-btn { background:var(--yellow); color:#222; border:none; padding:6px 16px; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; transition:filter .2s; }
        .card-table .cht-header .add-btn:hover { filter:brightness(.93); }
        .table { margin:0; }
        .table thead th { background:#f8fafc; color:#475569; border-top:none; border-bottom:2px solid #e2e8f0; font-size:12px; text-transform:uppercase; letter-spacing:.5px; padding:12px 14px; font-weight:600; }
        .table tbody td { padding:11px 14px; vertical-align:middle; font-size:13.5px; border-color:#f1f5f9; color:#334155; }
        .table tbody tr:hover td { background:#f8fafc; }
        .table tbody tr:last-child td { border-bottom:none; }
        .form-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
        .form-card .fcard-header { background:var(--blue); color:#fff; padding:14px 22px; font-weight:600; font-size:15px; border-radius:12px 12px 0 0; }
        .form-card .fcard-body { padding:28px 24px; }
        .form-label { font-weight:500; font-size:13.5px; color:#374151; margin-bottom:5px; }
        .form-control,.form-select { border-color:#d1d5db; border-radius:8px; font-size:14px; padding:8px 12px; color:#374151; transition:border-color .2s,box-shadow .2s; }
        .form-control:focus,.form-select:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(0,87,168,.12); }
        .form-section-title { font-weight:600; font-size:12px; color:var(--blue); border-bottom:2px solid var(--blue); padding-bottom:8px; margin-bottom:18px; text-transform:uppercase; letter-spacing:.5px; }
        .img-preview-box { border:2px dashed #d1d5db; border-radius:10px; padding:10px; text-align:center; margin-top:10px; background:#fafafa; }
        .img-preview-box img { max-height:120px; max-width:100%; border-radius:6px; object-fit:contain; }
        .btn-action { width:32px; height:32px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-radius:7px; font-size:13px; }
        .alert { border-radius:10px; border:none; font-size:14px; }
        .alert-success { background:#ecfdf5; color:#065f46; }
        .alert-danger { background:#fef2f2; color:#991b1b; }
        .sidebar-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1020; }
        @media(max-width:991.98px) { .sidebar{transform:translateX(-100%);} .sidebar.open{transform:translateX(0);} .sidebar-backdrop.open{display:block;} .main-wrapper{margin-left:0;} }
        .dataTables_wrapper .dataTables_filter input,.dataTables_wrapper .dataTables_length select { border-radius:8px; border:1px solid #d1d5db; padding:5px 10px; font-size:13px; }
        .dataTables_wrapper .dataTables_info { font-size:12.5px; color:#888; }
        div.dataTables_wrapper div.dataTables_paginate ul.pagination { margin-top:4px; }
        .page-link { font-size:13px; }
        .badge { font-size:11.5px; padding:4px 8px; }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        @if(!empty($siteSettings['company_logo']))
        <div class="text-center mb-2">
            <img src="{{ asset('storage/'.($siteSettings['company_logo'])) }}" alt="Logo"
                 style="max-height:48px; max-width:160px; object-fit:contain;">
        </div>
        @else
        <div class="brand-name"><i class="fas fa-hard-hat me-2"></i>{{ $siteSettings['company_name'] ?? 'CMIC Admin' }}</div>
        @endif
        <div class="brand-sub">{{ strtoupper($siteSettings['company_name'] ?? 'PT. CITRA MUDA INDO CONSULTANT') }}</div>
    </div>
    <div class="sidebar-body">
        <div class="nav-section">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard</a>
        <div class="nav-section">Konten Website</div>
        <a href="{{ route('admin.sliders.index') }}" class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}"><i class="fas fa-images"></i> Slider / Banner</a>
        <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i class="fas fa-cogs"></i> Lingkup Layanan</a>
        <a href="{{ route('admin.team.index') }}" class="sidebar-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}"><i class="fas fa-users"></i> SDM / Tim</a>
        @php $pengalamanOpen = request()->routeIs('admin.projects.*') || request()->routeIs('admin.jenis-proyek.*'); @endphp
        <div class="sidebar-group">
            <div class="sidebar-link sidebar-group-toggle {{ $pengalamanOpen ? 'open active':'' }}" onclick="toggleGroup(this)">
                <i class="fas fa-briefcase"></i> Pengalaman / Proyek
                <i class="fas fa-chevron-right arrow"></i>
            </div>
            <div class="sidebar-children {{ $pengalamanOpen ? 'show':'' }}">
                <a href="{{ route('admin.projects.index') }}" class="sidebar-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> Daftar Proyek</a>
                <a href="{{ route('admin.jenis-proyek.index') }}" class="sidebar-link {{ request()->routeIs('admin.jenis-proyek.*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Jenis Pengalaman</a>
            </div>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"><i class="fas fa-handshake"></i> Klien</a>
        <a href="{{ route('admin.client-types.index') }}" class="sidebar-link {{ request()->routeIs('admin.client-types.*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Jenis Klien</a>
        <a href="{{ route('admin.galleries.index') }}" class="sidebar-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"><i class="fas fa-photo-video"></i> Galeri</a>
        <a href="{{ route('admin.about.index') }}" class="sidebar-link {{ request()->routeIs('admin.about*') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> Tentang Kami</a>
        <a href="{{ route('admin.keunggulan.index') }}" class="sidebar-link {{ request()->routeIs('admin.keunggulan.*') ? 'active' : '' }}"><i class="fas fa-trophy"></i> Keunggulan</a>
        <a href="{{ route('admin.legal-items.index') }}" class="sidebar-link {{ request()->routeIs('admin.legal-items.*') ? 'active' : '' }}"><i class="fas fa-certificate"></i> Legalitas</a>
        <div class="nav-section">Navigasi & Tampilan</div>
        <a href="{{ route('admin.menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}"><i class="fas fa-bars"></i> Menu Navigasi</a>
        <a href="{{ route('admin.pages.index') }}" class="sidebar-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Halaman / Pages</a>
        <div class="nav-section">Komunikasi</div>
        <a href="{{ route('admin.contacts.index') }}" class="sidebar-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Pesan Masuk @if($unreadCount > 0)<span class="badge-pill">{{ $unreadCount }}</span>@endif</a>
        <div class="nav-section">Sistem</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-sliders-h"></i> Pengaturan</a>
        <a href="{{ route('admin.account.edit') }}" class="sidebar-link {{ request()->routeIs('admin.account.*') ? 'active' : '' }}"><i class="fas fa-user-cog"></i> Pengaturan Akun</a>
        <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"><i class="fas fa-history"></i> Log Aktivitas</a>
        <a href="{{ route('home') }}" target="_blank" class="sidebar-link"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
    </div>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);border:none;border-radius:6px;padding:5px 8px;" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </div>
</aside>
<div class="main-wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn btn-sm d-lg-none" style="color:#334155;" onclick="toggleSidebar()"><i class="fas fa-bars fa-lg"></i></button>
            <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="topbar-right">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm position-relative" style="color:#64748b;" title="Pesan Masuk">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">{{ $unreadCount }}</span>@endif
            </a>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-sm" style="color:#64748b;" title="Pengaturan"><i class="fas fa-cog"></i></a>
            <div class="vr mx-1" style="height:20px;opacity:.2;"></div>
            <span style="font-size:12.5px;color:#64748b;"><i class="fas fa-calendar-alt me-1"></i>{{ now()->format('d M Y') }}</span>
        </div>
    </div>
    @hasSection('breadcrumb')
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            @yield('breadcrumb')
        </ol>
    </div>
    @endif
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2"><i class="fas fa-check-circle fa-lg"></i><span>{{ session('success') }}</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2"><i class="fas fa-exclamation-circle fa-lg"></i><span>{{ session('error') }}</span><button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button></div>
        @endif
    </div>
    <div class="content-area">
        @yield('content')
    </div>
    <div class="text-center py-3" style="font-size:12px;color:#94a3b8;border-top:1px solid #e2e8f0;background:#fff;margin-top:auto;">
        &copy; {{ date('Y') }} {{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }} &mdash; Admin Panel
    </div>
</div>
{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div style="width:60px;height:60px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fas fa-trash-alt fa-xl text-danger"></i></div>
                <h6 class="fw-bold mb-2">Hapus Data?</h6>
                <p class="text-muted mb-0" style="font-size:13px;" id="deleteModalMsg">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-2 mt-3 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm px-4" id="deleteConfirmBtn">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('sidebarBackdrop').classList.toggle('open');}
function toggleGroup(el){el.classList.toggle('open');const children=el.nextElementSibling;children.classList.toggle('show');}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sidebarBackdrop').classList.remove('open');}
let _delForm=null;
function confirmDelete(form,msg){_delForm=form;document.getElementById('deleteModalMsg').textContent=msg||'Tindakan ini tidak dapat dibatalkan.';new bootstrap.Modal(document.getElementById('deleteModal')).show();}
document.getElementById('deleteConfirmBtn').addEventListener('click',function(){if(_delForm)_delForm.submit();});
function previewImage(input,previewId){var f=input.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){var b=document.getElementById(previewId);b.innerHTML='<img src="'+e.target.result+'" class="rounded">';b.style.borderStyle='solid';};r.readAsDataURL(f);}
setTimeout(function(){document.querySelectorAll('.alert').forEach(function(el){try{new bootstrap.Alert(el).close();}catch(e){}});},5000);
</script>
@stack('scripts')
</body>
</html>
