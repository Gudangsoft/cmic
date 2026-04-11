@extends('layouts.app')
@section('title', $jenisProyek->nama . ' - Pengalaman PT. CMIC')
@section('content')

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
$activeBg = $colorMap[$jenisProyek->warna] ?? '#1859A9';

// $allPhotos & $allPhotosSlice sudah dibangun di controller
@endphp

<style>
.jenis-nav-box { display:flex;align-items:flex-start;gap:6px;padding:10px 14px;border-radius:6px;text-decoration:none;color:#fff;font-size:13px;font-weight:600;line-height:1.3;transition:filter .2s,transform .15s; }
.jenis-nav-box:hover { color:#fff;filter:brightness(.85);transform:translateY(-1px); }
.jenis-nav-box.current { outline:3px solid rgba(255,255,255,.5);outline-offset:2px; }
.gallery-img-wrap { position:relative;overflow:hidden;border-radius:10px;cursor:pointer; }
.gallery-img-wrap img { width:100%;height:200px;object-fit:cover;transition:transform .3s; }
.gallery-img-wrap:hover img { transform:scale(1.05); }
.gallery-img-wrap .overlay { position:absolute;inset:0;background:rgba(0,0,0,.35);opacity:0;transition:opacity .2s;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem; }
.gallery-img-wrap:hover .overlay { opacity:1; }
/* Lightbox */
#lightboxModal .modal-dialog { max-width:900px; }
#lightboxModal .modal-content { background:#000;border:none; }
#lightboxModal img { max-height:80vh;object-fit:contain;width:100%; }
</style>

<div class="page-header" style="background:linear-gradient(135deg,{{ $activeBg }},{{ $activeBg }}cc);">
    <div class="container">
        <h1 style="font-size:clamp(1.2rem,3vw,1.8rem);">
            <i class="{{ $jenisProyek->ikon ?? 'fas fa-folder' }} me-2 opacity-75"></i>{{ $jenisProyek->nama }}
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pengalaman') }}" style="color:rgba(255,255,255,.7);">Pengalaman</a></li>
                <li class="breadcrumb-item active" style="color:#fff;">{{ $jenisProyek->nama }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">

            {{-- LEFT: Navigation Jenis --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;position:sticky;top:80px;">
                    <div class="card-header fw-bold text-white" style="background:{{ $activeBg }};font-size:13px;">
                        <i class="fas fa-layer-group me-2"></i>Bidang Pengalaman
                    </div>
                    <div class="card-body p-2">
                        @foreach($allJenis as $j)
                        @php $jBg = $colorMap[$j->warna] ?? '#1859A9'; @endphp
                        <a href="{{ route('pengalaman.jenis', $j) }}"
                           class="jenis-nav-box mb-2 {{ $j->id === $jenisProyek->id ? 'current':'' }}"
                           style="background:{{ $jBg }};">
                            <span style="flex-shrink:0;opacity:.8;">&#10022;</span>
                            <span>{{ $j->nama }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Content --}}
            <div class="col-lg-9">

                {{-- Items List --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header fw-bold text-white d-flex align-items-center justify-content-between"
                         style="background:{{ $activeBg }};">
                        <span><i class="{{ $jenisProyek->ikon ?? 'fas fa-folder' }} me-2"></i>{{ $jenisProyek->nama }}</span>
                        <span class="badge bg-white text-dark" style="font-size:12px;">{{ $projects->count() }} proyek</span>
                    </div>
                    <div class="card-body p-3">
                        @if($projects->count())
                        <div class="row g-3">
                            @foreach($projects as $proj)
                            <div class="col-sm-6 col-md-4">
                                <a href="{{ route('pengalaman.show', $proj) }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius:10px;overflow:hidden;transition:transform .2s,box-shadow .2s;"
                                         onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.12)'"
                                         onmouseleave="this.style.transform='';this.style.boxShadow=''">
                                        @if($proj->image)
                                        <img src="{{ asset('storage/'.$proj->image) }}" style="height:130px;object-fit:cover;width:100%;" alt="{{ $proj->name }}">
                                        @else
                                        <div style="height:80px;background:linear-gradient(135deg,{{ $activeBg }},{{ $activeBg }}aa);display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-briefcase text-white fa-2x opacity-50"></i>
                                        </div>
                                        @endif
                                        <div class="p-2">
                                            <div style="font-size:12.5px;font-weight:600;color:#1e3a5f;line-height:1.3;">{{ $proj->name }}</div>
                                            @if($proj->clientModel || $proj->client)
                                            <div class="text-muted mt-1" style="font-size:11px;">
                                                <i class="fas fa-building me-1"></i>{{ $proj->clientModel?->name ?? $proj->client }}
                                            </div>
                                            @endif
                                            @if($proj->year)
                                            <span class="badge bg-light text-dark border mt-1" style="font-size:10px;">{{ $proj->year }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-briefcase fa-2x mb-2 d-block" style="opacity:.3;"></i>
                            Belum ada data proyek untuk bidang ini.
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Gallery --}}
                @if($allPhotos->count())
                <div class="card border-0 shadow-sm mt-4" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header fw-bold" style="background:#f8fafc;color:#334155;border-bottom:2px solid {{ $activeBg }};">
                        <i class="fas fa-photo-video me-2" style="color:{{ $activeBg }};"></i>Galeri Foto Pengerjaan
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            @foreach($allPhotosSlice as $idx => $photo)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="gallery-img-wrap" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ $photo['src'] }}" alt="{{ $photo['caption'] }}" loading="lazy">
                                    <div class="overlay"><i class="fas fa-expand-alt"></i></div>
                                </div>
                                <div class="mt-1 text-muted text-truncate" style="font-size:11px;" title="{{ $photo['caption'] }}">{{ $photo['caption'] }}</div>
                            </div>
                            @endforeach
                        </div>
                        @if($allPhotos->count() > 12)
                        <div class="text-center mt-3">
                            <a href="{{ route('galeri') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-images me-1"></i>Lihat Semua Galeri ({{ $allPhotos->count() }} foto)
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('pengalaman') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Bidang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Lightbox Modal --}}
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0" style="background:#000;">
                <span class="text-white small" id="lbCaption"></span>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2 text-center" style="background:#000;position:relative;">
                <button class="btn btn-sm btn-dark rounded-circle" id="lbPrev"
                        style="position:absolute;left:8px;top:50%;transform:translateY(-50%);z-index:10;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img src="" id="lbImg" class="img-fluid" alt="">
                <button class="btn btn-sm btn-dark rounded-circle" id="lbNext"
                        style="position:absolute;right:8px;top:50%;transform:translateY(-50%);z-index:10;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const photos = @json($allPhotosSlice);
let lbIdx = 0;

function openLightbox(idx) {
    lbIdx = idx;
    updateLightbox();
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}

function updateLightbox() {
    document.getElementById('lbImg').src     = photos[lbIdx].src;
    document.getElementById('lbCaption').textContent = photos[lbIdx].caption;
}

document.getElementById('lbPrev').addEventListener('click', function(){
    lbIdx = (lbIdx - 1 + photos.length) % photos.length;
    updateLightbox();
});
document.getElementById('lbNext').addEventListener('click', function(){
    lbIdx = (lbIdx + 1) % photos.length;
    updateLightbox();
});
document.addEventListener('keydown', function(e){
    if(!document.getElementById('lightboxModal').classList.contains('show')) return;
    if(e.key === 'ArrowLeft')  { lbIdx = (lbIdx-1+photos.length)%photos.length; updateLightbox(); }
    if(e.key === 'ArrowRight') { lbIdx = (lbIdx+1)%photos.length; updateLightbox(); }
});
</script>
@endpush
