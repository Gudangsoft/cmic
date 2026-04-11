@extends('layouts.admin')
@section('title', isset($client->id) ? 'Edit Klien' : 'Tambah Klien')
@section('page-title', isset($client->id) ? 'Edit Klien' : 'Tambah Klien')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Klien</a></li>
<li class="breadcrumb-item active">{{ isset($client->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-7 col-lg-9">
<div class="card form-card">
    <div class="fcard-header"><i class="fas fa-handshake me-2"></i>{{ isset($client->id) ? 'Edit' : 'Tambah' }} Klien</div>
    <div class="fcard-body">
        <form action="{{ isset($client->id) ? route('admin.clients.update',$client) : route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($client->id)) @method('PUT') @endif

            <div class="form-section-title">Data Klien</div>
            <div class="mb-3">
                <label class="form-label">Jenis Klien</label>
                <select name="client_type_id" class="form-select">
                    <option value="">-- Pilih Jenis Klien --</option>
                    @foreach($clientTypes as $type)
                    <option value="{{ $type->id }}" {{ old('client_type_id', $client->client_type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
                <small class="text-muted">Opsional. <a href="{{ route('admin.client-types.create') }}" target="_blank">Kelola jenis klien →</a></small>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Klien / Instansi <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $client->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Website</label>
                <input type="url" name="website" class="form-control" value="{{ old('website', $client->website) }}" placeholder="https://...">
            </div>

            <div class="form-section-title mt-4">Logo & Pengaturan</div>
            <div class="row g-3">
                <div class="col-md-7">
                    <label class="form-label">Logo Klien</label>
                    <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewImage(this,'clientLogo')">
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>300×150 px</strong>. PNG transparan lebih baik. Maks. 1MB.</small>
                    <div class="img-preview-box mt-2" id="clientLogo" style="background:#f8fafc;">
                        @if(isset($client->id) && $client->logo)
                        <img src="{{ asset('storage/'.$client->logo) }}" style="max-height:80px;object-fit:contain;">
                        @else
                        <div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-image fa-2x mb-2 d-block"></i>Preview logo</div>
                        @endif
                    </div>
                    <small class="text-muted">Format: PNG/JPG/SVG. Ukuran transparan (PNG) lebih baik.</small>
                </div>
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $client->order ?? 0) }}" min="0">
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $client->is_active ?? true) ? 'checked' : '' }} style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
