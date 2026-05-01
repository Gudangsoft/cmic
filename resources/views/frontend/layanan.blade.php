@extends('layouts.app')
@section('title', 'Lingkup Layanan - ' . ($siteSettings['company_name'] ?? 'PT. CMIC'))
@section('content')
<div class="page-header">
    <div class="container">
        <h1>Lingkup Layanan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Lingkup Layanan</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">

        {{-- Deskripsi Pembuka --}}
        @if(!empty($siteSettings['services_section_title']) || !empty($siteSettings['services_intro']))
        <div class="mb-5">
            <div class="row align-items-center g-4">
                {{-- Kolom Kiri: Gambar --}}
                @php
                    $hasImg = !empty($siteSettings['services_section_image']) || !empty($siteSettings['company_logo']);
                @endphp
                @if($hasImg)
                <div class="col-lg-3 col-md-4 text-center">
                    @if(!empty($siteSettings['services_section_image']))
                        <img src="{{ asset('storage/'.$siteSettings['services_section_image']) }}"
                             alt="{{ $siteSettings['services_section_title'] ?? 'Lingkup Layanan' }}"
                             style="max-width:200px;width:100%;object-fit:contain;filter:drop-shadow(0 6px 18px rgba(0,87,168,0.2));">
                    @else
                        <img src="{{ asset('storage/'.($siteSettings['company_logo'])) }}"
                             alt="{{ $siteSettings['company_name'] ?? 'CMIC' }}"
                             style="max-width:200px;width:100%;object-fit:contain;filter:drop-shadow(0 6px 18px rgba(0,87,168,0.2));">
                    @endif
                </div>
                {{-- Kolom Kanan: Teks --}}
                <div class="col-lg-9 col-md-8">
                @else
                <div class="col-12">
                @endif
                    @if(!empty($siteSettings['services_section_title']))
                    <h2 class="section-title {{ $hasImg ? 'text-start' : 'text-center' }}">{{ $siteSettings['services_section_title'] }}</h2>
                    <span class="section-divider {{ $hasImg ? 'ms-0' : '' }}"></span>
                    @endif
                    @if(!empty($siteSettings['services_section_subtitle']))
                    <p class="text-primary fw-semibold mt-2" style="font-size:15px;">{{ $siteSettings['services_section_subtitle'] }}</p>
                    @endif
                    @if(!empty($siteSettings['services_intro']))
                    <p class="text-muted mt-2 mb-0">{{ $siteSettings['services_intro'] }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($services->count())
        <div class="row g-4 justify-content-center">
            @foreach($services as $service)
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100">
                    @if($service->image)
                    <img src="{{ asset('storage/'.$service->image) }}" class="card-img-top" style="height:200px; object-fit:cover;" alt="{{ $service->title }}">
                    @else
                    <div style="height:120px; background:var(--cmic-blue); display:flex; align-items:center; justify-content:center;">
                        <i class="fas {{ $service->icon ?? 'fa-building' }} fa-3x text-white"></i>
                    </div>
                    @endif
                    <div class="card-body p-4">
                        <h5 class="card-title">{{ $service->title }}</h5>
                        <div class="card-text text-muted" style="font-size:14px;">{!! $service->description !!}</div>

                        {{-- Galeri Foto --}}
                        @if(!empty($service->gallery) && count($service->gallery) > 0)
                        <div class="mt-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="fw-semibold" style="font-size:12px;color:#0057A8;"><i class="fas fa-images me-1"></i>Foto Kegiatan</span>
                                <span class="badge ms-2" style="background:#eef2fb;color:#0057A8;font-size:10px;">{{ count($service->gallery) }} foto</span>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($service->gallery as $i => $img)
                                <a href="{{ asset('storage/'.$img) }}"
                                   data-fancybox="gallery-{{ $service->id }}"
                                   data-caption="{{ $service->title }} — Foto {{ $i+1 }}">
                                    <img src="{{ asset('storage/'.$img) }}"
                                         style="width:54px;height:42px;object-fit:cover;border-radius:5px;border:2px solid #e2e8f0;transition:border-color .2s;"
                                         onmouseenter="this.style.borderColor='#0057A8'"
                                         onmouseleave="this.style.borderColor='#e2e8f0'"
                                         alt="Foto {{ $i+1 }}">
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-cogs fa-3x mb-3"></i>
            <p>Data layanan belum tersedia.</p>
        </div>
        @endif
    </div>
</section>

@endsection
