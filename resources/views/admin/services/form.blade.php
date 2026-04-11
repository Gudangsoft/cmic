@extends('layouts.admin')
@section('title', isset($service->id) ? 'Edit Layanan' : 'Tambah Layanan')
@section('page-title', isset($service->id) ? 'Edit Layanan' : 'Tambah Layanan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">Layanan</a></li>
<li class="breadcrumb-item active">{{ isset($service->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-9 col-lg-11">
<div class="card form-card">
    <div class="fcard-header"><i class="fas fa-cogs me-2"></i>{{ isset($service->id) ? 'Edit' : 'Tambah' }} Lingkup Layanan</div>
    <div class="fcard-body">
        <form action="{{ isset($service->id) ? route('admin.services.update',$service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($service->id)) @method('PUT') @endif
            <div class="form-section-title">Detail Layanan</div>
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $service->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ikon FontAwesome</label>
                    <div class="input-group">
                        <span class="input-group-text" id="iconPreview"><i class="fas {{ old('icon', $service->icon ?? 'fa-star') }}"></i></span>
                        <input type="text" name="icon" id="iconInput" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="fa-building" oninput="document.getElementById('iconPreview').innerHTML='<i class=\'fas \'+this.value+\'\'></i>'">
                    </div>
                    <small class="text-muted">Cari icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a></small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $service->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-section-title mt-4">Gambar & Pengaturan</div>
            <div class="row g-3">
                <div class="col-md-7">
                    <label class="form-label">Gambar Utama Layanan</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this,'svcImgPrev')">
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>800×500 px</strong> (landscape 16:10). Format JPG/PNG. Maks. 2MB.</small>
                    <div class="img-preview-box" id="svcImgPrev">
                        @if(isset($service->id) && $service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" class="rounded">
                        @else
                        <div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-image fa-2x mb-2 d-block"></i>Preview gambar</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $service->order ?? 0) }}" min="0">
                        <small class="text-muted">Angka lebih kecil = tampil lebih dulu</small>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                    </div>
                </div>
            </div>

            {{-- Galeri --}}
            <div class="form-section-title mt-4">Galeri Foto Layanan</div>
            <div class="row g-3">
                <div class="col-12">
                    <div class="p-3 border rounded" style="background:#f8faff;border-color:#e3eaff!important;">
                        <div class="d-flex align-items-center mb-3">
                            <label class="form-label mb-0 fw-semibold"><i class="fas fa-images me-1 text-primary"></i>Upload Foto Galeri</label>
                            <small class="text-muted ms-2">(bisa pilih banyak sekaligus, maks. 2MB/foto)</small>
                            <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>800×600 px</strong>. Format JPG/PNG.</small>
                        </div>
                        <input type="file" name="gallery_new[]" id="galleryInput" class="form-control mb-3"
                               accept="image/*" multiple onchange="previewGallery(this)">
                        <div id="galleryNewPreview" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>

                {{-- Foto yang sudah tersimpan --}}
                @php $gallery = $service->gallery ?? []; @endphp
                @if(count($gallery) > 0)
                <div class="col-12">
                    <label class="form-label fw-semibold"><i class="fas fa-folder-open me-1 text-warning"></i>Foto Tersimpan</label>
                    <div class="d-flex flex-wrap gap-3" id="savedGallery">
                        @foreach($gallery as $idx => $img)
                        <div class="gallery-thumb-wrap" id="gthumb_{{ $idx }}" style="position:relative;">
                            <img src="{{ asset('storage/'.$img) }}" style="width:120px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                            <button type="button" onclick="removeGalleryItem({{ $idx }}, this)"
                                    style="position:absolute;top:-8px;right:-8px;width:22px;height:22px;border-radius:50%;background:#ef4444;color:#fff;border:none;font-size:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;" title="Hapus">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="gallery_keep[]" value="{{ $img }}" id="gkeep_{{ $idx }}">
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Klik × untuk menghapus foto dari galeri.</small>
                </div>
                @endif
            </div>
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
@push('scripts')
<script>
function previewGallery(input) {
    var area = document.getElementById('galleryNewPreview');
    area.innerHTML = '';
    Array.from(input.files).forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;';
            wrap.innerHTML = '<img src="'+e.target.result+'" style="width:120px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #0057A8;">'
                + '<span style="position:absolute;top:-6px;right:-6px;background:#0057A8;color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:10px;"><i class="fas fa-check"></i></span>';
            area.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}
function removeGalleryItem(idx, btn) {
    if (!confirm('Hapus foto ini dari galeri?')) return;
    btn.closest('.gallery-thumb-wrap').style.opacity = '0.3';
    btn.closest('.gallery-thumb-wrap').style.pointerEvents = 'none';
    var keep = document.getElementById('gkeep_' + idx);
    if (keep) keep.disabled = true;
    btn.innerHTML = '<i class="fas fa-undo"></i>';
    btn.style.background = '#94a3b8';
    btn.onclick = function() { restoreGalleryItem(idx, btn); };
}
function restoreGalleryItem(idx, btn) {
    btn.closest('.gallery-thumb-wrap').style.opacity = '1';
    btn.closest('.gallery-thumb-wrap').style.pointerEvents = 'auto';
    var keep = document.getElementById('gkeep_' + idx);
    if (keep) keep.disabled = false;
    btn.innerHTML = '<i class="fas fa-times"></i>';
    btn.style.background = '#ef4444';
    btn.onclick = function() { removeGalleryItem(idx, btn); };
}
</script>
@endpush
