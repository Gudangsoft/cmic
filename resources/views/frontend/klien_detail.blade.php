@extends('layouts.app')
@section('title', $client->name . ' - Klien PT. CMIC')
@section('content')
<div class="page-header">
    <div class="container">
        <h1 style="font-size:22px;">{{ $client->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('klien') }}">Klien</a></li>
                <li class="breadcrumb-item active">{{ $client->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">

        {{-- Header Klien --}}
        <div class="card border-0 shadow-sm mb-5 p-4" style="border-radius:14px;">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div style="flex-shrink:0;">
                    @if($client->logo)
                    <img src="{{ asset('storage/'.$client->logo) }}"
                         style="max-height:90px; max-width:200px; object-fit:contain;"
                         alt="{{ $client->name }}">
                    @else
                    <div style="width:90px;height:90px;background:#eef2fb;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-building fa-3x" style="color:var(--cmic-blue);"></i>
                    </div>
                    @endif
                </div>
                <div>
                    <h2 style="color:var(--cmic-dark-blue); font-size:22px; font-weight:700; margin-bottom:6px;">{{ $client->name }}</h2>
                    @if($client->website)
                    <a href="{{ $client->website }}" target="_blank" rel="noopener"
                       style="font-size:13px; color:var(--cmic-blue);">
                        <i class="fas fa-globe me-1"></i>{{ $client->website }}
                    </a>
                    @endif
                    <div class="mt-2">
                        <span class="badge" style="background:var(--cmic-blue); font-size:12px;">
                            <i class="fas fa-briefcase me-1"></i>{{ $projects->count() }} Proyek Dikerjakan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Proyek --}}
        <h4 class="mb-4" style="color:var(--cmic-dark-blue); font-size:18px; font-weight:700; border-left:4px solid var(--cmic-blue); padding-left:12px;">
            Proyek yang Dikerjakan
        </h4>

        @if($projects->count())
        <div class="row g-4">
            @foreach($projects as $project)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm"
                     style="border-radius:12px; transition:transform .2s,box-shadow .2s;"
                     onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.12)'"
                     onmouseleave="this.style.transform='';this.style.boxShadow=''">
                    <a href="{{ route('pengalaman.show', $project) }}" class="text-decoration-none">
                        @if($project->image)
                        <img src="{{ asset('storage/'.$project->image) }}"
                             class="card-img-top"
                             style="height:190px; object-fit:cover; border-radius:12px 12px 0 0;"
                             alt="{{ $project->name }}">
                        @else
                        <div style="height:130px; background:linear-gradient(135deg,#003A78,#0057A8); border-radius:12px 12px 0 0; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-building fa-3x text-white opacity-75"></i>
                        </div>
                        @endif
                    </a>
                    <div class="card-body d-flex flex-column p-3">
                        <a href="{{ route('pengalaman.show', $project) }}" class="text-decoration-none">
                            <h6 class="mb-2" style="color:var(--cmic-blue); font-weight:600; font-size:14px; line-height:1.4;">
                                {{ $project->name }}
                            </h6>
                        </a>
                        <div style="font-size:12.5px; color:#666;" class="mb-2">
                            @if($project->location)<div><i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $project->location }}</div>@endif
                            @if($project->year)<div><i class="fas fa-calendar me-1 text-muted"></i>{{ $project->year }}</div>@endif
                            @if($project->category)
                            <span class="badge mt-1" style="background:var(--cmic-blue); font-size:11px;">{{ $project->category }}</span>
                            @endif
                        </div>
                        @if($project->description)
                        <p class="mb-2" style="font-size:12.5px; color:#555; line-height:1.6; border-top:1px solid #f0f0f0; padding-top:8px;">
                            {{ Str::limit($project->description, 90) }}
                        </p>
                        @endif
                        <div class="mt-auto">
                            <a href="{{ route('pengalaman.show', $project) }}"
                               class="btn btn-sm btn-outline-primary" style="font-size:12px;">
                                <i class="fas fa-arrow-right me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3"></i>
            <p>Belum ada proyek tercatat untuk klien ini.</p>
        </div>
        @endif

        <div class="mt-5">
            <a href="{{ route('klien') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Klien
            </a>
        </div>
    </div>
</section>
@endsection
