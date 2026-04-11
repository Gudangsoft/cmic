@extends('layouts.admin')
@section('title', isset($gallery->id) ? 'Edit Galeri' : 'Tambah Galeri')
@section('page-title', isset($gallery->id) ? 'Edit Item Galeri' : 'Tambah Item Galeri')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.galleries.index') }}">Galeri</a></li>
<li class="breadcrumb-item active">{{ isset($gallery->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@push('styles')
<style>
.type-tab-btn { border:2px solid #e2e8f0; border-radius:8px; padding:10px 22px; cursor:pointer; background:#f8faff; color:#64748b; font-weight:600; transition:.15s; }
.type-tab-btn.active { border-color:#0057A8; background:#eef6ff; color:#0057A8; }
.video-preview-box { background:#000; border-radius:10px; overflow:hidden; aspect-ratio:16/9; display:flex; align-items:center; justify-content:center; }
.video-preview-box iframe { width:100%; height:100%; border:none; }
/* Project picker */
.proj-pick-card { border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; margin-bottom:10px; }
.proj-pick-head { background:#f8faff; padding:7px 13px; font-weight:600; font-size:13px; color:#0057A8; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:8px; }
.proj-pick-head .head-toggle { flex:1; cursor:pointer; user-select:none; }
.proj-pick-head .proj-chk { width:17px; height:17px; cursor:pointer; flex-shrink:0; margin:0; }
.proj-sel-badge { font-size:10.5px; font-weight:700; color:#0057A8; background:#dbeafe; padding:1px 8px; border-radius:10px; white-space:nowrap; }
.proj-pick-photos { display:flex; flex-wrap:wrap; gap:7px; padding:10px 13px; }
.pick-thumb { width:80px; height:60px; object-fit:cover; border-radius:6px; cursor:pointer; border:3px solid transparent; transition:border-color .15s, opacity .15s; opacity:.8; }
.pick-thumb:hover { opacity:1; border-color:#0057A8; }
.selected-from-proj { background:#eef6ff; border:1px solid #0057A8; border-radius:8px; padding:8px 12px; font-size:13px; display:none; align-items:center; gap:10px; margin-top:8px; }
/* Project card for album selection */
.proj-album-card { border:2px solid #e2e8f0; border-radius:10px; padding:10px 14px; display:flex; align-items:center; gap:12px; cursor:pointer; margin-bottom:8px; transition:border-color .15s, background .15s; }
.proj-album-card:hover { border-color:#90b8e0; background:#f8faff; }
.proj-album-card.active { border-color:#0057A8; background:#eef6ff; }
.proj-album-card .proj-check { color:#0057A8; display:none; flex-shrink:0; }
.proj-album-card.active .proj-check { display:block; }
/* Multi-pick (CREATE mode) */
.multi-pick-wrap { position:relative; display:inline-block; }
.multi-pick-thumb { width:80px; height:60px; object-fit:cover; border-radius:6px; cursor:pointer; border:3px solid transparent; transition:border-color .15s, opacity .15s; opacity:.75; display:block; }
.multi-pick-thumb:hover { opacity:1; border-color:#90b8e0; }
.multi-pick-thumb.selected { border-color:#0057A8; opacity:1; }
.pick-check-icon { position:absolute; top:3px; right:3px; width:20px; height:20px; background:#0057A8; border-radius:50%; display:none; align-items:center; justify-content:center; pointer-events:none; }
.selected-mini { width:44px; height:33px; object-fit:cover; border-radius:4px; flex-shrink:0; cursor:pointer; border:2px solid transparent; transition:border-color .15s; }
.selected-mini:hover { border-color:#dc3545; opacity:.75; }
</style>
@endpush
@section('content')
<div class="row justify-content-center"><div class="col-xl-8 col-lg-10">
<div class="card form-card">
    <div class="fcard-header"><i class="fas fa-photo-video me-2"></i>{{ isset($gallery->id) ? 'Edit' : 'Tambah' }} Item Galeri</div>
    <div class="fcard-body">
        <form action="{{ isset($gallery->id) ? route('admin.galleries.update',$gallery) : route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($gallery->id)) @method('PUT') @endif

            {{-- Type selector --}}
            <div class="form-section-title">Tipe Media</div>
            <div class="d-flex gap-3 mb-4" id="typeTabs">
                <div class="type-tab-btn {{ ($gallery->type ?? 'image') === 'image' ? 'active' : '' }}" onclick="switchType('image')">
                    <i class="fas fa-image me-2"></i>Foto
                </div>
                <div class="type-tab-btn {{ ($gallery->type ?? 'image') === 'video' ? 'active' : '' }}" onclick="switchType('video')">
                    <i class="fas fa-video me-2"></i>Video
                </div>
            </div>
            <input type="hidden" name="type" id="typeInput" value="{{ old('type', $gallery->type ?? 'image') }}">

            <div class="form-section-title">Informasi</div>
            {{-- Album (top-level grouping) --}}
            <div class="mb-3">
                <label class="form-label">Nama Album <span class="text-muted fw-normal">(opsional — untuk pengelompokan)</span></label>
                <input type="text" name="album" id="albumInput" class="form-control @error('album') is-invalid @enderror"
                       list="albumDatalist"
                       value="{{ old('album', $gallery->album) }}"
                       placeholder="Ketik nama album atau pilih yang sudah ada…">
                <datalist id="albumDatalist">
                    @foreach($albums as $al)<option value="{{ $al }}">@endforeach
                    @foreach($jenisOptions ?? [] as $j)
                        @if(!$albums->contains($j))<option value="{{ $j }}">@endif
                    @endforeach
                </datalist>
                @error('album')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted"><i class="fas fa-layer-group me-1"></i>Album → Sub-album (proyek) → foto</small>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Judul / Sub-Album <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $gallery->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $gallery->category) }}" placeholder="Misal: Proyek, Kantor">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan / Deskripsi</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Deskripsi singkat...">{{ old('description', $gallery->description) }}</textarea>
            </div>

            {{-- Image section --}}
            <div id="imageSection" style="{{ ($gallery->type ?? 'image') === 'video' ? 'display:none;' : '' }}">
                <div class="form-section-title mt-4">File Foto</div>
                <div class="row g-3">
                    <div class="col-md-7">
                        {{-- Source toggle --}}
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-primary" id="btnSrcUpload" onclick="switchImgSrc('upload')"><i class="fas fa-upload me-1"></i>Upload File</button>
                            @if(isset($projects) && $projects->count())
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnSrcProject" onclick="switchImgSrc('project')">
                                    <i class="fas fa-folder-open me-1"></i>{{ isset($gallery->id) ? 'Ambil dari Proyek' : 'Pilih Foto dari Proyek' }}
                            </button>
                            @endif
                        </div>

                        {{-- Upload area --}}
                        <div id="srcUpload">
                            <label class="form-label">
                                File Foto
                                @if(isset($gallery->id) && $gallery->type==='image')
                                    <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span>
                                @else
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="file" name="image" id="imageInput" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="onUploadChange(this)">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Format JPG/PNG. Maks. 5MB.</small>
                        </div>

                        @if(isset($projects) && $projects->count())
                        {{-- Project picker area --}}
                        <div id="srcProject" style="display:none;">
                            @if(!isset($gallery->id))
                            {{-- CREATE: multi-pick individual photos from any project --}}
                            <div class="alert alert-info py-2 px-3 mb-2" style="font-size:12.5px;">
                                <i class="fas fa-info-circle me-1"></i>Klik foto untuk memilih/batal pilih. Bisa pilih dari berbagai proyek sekaligus. Judul di atas akan menjadi nama sub-album.
                            </div>
                            {{-- Selected photos tray --}}
                            <div id="selectedTray" style="background:#eef6ff;border:1px solid #0057A8;border-radius:8px;padding:10px 14px;margin-bottom:10px;display:none;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-semibold" style="color:#0057A8;font-size:13px;">
                                        <i class="fas fa-check-circle me-1"></i><span id="selectedCount">0</span> foto dipilih
                                    </span>
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="clearAllPicks()">
                                        <i class="fas fa-times me-1"></i>Hapus Semua
                                    </button>
                                </div>
                                <div id="selectedMinis" style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:4px;"></div>
                                <div id="selectedHiddenInputs"></div>
                            </div>
                            {{-- Project list with expandable photo grids --}}
                            <div id="multiPickList" style="max-height:420px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:10px;padding:8px;">
                                @foreach($projects as $proj)
                                @php $imgs = $proj->gallery ?? []; if($proj->image) array_unshift($imgs, $proj->image); $imgs = array_unique(array_values($imgs)); @endphp
                                @if(count($imgs))
                                <div class="proj-pick-card">
                                    <div class="proj-pick-head">
                                        <input type="checkbox" class="proj-chk form-check-input"
                                               title="Pilih semua foto dari proyek ini"
                                               onclick="event.stopPropagation(); toggleProjectAll(this, this.closest('.proj-pick-card'))">
                                        <span class="head-toggle" onclick="toggleProjPhotos(this.parentElement)">
                                            <i class="fas fa-folder-open me-2"></i>{{ $proj->name }}
                                            <span class="badge bg-secondary ms-1" style="font-size:10px;">{{ count($imgs) }} foto</span>
                                            <span class="proj-sel-badge ms-2" style="display:none;"></span>
                                        </span>
                                        <i class="fas fa-chevron-down text-secondary" style="font-size:11px;transition:transform .2s;"></i>
                                    </div>
                                    <div class="proj-pick-photos" style="display:none;">
                                        @foreach($imgs as $imgPath)
                                        <div class="multi-pick-wrap">
                                            <img src="{{ asset('storage/'.$imgPath) }}"
                                                 class="multi-pick-thumb"
                                                 data-path="{{ $imgPath }}"
                                                 data-label="{{ $proj->name }}"
                                                 onclick="togglePick(this)"
                                                 title="{{ $proj->name }}">
                                            <div class="pick-check-icon"><i class="fas fa-check" style="color:#fff;font-size:9px;"></i></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @else
                            {{-- EDIT: pick single photo from project --}}
                            <label class="form-label">Pilih Foto dari Proyek</label>
                            <input type="hidden" name="image_from_project" id="imageFromProject" value="">
                            <div class="selected-from-proj" id="selectedFromProjBox">
                                <img id="selectedFromProjThumb" src="" style="width:60px;height:45px;object-fit:cover;border-radius:6px;">
                                <div style="flex:1;">
                                    <div class="fw-semibold" id="selectedFromProjName" style="font-size:13px;color:#0057A8;"></div>
                                    <div style="font-size:11px;color:#64748b;">Foto dipilih dari proyek</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearProjPick()"><i class="fas fa-times"></i></button>
                            </div>
                            <div style="max-height:380px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:10px;padding:8px;margin-top:8px;" id="projPickerList">
                                @foreach($projects as $proj)
                                @php $imgs = $proj->gallery ?? []; if($proj->image) array_unshift($imgs, $proj->image); $imgs = array_unique(array_values($imgs)); @endphp
                                @if(count($imgs))
                                <div class="proj-pick-card">
                                    <div class="proj-pick-head" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display?'':'none'">
                                        <i class="fas fa-folder-open me-2"></i>{{ $proj->name }}
                                        <span class="badge bg-secondary ms-1" style="font-size:10px;">{{ count($imgs) }}</span>
                                    </div>
                                    <div class="proj-pick-photos">
                                        @foreach($imgs as $imgPath)
                                        <img src="{{ asset('storage/'.$imgPath) }}"
                                             class="pick-thumb"
                                             data-path="{{ $imgPath }}"
                                             data-label="{{ $proj->name }}"
                                             onclick="pickProjPhoto(this)"
                                             title="{{ $proj->name }}">
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Shared preview (only shown for edit or upload) --}}
                        @if(isset($gallery->id) || true)
                        <div class="img-preview-box mt-3" id="galImgPrev" style="{{ !isset($gallery->id) ? 'display:none;' : '' }}">
                            @if(isset($gallery->id) && $gallery->image)
                            <img src="{{ asset('storage/'.$gallery->image) }}" class="rounded">
                            @else
                            <div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-image fa-2x mb-2 d-block"></i>Preview foto</div>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', $gallery->order ?? 0) }}" min="0">
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $gallery->is_active ?? true) ? 'checked' : '' }} style="width:40px;height:22px;">
                            <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Video section --}}
            <div id="videoSection" style="{{ ($gallery->type ?? 'image') !== 'video' ? 'display:none;' : '' }}">
                <div class="form-section-title mt-4">URL Video</div>
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">Link Video (YouTube / Vimeo) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="url" name="video_url" id="videoUrlInput" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $gallery->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            <button type="button" class="btn btn-outline-secondary" onclick="previewVideo()"><i class="fas fa-eye me-1"></i>Preview</button>
                        </div>
                        @error('video_url')<div class="text-danger mt-1" style="font-size:13px;">{{ $message }}</div>@enderror
                        <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Tempel URL YouTube atau Vimeo.</small>
                        <div class="mt-3" id="videoPrevBox" style="display:none;">
                            <div class="video-preview-box">
                                <iframe id="videoPrevFrame" src="" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', $gallery->order ?? 0) }}" min="0">
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active_v" value="1" {{ old('is_active', $gallery->is_active ?? true) ? 'checked' : '' }} style="width:40px;height:22px;">
                            <label class="form-check-label ms-2 fw-medium" for="is_active_v">Aktif (tampil di website)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
@push('scripts')
<script>
function switchType(t) {
    document.getElementById('typeInput').value = t;
    document.querySelectorAll('.type-tab-btn').forEach(function(b){b.classList.remove('active');});
    event.currentTarget.classList.add('active');
    document.getElementById('imageSection').style.display = t === 'image' ? '' : 'none';
    document.getElementById('videoSection').style.display = t === 'video' ? '' : 'none';
}

function switchImgSrc(src) {
    var isUpload = src === 'upload';
    document.getElementById('srcUpload').style.display   = isUpload ? '' : 'none';
    document.getElementById('srcProject').style.display  = isUpload ? 'none' : '';
    document.getElementById('btnSrcUpload').className    = isUpload ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-primary';
    var btnP = document.getElementById('btnSrcProject');
    if (btnP) btnP.className = isUpload ? 'btn btn-sm btn-outline-primary' : 'btn btn-sm btn-primary';

    var prev = document.getElementById('galImgPrev');
    if (isUpload) {
        // Clear project selection
        var spId = document.getElementById('selectedProjectId');
        if (spId) spId.value = '';
        var ifp = document.getElementById('imageFromProject');
        if (ifp) ifp.value = '';
        // Deselect album cards
        document.querySelectorAll('.proj-album-card').forEach(function(c){ c.classList.remove('active'); });
        // Clear multi-picks
        clearAllPicks();
        // Show upload preview
        if (prev) prev.style.display = '';
    } else {
        // Hide upload preview when in project mode (album preview takes its place for create)
        @if(!isset($gallery->id))
        if (prev) prev.style.display = 'none';
        @endif
    }
}

/* ---- CREATE: multi-pick photos from projects ---- */
var pickedPaths = {};

function toggleProjPhotos(head) {
    var photos  = head.nextElementSibling;
    var chevron = head.querySelector('.fa-chevron-down');
    var hidden  = photos.style.display === 'none';
    photos.style.display = hidden ? '' : 'none';
    if (chevron) chevron.style.transform = hidden ? 'rotate(0deg)' : 'rotate(-90deg)';
}

function toggleProjectAll(chk, card) {
    var thumbs = card.querySelectorAll('.multi-pick-thumb');
    var photos  = card.querySelector('.proj-pick-photos');
    var chevron = card.querySelector('.proj-pick-head .fa-chevron-down');
    var select  = chk.checked;
    // Auto-expand when selecting
    if (select && photos) {
        photos.style.display = '';
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
    thumbs.forEach(function(t) {
        var icon = t.parentElement.querySelector('.pick-check-icon');
        if (select) {
            if (!t.classList.contains('selected')) {
                t.classList.add('selected');
                if (icon) icon.style.display = 'flex';
                pickedPaths[t.dataset.path] = t.src;
            }
        } else {
            t.classList.remove('selected');
            if (icon) icon.style.display = 'none';
            delete pickedPaths[t.dataset.path];
        }
    });
    syncProjectCheckbox(card);
    updatePickTray();
}

function syncProjectCheckbox(card) {
    var chk     = card.querySelector('.proj-chk');
    var thumbs  = card.querySelectorAll('.multi-pick-thumb');
    var badge   = card.querySelector('.proj-sel-badge');
    if (!chk || !thumbs.length) return;
    var total    = thumbs.length;
    var selected = card.querySelectorAll('.multi-pick-thumb.selected').length;
    chk.checked       = selected === total;
    chk.indeterminate = selected > 0 && selected < total;
    if (badge) {
        if (selected > 0) {
            badge.textContent   = selected + '/' + total + ' dipilih';
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    }
}

function togglePick(thumb) {
    var wrap = thumb.parentElement;
    var icon = wrap.querySelector('.pick-check-icon');
    var path = thumb.dataset.path;
    if (thumb.classList.contains('selected')) {
        thumb.classList.remove('selected');
        if (icon) icon.style.display = 'none';
        delete pickedPaths[path];
    } else {
        thumb.classList.add('selected');
        if (icon) icon.style.display = 'flex';
        pickedPaths[path] = thumb.src;
    }
    var card = thumb.closest('.proj-pick-card');
    if (card) syncProjectCheckbox(card);
    updatePickTray();
}
function updatePickTray() {
    var paths = Object.keys(pickedPaths);
    var tray   = document.getElementById('selectedTray');
    var minis  = document.getElementById('selectedMinis');
    var inputs = document.getElementById('selectedHiddenInputs');
    if (!tray) return;
    document.getElementById('selectedCount').textContent = paths.length;
    tray.style.display = paths.length ? '' : 'none';
    minis.innerHTML  = '';
    inputs.innerHTML = '';
    paths.forEach(function(p) {
        // mini thumbnail
        var img = document.createElement('img');
        img.src = pickedPaths[p];
        img.className = 'selected-mini';
        img.title = 'Klik untuk batal pilih';
        (function(path) {
            img.onclick = function() {
                document.querySelectorAll('.multi-pick-thumb').forEach(function(t) {
                    if (t.dataset.path === path) {
                        t.classList.remove('selected');
                        var icon = t.parentElement.querySelector('.pick-check-icon');
                        if (icon) icon.style.display = 'none';
                    }
                });
                delete pickedPaths[path];
                updatePickTray();
            };
        })(p);
        minis.appendChild(img);
        // hidden input
        var inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'selected_photos[]';
        inp.value = p;
        inputs.appendChild(inp);
    });
}
function clearAllPicks() {
    document.querySelectorAll('.multi-pick-thumb.selected').forEach(function(t) {
        t.classList.remove('selected');
        var icon = t.parentElement.querySelector('.pick-check-icon');
        if (icon) icon.style.display = 'none';
    });
    pickedPaths = {};
    document.querySelectorAll('#multiPickList .proj-pick-card').forEach(function(card) {
        syncProjectCheckbox(card);
    });
    updatePickTray();
}

/* ---- EDIT: pick single photo from project ---- */
function onUploadChange(input) {
    var ifp = document.getElementById('imageFromProject');
    if (ifp) ifp.value = '';
    previewImage(input, 'galImgPrev');
    var prev = document.getElementById('galImgPrev');
    if (prev) prev.style.display = '';
}

function pickProjPhoto(img) {
    document.querySelectorAll('.pick-thumb').forEach(function(t){ t.style.borderColor='transparent'; t.style.opacity='.8'; });
    img.style.borderColor = '#0057A8';
    img.style.opacity = '1';
    document.getElementById('imageFromProject').value = img.dataset.path;
    var fi = document.getElementById('imageInput');
    if (fi) fi.value = '';
    // Show selected box
    document.getElementById('selectedFromProjThumb').src = img.src;
    document.getElementById('selectedFromProjName').textContent = img.dataset.label;
    document.getElementById('selectedFromProjBox').style.display = 'flex';
    // Update preview
    var prev = document.getElementById('galImgPrev');
    prev.style.display = '';
    prev.innerHTML = '<img src="'+img.src+'" class="rounded" style="width:100%;max-height:200px;object-fit:contain;">';
}

function clearProjPick() {
    document.getElementById('imageFromProject').value = '';
    document.getElementById('selectedFromProjBox').style.display = 'none';
    document.querySelectorAll('.pick-thumb').forEach(function(t){ t.style.borderColor='transparent'; t.style.opacity='.8'; });
    var prev = document.getElementById('galImgPrev');
    prev.innerHTML = '<div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-image fa-2x mb-2 d-block"></i>Preview foto</div>';
}

/* ---- Video ---- */
function getEmbedUrl(url) {
    var ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})/);
    if (ytMatch) return 'https://www.youtube.com/embed/' + ytMatch[1];
    var vmMatch = url.match(/vimeo\.com\/(\d+)/);
    if (vmMatch) return 'https://player.vimeo.com/video/' + vmMatch[1];
    return url;
}
function previewVideo() {
    var url = document.getElementById('videoUrlInput').value.trim();
    if (!url) return;
    document.getElementById('videoPrevFrame').src = getEmbedUrl(url);
    document.getElementById('videoPrevBox').style.display = '';
}
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($gallery->id) && $gallery->type === 'video' && $gallery->video_url)
    previewVideo();
    @endif
});
</script>
@endpush

