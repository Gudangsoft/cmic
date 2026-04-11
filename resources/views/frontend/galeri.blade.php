@extends('layouts.app')
@section('title', 'Galeri - PT. CMIC')
@push('styles')
<style>
/* Level-1: Album cards */
.album-l1-card {
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,.10);
    background: #fff; cursor: pointer;
    transition: transform .25s, box-shadow .25s;
}
.album-l1-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,87,168,.18); }
.album-l1-cover {
    position: relative; aspect-ratio: 4/3;
    overflow: hidden; background: #0f172a;
}
.album-l1-cover img { width:100%; height:100%; object-fit:cover; transition: transform .35s; }
.album-l1-card:hover .album-l1-cover img { transform: scale(1.06); }
.album-l1-overlay {
    position:absolute; inset:0;
    background: linear-gradient(to top, rgba(0,0,0,.6) 0%, transparent 55%);
    display:flex; align-items:flex-end; padding:12px 14px;
}
.count-badge {
    background: rgba(255,255,255,.18); backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.3);
    color:#fff; font-size:11.5px; font-weight:600;
    padding: 3px 10px; border-radius: 20px;
}
.sub-count-badge {
    position:absolute; top:10px; right:10px;
    background: rgba(0,87,168,.88); color:#fff;
    font-size:10px; font-weight:700;
    padding: 2px 9px; border-radius: 4px;
}
.album-l1-title { padding:10px 14px 12px; background:#fff; }
.album-l1-title h6 { font-size:14px; font-weight:700; color:var(--cmic-blue,#0057A8); margin:0 0 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.album-l1-title .sub { font-size:11.5px; color:#64748b; }

/* Level-2: Sub-album cards (shown in modal) */
.sub-album-card {
    border-radius:10px; overflow:hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.08);
    background:#fff; cursor:pointer;
    transition: transform .2s, box-shadow .2s;
}
.sub-album-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,87,168,.14); }
.sub-album-cover { position:relative; aspect-ratio:4/3; overflow:hidden; background:#0f172a; }
.sub-album-cover img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.sub-album-card:hover .sub-album-cover img { transform:scale(1.06); }
.sub-album-cover-overlay {
    position:absolute; inset:0;
    background:linear-gradient(to top, rgba(0,0,0,.55) 0%, transparent 55%);
    display:flex; align-items:flex-end; padding:8px 10px;
}
.sub-album-title-bar { padding:8px 10px 10px; }
.sub-album-title-bar h6 { font-size:13px; font-weight:700; color:#1e3a5f; margin:0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

/* Hidden fancybox triggers */
.gallery-hidden-links { display:none; }
</style>
@endpush
@section('content')
<div class="page-header">
    <div class="container">
        <h1>Galeri</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Galeri</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Galeri Foto & Video</h2>
            <span class="section-divider"></span>
        </div>

        @php
            $namedAlbums = $albumGroups->filter(fn($v, $k) => $k !== '');
            $noAlbumSubs = $albumGroups->get('', collect());
        @endphp

        @if($albumGroups->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-images fa-3x mb-3 d-block"></i>
            <p>Data galeri belum tersedia.</p>
        </div>
        @else

        {{-- ====== Named Albums (Level 1 cards) ====== --}}
        @if($namedAlbums->isNotEmpty())
        <div class="row g-4">
            @foreach($namedAlbums as $albumName => $subAlbums)
            @php
                $allItems   = $subAlbums->flatten();
                $firstItem  = $allItems->first();
                $cover      = $firstItem && $firstItem->type === 'image' && $firstItem->image
                                ? asset('storage/'.$firstItem->image) : ($firstItem ? $firstItem->video_thumbnail : null);
                $totalCount = $allItems->count();
                $subCount   = $subAlbums->count();
                $albumId    = 'album_' . $loop->index;
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="album-l1-card" onclick="openAlbumModal('{{ $albumId }}')">
                    <div class="album-l1-cover">
                        @if($cover)
                            <img src="{{ $cover }}" alt="{{ $albumName }}" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-layer-group fa-3x" style="color:#334155;"></i>
                            </div>
                        @endif
                        <div class="album-l1-overlay">
                            <span class="count-badge"><i class="fas fa-images me-1"></i>{{ $totalCount }} foto</span>
                        </div>
                        @if($subCount > 1)
                        <span class="sub-count-badge"><i class="fas fa-folder me-1"></i>{{ $subCount }} album</span>
                        @endif
                    </div>
                    <div class="album-l1-title">
                        <h6 title="{{ $albumName }}">{{ $albumName }}</h6>
                        <div class="sub"><i class="fas fa-layer-group me-1"></i>{{ $subCount }} sub-album</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ====== No-album items: render sub-albums directly ====== --}}
        @if($noAlbumSubs->isNotEmpty())
        @if($namedAlbums->isNotEmpty())
        <h5 class="mt-5 mb-3" style="color:#64748b;font-size:15px;"><i class="fas fa-folder-open me-2"></i>Galeri Lainnya</h5>
        @endif
        <div class="row g-4">
            @foreach($noAlbumSubs as $subTitle => $items)
            @php
                $firstItem  = $items->first();
                $isVideo    = $firstItem && $firstItem->type === 'video';
                $cover      = $isVideo ? ($firstItem->video_thumbnail ?? null) : ($firstItem && $firstItem->image ? asset('storage/'.$firstItem->image) : null);
                $subKey     = 'noalbum_' . $loop->index;
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6">
                {{-- Hidden fancybox links --}}
                <div class="gallery-hidden-links">
                    @foreach($items as $item)
                    @php $isVid = $item->type === 'video'; $href = $isVid ? $item->video_embed_url : ($item->image ? asset('storage/'.$item->image) : null); @endphp
                    @if($href)
                    <a href="{{ $href }}"
                       data-fancybox="{{ $subKey }}"
                       data-type="{{ $isVid ? 'iframe' : 'image' }}"
                       data-caption="{{ $subTitle }}"
                       @if($isVid)data-options='{"iframe":{"css":{"width":"90vw","height":"80vh"}}}'@endif></a>
                    @endif
                    @endforeach
                </div>
                <div class="sub-album-card" onclick="openAlbumGroup('{{ $subKey }}')">
                    <div class="sub-album-cover">
                        @if($cover)
                            <img src="{{ $cover }}" alt="{{ $subTitle }}" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><i class="fas fa-images fa-2x" style="color:#334155;"></i></div>
                        @endif
                        <div class="sub-album-cover-overlay">
                            <span class="count-badge"><i class="fas {{ $isVideo ? 'fa-video' : 'fa-images' }} me-1"></i>{{ $items->count() }} {{ $isVideo ? 'video' : 'foto' }}</span>
                        </div>
                    </div>
                    <div class="sub-album-title-bar">
                        <h6 title="{{ $subTitle }}">{{ $subTitle }}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @endif {{-- end if not empty --}}
    </div>
</section>

{{-- ====== Sub-album modals for named albums ====== --}}
@foreach($namedAlbums as $albumName => $subAlbums)
@php $albumId = 'album_' . $loop->index; @endphp
<div class="modal fade" id="modal_{{ $albumId }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-layer-group me-2 text-primary"></i>{{ $albumName }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @foreach($subAlbums as $subTitle => $items)
                    @php
                        $fi  = $items->first();
                        $isV = $fi && $fi->type === 'video';
                        $cov = $isV ? ($fi->video_thumbnail ?? null) : ($fi && $fi->image ? asset('storage/'.$fi->image) : null);
                        $sid = $albumId . '_sub_' . $loop->index;
                    @endphp
                    {{-- Hidden fancybox links for this sub-album --}}
                    <div class="gallery-hidden-links">
                        @foreach($items as $item)
                        @php $isVid = $item->type === 'video'; $href = $isVid ? $item->video_embed_url : ($item->image ? asset('storage/'.$item->image) : null); @endphp
                        @if($href)
                        <a href="{{ $href }}"
                           data-fancybox="{{ $sid }}"
                           data-type="{{ $isVid ? 'iframe' : 'image' }}"
                           data-caption="{{ $subTitle }}"
                           @if($isVid)data-options='{"iframe":{"css":{"width":"90vw","height":"80vh"}}}'@endif></a>
                        @endif
                        @endforeach
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="sub-album-card" onclick="openAlbumGroup('{{ $sid }}')">
                            <div class="sub-album-cover">
                                @if($cov)
                                    <img src="{{ $cov }}" alt="{{ $subTitle }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><i class="fas fa-images fa-2x" style="color:#334155;"></i></div>
                                @endif
                                <div class="sub-album-cover-overlay">
                                    <span class="count-badge"><i class="fas fa-{{ $isV ? 'video' : 'images' }} me-1"></i>{{ $items->count() }} {{ $isV ? 'video' : 'foto' }}</span>
                                </div>
                            </div>
                            <div class="sub-album-title-bar">
                                <h6 title="{{ $subTitle }}">{{ $subTitle }}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
@push('scripts')
<script>
function openAlbumModal(albumId) {
    var modal = new bootstrap.Modal(document.getElementById('modal_' + albumId));
    modal.show();
}
function openAlbumGroup(groupKey) {
    // Close any open Bootstrap modal first, then open Fancybox after transition
    var openModal = document.querySelector('.modal.show');
    if (openModal) {
        var bsModal = bootstrap.Modal.getInstance(openModal);
        if (bsModal) {
            openModal.addEventListener('hidden.bs.modal', function handler() {
                openModal.removeEventListener('hidden.bs.modal', handler);
                var first = document.querySelector('[data-fancybox="' + groupKey + '"]');
                if (first) first.click();
            }, { once: true });
            bsModal.hide();
            return;
        }
    }
    var first = document.querySelector('[data-fancybox="' + groupKey + '"]');
    if (first) first.click();
}
</script>
@endpush


