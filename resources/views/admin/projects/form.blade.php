@extends('layouts.admin')
@section('title', isset($project->id) ? 'Edit Proyek' : 'Tambah Proyek')
@section('page-title', isset($project->id) ? 'Edit Proyek' : 'Tambah Proyek')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Proyek</a></li>
<li class="breadcrumb-item active">{{ isset($project->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-10">
<div class="card form-card">
    <div class="fcard-header"><i class="fas fa-briefcase me-2"></i>{{ isset($project->id) ? 'Edit' : 'Tambah' }} Pengalaman / Proyek</div>
    <div class="fcard-body">
        <form action="{{ isset($project->id) ? route('admin.projects.update',$project) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($project->id)) @method('PUT') @endif

            <div class="form-section-title">Informasi Proyek</div>
            <div class="mb-3">
                <label class="form-label">Nama Proyek <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $project->name) }}" required placeholder="Nama lengkap proyek...">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                {{-- Client dropdown --}}
                <div class="col-md-6">
                    <label class="form-label">Pemberi Tugas / Klien</label>
                    <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" id="clientSelect">
                        <option value="">— Pilih Klien Terdaftar —</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ old('client_id', $project->client_id) == $c->id ? 'selected':'' }}>
                            {{ $c->name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="mt-1">
                        <input type="text" name="client" id="clientFreetext" class="form-control form-control-sm mt-1"
                               value="{{ old('client', $project->client) }}"
                               placeholder="Atau ketik manual jika tidak ada di daftar..."
                               style="{{ old('client_id', $project->client_id) ? 'display:none;':'' }}">
                    </div>
                    <small class="text-muted">Pilih dari daftar klien agar tersinkron, atau ketik manual.</small>
                    @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi Proyek</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $project->location) }}" placeholder="Kota / Provinsi">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $project->year) }}" min="1990" max="2099" placeholder="{{ date('Y') }}">
                </div>
                {{-- Jenis dropdown --}}
                <div class="col-md-8">
                    <label class="form-label">Bidang / Jenis Pengalaman</label>
                    @php
                        $colorMap = ['primary'=>'#1859A9','warning'=>'#E5900A','success'=>'#15803d','danger'=>'#b91c1c','info'=>'#0891b2','secondary'=>'#475569','dark'=>'#1e293b'];
                    @endphp
                    <select name="jenis_proyek_id" class="form-select @error('jenis_proyek_id') is-invalid @enderror">
                        <option value="">— Pilih Jenis Pengalaman —</option>
                        @foreach($jenisAll as $j)
                        <option value="{{ $j->id }}" {{ old('jenis_proyek_id', $project->jenis_proyek_id) == $j->id ? 'selected':'' }}>
                            {{ $j->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_proyek_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Tentukan bidang agar proyek muncul di halaman Jenis Pengalaman yang tepat.</small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi / Uraian Pekerjaan</label>
                <textarea name="description" rows="4" class="form-control" placeholder="Deskripsi singkat pekerjaan yang dilakukan...">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="form-section-title mt-4">Dokumentasi & Status</div>
            <div class="row g-3">
                <div class="col-md-7">
                    <label class="form-label">Foto Dokumentasi Utama</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this,'projImg')">
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>800×500 px</strong> (landscape). Format JPG/PNG. Maks. 2MB.</small>
                    <div class="img-preview-box mt-2" id="projImg">
                        @if(isset($project->id) && $project->image)
                        <img src="{{ asset('storage/'.$project->image) }}" class="rounded">
                        @else
                        <div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-image fa-2x mb-2 d-block"></i>Preview gambar</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label d-block mb-2">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $project->is_active ?? true) ? 'checked' : '' }} style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                    </div>
                </div>
            </div>

            {{-- Album Galeri --}}
            <div class="form-section-title mt-4">Album Galeri Proyek</div>
            <div class="row g-3">
                <div class="col-12">
                    <div class="p-3 border rounded" style="background:#f8faff;border-color:#e3eaff!important;">
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                            <label class="form-label mb-0 fw-semibold"><i class="fas fa-images me-1 text-primary"></i>Upload Foto Album</label>
                            <small class="text-muted">(bisa pilih banyak sekaligus, maks. 10 foto, 2MB/foto)</small>
                        </div>
                        <small class="text-muted d-block mb-2"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>800×600 px</strong>. Format JPG/PNG.</small>
                        <input type="file" name="gallery_new[]" id="galleryInput" class="form-control mb-3"
                               accept="image/*" multiple onchange="previewProjGallery(this)">
                        <div id="galleryNewPreview" class="d-flex flex-wrap gap-2"></div>
                        <div id="galleryLimitWarn" class="alert alert-warning py-2 px-3 mt-2 d-none" style="font-size:13px;">
                            <i class="fas fa-exclamation-triangle me-1"></i>Maksimal <strong>10 foto</strong> per album. Foto melebihi batas tidak akan disimpan.
                        </div>
                    </div>
                </div>

                {{-- Foto tersimpan --}}
                @php $gallery = $project->gallery ?? []; @endphp
                @if(count($gallery) > 0)
                <div class="col-12">
                    <label class="form-label fw-semibold"><i class="fas fa-folder-open me-1 text-warning"></i>Foto Tersimpan <span class="badge bg-secondary ms-1" style="font-size:11px;">{{ count($gallery) }}</span></label>
                    <div class="d-flex flex-wrap gap-3" id="savedGallery">
                        @foreach($gallery as $idx => $img)
                        <div class="gallery-thumb-wrap" id="gthumb_{{ $idx }}" style="position:relative;">
                            <img src="{{ asset('storage/'.$img) }}" style="width:120px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                            <button type="button" onclick="removeProjGalleryItem({{ $idx }}, this)"
                                    style="position:absolute;top:-8px;right:-8px;width:22px;height:22px;border-radius:50%;background:#ef4444;color:#fff;border:none;font-size:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;" title="Hapus">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="gallery_keep[]" value="{{ $img }}" id="gkeep_{{ $idx }}">
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Klik × untuk menghapus foto dari album.</small>
                </div>
                @endif
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
@push('scripts')
<script>
var PROJ_GALLERY_MAX = 10;

