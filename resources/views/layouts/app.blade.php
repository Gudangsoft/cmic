<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant')</title>
    @if(!empty($siteSettings['meta_description']))<meta name="description" content="{{ $siteSettings['meta_description'] }}">@endif
    @if(!empty($siteSettings['meta_keywords']))<meta name="keywords" content="{{ $siteSettings['meta_keywords'] }}">@endif
    @if(!empty($siteSettings['company_favicon']))
        <link rel="icon" href="{{ asset('storage/'.($siteSettings['company_favicon'])) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts (non-blocking) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"></noscript>
    <!-- Fancybox (non-blocking) -->
    <link rel="stylesheet" media="print" onload="this.media='all'" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <style>
        :root {
            --cmic-blue: {{ $siteSettings['theme_color_primary'] ?? '#0057A8' }};
            --cmic-dark-blue: {{ $siteSettings['theme_color_secondary'] ?? '#003A78' }};
            --cmic-yellow: {{ $siteSettings['theme_color_accent'] ?? '#F5C518' }};
            --cmic-light-bg: #f4f6f9;
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background: #fff; }

        /* Brand Header */
        .brand-header {
            background: linear-gradient(120deg, #001f4d 0%, #003080 50%, #0044aa 100%);
            padding: 18px 0;
            border-bottom: 4px solid var(--cmic-yellow);
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }
        .brand-logo-ring {
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            padding: 6px;
            transition: transform 0.3s;
        }
        .brand-logo-ring:hover { transform: scale(1.05); }
        .brand-logo-ring img {
            height: 110px; object-fit: contain;
            filter: drop-shadow(0 2px 12px rgba(0,0,0,0.6)) brightness(1.08);
        }
        .brand-info { padding-left: 32px; }
        .brand-company-name {
            font-size: 26px; font-weight: 800; color: #fff;
            letter-spacing: 0.5px; line-height: 1.25;
            text-shadow: 0 3px 12px rgba(0,0,0,0.4);
        }
        .brand-tagline {
            color: var(--cmic-yellow); font-size: 12px; font-weight: 700;
            letter-spacing: 1px; margin-top: 4px;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        /* Navbar */
        .navbar-cmic { background: var(--cmic-blue); padding: 4px 0; }
        .navbar-cmic .navbar-brand img { height: 42px; }
        .navbar-cmic .nav-link {
            color: #fff !important;
            padding: 14px 18px !important;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 0;
            transition: background 0.2s;
        }
        .navbar-cmic .nav-link:hover {
            background: var(--cmic-yellow);
            color: #1a1a1a !important;
        }
        .navbar-cmic .nav-link.active {
            background: var(--cmic-yellow);
            color: #1a1a1a !important;
            margin: 0 6px;
            border-radius: 4px;
        }
        .navbar-cmic .dropdown-menu {
            background: var(--cmic-dark-blue);
            border: none;
            border-radius: 0 0 6px 6px;
            min-width: 200px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .navbar-cmic .dropdown-item {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            font-weight: 500;
            padding: 10px 18px;
        }
        .navbar-cmic .dropdown-item:hover,
        .navbar-cmic .dropdown-item.active {
            background: var(--cmic-yellow);
            color: #1a1a1a;
        }
        .navbar-cmic .navbar-toggler { border-color: rgba(255,255,255,0.5); }
        .navbar-cmic .navbar-toggler-icon { filter: invert(1); }

        /* Top bar */
        .top-bar {
            background: var(--cmic-dark-blue);
            font-size: 12px;
            color: rgba(255,255,255,0.85);
            padding: 6px 0;
        }
        .top-bar a { color: rgba(255,255,255,0.85); text-decoration: none; }
        .top-bar a:hover { color: var(--cmic-yellow); }

        /* Hero Carousel */
        .carousel-item img { height: 520px; object-fit: cover; }
        .carousel-caption { background: rgba(0,57,120,0.65); padding: 20px; border-radius: 6px; }
        .carousel-caption h2 { font-weight: 700; font-size: 2rem; }
        .carousel-caption .btn-hero {
            background: var(--cmic-yellow);
            border: none;
            color: #222;
            font-weight: 700;
            padding: 10px 28px;
            border-radius: 4px;
        }

        /* Section headings */
        .section-title { color: var(--cmic-blue); font-weight: 700; }
        .section-divider {
            width: 60px; height: 4px;
            background: var(--cmic-yellow);
            display: block;
            margin: 10px auto 30px;
        }

        /* Cards */
        .card-service { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .card-service:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,87,168,0.18); }
        .card-service .card-title { color: var(--cmic-blue); font-weight: 600; }
        .icon-box {
            width: 60px; height: 60px;
            background: var(--cmic-blue);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px;
        }
        .icon-box i { color: #fff; font-size: 24px; }

        /* Stats counter */
        .counter-section { background: var(--cmic-blue); }
        .counter-box { color: #fff; text-align: center; padding: 30px 0; }
        .counter-box .number { font-size: 3rem; font-weight: 700; color: var(--cmic-yellow); }
        .counter-box .label { font-size: 14px; opacity: 0.9; }

        /* Team card */
        .team-card { text-align: center; }
        .team-card img {
            width: 130px; height: 130px;
            object-fit: cover; border-radius: 50%;
            border: 4px solid var(--cmic-blue);
            margin-bottom: 12px;
        }
        .team-card .name { font-weight: 700; color: var(--cmic-blue); }
        .team-card .position { color: #666; font-size: 13px; }

        /* Footer */
        footer {
            background: linear-gradient(160deg, #001d3d 0%, var(--cmic-dark-blue) 50%, #002856 100%);
            color: rgba(255,255,255,0.85);
            position: relative;
            overflow: hidden;
        }
        footer::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--cmic-yellow), #fff 50%, var(--cmic-yellow));
        }
        footer::after {
            content: '';
            position: absolute; bottom: 0; right: -80px;
            width: 360px; height: 360px; border-radius: 50%;
            background: radial-gradient(circle, rgba(245,197,24,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .footer-logo-ring {
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            transition: transform .3s;
        }
        .footer-logo-ring:hover { transform: scale(1.05); }
        .footer-logo-ring img { height: 70px; width: auto; object-fit: contain; filter: brightness(1.1) drop-shadow(0 2px 8px rgba(0,0,0,0.5)); }
        .footer-brand-block { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
        .footer-brand-name { font-size: 1.2rem; font-weight: 800; color: #fff; letter-spacing: 0.3px; margin-bottom: 4px; line-height: 1.2; }
        .footer-tagline { color: var(--cmic-yellow); font-size: 11.5px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0; }
        .footer-divider { width: 48px; height: 3px; background: linear-gradient(90deg, var(--cmic-yellow), transparent); border-radius: 2px; margin-bottom: 20px; }
        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 12px;
            margin-bottom: 14px;
        }
        .footer-contact-icon {
            width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
            background: rgba(245,197,24,0.12); border: 1px solid rgba(245,197,24,0.25);
            display: flex; align-items: center; justify-content: center;
            color: var(--cmic-yellow); font-size: 13px;
        }
        .footer-contact-text { font-size: 13px; color: rgba(255,255,255,0.82); line-height: 1.5; }
        .footer-contact-label { font-size: 10px; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 1px; }
        .footer-map-title { font-size: 13px; font-weight: 700; color: var(--cmic-yellow); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .footer-map-title::before { content: ''; display: inline-block; width: 28px; height: 3px; background: var(--cmic-yellow); border-radius: 2px; }
        .footer-map-wrapper { border-radius: 12px; overflow: hidden; border: 2px solid rgba(245,197,24,0.25); box-shadow: 0 8px 32px rgba(0,0,0,0.35); }
        footer a { color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s; }
        footer a:hover { color: var(--cmic-yellow); }
        .footer-social-bar { border-top: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.2); }
        .footer-social-icon { font-size: 22px; color: rgba(255,255,255,0.7); transition: color .2s, transform .2s; display: inline-block; }
        .footer-social-icon:hover { color: var(--cmic-yellow); transform: translateY(-3px); }
        footer .footer-bottom {
            background: rgba(0,0,0,0.35);
            font-size: 12.5px; padding: 14px 0;
            color: rgba(255,255,255,0.5);
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        /* BTN Primary */
        .btn-cmic { background: var(--cmic-blue); color: #fff; border: none; }
        .btn-cmic:hover { background: var(--cmic-dark-blue); color: #fff; }
        .btn-cmic-outline { border: 2px solid var(--cmic-blue); color: var(--cmic-blue); }
        .btn-cmic-outline:hover { background: var(--cmic-blue); color: #fff; }

        /* Page header */
        .page-header {
            background: linear-gradient(135deg, var(--cmic-dark-blue), var(--cmic-blue));
            color: #fff; padding: 50px 0 40px;
        }
        .page-header h1 { font-weight: 700; font-size: 2.2rem; }
        .breadcrumb-item a { color: var(--cmic-yellow); }
        .breadcrumb-item.active { color: rgba(255,255,255,0.8); }
        .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.6); }

        /* Gallery */
        .gallery-item img { height: 220px; object-fit: cover; border-radius: 6px; transition: transform 0.3s; }
        .gallery-item img:hover { transform: scale(1.03); }
        .gallery-overlay {
            position: absolute; inset: 0;
            background: rgba(0,57,120,0.7);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s; border-radius: 6px;
        }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        .gallery-item { position: relative; cursor: pointer; overflow: hidden; border-radius: 6px; }

        /* Client logo */
        .client-logo { filter: grayscale(80%); opacity: 0.7; transition: all 0.3s; max-height: 70px; }
        .client-logo:hover { filter: grayscale(0); opacity: 1; }

        /* Contact */
        .contact-info-box { background: var(--cmic-blue); color: #fff; padding: 30px; border-radius: 10px; }
        .contact-info-box h4 { font-weight: 700; color: var(--cmic-yellow); }

        /* Rich Text Content Styling (from CKEditor) */
        .text-muted p { margin-bottom: 1rem; }
        .text-muted ul, .text-muted ol { margin-bottom: 1rem; padding-left: 2rem; }
        .text-muted ul li, .text-muted ol li { margin-bottom: 0.5rem; }
        .text-muted strong { color: var(--cmic-blue); font-weight: 700; }
        .text-muted em { font-style: italic; }
        .text-muted u { text-decoration: underline; }
        .text-muted h2 { font-size: 1.5rem; font-weight: 700; color: var(--cmic-blue); margin-top: 1.5rem; margin-bottom: 1rem; }
        .text-muted h3 { font-size: 1.2rem; font-weight: 600; color: var(--cmic-blue); margin-top: 1.2rem; margin-bottom: 0.8rem; }
        .text-muted blockquote { 
            border-left: 4px solid var(--cmic-yellow); 
            padding-left: 1rem; 
            margin-left: 0; 
            margin-bottom: 1rem;
            color: #555;
            font-style: italic;
        }

        @media(max-width: 768px) {
            .carousel-item img { height: 220px; }
            .carousel-caption { padding: 10px 14px; }
            .carousel-caption h2 { font-size: 1.2rem; }
            .carousel-caption p { font-size: 12px; display: none; }
            .page-header { padding: 30px 0 22px; }
            .page-header h1 { font-size: 1.4rem; }
            .counter-box .number { font-size: 2rem; }
            .counter-box { padding: 18px 0; }
            .section-title { font-size: 1.3rem; }
            .navbar-cmic .nav-link { padding: 10px 14px !important; }
            .navbar-collapse { background: var(--cmic-dark-blue); padding: 0 10px 10px; }
            footer .col-lg-4, footer .col-6 { margin-bottom: 10px; }
            .brand-header { padding: 14px 0; }
            .brand-logo-ring { width: 78px; height: 78px; }
            .brand-logo-ring img { height: 62px; }
            .brand-company-name { font-size: 15px; }
            .brand-info { padding-left: 14px; }
        }
        @media(max-width: 576px) {
            .team-card img { width: 100px; height: 100px; }
            .gallery-item img { height: 160px; }
        }
        /* WhatsApp FAB */
        .wa-fab {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            width: 54px; height: 54px; border-radius: 50%;
            background: #25D366; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; box-shadow: 0 4px 16px rgba(0,0,0,.25);
            text-decoration: none; transition: transform .2s, box-shadow .2s;
        }
        .wa-fab:hover { transform: scale(1.1); box-shadow: 0 8px 24px rgba(0,0,0,.3); color:#fff; }
        .wa-fab-label {
            position: fixed; bottom: 36px; right: 86px; z-index: 9998;
            background: #333; color: #fff; font-size: 12px; padding: 5px 10px;
            border-radius: 5px; white-space: nowrap; opacity: 0; pointer-events: none;
            transition: opacity .2s;
        }
        .wa-fab:hover ~ .wa-fab-label { opacity: 1; }
        .maps-embed-wrapper iframe { width: 100% !important; height: 240px !important; display: block; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Top Bar -->
<div class="top-bar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-phone me-1"></i>
            {{ $siteSettings['company_phone'] ?? '+62-21-0000-0000' }}
            <span class="mx-3">|</span>
            <i class="fas fa-envelope me-1"></i>
            {{ $siteSettings['company_email'] ?? 'info@cmic.co.id' }}
        </div>
        <div>
            <a href="{{ !empty($siteSettings['facebook']) ? $siteSettings['facebook'] : '#' }}" target="_blank" class="me-2" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="{{ !empty($siteSettings['instagram']) ? $siteSettings['instagram'] : '#' }}" target="_blank" class="me-2" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="{{ !empty($siteSettings['linkedin']) ? $siteSettings['linkedin'] : '#' }}" target="_blank" class="me-2" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="{{ !empty($siteSettings['youtube']) ? $siteSettings['youtube'] : '#' }}" target="_blank" class="me-2" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="{{ !empty($siteSettings['twitter']) ? $siteSettings['twitter'] : '#' }}" target="_blank" class="me-2" title="Twitter / X"><i class="fab fa-twitter"></i></a>
            @if(!empty($siteSettings['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['whatsapp']) }}" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            @endif
        </div>
    </div>
</div>

<!-- Brand Header -->
<div class="brand-header">
    <div class="container">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            <div class="brand-logo-ring">
                @if(!empty($siteSettings['company_logo']))
                    <img src="{{ asset('storage/'.($siteSettings['company_logo'])) }}"
                         alt="{{ $siteSettings['company_name'] ?? 'CMIC' }}"
                         onerror="this.style.display='none';">
                @else
                    <img src="{{ asset('images/logo.png') }}" alt="CMIC Logo"
                         onerror="this.style.display='none';">
                @endif
            </div>
            <div class="brand-info">
                <div class="brand-company-name">{{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }}</div>
                <div class="brand-tagline">{{ $siteSettings['company_tagline'] ?? 'Solusi Terbaik untuk Perencanaan & Pengawasan Konstruksi' }}</div>
            </div>
        </a>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-cmic sticky-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                @forelse($globalMenus ?? [] as $navMenu)
                    @if($navMenu->children->count())
                    {{-- Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ $navMenu->isActive() ? 'active' : '' }}"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           target="{{ $navMenu->target }}">
                            @if($navMenu->icon)<i class="{{ $navMenu->icon }} me-1"></i>@endif
                            {{ $navMenu->label }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($navMenu->children as $child)
                            <li>
                                <a class="dropdown-item {{ $child->isActive() ? 'active' : '' }}"
                                   href="{{ $child->link() }}" target="{{ $child->target }}">
                                    @if($child->icon)<i class="{{ $child->icon }} me-1"></i>@endif
                                    {{ $child->label }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link {{ $navMenu->isActive() ? 'active' : '' }}"
                           href="{{ $navMenu->link() }}" target="{{ $navMenu->target }}">
                            @if($navMenu->icon)<i class="{{ $navMenu->icon }} me-1"></i>@endif
                            {{ $navMenu->label }}
                        </a>
                    </li>
                    @endif
                @empty
                    {{-- Fallback static jika menu DB kosong --}}
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('layanan') ? 'active' : '' }}" href="{{ route('layanan') }}">Lingkup Layanan</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('sdm') ? 'active' : '' }}" href="{{ route('sdm') }}">SDM</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('pengalaman') ? 'active' : '' }}" href="{{ route('pengalaman') }}">Pengalaman</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('klien') ? 'active' : '' }}" href="{{ route('klien') }}">Klien</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('galeri') ? 'active' : '' }}" href="{{ route('galeri') }}">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}" href="{{ route('kontak') }}">Kontak Kami</a></li>
                @endforelse
            </ul>
        </div>
    </div>
</nav>

@yield('content')

{{-- WhatsApp Floating Button --}}
@if(!empty($siteSettings['whatsapp']))
@php $waNumber = preg_replace('/[^0-9]/', '', $siteSettings['whatsapp']); @endphp
<a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="wa-fab" title="Chat WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<span class="wa-fab-label">Chat via WhatsApp</span>
@endif

<!-- Footer -->
<footer class="pt-5 pb-0">
    <div class="container" style="position:relative; z-index:1;">
        <div class="row g-5 align-items-start">

            {{-- Kiri: Identitas Perusahaan --}}
            <div class="col-lg-6">
                <div class="footer-brand-block">
                    @if(!empty($siteSettings['company_logo']))
                        <div class="footer-logo-ring">
                            <img src="{{ asset('storage/'.($siteSettings['company_logo'])) }}"
                                 alt="Logo"
                                 onerror="this.closest('.footer-logo-ring').style.display='none';">
                        </div>
                    @endif
                    <div>
                        <div class="footer-brand-name">{{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }}</div>
                        <div class="footer-tagline">{{ $siteSettings['company_tagline'] ?? 'Konsultan Handal, Profesional & Independen' }}</div>
                    </div>
                </div>
                <div class="footer-divider"></div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="footer-contact-label">Alamat</div>
                        <div class="footer-contact-text">{{ $siteSettings['company_address'] ?? 'Jakarta, Indonesia' }}</div>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="footer-contact-label">Telepon</div>
                        <div class="footer-contact-text">{{ $siteSettings['company_phone'] ?? '-' }}</div>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="footer-contact-label">Email</div>
                        <div class="footer-contact-text">{{ $siteSettings['company_email'] ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Lokasi Kami --}}
            <div class="col-lg-6">
                <div class="footer-map-title"><span>Lokasi Kami</span></div>
                @if(!empty($siteSettings['maps_embed']))
                    <div class="footer-map-wrapper">
                        <div class="maps-embed-wrapper">
                            {!! $siteSettings['maps_embed'] !!}
                        </div>
                    </div>
                @else
                    <div class="footer-map-wrapper" style="background:rgba(255,255,255,0.05); height:240px; display:flex; align-items:center; justify-content:center;">
                        <div class="text-center" style="color:rgba(255,255,255,0.35);">
                            <i class="fas fa-map-marked-alt fa-3x mb-2"></i>
                            <div><small>Peta belum dikonfigurasi</small></div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    {{-- Social Media Footer --}}
    <div class="footer-social-bar mt-5 py-3 text-center">
        <div class="container">
            <a href="{{ !empty($siteSettings['facebook']) ? $siteSettings['facebook'] : '#' }}" target="_blank" class="footer-social-icon me-3" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="{{ !empty($siteSettings['instagram']) ? $siteSettings['instagram'] : '#' }}" target="_blank" class="footer-social-icon me-3" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="{{ !empty($siteSettings['linkedin']) ? $siteSettings['linkedin'] : '#' }}" target="_blank" class="footer-social-icon me-3" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="{{ !empty($siteSettings['youtube']) ? $siteSettings['youtube'] : '#' }}" target="_blank" class="footer-social-icon me-3" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="{{ !empty($siteSettings['twitter']) ? $siteSettings['twitter'] : '#' }}" target="_blank" class="footer-social-icon me-3" title="Twitter / X"><i class="fab fa-twitter"></i></a>
            @if(!empty($siteSettings['whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['whatsapp']) }}" target="_blank" class="footer-social-icon" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            @endif
        </div>
    </div>
    <div class="footer-bottom text-center">
        <div class="container">
            &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.75);">{{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }}</strong>. All rights reserved.
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Fancybox -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>Fancybox.bind('[data-fancybox]');</script>
@stack('scripts')
</body>
</html>
