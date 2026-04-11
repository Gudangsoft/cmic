@extends('layouts.app')
@section('title', $project->name . ' - PT. CMIC')
@section('content')

@php
$gallery = $project->gallery ?? [];
$allPhotos = collect();
if ($project->image) $allPhotos->push(['src' => asset('storage/'.$project->image), 'caption' => $project->name]);
foreach ($gallery as $g) $allPhotos->push(['src' => asset('storage/'.$g), 'caption' => $project->name]);

$colorMap = [
    'primary'   => '#1859A9',
    'warning'   => '#E5900A',
    'success'   => '#15803d',
    'danger'    => '#b91c1c',
    'info'      => '#0891b2',
    'secondary' => '#475569',
    'dark'      => '#1e293b',
];
$activeColor = $colorMap[$project->jenisProyek->warna ?? 'primary'] ?? '#1859A9';
@endphp

<style>
.gallery-thumb { position:relative;overflow:hidden;border-radius:8px;cursor:pointer; }
.gallery-thumb img { width:100%;height:160px;object-fit:cover;transition:transform .3s; }
.gallery-thumb:hover img { transform:scale(1.06); }
.gallery-thumb .overlay { position:absolute;inset:0;background:rgba(0,0,0,.35);opacity:0;transition:opacity .2s;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem; }
.gallery-thumb:hover .overlay { opacity:1; }
#lbModal .modal-dialog { max-width:900px; }
#lbModal .modal-content { background:#000;border:none; }
#lbModal img { max-height:82vh;object-fit:contain;width:100%; }
</style>