function previewProjGallery(input) {
    var area = document.getElementById('galleryNewPreview');
    var warn = document.getElementById('galleryLimitWarn');
    area.innerHTML = '';
    warn.classList.add('d-none');

    var kept = document.querySelectorAll('[id^="gkeep_"]:not([disabled])').length;
    var files = Array.from(input.files);
    var allowed = PROJ_GALLERY_MAX - kept;

    if (files.length > allowed) {
        warn.classList.remove('d-none');
    }

    files.slice(0, Math.max(0, allowed)).forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;';
            wrap.innerHTML = '<img src="' + e.target.result + '" style="width:120px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #0057A8;">'
                + '<span style="position:absolute;top:-6px;right:-6px;background:#0057A8;color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:10px;"><i class="fas fa-check"></i></span>';
            area.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}

function removeProjGalleryItem(idx, btn) {
    if (!confirm('Hapus foto ini dari album?')) return;
    var wrap = btn.closest('.gallery-thumb-wrap');
    wrap.style.opacity = '0.3';
    wrap.style.pointerEvents = 'none';
    var keep = document.getElementById('gkeep_' + idx);
    if (keep) keep.disabled = true;
    btn.innerHTML = '<i class="fas fa-undo"></i>';
    btn.style.background = '#94a3b8';
    btn.onclick = function() { restoreProjGalleryItem(idx, btn); };
}

function restoreProjGalleryItem(idx, btn) {
    var wrap = btn.closest('.gallery-thumb-wrap');
    wrap.style.opacity = '1';
    wrap.style.pointerEvents = 'auto';
    var keep = document.getElementById('gkeep_' + idx);
    if (keep) keep.disabled = false;
    btn.innerHTML = '<i class="fas fa-times"></i>';
    btn.style.background = '#ef4444';
    btn.onclick = function() { removeProjGalleryItem(idx, btn); };
}

// Toggle freetext client field based on dropdown selection (Select2 compatible)
$(function(){
    $('#clientSelect').on('change', function(){
        var ft = $('#clientFreetext');
        if($(this).val()) {
            ft.hide().val('');
        } else {
            ft.show();
        }
    });
});
</script>
@endpush
