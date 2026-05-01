@extends('layouts.app')
@section('title', 'Beranda - PT. Citra Muda Indo Consultant')

@section('content')
<!-- Hero Carousel -->
@if($sliders->count())
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($sliders as $i => $slider)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i==0?'active':'' }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach($sliders as $i => $slider)
        <div class="carousel-item {{ $i==0?'active':'' }}">
            @if($slider->type === 'text')
            {{-- TEXT SLIDER --}}
            @php
                $c1    = $slider->bg_color_start ?? '#003A78';
                $c2    = $slider->bg_color_end   ?? '#0057A8';
                $align = $slider->text_align      ?? 'center';
                $justifyMap = ['center'=>'center','left'=>'flex-start','right'=>'flex-end'];
                $justify = $justifyMap[$align] ?? 'center';
            @endphp
            <div style="background:linear-gradient(135deg,{{ $c1 }},{{ $c2 }});min-height:520px;display:flex;align-items:center;justify-content:{{ $justify }};padding:60px 40px;">
            <div style="max-width:1100px;width:100%;text-align:{{ $align }};" class="px-2 px-md-0">
                    @if($slider->title)
                    <h1 style="color:#fff;font-weight:800;font-size:clamp(20px,3.2vw,52px);line-height:1.15;margin-bottom:18px;text-shadow:0 2px 12px rgba(0,0,0,.18);white-space:nowrap;">
                        {{ $slider->title }}
                    </h1>
                    @endif
                    @if($slider->subtitle)
                    <p style="color:rgba(255,255,255,.92);font-size:clamp(15px,1.8vw,22px);margin-bottom:30px;font-weight:400;text-shadow:0 1px 6px rgba(0,0,0,.12);">
                        {{ $slider->subtitle }}
                    </p>
                    @endif
                    @if($slider->button_text)
                    <a href="{{ $slider->button_link ?? '#' }}"
                       style="display:inline-block;padding:13px 36px;border:2px solid #F5C518;color:#F5C518;border-radius:8px;font-weight:700;font-size:15px;text-decoration:none;letter-spacing:.5px;transition:all .25s;"
                       onmouseover="this.style.background='#F5C518';this.style.color='#003A78';"
                       onmouseout="this.style.background='transparent';this.style.color='#F5C518';">
                        {{ $slider->button_text }}
                    </a>
                    @endif
                </div>
            </div>
            @else
            {{-- IMAGE SLIDER --}}
            <img src="{{ asset('storage/'.$slider->image) }}" class="d-block w-100" alt="{{ $slider->title }}">
            @if($slider->title || $slider->subtitle)
            <div class="carousel-caption d-md-block">
                @if($slider->title)<h2>{{ $slider->title }}</h2>@endif
                @if($slider->subtitle)<p>{{ $slider->subtitle }}</p>@endif
                @if($slider->button_text)
                    <a href="{{ $slider->button_link ?? '#' }}" class="btn btn-hero mt-2">{{ $slider->button_text }}</a>
                @endif
            </div>
            @endif
            @endif
        </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
@else
<div style="background: linear-gradient(135deg, #003A78, #0057A8); min-height:480px; display:flex; align-items:center; justify-content:center;">
    <div class="text-center text-white">
        <h1 class="display-4 fw-bold">PT. Citra Muda Indo Consultant</h1>
        <p class="lead mt-3">{{ \App\Models\Setting::get('company_tagline', 'Solusi Terbaik untuk Perencanaan & Pengawasan Konstruksi') }}</p>
        <a href="{{ route('layanan') }}" class="btn btn-hero mt-3">Lihat Layanan Kami</a>
    </div>
</div>
@endif

