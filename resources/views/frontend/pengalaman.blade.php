@extends('layouts.app')
@section('title', 'Pengalaman - PT. CMIC')
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Pengalaman</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Pengalaman</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        {{-- Header: 2 kolom (gambar kiri, teks kanan) --}}
        <div class="mb-5">
            <div class="row align-items-center g-4">
                @php $hasImg = !empty($pengalamanSettings['pengalaman_section_image']) || !empty($pengalamanSettings['company_logo']); @endphp
                @if($hasImg)
                <div class="col-lg-3 col-md-4 text-center">
                    @if(!empty($pengalamanSettings['pengalaman_section_image']))
                        <img src="{{ asset('storage/'.$pengalamanSettings['pengalaman_section_image']) }}"
                             alt="{{ $pengalamanSettings['pengalaman_section_title'] ?? 'Pengalaman' }}"
                             style="max-width:200px;width:100%;object-fit:contain;filter:drop-shadow(0 6px 18px rgba(0,87,168,0.2));">
                    @else
                        <img src="{{ asset('storage/'.$pengalamanSettings['company_logo']) }}"
                             alt="Logo"
                             style="max-width:200px;width:100%;object-fit:contain;filter:drop-shadow(0 6px 18px rgba(0,87,168,0.2));">
                    @endif
                </div>
                <div class="col-lg-9 col-md-8">
                @else
                <div class="col-12 text-center">
                @endif
                    <h2 class="section-title {{ $hasImg ? 'text-start' : 'text-center' }}">
                        {{ $pengalamanSettings['pengalaman_section_title'] ?? 'Bidang Pengalaman Kami' }}
                    </h2>
                    <span class="section-divider {{ $hasImg ? 'ms-0' : '' }}"></span>
                    @if(!empty($pengalamanSettings['pengalaman_section_subtitle']))
                    <p class="text-muted mt-2 mb-0">{{ $pengalamanSettings['pengalaman_section_subtitle'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        @php
        $colorMap = [
            'primary'   => '#1859A9',
            'warning'   => '#E5900A',
            'success'   => '#15803d',
            'danger'    => '#b91c1c',
            'info'      => '#0891b2',
            'secondary' => '#475569',
            'dark'      => '#1e293b',
        ];
        @endphp

        @if($jenisProyeks->count())
        <div class="row g-3 mb-5">
            @foreach($jenisProyeks as $jenis)
            @php $bg = $colorMap[$jenis->warna] ?? '#1859A9'; @endphp
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <a href="{{ route('pengalaman.jenis', $jenis) }}"
                   class="d-flex flex-column align-items-center justify-content-center text-center gap-2 p-3 rounded-3 text-decoration-none h-100"
                   style="background:{{ $bg }};color:#fff;transition:transform .2s,box-shadow .2s;min-height:100px;box-shadow:0 4px 12px {{ $bg }}55;"
                   onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 28px {{ $bg }}88'"
                   onmouseleave="this.style.transform='';this.style.boxShadow='0 4px 12px {{ $bg }}55'">
                    <i class="fas fa-check-circle" style="font-size:20px;opacity:.85;"></i>
                    <span style="font-weight:700;font-size:13.5px;line-height:1.4;">{{ $jenis->nama }}</span>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-layer-group fa-3x mb-3"></i>
            <p>Data bidang pengalaman belum tersedia.</p>
        </div>
        @endif


    </div>
</section>
@endsection
