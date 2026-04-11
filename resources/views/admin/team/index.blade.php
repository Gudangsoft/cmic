@extends('layouts.admin')
@section('title','SDM / Tim') @section('page-title','Manajemen SDM / Tim')
@section('breadcrumb')<li class="breadcrumb-item active">SDM / Tim</li>@endsection
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Pengaturan Section Title --}}
<div class="form-card mb-4">
    <div class="fcard-header d-flex align-items-center">
        <i class="fas fa-heading me-2"></i><span>Pengaturan Section Tim Profesional</span>
        <span class="ms-2 badge bg-primary" style="font-size:10px;font-weight:500;">Tampil di website</span>
    </div>
    <div class="fcard-body">
        <form action="{{ route('admin.team.updateIntro') }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">Judul Section</label>
                    <input type="text" name="team_section_title" class="form-control"
                           value="{{ old('team_section_title', $settings['team_section_title'] ?? 'Tim Profesional Kami') }}"
                           placeholder="Contoh: Tim Profesional Kami">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Subjudul / Deskripsi</label>
                    <input type="text" name="team_section_subtitle" class="form-control"
                           value="{{ old('team_section_subtitle', $settings['team_section_subtitle'] ?? 'Didukung oleh tenaga ahli berpengalaman di bidangnya') }}"
                           placeholder="Contoh: Didukung oleh tenaga ahli berpengalaman di bidangnya">
                </div>
                <div class="col-md-1 text-end">
                    <button type="submit" class="btn btn-primary w-100" title="Simpan">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Daftar Anggota --}}
<div class="card form-card">
    <div class="fcard-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-users me-2"></i>Daftar Anggota Tim</span>
        <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-sm px-3">
            <i class="fas fa-plus me-1"></i>Tambah Anggota
        </a>
    </div>
    <div class="fcard-body p-3">
        @if($members->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-users fa-3x mb-3 d-block" style="color:#e2e8f0;"></i>
            Belum ada data tim. <a href="{{ route('admin.team.create') }}">Tambah sekarang</a>
        </div>
        @else

        @foreach($members->groupBy('section') as $section => $sectionMembers)
        <div class="mb-4">
            <div class="d-flex align-items-center mb-3">
                <span style="background:#1a5276;color:#fff;font-size:13px;font-weight:600;padding:5px 18px;border-radius:20px;">
                    <i class="fas fa-layer-group me-1"></i>{{ $section ?: 'Lainnya' }}
                    <span class="ms-1 opacity-75">({{ $sectionMembers->count() }})</span>
                </span>
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach($sectionMembers as $m)
                <div style="background:#fff;border-radius:14px;border:1px solid #e8edf5;box-shadow:0 2px 10px rgba(0,0,0,.05);overflow:hidden;transition:box-shadow .2s;" onmouseenter="this.style.boxShadow='0 6px 24px rgba(0,87,168,.12)'" onmouseleave="this.style.boxShadow='0 2px 10px rgba(0,0,0,.05)'">
                    <div class="d-flex">
                        {{-- Foto (kiri) --}}
                        <div style="flex-shrink:0;width:130px;min-height:130px;background:#eef4ff;display:flex;align-items:center;justify-content:center;position:relative;">
                            <img src="{{ $m->photo ? asset('storage/'.$m->photo) : 'https://ui-avatars.com/api/?name='.urlencode($m->name).'&size=130&background=0057A8&color=fff' }}"
                                 style="width:130px;height:130px;object-fit:cover;display:block;">
                            <span style="position:absolute;bottom:6px;left:50%;transform:translateX(-50%);white-space:nowrap;font-size:10px;" class="badge {{ $m->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        {{-- Info (kanan) --}}
                        <div class="d-flex flex-column justify-content-between p-3" style="flex:1;min-width:0;">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                    <div>
                                        <h6 class="fw-bold mb-0" style="color:#1a2a4a;font-size:15px;">{{ $m->name }}</h6>
                                        <div style="font-size:13px;color:#0057A8;font-weight:600;">{{ $m->position }}</div>
                                    </div>
                                    <span class="badge bg-light text-dark border flex-shrink-0" style="font-size:10px;" title="Urutan tampil">
                                        <i class="fas fa-sort-numeric-up me-1"></i>{{ $m->order }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    @if($m->education)
                                    <span style="font-size:12px;color:#555;"><i class="fas fa-graduation-cap me-1 text-primary"></i>{{ $m->education }}</span>
                                    @endif
                                    @if($m->expertise)
                                    <span style="font-size:12px;color:#555;"><i class="fas fa-star me-1 text-warning"></i>{{ Str::limit($m->expertise, 60) }}</span>
                                    @endif
                                </div>
                                @if($m->description)
                                <p style="font-size:12px;color:#64748b;margin-top:6px;margin-bottom:0;line-height:1.5;">{{ Str::limit($m->description, 120) }}</p>
                                @endif
                            </div>
                            <div class="d-flex gap-1 justify-content-end mt-2">
                                <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-sm btn-outline-primary px-3">
                                    <i class="fas fa-pen me-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.team.destroy', $m) }}" method="POST" class="d-inline" id="delTeam{{ $m->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger px-3"
                                            onclick="confirmDelete(document.getElementById('delTeam{{ $m->id }}'),'Hapus anggota &quot;{{ addslashes($m->name) }}&quot;?')">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        @endif
    </div>
</div>

@endsection
