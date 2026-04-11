@extends('layouts.admin')
@section('title', isset($member->id) ? 'Edit Anggota Tim' : 'Tambah Anggota Tim')
@section('page-title', isset($member->id) ? 'Edit Anggota Tim' : 'Tambah Anggota Tim')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.team.index') }}">SDM / Tim</a></li>
<li class="breadcrumb-item active">{{ isset($member->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-9 col-lg-11">
<div class="card form-card">
    <div class="fcard-header"><i class="fas fa-user-tie me-2"></i>{{ isset($member->id) ? 'Edit' : 'Tambah' }} Anggota Tim</div>
    <div class="fcard-body">
        <form action="{{ isset($member->id) ? route('admin.team.update',$member) : route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($member->id)) @method('PUT') @endif

            <div class="form-section-title">Data Pribadi</div>
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bagian / Kelompok <span class="text-danger">*</span></label>
                    @php
                        $sections = ['Tenaga Ahli', 'Staf Pendukung', 'Manajemen', 'Direksi'];
                        $currentSection = old('section', $member->section ?? 'Tenaga Ahli');
                    @endphp
                    <select name="section" class="form-select @error('section') is-invalid @enderror" required>
                        @foreach($sections as $sec)
                        <option value="{{ $sec }}" {{ $currentSection === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                        @endforeach
                        @if(!in_array($currentSection, $sections))
                        <option value="{{ $currentSection }}" selected>{{ $currentSection }}</option>
                        @endif
                    </select>
                    @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan / Posisi <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $member->position) }}" required>
                    @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <input type="text" name="education" class="form-control" value="{{ old('education', $member->education) }}" placeholder="S1 Teknik Sipil, UNHAS">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $member->order ?? 0) }}" min="0">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Keahlian / Kompetensi</label>
                <textarea name="expertise" rows="2" class="form-control" placeholder="Contoh: Perencanaan Jalan, Drainase, Manajemen Proyek...">{{ old('expertise', $member->expertise) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi / Biografi</label>
                <textarea name="description" rows="4" class="form-control" placeholder="Deskripsi singkat tentang anggota tim, pengalaman, atau latar belakangnya...">{{ old('description', $member->description) }}</textarea>
                <small class="text-muted">Tampil di halaman detail profil. Bisa dikosongkan.</small>
            </div>

            <div class="form-section-title mt-4">Foto & Status</div>
            <div class="row g-3 align-items-start">
                <div class="col-md-6">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(this,'memberPhoto')">
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ukuran ideal: <strong>400×400 px</strong> (persegi/kotak). Format JPG/PNG. Maks. 2MB.</small>
                    <div class="img-preview-box mt-2 text-center" id="memberPhoto">
                        @if(isset($member->id) && $member->photo)
                        <img src="{{ asset('storage/'.$member->photo) }}" style="height:100px;width:100px;border-radius:50%;object-fit:cover;">
                        @else
                        <div style="padding:20px 0;color:#94a3b8;font-size:13px;"><i class="fas fa-user-circle fa-3x mb-2 d-block"></i>Preview foto</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block mb-2">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }} style="width:40px;height:22px;">
                        <label class="form-check-label ms-2 fw-medium" for="is_active">Aktif (tampil di website)</label>
                    </div>
                    <small class="text-muted d-block mt-1">Nonaktifkan untuk menyembunyikan dari halaman SDM.</small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan</button>
                <a href="{{ route('admin.team.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
