@extends('layouts.app')
@section('title', ($page->meta_title ?: $page->title).' - '.($siteSettings['company_name'] ?? 'CMIC'))

@push('styles')
<style>
    .page-content h1, .page-content h2, .page-content h3,
    .page-content h4, .page-content h5, .page-content h6 {
        color: var(--cmic-dark-blue);
        margin-top: 1.5rem;
        margin-bottom: .75rem;
        font-weight: 700;
    }
    .page-content p { margin-bottom: 1rem; line-height: 1.8; color: #444; }
    .page-content ul, .page-content ol { padding-left: 1.5rem; margin-bottom: 1rem; }
    .page-content li { margin-bottom: .4rem; color: #444; line-height: 1.7; }
    .page-content img { max-width: 100%; border-radius: 8px; margin: 12px 0; }
    .page-content a { color: var(--cmic-blue); }
    .page-content blockquote {
        border-left: 4px solid var(--cmic-blue);
        background: #f0f7ff;
        padding: 14px 20px;
        border-radius: 0 8px 8px 0;
        margin: 1rem 0;
        color: #374151;
        font-style: italic;
    }
    .page-content table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
    .page-content table th { background: var(--cmic-blue); color: #fff; padding: 10px 14px; text-align: left; }
    .page-content table td { border: 1px solid #e2e8f0; padding: 10px 14px; color: #374151; }
    .page-content table tr:nth-child(even) td { background: #f8fafc; }
    .page-content hr { border-color: #e2e8f0; margin: 2rem 0; }
    .page-content pre { background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 8px; overflow-x: auto; }
    .page-content code { background: #f1f5f9; color: #c7254e; padding: 2px 6px; border-radius: 4px; font-size: .9em; }
    .page-content pre code { background: none; color: inherit; padding: 0; }
</style>
@endpush

@section('content')
{{-- Banner --}}
@if($page->banner_image)
<div style="position:relative; overflow:hidden; max-height:380px;">
    <img src="{{ asset('storage/'.$page->banner_image) }}"
         alt="{{ $page->title }}"
         style="width:100%; height:380px; object-fit:cover; display:block;">
    <div style="position:absolute; inset:0; background:linear-gradient(to bottom, rgba(0,30,80,.3), rgba(0,30,80,.65)); display:flex; align-items:center; justify-content:center;">
        <div class="text-center text-white px-3">
            <h1 style="font-weight:700; font-size:2.4rem; text-shadow:0 2px 8px rgba(0,0,0,.4);">{{ $page->title }}</h1>
        </div>
    </div>
</div>
@else
<div class="page-header">
    <div class="container">
        <h1>{{ $page->title }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>
</div>
@endif

{{-- Content --}}
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($page->banner_image)
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--cmic-blue);">Beranda</a></li>
                        <li class="breadcrumb-item active text-muted">{{ $page->title }}</li>
                    </ol>
                </nav>
                @endif

                <div class="page-content">
                    @if($page->content)
                        {!! Str::markdown($page->content) !!}
                    @else
                        <p class="text-muted text-center py-5">Konten halaman belum tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
