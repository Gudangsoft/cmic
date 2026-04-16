@extends('layouts.admin')
@section('title', isset($page) ? 'Edit Halaman' : 'Buat Halaman')
@section('page-title', isset($page) ? 'Edit Halaman' : 'Buat Halaman Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Halaman</a></li>
<li class="breadcrumb-item active">{{ isset($page) ? 'Edit' : 'Buat' }}</li>
@endsection

@push('styles')
<style>
    .preview-box { border: 1px solid #d1d5db; border-radius: 8px; padding: 16px; min-height: 80px; background: #f8fafc; }
    /* Page content frontend preview styles */
    .page-content-preview h1,.page-content-preview h2,.page-content-preview h3 { color: #003A78; margin-top: 1rem; }
    .page-content-preview p { margin-bottom: .75rem; }
    .page-content-preview ul,.page-content-preview ol { padding-left: 1.5rem; }
    .page-content-preview table { width: 100%; border-collapse: collapse; }
    .page-content-preview table th,.page-content-preview table td { border: 1px solid #e2e8f0; padding: 8px 12px; }
    .page-content-preview table th { background: #f1f5f9; }
    .slug-preview { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .slug-preview span { color: #0057A8; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="row g-4">
    {{-- Main form --}}
    <div class="col-lg-8">
        <form id="pageForm"
              action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($page)) @method('PUT') @endif

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="form-card mb-4">
                <div class="fcard-header">
                    <i class="fas fa-heading me-2"></i>Informasi Halaman
                </div>
                <div class="fcard-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Judul Halaman <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="titleInput"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $page->title ?? '') }}"
                               placeholder="Contoh: Tentang Perusahaan" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Slug / URL</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted" style="font-size:13px;">/halaman/</span>
                            <input type="text" name="slug" id="slugInput"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $page->slug ?? '') }}"
                                   placeholder="tentang-perusahaan">
                        </div>
                        <div class="slug-preview mt-1">
                            URL lengkap: <span id="slugPreview">{{ isset($page) ? url('/halaman/'.$page->slug) : url('/halaman/...') }}</span>
                        </div>
                        <small class="text-muted">Kosongkan untuk generate otomatis dari judul. Gunakan huruf kecil dan tanda -.</small>
                        @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-card mb-4">
                <div class="fcard-header">
                    <i class="fas fa-align-left me-2"></i>Konten Halaman
                </div>
                <div class="fcard-body">
                    <textarea id="contentEditor" name="content" class="form-control tinymce-editor">{{ old('content', $page->content ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-card mb-4">
                <div class="fcard-header"><i class="fas fa-search me-2"></i>SEO Meta</div>
                <div class="fcard-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control"
                               value="{{ old('meta_title', $page->meta_title ?? '') }}"
                               placeholder="Judul untuk Google (kosongkan = pakai judul halaman)">
                        <small class="text-muted">Disarankan max 60 karakter</small>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Meta Deskripsi</label>
                        <textarea name="meta_description" rows="2" class="form-control"
                                  placeholder="Deskripsi singkat untuk hasil pencarian Google..."
                                  maxlength="500">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                        <small class="text-muted">Disarankan max 160 karakter</small>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Sidebar options --}}
    <div class="col-lg-4">
        <div class="form-card mb-4" style="position:sticky; top:80px;">
            <div class="fcard-header"><i class="fas fa-cog me-2"></i>Pengaturan Halaman</div>
            <div class="fcard-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                               value="1" form="pageForm"
                               {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Aktif (tampil di website)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" name="order" class="form-control" form="pageForm"
                           value="{{ old('order', $page->order ?? 0) }}" min="0">
                    <small class="text-muted">Untuk pengurutan di daftar halaman</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Banner / Header Image</label>
                    <div class="img-preview-box" id="bannerPreview">
                        @if(!empty($page->banner_image))
                            <img src="{{ asset('storage/'.$page->banner_image) }}" class="rounded w-100">
                        @else
                            <div class="text-muted py-3">
                                <i class="fas fa-image fa-2x d-block mb-2 opacity-25"></i>
                                <small>Belum ada banner</small>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="banner_image" class="form-control mt-2" form="pageForm"
                           accept="image/*" onchange="previewImage(this, 'bannerPreview')">
                    <small class="text-muted">Gambar header halaman. Disarankan 1200×400px. Maks. 2MB.</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" form="pageForm" class="btn btn-primary py-2 fw-semibold">
                        <i class="fas fa-save me-2"></i>{{ isset($page) ? 'Update Halaman' : 'Simpan Halaman' }}
                    </button>
                    @if(isset($page))
                    <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-outline-info py-2">
                        <i class="fas fa-eye me-2"></i>Preview di Website
                    </a>
                    @endif
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary py-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>// Slug auto-generate from title
var titleInput = document.getElementById('titleInput');
var slugInput  = document.getElementById('slugInput');
var slugPreview = document.getElementById('slugPreview');
var userEditedSlug = {{ isset($page) ? 'true' : 'false' }};

function slugify(str) {
    return str.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}

titleInput.addEventListener('input', function () {
    if (!userEditedSlug) {
        var s = slugify(this.value);
        slugInput.value = s;
        slugPreview.textContent = '{{ url("/halaman") }}/' + (s || '...');
    }
});

slugInput.addEventListener('input', function () {
    userEditedSlug = true;
    slugPreview.textContent = '{{ url("/halaman") }}/' + (this.value || '...');
});
</script>
@endpush