<div class="page-header" style="background:linear-gradient(135deg,{{ $activeColor }},{{ $activeColor }}cc);">
    <div class="container">
        <h1 style="font-size:22px;">{{ $project->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pengalaman') }}">Pengalaman</a></li>
                @if($project->jenisProyek)
                <li class="breadcrumb-item"><a href="{{ route('pengalaman.jenis', $project->jenisProyek) }}">{{ $project->jenisProyek->nama }}</a></li>
                @endif
                <li class="breadcrumb-item active" style="max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $project->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            {{-- Konten Utama --}}
            <div class="col-lg-8">
                {{-- Foto utama --}}
                @if($project->image)
                <div class="gallery-thumb mb-4" onclick="openLb(0)">
                    <img src="{{ asset('storage/'.$project->image) }}"
                         style="height:380px;border-radius:10px;"
                         alt="{{ $project->name }}">
                    <div class="overlay"><i class="fas fa-expand-alt"></i></div>
                </div>
                @else
                <div class="rounded mb-4 d-flex align-items-center justify-content-center"
                     style="height:220px; background:linear-gradient(135deg,{{ $activeColor }},{{ $activeColor }}cc);border-radius:10px;">
                    <i class="fas fa-briefcase fa-4x text-white opacity-50"></i>
                </div>
                @endif

                {{-- Judul & Kategori --}}
                <div class="mb-3">
                    @if($project->jenisProyek)
                    <span class="badge mb-2" style="background:{{ $activeColor }};font-size:12px;">{{ $project->jenisProyek->nama }}</span>
                    @elseif($project->category)
                    <span class="badge mb-2" style="background:{{ $activeColor }}; font-size:12px;">{{ $project->category }}</span>
                    @endif
                    <h2 style="color:var(--cmic-dark-blue); font-size:22px; font-weight:700; line-height:1.4;">
                        {{ $project->name }}
                    </h2>
                </div>

                {{-- Deskripsi / Uraian Pekerjaan --}}
                @if($project->description)
                <div class="mb-4">
                    <h5 style="color:{{ $activeColor }}; font-size:15px; font-weight:600; border-bottom:2px solid {{ $activeColor }}; padding-bottom:6px; margin-bottom:12px;">
                        <i class="fas fa-align-left me-2"></i>Uraian Pekerjaan
                    </h5>
                    <div style="font-size:14.5px; color:#444; line-height:1.8; white-space:pre-line;">{{ $project->description }}</div>
                </div>
                @endif

                {{-- Galeri Album --}}
                @if(count($gallery) > 0)
                <div class="mb-4">
                    <h5 style="color:{{ $activeColor }}; font-size:15px; font-weight:600; border-bottom:2px solid {{ $activeColor }}; padding-bottom:6px; margin-bottom:14px;">
                        <i class="fas fa-images me-2"></i>Galeri Foto
                        <span class="badge ms-2" style="background:{{ $activeColor }};font-size:11px;">{{ count($gallery) }} foto</span>
                    </h5>
                    <div class="row g-2">
                        @foreach($gallery as $idx => $img)
                        @php $lbIdx = $project->image ? $idx + 1 : $idx; @endphp
                        <div class="col-6 col-md-4">
                            <div class="gallery-thumb" onclick="openLb({{ $lbIdx }})">
                                <img src="{{ asset('storage/'.$img) }}" alt="Galeri {{ $idx+1 }}" loading="lazy">
                                <div class="overlay"><i class="fas fa-expand-alt"></i></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Tombol Kembali --}}
                @if($project->jenisProyek)
                <a href="{{ route('pengalaman.jenis', $project->jenisProyek) }}" class="btn mt-2 me-2" style="border:2px solid {{ $activeColor }};color:{{ $activeColor }};" onmouseenter="this.style.background='{{ $activeColor }}';this.style.color='#fff'" onmouseleave="this.style.background='';this.style.color='{{ $activeColor }}'">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke {{ $project->jenisProyek->nama }}
                </a>
                @else
                <a href="{{ route('pengalaman') }}" class="btn btn-outline-primary mt-2">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pengalaman
                </a>
                @endif
            </div>

            {{-- Sidebar Info --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:12px;">
                    <h6 style="color:{{ $activeColor }}; font-weight:700; border-bottom:2px solid {{ $activeColor }}; padding-bottom:8px; margin-bottom:16px;">
                        <i class="fas fa-info-circle me-2"></i>Informasi Proyek
                    </h6>
                    <table class="table table-borderless mb-0" style="font-size:13.5px;">
                        <tbody>
                            @if($project->clientModel)
                            <tr>
                                <td class="ps-0 text-muted fw-semibold" style="width:120px;vertical-align:top;">
                                    <i class="fas fa-building me-1"></i>Pemberi Tugas
                                </td>
                                <td class="fw-medium">
                                    @if($project->clientModel->logo)
                                    <img src="{{ asset('storage/'.$project->clientModel->logo) }}" style="height:18px;object-fit:contain;margin-right:4px;">
                                    @endif
                                    {{ $project->clientModel->name }}
                                </td>
                            </tr>
                            @elseif($project->client)
                            <tr>
                                <td class="ps-0 text-muted fw-semibold" style="width:120px;vertical-align:top;">
                                    <i class="fas fa-building me-1"></i>Pemberi Tugas
                                </td>
                                <td class="fw-medium">{{ $project->client }}</td>
                            </tr>
                            @endif
                            @if($project->location)
                            <tr>
                                <td class="ps-0 text-muted fw-semibold" style="vertical-align:top;">
                                    <i class="fas fa-map-marker-alt me-1"></i>Lokasi
                                </td>
                                <td>{{ $project->location }}</td>
                            </tr>
                            @endif
                            @if($project->year)
                            <tr>
                                <td class="ps-0 text-muted fw-semibold" style="vertical-align:top;">
                                    <i class="fas fa-calendar-alt me-1"></i>Tahun
                                </td>
                                <td>{{ $project->year }}</td>
                            </tr>
                            @endif
                            @if($project->jenisProyek)
                            <tr>
                                <td class="ps-0 text-muted fw-semibold" style="vertical-align:top;">
                                    <i class="fas fa-tag me-1"></i>Bidang
                                </td>
                                <td><span class="badge" style="background:{{ $activeColor }};">{{ $project->jenisProyek->nama }}</span></td>
                            </tr>
                            @endif
                            @if(count($gallery) > 0)
                            <tr>
                                <td class="ps-0 text-muted fw-semibold" style="vertical-align:top;">
                                    <i class="fas fa-images me-1"></i>Album
                                </td>
                                <td>{{ count($gallery) }} foto</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Proyek Terkait --}}
                @if($related->count())
                <div class="card border-0 shadow-sm p-4" style="border-radius:12px;">
                    <h6 style="color:{{ $activeColor }}; font-weight:700; border-bottom:2px solid {{ $activeColor }}; padding-bottom:8px; margin-bottom:16px;">
                        <i class="fas fa-layer-group me-2"></i>Proyek Terkait
                    </h6>
                    @foreach($related as $rel)
                    <a href="{{ route('pengalaman.show', $rel) }}"
                       class="d-flex gap-3 align-items-start mb-3 text-decoration-none"
                       style="color:inherit;">
                        @if($rel->image)
                        <img src="{{ asset('storage/'.$rel->image) }}"
                             style="width:60px;height:50px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                        @else
                        <div style="width:60px;height:50px;background:linear-gradient(135deg,{{ $activeColor }},{{ $activeColor }}cc);border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-briefcase text-white" style="font-size:12px;"></i>
                        </div>
                        @endif
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--cmic-dark-blue);line-height:1.4;">{{ $rel->name }}</div>
                            @if($rel->year)<small class="text-muted">{{ $rel->year }}</small>@endif
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Lightbox --}}
@if($allPhotos->count())
<div class="modal fade" id="lbModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2 z-3" data-bs-dismiss="modal"></button>
                <button class="btn btn-dark position-absolute start-0 top-50 translate-middle-y ms-2 z-3"
                        style="border-radius:50%;width:36px;height:36px;padding:0;" onclick="lbNav(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <img id="lbImg" src="" alt="" class="d-block mx-auto">
                <div id="lbCaption" class="text-white text-center py-2" style="font-size:13px;background:rgba(0,0,0,.6);"></div>
                <button class="btn btn-dark position-absolute end-0 top-50 translate-middle-y me-2 z-3"
                        style="border-radius:50%;width:36px;height:36px;padding:0;" onclick="lbNav(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const lbPhotos = @json($allPhotos->values());
let lbCurrent = 0;
function openLb(idx) {
    lbCurrent = idx;
    document.getElementById('lbImg').src = lbPhotos[idx].src;
    document.getElementById('lbCaption').textContent = lbPhotos[idx].caption;
    new bootstrap.Modal(document.getElementById('lbModal')).show();
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
