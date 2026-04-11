@extends('layouts.admin')
@section('title','Galeri') @section('page-title','Manajemen Galeri')
@section('breadcrumb')<li class="breadcrumb-item active">Galeri</li>@endsection
@push('styles')
<style>
/* Album accordion */
.album-section { border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:18px; }
.album-section-header { background:linear-gradient(135deg,#0057A8 0%,#1a7cd6 100%); color:#fff; padding:12px 18px; display:flex; align-items:center; gap:10px; cursor:pointer; user-select:none; }
.album-section-header:hover { background:linear-gradient(135deg,#004d96 0%,#1570c5 100%); }
.album-section-header .album-icon { width:36px; height:36px; background:rgba(255,255,255,.2); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.album-section-header .album-name { font-size:16px; font-weight:700; flex:1; }
.album-section-header .album-meta { font-size:12px; opacity:.85; }
.album-section-header .chevron { transition:transform .25s; }
.album-section-header.collapsed .chevron { transform:rotate(-90deg); }
.no-album-header { background:linear-gradient(135deg,#64748b 0%,#94a3b8 100%); }

/* Sub-album rows */
.sub-album-section { border-bottom:1px solid #f1f5f9; }
.sub-album-section:last-child { border-bottom:none; }
.sub-album-header { background:#f8faff; padding:10px 18px; display:flex; align-items:center; gap:10px; cursor:pointer; }
.sub-album-header:hover { background:#eef3fb; }
.sub-album-cover { width:56px; height:42px; object-fit:cover; border-radius:7px; flex-shrink:0; }
.sub-album-cover-placeholder { width:56px; height:42px; background:#1e293b; border-radius:7px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
.sub-album-title { font-size:14px; font-weight:600; color:#1e3a5f; flex:1; }
.sub-album-count { font-size:12px; color:#64748b; }
.sub-album-chevron { color:#94a3b8; transition:transform .2s; }
.sub-album-header.collapsed .sub-album-chevron { transform:rotate(-90deg); }

/* Photo grid inside sub-album */
.sub-album-photos { padding:12px 18px 14px; background:#fff; }
.gallery-grid-inner { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:10px; }
.gallery-item { position:relative; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,.07); background:#f1f5f9; aspect-ratio:4/3; }
.gallery-item img { width:100%;height:100%;object-fit:cover; }
.gallery-item .overlay { position:absolute;inset:0;background:rgba(0,0,0,0);transition:background .2s;display:flex;flex-direction:column;justify-content:flex-end;padding:8px; }
.gallery-item:hover .overlay { background:rgba(0,0,0,.55); }
.gallery-item .overlay-content { opacity:0;transition:opacity .2s; }
.gallery-item:hover .overlay-content { opacity:1; }
.gallery-item .item-title { color:#fff;font-size:11px;font-weight:600;line-height:1.3; }
.gallery-item .video-play-icon { position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none; }
.gallery-item .video-play-icon i { font-size:32px;color:rgba(255,255,255,.9);filter:drop-shadow(0 2px 4px rgba(0,0,0,.5)); }
.item-badge-abs { position:absolute;top:6px;right:6px; }
.item-actions { display:flex;gap:4px;margin-top:5px; }

/* Import modal */
.album-card { border:2px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:14px; transition:border-color .2s; }
.album-card.selected { border-color:#0057A8; }
.album-card-head { background:#f8faff; padding:10px 14px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:10px; }
.album-card-head .chk { width:20px; height:20px; cursor:pointer; flex-shrink:0; }
.album-card-fields { padding:10px 14px; background:#fff; border-bottom:1px solid #f1f5f9; display:none; }
.album-card-fields.show { display:block; }
.album-card-photos { display:flex; flex-wrap:wrap; gap:6px; padding:10px 14px; background:#fafbff; }
.album-thumb-sm { width:70px; height:52px; object-fit:cover; border-radius:6px; opacity:.75; }
.album-card.selected .album-thumb-sm { opacity:1; }
.album-header-actions { display:flex; align-items:center; gap:6px; flex-shrink:0; }
.album-header-actions .btn { font-size:11px; padding:3px 10px; border-color:rgba(255,255,255,.4); color:#fff; background:rgba(255,255,255,.12); }
.album-header-actions .btn:hover { background:rgba(255,255,255,.28); }
.album-header-actions .btn-del { border-color:rgba(255,100,100,.5); background:rgba(200,0,0,.25); }
.album-header-actions .btn-del:hover { background:rgba(200,0,0,.45); }
</style>
@endpush
@section('content')
<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-photo-video me-2"></i>Daftar Galeri</span>
        <div class="d-flex gap-2">
            @if($projects->count())
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-1"></i>Import dari Proyek
            </button>
            @endif
            <a href="{{ route('admin.galleries.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($galleries->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-photo-video fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada item galeri.
        </div>
        @else
        @php
            $grouped = $galleries->groupBy(fn($g) => $g->album ?: '');
            $named   = $grouped->filter(fn($v, $k) => $k !== '');
            $noAlbum = $grouped->get('', collect());
        @endphp

        {{-- Named Albums --}}
        @foreach($named as $albumName => $albumItems)
        @php $subAlbums = $albumItems->groupBy('title'); $totalPhotos = $albumItems->count(); @endphp
        <div class="album-section">
            <div class="album-section-header" onclick="toggleAlbumSection(this)" data-bs-toggle="collapse" data-bs-target="#albumCollapse{{ $loop->index }}">
                <div class="album-icon"><i class="fas fa-layer-group fa-lg"></i></div>
                <div class="album-name">{{ $albumName }}</div>
                <div class="album-meta me-auto">{{ $subAlbums->count() }} sub-album · {{ $totalPhotos }} foto</div>
                <div class="album-header-actions" onclick="event.stopPropagation()">
                    <button type="button" class="btn btn-sm" onclick="openRenameModal('{{ addslashes($albumName) }}')"><i class="fas fa-pen me-1"></i>Ubah Nama</button>
                    <button type="button" class="btn btn-sm btn-del" onclick="confirmDestroyAlbum('{{ addslashes($albumName) }}', {{ $totalPhotos }})"><i class="fas fa-trash me-1"></i>Hapus Album</button>
                </div>
                <i class="fas fa-chevron-down chevron ms-2"></i>
            </div>
            <div id="albumCollapse{{ $loop->index }}" class="collapse show">
                @foreach($subAlbums as $subTitle => $subItems)
                @php
                    $first = $subItems->first();
                    $cover = $first && $first->type === 'image' && $first->image ? asset('storage/'.$first->image) : ($first ? $first->video_thumbnail : null);
                @endphp
                <div class="sub-album-section">
                    <div class="sub-album-header" onclick="toggleSubAlbum(this)"
                         data-bs-toggle="collapse" data-bs-target="#sub_{{ $loop->parent->index }}_{{ $loop->index }}">
                        @if($cover)
                            <img src="{{ $cover }}" class="sub-album-cover" alt="">
                        @else
                            <div class="sub-album-cover-placeholder"><i class="fas fa-images" style="color:#64748b;font-size:14px;"></i></div>
                        @endif
                        <div class="sub-album-title">{{ $subTitle }}</div>
                        <div class="sub-album-count me-2">{{ $subItems->count() }} foto</div>
                        <i class="fas fa-chevron-down sub-album-chevron"></i>
                    </div>
                    <div id="sub_{{ $loop->parent->index }}_{{ $loop->index }}" class="collapse">
                        <div class="sub-album-photos">
                            <div class="gallery-grid-inner">
                                @foreach($subItems as $g)
                                @php $isVideo = $g->type === 'video'; $thumb = $isVideo ? ($g->video_thumbnail ?? null) : ($g->image ? asset('storage/'.$g->image) : null); @endphp
                                <div class="gallery-item">
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $g->title }}" loading="lazy">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#1e293b;"><i class="fas fa-film fa-lg" style="color:#64748b;"></i></div>
                                    @endif
                                    @if($isVideo)<div class="video-play-icon"><i class="fas fa-play-circle"></i></div>@endif
                                    <div class="item-badge-abs">
                                        <span class="badge {{ $g->is_active ? 'bg-success':'bg-secondary' }}" style="font-size:9px;">{{ $g->is_active ? 'Aktif':'Off' }}</span>
                                    </div>
                                    <div class="overlay">
                                        <div class="overlay-content">
                                            @if($g->category)<div class="item-title">{{ $g->category }}</div>@endif
                                            <div class="item-actions">
                                                <a href="{{ route('admin.galleries.edit',$g) }}" class="btn btn-warning btn-sm py-1 px-2" style="font-size:10px;"><i class="fas fa-pen"></i></a>
                                                <form action="{{ route('admin.galleries.destroy',$g) }}" method="POST" id="dg{{ $g->id }}">@csrf @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm py-1 px-2" style="font-size:10px;" onclick="confirmDelete(document.getElementById('dg{{ $g->id }}'),'Hapus foto ini?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Items without an album --}}
        @if($noAlbum->isNotEmpty())
        @php $subAlbums = $noAlbum->groupBy('title'); @endphp
        <div class="album-section">
            <div class="album-section-header no-album-header" onclick="toggleAlbumSection(this)" data-bs-toggle="collapse" data-bs-target="#albumCollapseNone">
                <div class="album-icon"><i class="fas fa-folder fa-lg"></i></div>
                <div class="album-name" style="color:rgba(255,255,255,.9);">Tanpa Album</div>
                <div class="album-meta">{{ $subAlbums->count() }} group · {{ $noAlbum->count() }} foto</div>
                <i class="fas fa-chevron-down chevron ms-2"></i>
            </div>
            <div id="albumCollapseNone" class="collapse show">
                @foreach($subAlbums as $subTitle => $subItems)
                @php
                    $first = $subItems->first();
                    $cover = $first && $first->type === 'image' && $first->image ? asset('storage/'.$first->image) : ($first ? $first->video_thumbnail : null);
                @endphp
                <div class="sub-album-section">
                    <div class="sub-album-header" onclick="toggleSubAlbum(this)"
                         data-bs-toggle="collapse" data-bs-target="#subNone_{{ $loop->index }}">
                        @if($cover)
                            <img src="{{ $cover }}" class="sub-album-cover" alt="">
                        @else
                            <div class="sub-album-cover-placeholder"><i class="fas fa-images" style="color:#64748b;font-size:14px;"></i></div>
                        @endif
                        <div class="sub-album-title">{{ $subTitle }}</div>
                        <div class="sub-album-count me-2">{{ $subItems->count() }} foto</div>
                        <i class="fas fa-chevron-down sub-album-chevron"></i>
                    </div>
                    <div id="subNone_{{ $loop->index }}" class="collapse">
                        <div class="sub-album-photos">
                            <div class="gallery-grid-inner">
                                @foreach($subItems as $g)
                                @php $isVideo = $g->type === 'video'; $thumb = $isVideo ? ($g->video_thumbnail ?? null) : ($g->image ? asset('storage/'.$g->image) : null); @endphp
                                <div class="gallery-item">
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $g->title }}" loading="lazy">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#1e293b;"><i class="fas fa-film fa-lg" style="color:#64748b;"></i></div>
                                    @endif
                                    @if($isVideo)<div class="video-play-icon"><i class="fas fa-play-circle"></i></div>@endif
                                    <div class="item-badge-abs">
                                        <span class="badge {{ $g->is_active ? 'bg-success':'bg-secondary' }}" style="font-size:9px;">{{ $g->is_active ? 'Aktif':'Off' }}</span>
                                    </div>
                                    <div class="overlay">
                                        <div class="overlay-content">
                                            @if($g->category)<div class="item-title">{{ $g->category }}</div>@endif
                                            <div class="item-actions">
                                                <a href="{{ route('admin.galleries.edit',$g) }}" class="btn btn-warning btn-sm py-1 px-2" style="font-size:10px;"><i class="fas fa-pen"></i></a>
                                                <form action="{{ route('admin.galleries.destroy',$g) }}" method="POST" id="dgn{{ $g->id }}">@csrf @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm py-1 px-2" style="font-size:10px;" onclick="confirmDelete(document.getElementById('dgn{{ $g->id }}'),'Hapus foto ini?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif {{-- empty check --}}
    </div>
</div>

{{-- Rename Album Modal --}}
<div class="modal fade" id="renameAlbumModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fas fa-pen me-2 text-primary"></i>Ubah Nama Album</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.galleries.albumRename') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="old_name" id="renameOldName">
                    <div class="mb-2">
                        <label class="form-label fw-medium" style="font-size:13px;">Nama Lama</label>
                        <div class="form-control bg-light" id="renameOldDisplay" style="font-size:13px;color:#64748b;"></div>
                    </div>
                    <div>
                        <label class="form-label fw-medium" style="font-size:13px;">Nama Baru <span class="text-danger">*</span></label>
                        <input type="text" name="new_name" id="renameNewName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden form untuk hapus album --}}
<form method="POST" action="{{ route('admin.galleries.albumDestroy') }}" id="destroyAlbumForm">
    @csrf @method('DELETE')
    <input type="hidden" name="album_name" id="destroyAlbumName">
</form>

{{-- Import Modal (2-level: Album name + project as sub-album) --}}
@if($projects->count())
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-layer-group me-2 text-primary"></i>Import Galeri dari Proyek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:13px;">
                    <i class="fas fa-info-circle me-2"></i>
                    Centang proyek yang ingin diimport. Tentukan <strong>Nama Album</strong> (level 1) — proyek akan jadi sub-album di dalamnya.
                    Beberapa proyek bisa masuk ke <em>album yang sama</em> cukup dengan mengetik nama album yang sama.
                </div>
                <div id="importCount" class="mb-3 fw-semibold" style="color:#0057A8;font-size:13.5px;">0 proyek dipilih</div>

                @foreach($projects as $pi => $proj)
                @php $imgs = $proj->gallery ?? []; if($proj->image) array_unshift($imgs, $proj->image); $imgs = array_unique(array_values($imgs)); @endphp
                @if(count($imgs))
                <div class="album-card" id="albumCard{{ $pi }}" data-index="{{ $pi }}">
                    <div class="album-card-head">
                        <input type="checkbox" class="album-chk form-check-input chk" id="chk{{ $pi }}" onchange="toggleAlbumCard({{ $pi }})">
                        <label for="chk{{ $pi }}" style="cursor:pointer;flex:1;margin:0;">
                            <span class="fw-semibold" style="color:#0f172a;"><i class="fas fa-folder-open me-2 text-warning"></i>{{ $proj->name }}</span>
                            <span class="badge bg-secondary ms-2" style="font-size:10px;">{{ count($imgs) }} foto</span>
                        </label>
                    </div>
                    <div class="album-card-fields" id="albumFields{{ $pi }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-medium mb-1" style="font-size:13px;">Nama Album (level 1) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm album-name-input" id="albumName{{ $pi }}"
                                       list="existingAlbumsList" placeholder="Nama album pengelompokan...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium mb-1" style="font-size:13px;">Sub-Album (nama proyek)</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $proj->name }}" readonly style="background:#f8faff;color:#64748b;">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-medium mb-1" style="font-size:13px;">Kategori</label>
                                <input type="text" class="form-control form-control-sm album-cat" id="albumCat{{ $pi }}" value="Proyek">
                            </div>
                        </div>
                        <datalist id="existingAlbumsList">
                            @php $existingAlbums = \App\Models\Gallery::whereNotNull('album')->distinct()->orderBy('album')->pluck('album'); @endphp
                            @foreach($existingAlbums as $ea)<option value="{{ $ea }}">@endforeach
                            @foreach($jenisOptions ?? [] as $j)
                                @if(!$existingAlbums->contains($j))<option value="{{ $j }}">@endif
                            @endforeach
                        </datalist>
                        <input type="hidden" class="album-proj-name" id="albumProjName{{ $pi }}" value="{{ $proj->name }}">
                    </div>
                    <div class="album-card-photos">
                        @foreach($imgs as $imgPath)
                        <img src="{{ asset('storage/'.$imgPath) }}" class="album-thumb-sm" alt="" data-path="{{ $imgPath }}">
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnImport" onclick="doImport()" disabled>
                    <i class="fas fa-layer-group me-1"></i>Import Sub-Album Terpilih
                </button>
            </div>
        </div>
    </div>
</div>
<form method="POST" action="{{ route('admin.galleries.importFromProject') }}" id="importForm">@csrf<div id="importHiddenFields"></div></form>
@endif

@endsection
@push('scripts')
<script>
function toggleAlbumSection(header) {
    header.classList.toggle('collapsed');
}
function toggleSubAlbum(header) {
    header.classList.toggle('collapsed');
}
function openRenameModal(albumName) {
    document.getElementById('renameOldName').value  = albumName;
    document.getElementById('renameOldDisplay').textContent = albumName;
    document.getElementById('renameNewName').value  = albumName;
    new bootstrap.Modal(document.getElementById('renameAlbumModal')).show();
}
function confirmDestroyAlbum(albumName, count) {
    if (!confirm('Hapus album "' + albumName + '" beserta semua ' + count + ' foto di dalamnya?\nFoto dari proyek tidak akan ikut terhapus dari disk.')) return;
    document.getElementById('destroyAlbumName').value = albumName;
    document.getElementById('destroyAlbumForm').submit();
}

function toggleAlbumCard(idx) {
    var card   = document.getElementById('albumCard' + idx);
    var fields = document.getElementById('albumFields' + idx);
    var isChecked = document.getElementById('chk' + idx).checked;
    card.classList.toggle('selected', isChecked);
    fields.classList.toggle('show', isChecked);
    updateImportCount();
}

function updateImportCount() {
    var count = document.querySelectorAll('.album-chk:checked').length;
    document.getElementById('importCount').textContent = count + ' proyek dipilih';
    document.getElementById('btnImport').disabled = count === 0;
}

function doImport() {
    var checked = document.querySelectorAll('.album-chk:checked');
    if (!checked.length) return;

    var errors = [];
    checked.forEach(function(chk) {
        var card = chk.closest('.album-card');
        var idx  = card.dataset.index;
        var albumName = document.getElementById('albumName' + idx).value.trim();
        if (!albumName) errors.push('Isi Nama Album untuk proyek yang dipilih.');
    });
    if (errors.length) { alert(errors[0]); return; }

    var fields = '';
    var ai = 0;
    checked.forEach(function(chk) {
        var card      = chk.closest('.album-card');
        var idx       = card.dataset.index;
        var albumName = document.getElementById('albumName' + idx).value.trim();
        var projName  = document.getElementById('albumProjName' + idx).value;
        var cat       = document.getElementById('albumCat' + idx).value.trim();
        fields += '<input type="hidden" name="albums['+ai+'][album]" value="'+escHtml(albumName)+'">';
        fields += '<input type="hidden" name="albums['+ai+'][title]" value="'+escHtml(projName)+'">';
        fields += '<input type="hidden" name="albums['+ai+'][category]" value="'+escHtml(cat)+'">';
        var photos = card.querySelectorAll('.album-card-photos .album-thumb-sm');
        var pi = 0;
        photos.forEach(function(img) {
            fields += '<input type="hidden" name="albums['+ai+'][paths]['+pi+']" value="'+escHtml(img.dataset.path)+'">';
            pi++;
        });
        ai++;
    });
    document.getElementById('importHiddenFields').innerHTML = fields;
    document.getElementById('importForm').submit();
}

function escHtml(s) { var d=document.createElement('div'); d.appendChild(document.createTextNode(s)); return d.innerHTML; }
</script>
@endpush

