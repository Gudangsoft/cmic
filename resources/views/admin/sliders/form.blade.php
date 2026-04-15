@extends('layouts.admin')
@section('title', isset($slider->id) ? 'Edit Slider' : 'Tambah Slider')
@section('page-title', isset($slider->id) ? 'Edit Slider' : 'Tambah Slider')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.sliders.index') }}">Slider</a></li>
<li class="breadcrumb-item active">{{ isset($slider->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-xl-9 col-lg-11">
<div class="card form-card">
    <div class="fcard-header"><i class="fas fa-images me-2"></i>{{ isset($slider->id) ? 'Edit' : 'Tambah' }} Slider / Banner</div>
    <div class="fcard-body">
        <form action="{{ isset($slider->id) ? route('admin.sliders.update',$slider) : route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" id="sliderForm">
            @csrf
            @if(isset($slider->id)) @method('PUT') @endif

            {{-- Tipe Slider --}}
            <div class="form-section-title">Jenis Slider</div>
            <div class="mb-4">
                <div class="d-flex gap-3">
                    <div class="type-card {{ old('type', $slider->type ?? 'image') === 'image' ? 'selected' : '' }}"
                         id="typeCardImage" onclick="setSliderType('image')"
                         style="flex:1;border:2px solid #e2e8f0;border-radius:10px;padding:16px 18px;cursor:pointer;transition:all .2s;background:#fff;">
                        <input type="radio" name="type" value="image" id="typeImage"
                               {{ old('type', $slider->type ?? 'image') === 'image' ? 'checked' : '' }}
                               style="display:none;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:40px;background:#e8f0fe;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-image text-primary" style="font-size:18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Slider Gambar</div>
                                <div class="text-muted" style="font-size:12px;">Upload foto sebagai latar belakang</div>
                            </div>
                        </div>
                    </div>
                    <div class="type-card {{ old('type', $slider->type ?? 'image') === 'text' ? 'selected' : '' }}"
                         id="typeCardText" onclick="setSliderType('text')"
                         style="flex:1;border:2px solid #e2e8f0;border-radius:10px;padding:16px 18px;cursor:pointer;transition:all .2s;background:#fff;">
                        <input type="radio" name="type" value="text" id="typeText"
                               {{ old('type', $slider->type ?? 'image') === 'text' ? 'checked' : '' }}
                               style="display:none;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:48px;height:40px;background:linear-gradient(135deg,#003A78,#0057A8);border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-font text-white" style="font-size:18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Slider Teks</div>
                                <div class="text-muted" style="font-size:12px;">Latar gradien warna dengan teks</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Konten --}}
            <div class="form-section-title">Konten</div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul <span class="text-muted fw-normal">(opsional)</span></label>
                <input type="text" name="title" class="form-control form-control-lg"
                       value="{{ old('title', $slider->title) }}"
                       placeholder="Contoh: PT. Citra Muda Indo Consultant"
                       oninput="updatePreview()">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Sub Judul</label>
                <input type="text" name="subtitle" class="form-control"
                       value="{{ old('subtitle', $slider->subtitle) }}"
                       placeholder="Contoh: Solusi Terbaik untuk Perencanaan & Pengawasan Konstruksi"
                       oninput="updatePreview()">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Teks Tombol CTA <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="button_text" class="form-control"
                           value="{{ old('button_text', $slider->button_text) }}"
                           placeholder="Contoh: Lihat Layanan Kami"
                           oninput="updatePreview()">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Link Tombol</label>
                    <input type="text" name="button_link" class="form-control"
                           value="{{ old('button_link', $slider->button_link) }}"
                           placeholder="/lingkup-layanan">
                </div>
            </div>

            {{-- Text Slider Settings --}}
            <div id="sectionTextSettings" style="display:none;">
                <div class="form-section-title mt-2">Tampilan Gradien</div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Warna Kiri</label>
                        <div class="input-group">
                            <input type="color" name="bg_color_start" id="bgColorStart"
                                   class="form-control form-control-color"
                                   value="{{ old('bg_color_start', $slider->bg_color_start ?? '#003A78') }}"
                                   oninput="syncHex('bgColorStart','bgColorStartHex');updatePreview()">
                            <input type="text" id="bgColorStartHex" class="form-control" style="max-width:90px;"
                                   value="{{ old('bg_color_start', $slider->bg_color_start ?? '#003A78') }}"
                                   oninput="document.getElementById('bgColorStart').value=this.value;updatePreview();">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Warna Kanan</label>
                        <div class="input-group">
                            <input type="color" name="bg_color_end" id="bgColorEnd"
                                   class="form-control form-control-color"
                                   value="{{ old('bg_color_end', $slider->bg_color_end ?? '#0057A8') }}"
                                   oninput="syncHex('bgColorEnd','bgColorEndHex');updatePreview()">
                            <input type="text" id="bgColorEndHex" class="form-control" style="max-width:90px;"
                                   value="{{ old('bg_color_end', $slider->bg_color_end ?? '#0057A8') }}"
                                   oninput="document.getElementById('bgColorEnd').value=this.value;updatePreview();">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Posisi Teks</label>
                        <select name="text_align" id="textAlign" class="form-select" onchange="updatePreview()">
                            <option value="center" {{ old('text_align', $slider->text_align ?? 'center') === 'center' ? 'selected' : '' }}>Tengah</option>
                            <option value="left"   {{ old('text_align', $slider->text_align ?? 'center') === 'left'   ? 'selected' : '' }}>Kiri</option>
                            <option value="right"  {{ old('text_align', $slider->text_align ?? 'center') === 'right'  ? 'selected' : '' }}>Kanan</option>
                        </select>
                    </div>
                </div>

                {{-- Live Preview --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold"><i class="fas fa-eye me-1 text-primary"></i>Preview</label>
                    <div id="textSliderPreview" style="border-radius:10px;min-height:200px;display:flex;align-items:center;padding:40px 32px;transition:background .3s;overflow:hidden;">
                        <div id="previewInner" style="max-width:700px;width:100%;">
                            <h2 id="previewTitle" style="color:#fff;font-weight:800;font-size:clamp(16px,2.5vw,28px);margin-bottom:10px;"></h2>
                            <p id="previewSubtitle" style="color:rgba(255,255,255,.85);font-size:clamp(12px,1.4vw,16px);margin-bottom:18px;"></p>
                            <a id="previewBtn" href="#" style="display:inline-block;padding:9px 26px;border:2px solid #F5C518;color:#F5C518;border-radius:6px;font-weight:600;font-size:13px;text-decoration:none;"></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Image Slider Settings --}}
            <div id="sectionImageSettings">
                <div class="form-section-title mt-2">Gambar Latar</div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Gambar
                            @if(!isset($slider->id))
                                <span class="text-danger" id="imgRequired">*</span>
                            @else
                                <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span>
                            @endif
                        </label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*" onchange="previewImage(this,'imgPrev')">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>1920×700 px</strong> (landscape). Maks. 3MB.</small>
                        <div class="img-preview-box mt-2" id="imgPrev">
                            @if(isset($slider->id) && $slider->image)
                            <img src="{{ asset('storage/'.$slider->image) }}" class="rounded" alt="">
                            <div class="mt-1" style="font-size:11px;color:#888;">Gambar saat ini</div>
                            @else
                            <div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-image fa-2x mb-2 d-block"></i>Preview gambar</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengaturan Umum --}}
            <div class="form-section-title mt-4">Pengaturan</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $slider->order ?? 0) }}" min="0">
                    <small class="text-muted">Angka lebih kecil = tampil lebih dulu</small>
                </div>
                <div class="col-md-8 d-flex align-items-end pb-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}
                               style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@push('styles')
