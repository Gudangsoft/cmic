@extends('layouts.app')
@section('title', $pengalamanProyek->nama . ' - Pengalaman PT. CMIC')
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
$jenis    = $pengalamanProyek->jenis;
$activeBg = $jenis ? ($colorMap[$jenis->warna] ?? '#1859A9') : '#1859A9';

// Build galeri photos
$allPhotos = collect();
if ($pengalamanProyek->gambar) {
    $allPhotos->push(['src' => asset('storage/'.$pengalamanProyek->gambar), 'caption' => $pengalamanProyek->nama]);
}
foreach ($pengalamanProyek->galeri ?? [] as $g) {
    $allPhotos->push(['src' => asset('storage/'.$g), 'caption' => $pengalamanProyek->nama]);
}
@endphp

<style>
.jenis-nav-box { display:flex;align-items:flex-start;gap:6px;padding:10px 14px;border-radius:6px;text-decoration:none;color:#fff;font-size:13px;font-weight:600;line-height:1.3;transition:filter .2s,transform .15s; }
.jenis-nav-box:hover { color:#fff;filter:brightness(.85);transform:translateY(-1px); }
.gallery-img-wrap { position:relative;overflow:hidden;border-radius:10px;cursor:pointer; }
.gallery-img-wrap img { width:100%;height:180px;object-fit:cover;transition:transform .3s; }
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
        <h1 style="font-size:clamp(1rem,2.5vw,1.5rem);line-height:1.4;">
            {{ $pengalamanProyek->nama }}
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,.7);">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pengalaman') }}" style="color:rgba(255,255,255,.7);">Pengalaman</a></li>
                @if($jenis)
                <li class="breadcrumb-item"><a href="{{ route('pengalaman.jenis', $jenis) }}" style="color:rgba(255,255,255,.7);">{{ $jenis->nama }}</a></li>
                @endif
                <li class="breadcrumb-item active" style="color:#fff;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($pengalamanProyek->nama, 50) }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">

            {{-- LEFT: Navigation sidebar --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;position:sticky;top:80px;">
                    <div class="card-header fw-bold text-white" style="background:{{ $activeBg }};font-size:13px;">
                        <i class="fas fa-layer-group me-2"></i>Bidang Pengalaman
                    </div>
                    <div class="card-body p-2">
                        @foreach($allJenis as $j)
                        @php $jBg = $colorMap[$j->warna] ?? '#1859A9'; @endphp
                        <a href="{{ route('pengalaman.jenis', $j) }}"
                           class="jenis-nav-box mb-2 {{ $jenis && $j->id === $jenis->id ? 'current':'' }}"
                           style="background:{{ $jBg }};{{ $jenis && $j->id === $jenis->id ? 'outline:3px solid rgba(255,255,255,.5);outline-offset:2px;':'' }}">
                            <span style="flex-shrink:0;opacity:.8;">&#10022;</span>
                            <span>{{ $j->nama }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Content --}}
            <div class="col-lg-9">

                {{-- Foto utama --}}
                @if($pengalamanProyek->gambar)
                <img src="{{ asset('storage/'.$pengalamanProyek->gambar) }}"
                     class="img-fluid rounded shadow-sm mb-4 w-100"
                     style="max-height:380px;object-fit:cover;"
                     alt="{{ $pengalamanProyek->nama }}">
                @endif

                {{-- Judul & Info --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header fw-bold text-white" style="background:{{ $activeBg }};">
                        <i class="{{ $jenis->ikon ?? 'fas fa-folder' }} me-2"></i>{{ $pengalamanProyek->nama }}
                    </div>
                    <div class="card-body">
                        {{-- Info ringkas --}}
                        @if($pengalamanProyek->pemberi_tugas || $pengalamanProyek->lokasi || $pengalamanProyek->tahun)
                        <div class="row g-3 mb-4">
                            @if($pengalamanProyek->pemberi_tugas)
                            <div class="col-sm-4">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-building mt-1" style="color:{{ $activeBg }};flex-shrink:0;"></i>
                                    <div>
                                        <div class="text-muted" style="font-size:11px;">Pemberi Tugas</div>
                                        <div class="fw-semibold" style="font-size:13.5px;">{{ $pengalamanProyek->pemberi_tugas }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($pengalamanProyek->lokasi)
                            <div class="col-sm-4">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-map-marker-alt mt-1" style="color:{{ $activeBg }};flex-shrink:0;"></i>
                                    <div>
                                        <div class="text-muted" style="font-size:11px;">Lokasi</div>
                                        <div class="fw-semibold" style="font-size:13.5px;">{{ $pengalamanProyek->lokasi }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($pengalamanProyek->tahun)
                            <div class="col-sm-4">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-calendar-alt mt-1" style="color:{{ $activeBg }};flex-shrink:0;"></i>
                                    <div>
                                        <div class="text-muted" style="font-size:11px;">Tahun</div>
                                        <div class="fw-semibold" style="font-size:13.5px;">{{ $pengalamanProyek->tahun }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Deskripsi --}}
                        @if($pengalamanProyek->deskripsi)
                        <div style="font-size:14.5px;color:#444;line-height:1.8;white-space:pre-line;">{{ $pengalamanProyek->deskripsi }}</div>
                        @else
                        <p class="text-muted fst-italic mb-0" style="font-size:14px;">Deskripsi belum tersedia untuk pekerjaan ini.</p>
                        @endif
                    </div>
                </div>

                {{-- Galeri --}}
                @if($allPhotos->count() > 0)
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header fw-bold" style="background:#f8fafc;color:#334155;border-bottom:2px solid {{ $activeBg }};">
                        <i class="fas fa-photo-video me-2" style="color:{{ $activeBg }};"></i>Galeri Foto
                        <span class="badge ms-2" style="background:{{ $activeBg }};font-size:11px;">{{ $allPhotos->count() }}</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            @foreach($allPhotos as $idx => $photo)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="gallery-img-wrap" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ $photo['src'] }}" alt="{{ $photo['caption'] }}" loading="lazy">
                                    <div class="overlay"><i class="fas fa-expand-alt"></i></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Related items --}}
                @if($related->count())
                <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header fw-bold" style="background:#f8fafc;color:#334155;border-bottom:2px solid {{ $activeBg }};">
                        <i class="fas fa-list me-2" style="color:{{ $activeBg }};"></i>Pekerjaan Lain di Bidang Ini
                    </div>
                    <div class="card-body px-4 py-2">
                        @foreach($related as $rel)
                        <div style="display:flex;align-items:flex-start;gap:10px;padding:9px 0;border-bottom:1px solid #f1f5f9;">
                            <i class="fas fa-chevron-right mt-1" style="font-size:11px;color:{{ $activeBg }};flex-shrink:0;"></i>
                            <a href="{{ route('pengalaman.item', $rel) }}"
                               style="font-size:14px;color:#334155;text-decoration:none;line-height:1.5;"
                               onmouseenter="this.style.color='{{ $activeBg }}'"
                               onmouseleave="this.style.color='#334155'">
                                {{ $rel->nama }}
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    @if($jenis)
                    <a href="{{ route('pengalaman.jenis', $jenis) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke {{ $jenis->nama }}
                    </a>
                    @endif
                    <a href="{{ route('pengalaman') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-th-large me-1"></i>Semua Bidang
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Lightbox Modal --}}
@if($allPhotos->count())
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2 z-3" data-bs-dismiss="modal"></button>
                <button class="btn btn-dark position-absolute start-0 top-50 translate-middle-y ms-2 z-3" style="border-radius:50%;width:36px;height:36px;padding:0;" onclick="lbNav(-1)"><i class="fas fa-chevron-left"></i></button>
                <img id="lbImg" src="" alt="" class="d-block mx-auto">
                <div id="lbCaption" class="text-white text-center py-2" style="font-size:13px;background:rgba(0,0,0,.6);"></div>
                <button class="btn btn-dark position-absolute end-0 top-50 translate-middle-y me-2 z-3" style="border-radius:50%;width:36px;height:36px;padding:0;" onclick="lbNav(1)"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const lbPhotos = @json($allPhotos->values());
let lbCurrent = 0;
function openLightbox(idx) {
    lbCurrent = idx;
    document.getElementById('lbImg').src = lbPhotos[idx].src;
    document.getElementById('lbCaption').textContent = lbPhotos[idx].caption;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
function lbNav(dir) {
    lbCurrent = (lbCurrent + dir + lbPhotos.length) % lbPhotos.length;
    document.getElementById('lbImg').src = lbPhotos[lbCurrent].src;
    document.getElementById('lbCaption').textContent = lbPhotos[lbCurrent].caption;
}
</script>
@endpush
@endif

@endsection
