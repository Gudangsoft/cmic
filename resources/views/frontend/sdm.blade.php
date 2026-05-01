@extends('layouts.app')
@section('title', 'SDM - PT. CMIC')
@section('content')
<div class="page-header">
    <div class="container">
        <h1>Sumber Daya Manusia</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">SDM</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        @if($members->count())
            @foreach($members as $section => $sectionMembers)
            <div class="mb-5">
                <div class="text-center mb-4">
                    <h4 style="display:inline-block;color:#fff;background:var(--cmic-dark-blue);padding:8px 32px;border-radius:6px;font-size:16px;font-weight:600;letter-spacing:.3px;">
                        {{ $section ?: 'Lainnya' }}
                    </h4>
                </div>
                <div class="row g-4 justify-content-center">
                    @foreach($sectionMembers as $member)
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="card border-0 shadow-sm p-4 team-card h-100 text-center">
                            <img src="{{ $member->photo ? asset('storage/'.$member->photo) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=0057A8&color=fff&size=130' }}"
                                 alt="{{ $member->name }}"
                                 style="display:block;margin:0 auto 12px;">
                            <div class="name">{{ $member->name }}</div>
                            @if($member->position && $member->position !== 'Tenaga Ahli')
                            <div class="position mb-2">{{ $member->position }}</div>
                            @endif
                            @if($member->education)
                                <div class="mt-1" style="font-size:12px; color:#888;"><i class="fas fa-graduation-cap me-1"></i>{{ $member->education }}</div>
                            @endif
                            @if($member->expertise)
                                <div class="mt-1" style="font-size:12px; color:#888;"><i class="fas fa-star me-1"></i>{{ $member->expertise }}</div>
                            @endif
                            @if($member->description)
                                <div class="mt-2" style="font-size:12px; color:#555; line-height:1.6;">{{ $member->description }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-users fa-3x mb-3"></i>
            <p>Data SDM belum tersedia.</p>
        </div>
        @endif
    </div>
</section>
@endsection