<!-- Tentang Kami Preview -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-stretch g-4">
            <div class="col-lg-6 d-flex">
                @if(!empty($siteSettings['about_image']))
                <img src="{{ asset('storage/'.$siteSettings['about_image']) }}" class="img-fluid rounded shadow w-100" alt="Tentang CMIC">
                @else
                <div class="d-flex align-items-center justify-content-center rounded shadow w-100"
                     style="min-height:300px; background:linear-gradient(135deg,#003A78,#0057A8);">
                    <div class="text-center text-white px-4">
                        <i class="fas fa-building fa-4x mb-3 opacity-75"></i>
                        <div style="font-size:16px; font-weight:600;">PT. Citra Muda Indo Consultant</div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-6 d-flex">
                <div style="background: var(--cmic-dark-blue); border-radius: 16px; padding: 40px 44px; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(0,40,100,0.22); font-family: 'Poppins', sans-serif; width:100%; display:flex; flex-direction:column; justify-content:center;">
                    {{-- Header: company name --}}
                    <div style="margin-bottom:14px;position:relative;">
                        <h2 style="margin:0;font-size:20px;font-weight:700;color:#ffffff;letter-spacing:.3px;">Tentang Kami</h2>
                    </div>
                    {{-- Yellow accent divider --}}
                    <div style="width:48px;height:3px;background:var(--cmic-yellow);border-radius:2px;margin-bottom:22px;"></div>
                    {{-- About text --}}
                    <div style="color:rgba(255,255,255,.88);line-height:1.9;font-size:14px;font-weight:400;position:relative;">
                        {!! \App\Models\Setting::get('company_about', 'PT. Citra Muda Indo Consultant (CMIC) adalah perusahaan konsultan yang bergerak di bidang perencanaan, pengawasan, dan manajemen konstruksi. Kami berkomitmen memberikan layanan terbaik dengan tenaga ahli berpengalaman.') !!}
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('tentang') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 28px;background:var(--cmic-yellow);color:var(--cmic-dark-blue);border-radius:8px;font-weight:700;font-size:14px;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                            Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan -->
@if($services->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Lingkup Layanan</h2>
            <span class="section-divider"></span>
            <p class="text-muted">Layanan profesional yang kami tawarkan untuk mendukung keberhasilan proyek Anda</p>
        </div>
        <div class="row g-4">
            @foreach($services->take(3) as $service)
            <div class="col-lg-4 col-md-6">
                <div class="h-100 text-center p-4 rounded-3 position-relative overflow-hidden"
                     style="background: linear-gradient(145deg, var(--cmic-dark-blue) 0%, var(--cmic-blue) 100%);
                            box-shadow: 0 6px 24px rgba(0,57,120,0.22);
                            transition: transform .25s, box-shadow .25s; border: none; cursor:default;"
                     onmouseenter="this.style.transform='translateY(-6px)';this.style.boxShadow='0 14px 36px rgba(0,57,120,0.32)'"
                     onmouseleave="this.style.transform='';this.style.boxShadow='0 6px 24px rgba(0,57,120,0.22)'">
                    {{-- Decorative circle bg --}}
                    <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none;"></div>
                    <div style="position:absolute;bottom:-40px;left:-20px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none;"></div>
                    {{-- Icon --}}
                    <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;border:2px solid rgba(255,255,255,.25);">
                        @if($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" style="height:38px;object-fit:contain;filter:brightness(0) invert(1);" alt="{{ $service->title }}">
                        @else
                        <i class="fas {{ $service->icon ?? 'fa-building' }} fa-lg text-white"></i>
                        @endif
                    </div>
                    {{-- Title --}}
                    <h5 style="color:#ffffff;font-weight:700;font-size:16px;margin-bottom:10px;position:relative;">{{ $service->title }}</h5>
                    {{-- Yellow accent bar --}}
                    <div style="width:36px;height:3px;background:var(--cmic-yellow);border-radius:2px;margin:0 auto;"></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('layanan') }}" class="btn btn-cmic-outline px-5 py-2">{{ \App\Models\Setting::get('btn_layanan_text', 'Lihat Semua Layanan') }}</a>
        </div>
    </div>
</section>
@endif

<!-- Klien -->
@if($clients->count())
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Klien Kami</h2>
            <span class="section-divider"></span>
        </div>
        <div class="row align-items-center justify-content-center g-4">
            @foreach($clients->take(24) as $client)
            <div class="col-6 col-md-3 col-lg-2 text-center">
                @if($client->logo)
                <img src="{{ asset('storage/'.$client->logo) }}" class="client-logo img-fluid" alt="{{ $client->name }}" title="{{ $client->name }}">
                @else
                <div class="p-3 border rounded text-muted small">{{ $client->name }}</div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('klien') }}" class="btn btn-cmic-outline px-4">{{ \App\Models\Setting::get('btn_klien_text', 'Lihat Semua Klien') }}</a>
        </div>
    </div>
</section>
@endif

@endsection
