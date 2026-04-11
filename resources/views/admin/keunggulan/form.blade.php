@extends('layouts.admin')
@section('title', isset($item->id) ? 'Edit Keunggulan' : 'Tambah Keunggulan')
@section('page-title', isset($item->id) ? 'Edit Keunggulan' : 'Tambah Keunggulan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.keunggulan.index') }}">Keunggulan</a></li>
<li class="breadcrumb-item active">{{ isset($item->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row">
    {{-- Form --}}
    <div class="col-lg-7">
        <div class="card form-card">
            <div class="fcard-header">
                <i class="fas fa-trophy me-2"></i>{{ isset($item->id) ? 'Edit' : 'Tambah' }} Keunggulan
            </div>
            <div class="fcard-body">
                <form action="{{ isset($item->id) ? route('admin.keunggulan.update', $item) : route('admin.keunggulan.store') }}"
                      method="POST">
                    @csrf @if(isset($item->id)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" id="labelInput"
                               class="form-control @error('label') is-invalid @enderror"
                               value="{{ old('label', $item->label) }}"
                               placeholder="Contoh: Berpengalaman, Terpercaya, Tim Profesional..." required
                               oninput="document.getElementById('prevLabel').textContent=this.value||'Label'">
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ikon FontAwesome</label>
                        <div class="input-group">
                            <span class="input-group-text" id="iconPreview" style="width:46px;justify-content:center;">
                                <i class="{{ old('icon', $item->icon ?? 'fas fa-star') }}" id="prevIconEl"></i>
                            </span>
                            <input type="text" name="icon" id="iconInput"
                                   class="form-control"
                                   value="{{ old('icon', $item->icon ?? 'fas fa-star') }}"
                                   placeholder="fas fa-star"
                                   oninput="updateIconPrev(this.value)">
                        </div>
                        <small class="text-muted">
                            Cari di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a>.
                            Contoh: <code>fas fa-award</code>, <code>fas fa-check-circle</code>, <code>fas fa-handshake</code>
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-muted fw-normal small">(opsional)</span></label>
                        <textarea name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  maxlength="500"
                                  placeholder="Keterangan singkat tentang keunggulan ini..."
                                  oninput="document.getElementById('prevDesc').textContent=this.value"
                                  >{{ old('description', $item->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Maks. 500 karakter</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="order" class="form-control"
                                   value="{{ old('order', $item->order ?? 0) }}" min="0">
                            <small class="text-muted">Angka lebih kecil = tampil lebih dulu</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       value="1" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}
                                       style="width:40px;height:22px;">
                                <label class="form-check-label ms-2 fw-medium" for="is_active">
                                    <i class="fas fa-eye me-1 text-muted"></i>Tampil di website
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.keunggulan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Live Preview --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm sticky-top" style="top:20px;">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-4"><i class="fas fa-eye me-2"></i>Preview Tampilan</h6>

                {{-- Single card preview --}}
                <div class="p-4 border rounded text-center mb-4"
                     style="border-color:#0057A8!important;border-radius:14px!important;">
                    <div id="prevIconWrap" style="width:64px;height:64px;background:#eef4ff;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="{{ old('icon', $item->icon ?? 'fas fa-star') }} fa-2x" id="prevIconLarge" style="color:#0057A8;"></i>
                    </div>
                    <div class="fw-bold" style="font-size:15px;color:#1a2a4a;" id="prevLabel">
                        {{ old('label', $item->label ?? 'Label') }}
                    </div>
                    <div class="text-muted mt-1" style="font-size:13px;" id="prevDesc">
                        {{ old('description', $item->description ?? '') }}
                    </div>
                </div>

                {{-- Grid mini preview --}}
                <div class="bg-light rounded p-3">
                    <div class="text-muted small mb-2 fw-semibold">Tampil di halaman Tentang Kami:</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 border rounded text-center" style="border-color:#0057A8!important;background:#fff;">
                                <i class="{{ old('icon', $item->icon ?? 'fas fa-star') }} fa-lg mb-1" id="prevIconGrid" style="color:#0057A8;display:block;"></i>
                                <div style="font-size:11px;font-weight:600;" id="prevLabelGrid">{{ old('label', $item->label ?? 'Label') }}</div>
                            </div>
                        </div>
                        <div class="col-6 opacity-50">
                            <div class="p-2 border rounded text-center" style="background:#fff;">
                                <i class="fas fa-star fa-lg mb-1" style="color:#aaa;display:block;"></i>
                                <div style="font-size:11px;font-weight:600;color:#aaa;">Keunggulan lain</div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('tentang') }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 mt-3">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Halaman Tentang Kami
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateIconPrev(val) {
    val = val.trim() || 'fas fa-star';
    document.getElementById('prevIconEl').className    = val;
    document.getElementById('prevIconLarge').className = val + ' fa-2x';
    document.getElementById('prevIconGrid').className  = val + ' fa-lg mb-1';
}
// sync label to grid preview
document.getElementById('labelInput').addEventListener('input', function() {
    document.getElementById('prevLabelGrid').textContent = this.value || 'Label';
});
</script>
@endpush
