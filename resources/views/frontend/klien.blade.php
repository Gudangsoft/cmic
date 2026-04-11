@extends('layouts.app')
@section('title', 'Klien - PT. CMIC')
@section('content')
<div class="page-header">
    <div class="container">
        <h1>Klien Kami</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Klien</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Klien &amp; Mitra Kami</h2>
            <span class="section-divider"></span>
            <p class="text-muted">Kepercayaan klien adalah amanah terbesar kami</p>
        </div>
        @if($clients->count())
        <div class="row g-4 align-items-stretch justify-content-center">
            @foreach($clients as $client)
            <div class="col-6 col-md-3 col-lg-2 text-center">
                <a href="{{ route('klien.show', $client) }}" class="text-decoration-none d-block h-100">
                <div class="p-3 border rounded shadow-sm h-100 d-flex flex-column align-items-center justify-content-center"
                     style="transition:transform .2s,box-shadow .2s,border-color .2s; border-color:#e2e8f0!important; cursor:pointer;"
                     onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 6px 20px rgba(0,87,168,.15)';this.style.borderColor='var(--cmic-blue)!important';"
                     onmouseleave="this.style.transform='';this.style.boxShadow='';this.style.borderColor='#e2e8f0!important';">
                    @if($client->logo)
                        <img src="{{ asset('storage/'.$client->logo) }}" class="img-fluid p-1 mb-2"
                             style="max-height:60px; max-width:120px; object-fit:contain;"
                             alt="{{ $client->name }}" title="{{ $client->name }}">
                    @else
                        <i class="fas fa-building fa-2x mb-2" style="color:var(--cmic-blue);"></i>
                    @endif
                    <div style="font-size:12px; font-weight:600; color:var(--cmic-blue); line-height:1.3;">{{ $client->name }}</div>
                    @if(isset($projectCounts[$client->name]) && $projectCounts[$client->name] > 0)
                    <span class="badge mt-2" style="background:var(--cmic-blue); font-size:10px;">
                        {{ $projectCounts[$client->name] }} proyek
                    </span>
                    @endif
                </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-handshake fa-3x mb-3"></i>
            <p>Data klien belum tersedia.</p>
        </div>
        @endif
    </div>
</section>
@endsection