<style>
.type-card.selected { border-color: #0057A8 !important; background: #f0f6ff !important; }
.type-card:hover { border-color: #0057A8 !important; }
</style>
@endpush
@push('scripts')
<script>
var currentType = '{{ old('type', $slider->type ?? 'image') }}';

function setSliderType(type) {
    currentType = type;
    document.getElementById('typeImage').checked = (type === 'image');
    document.getElementById('typeText').checked  = (type === 'text');
    document.getElementById('typeCardImage').classList.toggle('selected', type === 'image');
    document.getElementById('typeCardText').classList.toggle('selected', type === 'text');
    document.getElementById('sectionTextSettings').style.display  = (type === 'text')  ? '' : 'none';
    document.getElementById('sectionImageSettings').style.display = (type === 'image') ? '' : 'none';
    if (type === 'text') updatePreview();
}

function syncHex(colorId, hexId) {
    document.getElementById(hexId).value = document.getElementById(colorId).value;
}

function updatePreview() {
    var c1    = document.getElementById('bgColorStart').value || '#003A78';
    var c2    = document.getElementById('bgColorEnd').value   || '#0057A8';
    var align = document.getElementById('textAlign').value    || 'center';
    var title    = document.querySelector('[name=title]').value;
    var subtitle = document.querySelector('[name=subtitle]').value;
    var btnText  = document.querySelector('[name=button_text]').value;

    var prev = document.getElementById('textSliderPreview');
    prev.style.background = 'linear-gradient(135deg,' + c1 + ',' + c2 + ')';
    prev.style.justifyContent = align === 'center' ? 'center' : (align === 'right' ? 'flex-end' : 'flex-start');

    var inner = document.getElementById('previewInner');
    inner.style.textAlign = align;

    document.getElementById('previewTitle').textContent    = title    || 'Judul slider di sini...';
    document.getElementById('previewSubtitle').textContent = subtitle || 'Sub judul / deskripsi singkat...';
    var btn = document.getElementById('previewBtn');
    btn.textContent  = btnText || '';
    btn.style.display = btnText ? 'inline-block' : 'none';
}

// Init on page load
setSliderType(currentType);
</script>
@endpush
