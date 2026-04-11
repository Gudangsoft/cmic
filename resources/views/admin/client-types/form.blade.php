@extends('layouts.admin')
@section('title', isset($clientType->id) ? 'Edit Jenis Klien' : 'Tambah Jenis Klien')
@section('page-title', isset($clientType->id) ? 'Edit Jenis Klien' : 'Tambah Jenis Klien')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.client-types.index') }}">Jenis Klien</a></li>
<li class="breadcrumb-item active">{{ isset($clientType->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-6 col-lg-8">
<div class="card form-card">
    <div class="fcard-header">
        <i class="fas fa-tags me-2"></i>{{ isset($clientType->id) ? 'Edit' : 'Tambah' }} Jenis Klien
    </div>
    <div class="fcard-body">
        <form action="{{ isset($clientType->id) ? route('admin.client-types.update', $clientType) : route('admin.client-types.store') }}" method="POST">
            @csrf @if(isset($clientType->id)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Jenis Klien <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $clientType->name) }}"
                       placeholder="Contoh: Pemerintah, BUMN, Swasta" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="order" class="form-control"
                           value="{{ old('order', $clientType->order ?? 0) }}" min="0">
                </div>
                <div class="col-md-6 d-flex align-items-end pb-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $clientType->is_active ?? true) ? 'checked' : '' }}
                               style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.client-types.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
