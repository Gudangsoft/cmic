@extends('layouts.app')
@section('title', 'Tentang Kami - ' . ($siteSettings['company_name'] ?? 'PT. CMIC'))
@section('content')
<div class="page-header">
    <div class="container">
        <h1>Tentang Kami</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Tentang Kami</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                @if(!empty($siteSettings['about_image']))
                    <img src="{{ asset('storage/' . $siteSettings['about_image']) }}"
                         class="img-fluid rounded shadow-lg" alt="{{ $siteSettings['company_name'] ?? 'PT CMIC' }}"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div class="rounded shadow-lg d-none align-items-center justify-content-center"
                         style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#0057A8,#003d7a);">
                        <div class="text-center text-white p-4">
                            <i class="fas fa-building fa-4x mb-3 opacity-75"></i>
                            <div class="fw-bold fs-5">{{ $siteSettings['company_name'] ?? 'PT. CMIC' }}</div>
                        </div>
                    </div>
                @else
                    <div class="rounded shadow-lg d-flex align-items-center justify-content-center"
                         style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#0057A8,#003d7a);">
                        <div class="text-center text-white p-4">
                            <i class="fas fa-building fa-4x mb-3 opacity-75"></i>
                            <div class="fw-bold fs-5">{{ $siteSettings['company_name'] ?? 'PT. CMIC' }}</div>
                            <div class="small opacity-75 mt-1">Upload foto di menu Tentang Kami</div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                <h2 class="section-title">{{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }}</h2>
                <span class="section-divider d-block mb-4" style="margin:0;"></span>
                <div class="text-muted" style="line-height:1.8; font-size:15px;">
                    {!! $siteSettings['company_about'] ?? '<p>PT. Citra Muda Indo Consultant (CMIC) adalah perusahaan konsultan profesional yang bergerak di bidang perencanaan, pengawasan, dan manajemen konstruksi.</p>' !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Blue Stats Box --}}
<section style="background: linear-gradient(135deg, var(--cmic-dark-blue) 0%, var(--cmic-blue) 100%); padding: 56px 0; position:relative; overflow:hidden;">
    {{-- Decorative circles --}}
    <div style="position:absolute;top:-60px;right:-60px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:-40px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,.03);pointer-events:none;"></div>
    <div class="container position-relative">
        <div class="row g-0 text-center text-white">
            <div class="col-6 col-md-3">
                <div class="py-3 px-2" style="border-right:1px solid rgba(255,255,255,.15);">
                    <div style="font-size:2.8rem;font-weight:800;color:var(--cmic-yellow);line-height:1;font-family:inherit;">
                        {{ $siteSettings['stat_years'] ?? '10' }}<span style="font-size:1.8rem;">+</span>
                    </div>
                    <div class="mt-2" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.85;font-weight:600;">Tahun Pengalaman</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="py-3 px-2" style="border-right:1px solid rgba(255,255,255,.15);">
                    <div style="font-size:2.8rem;font-weight:800;color:var(--cmic-yellow);line-height:1;">
                        {{ $siteSettings['stat_projects'] ?? '150' }}<span style="font-size:1.8rem;">+</span>
                    </div>
                    <div class="mt-2" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.85;font-weight:600;">Proyek Selesai</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="py-3 px-2" style="border-right:1px solid rgba(255,255,255,.15);">
                    <div style="font-size:2.8rem;font-weight:800;color:var(--cmic-yellow);line-height:1;">
                        {{ $siteSettings['stat_experts'] ?? '50' }}<span style="font-size:1.8rem;">+</span>
                    </div>
                    <div class="mt-2" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.85;font-weight:600;">Tenaga Ahli</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="py-3 px-2">
                    <div style="font-size:2.8rem;font-weight:800;color:var(--cmic-yellow);line-height:1;">
                        {{ $siteSettings['stat_clients'] ?? '100' }}<span style="font-size:1.8rem;">+</span>
                    </div>
                    <div class="mt-2" style="font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.85;font-weight:600;">Klien Puas</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi -->
