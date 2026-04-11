@extends('layouts.admin')
@section('title', isset($item->id) ? 'Edit Legalitas' : 'Tambah Legalitas')
@section('page-title', isset($item->id) ? 'Edit Legalitas' : 'Tambah Legalitas')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.legal-items.index') }}">Legalitas</a></li>
<li class="breadcrumb-item active">{{ isset($item->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-7 col-lg-9">
<div class="card form-card">
    <div class="fcard-header">
        <i class="fas fa-certificate me-2"></i>{{ isset($item->id) ? 'Edit' : 'Tambah' }} Data Legalitas
    </div>
    <div class="fcard-body">
        <form action="{{ isset($item->id) ? route('admin.legal-items.update', $item) : route('admin.legal-items.store') }}" method="POST">
            @csrf @if(isset($item->id)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                       value="{{ old('label', $item->label) }}"
                       placeholder="Contoh: NPWP, NIB, SIUJK, Akta Pendirian, SBU..." required>
                @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai / Nomor <span class="text-danger">*</span></label>
                <input type="text" name="value" class="form-control @error('value') is-invalid @enderror"
                       value="{{ old('value', $item->value) }}"
                       placeholder="Contoh: 01.234.567.8-901.000" required>
                @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ikon FontAwesome</label>
                <div class="input-group">
                    <span class="input-group-text" id="iconPreview">
                        <i class="{{ old('icon', $item->icon ?? 'fas fa-file-alt') }}"></i>
                    </span>
                    <input type="text" name="icon" id="iconInput" class="form-control"
                           value="{{ old('icon', $item->icon ?? 'fas fa-file-alt') }}"
                           placeholder="fas fa-file-alt"
                           oninput="document.getElementById('iconPreview').innerHTML='<i class=\''+this.value+'\'></i>'">
                </div>
                <small class="text-muted">Cari icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com</a>. Contoh: <code>fas fa-id-card</code>, <code>fas fa-certificate</code>, <code>fas fa-hard-hat</code></small>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="order" class="form-control"
                           value="{{ old('order', $item->order ?? 0) }}" min="0">
                    <small class="text-muted">Angka lebih kecil = tampil lebih dulu</small>
                </div>
                <div class="col-md-6 d-flex align-items-end pb-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1"
                               {{ old('is_visible', $item->is_visible ?? true) ? 'checked' : '' }}
                               style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_visible">
                            <i class="fas fa-eye me-1 text-muted"></i>Tampil di website
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.legal-items.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
