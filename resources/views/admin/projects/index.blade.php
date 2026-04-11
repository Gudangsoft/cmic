@extends('layouts.admin')
@section('title','Proyek') @section('page-title','Manajemen Pengalaman / Proyek')
@section('breadcrumb')<li class="breadcrumb-item active">Proyek</li>@endsection
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Pengaturan Halaman Pengalaman --}}
<div class="form-card mb-4">
    <div class="fcard-header d-flex align-items-center">
        <i class="fas fa-align-left me-2"></i><span>Pengaturan Halaman Pengalaman</span>
        <span class="ms-2 badge bg-primary" style="font-size:10px;font-weight:500;">Tampil di website</span>
    </div>
    <div class="fcard-body">
        <form action="{{ route('admin.projects.updateIntro') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-4 align-items-start">

                {{-- Kolom Kiri: Gambar --}}
                <div class="col-lg-3 col-md-4">
                    <label class="form-label fw-semibold small"><i class="fas fa-image me-1 text-primary"></i>Gambar Pembuka</label>
                    <div class="mb-2 text-center">
                        @if(!empty($settings['pengalaman_section_image']))
                            <img src="{{ asset('storage/'.$settings['pengalaman_section_image']) }}"
                                 id="projImgPrev"
                                 style="width:100%;max-height:180px;object-fit:contain;border-radius:10px;border:2px solid #e2e8f0;background:#f8faff;">
                        @else
                            <div id="projImgPrev" style="width:100%;height:150px;background:#f1f5f9;border-radius:10px;border:2px dashed #cbd5e1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#94a3b8;">
                                <i class="fas fa-image mb-1" style="font-size:28px;"></i>
                                <span style="font-size:11px;">Preview gambar</span>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="pengalaman_section_image" class="form-control form-control-sm" accept="image/*"
                           onchange="previewProjImg(this)">
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ideal: <strong>300×300 px</strong>. JPG/PNG, maks. 2MB.</small>
                    @if(!empty($settings['pengalaman_section_image']))
                    <div class="mt-2">
                        <label class="form-check-label small text-danger d-flex align-items-center gap-1">
                            <input type="checkbox" name="pengalaman_section_image_delete" value="1" class="form-check-input">
                            Hapus gambar saat ini
                        </label>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Teks --}}
                <div class="col-lg-9 col-md-8">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Judul Section</label>
                            <input type="text" name="pengalaman_section_title" class="form-control"
                                   value="{{ old('pengalaman_section_title', $settings['pengalaman_section_title'] ?? 'Bidang Pengalaman Kami') }}"
                                   placeholder="Contoh: Bidang Pengalaman Kami">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Deskripsi / Subjudul</label>
                            <textarea name="pengalaman_section_subtitle" rows="3" class="form-control"
                                placeholder="Contoh: Pilih bidang pengalaman di bawah ini untuk melihat daftar pekerjaan yang telah kami kerjakan.">{{ old('pengalaman_section_subtitle', $settings['pengalaman_section_subtitle'] ?? '') }}</textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #4f8ef7!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(79,142,247,.12);">
                    <i class="fas fa-briefcase" style="color:#4f8ef7;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['total'] }}</div><div class="text-muted small">Total Proyek</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #22c55e!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(34,197,94,.12);">
                    <i class="fas fa-check-circle" style="color:#22c55e;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['aktif'] }}</div><div class="text-muted small">Aktif</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #94a3b8!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(148,163,184,.12);">
                    <i class="fas fa-minus-circle" style="color:#94a3b8;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['nonaktif'] }}</div><div class="text-muted small">Nonaktif</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #f59e0b!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(245,158,11,.12);">
                    <i class="fas fa-image" style="color:#f59e0b;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['with_image'] }}</div><div class="text-muted small">Ada Foto</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #8b5cf6!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(139,92,246,.12);">
                    <i class="fas fa-images" style="color:#8b5cf6;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['with_gallery'] }}</div><div class="text-muted small">Ada Galeri</div></div>
            </div>
        </div>
    </div>
</div>

{{-- Info box --}}
<div class="alert alert-info d-flex gap-3 align-items-start mb-4 border-0 shadow-sm" style="border-radius:10px;">
    <i class="fas fa-info-circle mt-1 flex-shrink-0" style="color:#0891b2;"></i>
    <div style="font-size:13.5px;">
        <strong>Daftar Proyek</strong> adalah portofolio proyek dengan foto & galeri dokumentasi, terhubung ke <strong>Klien</strong> &amp; <strong>Jenis Pengalaman</strong>.
        Untuk menambah daftar pekerjaan (tanpa foto), gunakan menu
        <a href="{{ route('admin.pengalaman-proyek.index') }}" class="fw-semibold">Item Layanan</a>.
    </div>