<section class="py-5" style="background:var(--cmic-light-bg)">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Visi & Misi</h2>
            <span class="section-divider"></span>
        </div>
        <div class="row g-4">
            <div class="col-12">
                <div class="card h-100 border-0 shadow-sm p-4 text-center">
                    <div class="d-flex flex-column align-items-center mb-3">
                        <div style="width:50px;height:50px;background:var(--cmic-blue);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-eye text-white fa-lg"></i>
                        </div>
                        <h4 class="mt-2 mb-0" style="color:var(--cmic-blue); font-weight:700;">Visi</h4>
                    </div>
                    <p class="text-muted">{{ $siteSettings['about_visi'] ?? 'Menjadi perusahaan konsultan terkemuka di Indonesia yang memberikan solusi terbaik dalam perencanaan dan pengawasan konstruksi dengan mengutamakan kualitas, inovasi, dan integritas.' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="card h-100 border-0 shadow p-5 text-center" style="background: linear-gradient(135deg, rgba(0,87,168,0.05), rgba(245,197,24,0.05)); border: 1px solid rgba(245,197,24,0.2);">
                    <div class="d-flex flex-column align-items-center mb-3">
                        <div style="width:60px;height:60px;background:var(--cmic-yellow);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,197,24,0.3);">
                            <i class="fas fa-bullseye text-dark fa-lg"></i>
                        </div>
                        <h4 class="mt-2 mb-0" style="color:var(--cmic-blue); font-weight:700;">Misi</h4>
                    </div>
                    @php
                        $misiLines = array_filter(array_map('trim', explode("\n", $siteSettings['about_misi'] ?? "Memberikan layanan konsultansi berkualitas tinggi\nMengembangkan sumber daya manusia yang kompeten\nMenerapkan teknologi terkini dalam setiap proyek\nMenjaga kepercayaan klien dengan profesionalisme tinggi")));
                    @endphp
                    <ul class="ps-0 mx-auto text-start" style="list-style:none; display:table;">
                        @foreach($misiLines as $poin)
                        <li class="mb-2"><i class="fas fa-check me-2" style="color:var(--cmic-yellow); font-weight:700;"></i><span style="color:var(--cmic-blue); font-weight:500;">{{ $poin }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Struktur Organisasi --}}
@php
    $orgMode     = $siteSettings['about_org_mode'] ?? 'image';
    $orgTemplate = (int)($siteSettings['about_org_template'] ?? 1);
    $orgItems    = json_decode($siteSettings['about_org_data'] ?? '[]', true) ?: [];
    $showOrg     = ($orgMode === 'manual' && count($orgItems) > 0)
                || ($orgMode !== 'manual' && !empty($siteSettings['about_org_image']));
    $levelColors = [1=>'#0057A8',2=>'#1976D2',3=>'#00897B',4=>'#43A047',5=>'#78909C'];
    $levelNames  = [1=>'Pimpinan',2=>'Direktur',3=>'Manager',4=>'Supervisor',5=>'Staff'];
@endphp
@if($showOrg)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Struktur Organisasi</h2>
            <span class="section-divider"></span>
        </div>

        @if($orgMode === 'manual' && count($orgItems) > 0)
            @php $byLevel = collect($orgItems)->groupBy('level')->sortKeys(); @endphp

            {{-- ===== TEMPLATE 1: Hierarki Kotak ===== --}}
            @if($orgTemplate === 1)
            <div class="org-chart-wrap">
                @foreach($byLevel as $level => $members)
                @php $color = $levelColors[$level] ?? '#555'; @endphp
                <div class="org-level-row">
                    @foreach($members as $member)
                    <div class="org-node" style="border-color:{{ $color }};">
                        <div class="org-node-header" style="background:{{ $color }};">{{ $member['jabatan'] ?? '-' }}</div>
                        <div class="org-node-body">
                            @if(!empty($member['foto']))
                            <img src="{{ asset('storage/'.$member['foto']) }}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid {{ $color }};margin:0 auto 8px;display:block;box-shadow:0 4px 12px rgba(0,0,0,.15);">
                            @else
                            <div class="org-avatar" style="background:{{ $color }}20;color:{{ $color }};">{{ strtoupper(mb_substr($member['nama'] ?? 'X', 0, 1)) }}</div>
                            @endif
                            <div class="org-name">{{ $member['nama'] ?? '-' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if(!$loop->last)
                @php $nextLevel = $byLevel->keys()[$loop->index + 1] ?? $level; @endphp
                <div style="width:2px;height:32px;background:{{ $levelColors[$nextLevel] ?? '#ccc' }};margin:0 auto;"></div>
                @endif
                @endforeach
            </div>
            <style>
            .org-chart-wrap{display:flex;flex-direction:column;align-items:center;gap:0}
            .org-level-row{display:flex;justify-content:center;flex-wrap:wrap;gap:16px;margin:0}
            .org-node{border:2px solid;border-radius:12px;overflow:hidden;min-width:140px;max-width:180px;box-shadow:0 4px 12px rgba(0,0,0,.08);background:#fff;transition:transform .2s,box-shadow .2s}
            .org-node:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.12)}
            .org-node-header{color:#fff;font-size:11px;font-weight:700;padding:6px 10px;text-align:center;letter-spacing:.3px;text-transform:uppercase}
            .org-node-body{padding:14px 10px;text-align:center}
            .org-avatar{width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;margin:0 auto 8px}
            .org-name{font-size:13px;font-weight:600;color:#1a2a4a}
            </style>

            {{-- ===== TEMPLATE 2: Kartu Profil Grid ===== --}}
            @elseif($orgTemplate === 2)
            @foreach($byLevel as $level => $members)
            @php $color = $levelColors[$level] ?? '#555'; @endphp
            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background:{{ $color }};font-size:12px;padding:6px 14px;">{{ $levelNames[$level] ?? 'Level '.$level }}</span>
                    <hr class="flex-grow-1 m-0" style="border-color:{{ $color }}40;">
                </div>
                <div class="row g-3 justify-content-center">
                    @foreach($members as $member)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm text-center h-100 p-3" style="border-radius:16px;transition:transform .2s,box-shadow .2s;" onmouseenter="this.style.transform='translateY(-5px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.12)'" onmouseleave="this.style.transform='';this.style.boxShadow=''">
                            <div class="position-relative mx-auto mb-3" style="width:88px;">
                                @if(!empty($member['foto']))
                                <img src="{{ asset('storage/'.$member['foto']) }}" style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:4px solid {{ $color }};box-shadow:0 4px 16px {{ $color }}44;">
                                @else
                                <div style="width:88px;height:88px;border-radius:50%;background:{{ $color }}20;border:4px solid {{ $color }};display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:{{ $color }};">{{ strtoupper(mb_substr($member['nama'] ?? 'X',0,1)) }}</div>
                                @endif
                                <span style="position:absolute;bottom:2px;right:2px;width:18px;height:18px;border-radius:50%;background:{{ $color }};border:2px solid #fff;"></span>
                            </div>
                            <div class="fw-bold" style="font-size:13px;color:#1a2a4a;">{{ $member['nama'] ?? '-' }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">{{ $member['jabatan'] ?? '-' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            {{-- ===== TEMPLATE 3: Pohon Struktur ===== --}}
            @elseif($orgTemplate === 3)
            <style>
            .tree-wrap{display:flex;flex-direction:column;align-items:center;}
            .tree-level{display:flex;justify-content:center;flex-wrap:wrap;gap:24px;position:relative;padding:0 20px;}
            .tree-level::before{content:'';position:absolute;top:-20px;left:50%;width:2px;height:20px;background:#cbd5e1;}
            .tree-level:first-child::before{display:none;}
            .tree-spacer{height:20px;width:2px;background:#cbd5e1;margin:0 auto;}
            .tree-card{background:#fff;border-radius:14px;box-shadow:0 4px 20px rgba(0,87,168,.1);overflow:hidden;min-width:140px;max-width:170px;text-align:center;transition:transform .2s,box-shadow .2s;position:relative;}
            .tree-card:hover{transform:translateY(-4px);box-shadow:0 10px 32px rgba(0,87,168,.18);}
            .tree-card-bar{height:5px;}
            .tree-card-body{padding:14px 10px 12px;}
            .tree-card-name{font-size:12px;font-weight:700;color:#1a2a4a;margin-top:6px;}
            .tree-card-pos{font-size:10px;color:#64748b;margin-top:2px;}
            </style>
            <div class="tree-wrap">
                @foreach($byLevel as $level => $members)
                @php $color = $levelColors[$level] ?? '#555'; @endphp
                @if(!$loop->first)<div class="tree-spacer"></div>@endif
                <div class="tree-level" @if($loop->first) style="padding-top:0" @endif>
                    @foreach($members as $member)
                    <div class="tree-card">
                        <div class="tree-card-bar" style="background:{{ $color }};"></div>
                        <div class="tree-card-body">
                            @if(!empty($member['foto']))
                            <img src="{{ asset('storage/'.$member['foto']) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:3px solid {{ $color }}20;">
                            @else
                            <div style="width:56px;height:56px;border-radius:50%;background:{{ $color }}15;color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;margin:0 auto;">{{ strtoupper(mb_substr($member['nama'] ?? 'X',0,1)) }}</div>
                            @endif
                            <div class="tree-card-name">{{ $member['nama'] ?? '-' }}</div>
                            <div class="tree-card-pos">{{ $member['jabatan'] ?? '-' }}</div>
                            <span class="badge mt-2" style="background:{{ $color }}15;color:{{ $color }};font-size:9px;font-weight:700;border-radius:20px;padding:3px 8px;">{{ $levelNames[$level] ?? 'L'.$level }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>

            {{-- ===== TEMPLATE 4: Timeline Zigzag ===== --}}
            @elseif($orgTemplate === 4)
            <style>
            .tl-wrap{position:relative;max-width:800px;margin:0 auto;}
            .tl-wrap::before{content:'';position:absolute;left:50%;top:0;bottom:0;width:3px;background:linear-gradient(to bottom,#0057A8,#78909C);transform:translateX(-50%);border-radius:2px;}
            .tl-item{display:flex;align-items:center;margin-bottom:36px;position:relative;}
            .tl-item:nth-child(odd){flex-direction:row;}
            .tl-item:nth-child(even){flex-direction:row-reverse;}
            .tl-card{background:#fff;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,.08);padding:16px 18px;width:calc(50% - 40px);display:flex;align-items:center;gap:14px;transition:transform .2s,box-shadow .2s;}
            .tl-card:hover{transform:scale(1.02);box-shadow:0 8px 28px rgba(0,0,0,.13);}
            .tl-dot{width:20px;height:20px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 3px currentColor;z-index:1;flex-shrink:0;position:absolute;left:50%;transform:translateX(-50%);}
            .tl-item:nth-child(odd) .tl-card{margin-right:auto;}
            .tl-item:nth-child(even) .tl-card{margin-left:auto;}
            @media(max-width:576px){.tl-wrap::before{left:20px;}.tl-item,.tl-item:nth-child(even){flex-direction:row;}.tl-card,.tl-item:nth-child(even) .tl-card{width:calc(100% - 50px);margin-left:50px;}.tl-dot{left:20px;}}
            </style>
            <div class="tl-wrap">
                @foreach($orgItems as $member)
                @php $color = $levelColors[$member['level'] ?? 1] ?? '#555'; @endphp
                <div class="tl-item">
                    <div class="tl-card" style="border-left:4px solid {{ $color }};">
                        <div style="flex-shrink:0;">
                            @if(!empty($member['foto']))
                            <img src="{{ asset('storage/'.$member['foto']) }}" style="width:54px;height:54px;border-radius:50%;object-fit:cover;border:3px solid {{ $color }};">
                            @else
                            <div style="width:54px;height:54px;border-radius:50%;background:{{ $color }}18;color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;">{{ strtoupper(mb_substr($member['nama'] ?? 'X',0,1)) }}</div>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#1a2a4a;">{{ $member['nama'] ?? '-' }}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">{{ $member['jabatan'] ?? '-' }}</div>
                            <span class="badge mt-1" style="background:{{ $color }};font-size:9px;border-radius:20px;">{{ $levelNames[$member['level'] ?? 1] ?? '' }}</span>
                        </div>
                    </div>
                    <div class="tl-dot" style="color:{{ $color }};background:{{ $color }};"></div>
                </div>
                @endforeach
            </div>

            {{-- ===== TEMPLATE 5: Daftar Bergaya ===== --}}
            @elseif($orgTemplate === 5)
            <style>
            .dl-section{margin-bottom:32px;}
            .dl-section-title{display:flex;align-items:center;gap:12px;margin-bottom:14px;}
            .dl-section-title span{font-size:12px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;}
            .dl-section-title hr{flex:1;border-width:2px;margin:0;}
            .dl-item{display:flex;align-items:center;gap:16px;background:#fff;padding:14px 18px;border-radius:12px;margin-bottom:10px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:5px solid;transition:transform .15s,box-shadow .15s;}
            .dl-item:hover{transform:translateX(4px);box-shadow:0 4px 18px rgba(0,0,0,.1);}
            .dl-rank{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;}
            .dl-info-name{font-size:14px;font-weight:700;color:#1a2a4a;}
            .dl-info-pos{font-size:12px;color:#64748b;}
            </style>
            @foreach($byLevel as $level => $members)
            @php $color = $levelColors[$level] ?? '#555'; $ln = $levelNames[$level] ?? 'Level '.$level; @endphp
            <div class="dl-section">
                <div class="dl-section-title">
                    <span style="color:{{ $color }};">{{ $ln }}</span>
                    <hr style="border-color:{{ $color }}40;">
                </div>
                @foreach($members as $k => $member)
                <div class="dl-item" style="border-color:{{ $color }};">
                    <div class="dl-rank" style="background:{{ $color }};">{{ $k + 1 }}</div>
                    @if(!empty($member['foto']))
                    <img src="{{ asset('storage/'.$member['foto']) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:3px solid {{ $color }}20;flex-shrink:0;">
                    @else
                    <div style="width:52px;height:52px;border-radius:50%;background:{{ $color }}15;color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;flex-shrink:0;">{{ strtoupper(mb_substr($member['nama'] ?? 'X',0,1)) }}</div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="dl-info-name">{{ $member['nama'] ?? '-' }}</div>
                        <div class="dl-info-pos">{{ $member['jabatan'] ?? '-' }}</div>
                    </div>
                    <span class="badge" style="background:{{ $color }}18;color:{{ $color }};border-radius:20px;font-size:10px;padding:5px 12px;font-weight:700;">{{ $ln }}</span>
                </div>
                @endforeach
            </div>
            @endforeach

            @endif {{-- end template switch --}}

        @else
            <div class="text-center">
                <img src="{{ asset('storage/' . $siteSettings['about_org_image']) }}"
                     class="img-fluid rounded shadow" style="max-width:1000px;width:100%;"
                     alt="Struktur Organisasi {{ $siteSettings['company_name'] ?? '' }}">
            </div>
        @endif
    </div>
</section>
@endif

{{-- Legalitas Perusahaan --}}
@if($legalItems->isNotEmpty())
<section class="py-5" style="background:var(--cmic-light-bg)">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Legalitas Perusahaan</h2>
            <span class="section-divider"></span>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($legalItems as $item)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="d-flex flex-column align-items-center gap-3">
                        <div style="width:56px;height:56px;background:var(--cmic-blue);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(0,87,168,0.2);">
                            <i class="{{ $item->icon }} text-white fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1" style="letter-spacing:.4px;">{{ $item->label }}</div>
                            <div class="fw-bold" style="font-size:15px;color:var(--cmic-blue);">{{ $item->value }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