</div>

<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-briefcase me-2"></i>Daftar Pengalaman / Proyek</span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.projects.export') }}" class="btn btn-sm btn-outline-success" title="Export ke Excel/CSV">
                <i class="fas fa-file-excel me-1"></i>Export CSV
            </a>
            <a href="{{ route('admin.projects.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah Proyek</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($projects->isEmpty())
        <div class="text-center py-5 text-muted"><i class="fas fa-briefcase fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada data proyek. <a href="{{ route('admin.projects.create') }}">Tambah sekarang</a></div>
        @else
        <div class="table-responsive">
        <table class="table" id="projTable">
            <thead><tr>
                <th style="width:50px;">#</th>
                <th>Gambar</th>
                <th>Nama Proyek</th>
                <th>Klien / Pemberi Tugas</th>
                <th>Bidang / Jenis</th>
                <th style="width:70px;">Tahun</th>
                <th style="width:80px;">Album</th>
                <th style="width:90px;">Status</th>
                <th style="width:110px;" class="text-center">Aksi</th>
            </tr></thead>
            <tbody>
            @foreach($projects as $p)
            @php
                $colorMap = ['primary'=>'#1859A9','warning'=>'#E5900A','success'=>'#15803d','danger'=>'#b91c1c','info'=>'#0891b2','secondary'=>'#475569','dark'=>'#1e293b'];
                $jenisBg  = $p->jenisProyek ? ($colorMap[$p->jenisProyek->warna] ?? '#1859A9') : null;
            @endphp
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>@if($p->image)<img src="{{ asset('storage/'.$p->image) }}" style="height:44px;width:70px;object-fit:cover;border-radius:6px;">@else<div style="width:70px;height:44px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image text-muted"></i></div>@endif</td>
                <td class="fw-medium" style="max-width:220px;">{{ $p->name }}</td>
                <td style="font-size:13px;">
                    @if($p->clientModel)
                        <div class="d-flex align-items-center gap-1">
                            @if($p->clientModel->logo)
                            <img src="{{ asset('storage/'.$p->clientModel->logo) }}" style="height:20px;width:28px;object-fit:contain;border-radius:2px;" alt="">
                            @endif
                            <span class="fw-medium" style="color:#334155;">{{ $p->clientModel->name }}</span>
                        </div>
                    @elseif($p->client)
                        <span style="color:#64748b;">{{ $p->client }}</span>
                    @else
                        <span class="text-muted">�</span>
                    @endif
                </td>
                <td>
                    @if($p->jenisProyek)
                    <span class="badge" style="background:{{ $jenisBg }};font-size:11px;max-width:160px;white-space:normal;line-height:1.3;">
                        {{ $p->jenisProyek->nama }}
                    </span>
                    @elseif($p->category)
                    <span class="badge bg-secondary" style="font-size:11px;">{{ $p->category }}</span>
                    @else
                    <span class="text-muted small">�</span>
                    @endif
                </td>
                <td><span class="badge bg-light text-dark border">{{ $p->year ?? '�' }}</span></td>
                <td>
                    @php $albumCount = count($p->gallery ?? []); @endphp
                    @if($albumCount > 0)
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size:11px;">
                        <i class="fas fa-images me-1"></i>{{ $albumCount }}
                    </span>
                    @else
                    <span class="text-muted small">�</span>
                    @endif
                </td>
                <td><span class="badge {{ $p->is_active ? 'bg-success':'bg-secondary' }}">{{ $p->is_active ? 'Aktif':'Nonaktif' }}</span></td>
                <td class="text-center">
                    <a href="{{ route('admin.projects.edit', $p) }}" class="btn btn-action btn-outline-primary me-1" title="Edit"><i class="fas fa-pen"></i></a>
                    <form action="{{ route('admin.projects.destroy', $p) }}" method="POST" class="d-inline" id="delProj{{ $p->id }}">@csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger" onclick="confirmDelete(document.getElementById('delProj{{ $p->id }}'),'Hapus proyek &quot;{{ addslashes($p->name) }}&quot;?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script>
function previewProjImg(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var prev = document.getElementById('projImgPrev');
        prev.outerHTML = '<img id="projImgPrev" src="'+e.target.result+'" style="width:100%;max-height:180px;object-fit:contain;border-radius:10px;border:2px solid #0057A8;background:#f8faff;">';
    };
    reader.readAsDataURL(input.files[0]);
}
$(function(){ if($('#projTable tbody tr').length > 4) $('#projTable').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'_START_-_END_ dari _TOTAL_',paginate:{previous:'&lsaquo;',next:'&rsaquo;'}},pageLength:15,columnDefs:[{orderable:false,targets:[1,8]}],order:[]}); });
</script>
@endpush
