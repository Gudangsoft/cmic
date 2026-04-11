@extends('layouts.admin')
@section('title','Item Pengalaman Proyek')
@section('page-title','Item Pengalaman Proyek')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jenis-proyek.index') }}">Jenis Proyek</a></li>
<li class="breadcrumb-item active">Item Pengalaman</li>
@endsection
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
        <form action="{{ route('admin.pengalaman-proyek.updateIntro') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-4 align-items-start">

                {{-- Kolom Kiri: Gambar --}}
                <div class="col-lg-3 col-md-4">
                    <label class="form-label fw-semibold small"><i class="fas fa-image me-1 text-primary"></i>Gambar Pembuka</label>
                    <div class="mb-2 text-center">
                        @if(!empty($settings['pengalaman_section_image']))
                            <img src="{{ asset('storage/'.$settings['pengalaman_section_image']) }}"
                                 id="pengImgPrev"
                                 style="width:100%;max-height:180px;object-fit:contain;border-radius:10px;border:2px solid #e2e8f0;background:#f8faff;">
                        @else
                            <div id="pengImgPrev" style="width:100%;height:150px;background:#f1f5f9;border-radius:10px;border:2px dashed #cbd5e1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#94a3b8;">
                                <i class="fas fa-image mb-1" style="font-size:28px;"></i>
                                <span style="font-size:11px;">Preview gambar</span>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="pengalaman_section_image" class="form-control form-control-sm" accept="image/*"
                           onchange="previewPengImg(this)">
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
    <div class="col-4">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #4f8ef7!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(79,142,247,.12);">
                    <i class="fas fa-list-ul" style="color:#4f8ef7;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['total'] }}</div><div class="text-muted small">Total Item</div></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #22c55e!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(34,197,94,.12);">
                    <i class="fas fa-check" style="color:#22c55e;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['aktif'] }}</div><div class="text-muted small">Aktif</div></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #94a3b8!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(148,163,184,.12);">
                    <i class="fas fa-minus-circle" style="color:#94a3b8;font-size:18px;"></i>
                </div>
                <div><div class="fw-bold fs-4 lh-1">{{ $stats['nonaktif'] }}</div><div class="text-muted small">Nonaktif</div></div>
            </div>
        </div>
    </div>
</div>

{{-- Main card --}}
<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-list-ul me-2"></i>Daftar Item Pengalaman Proyek</span>
        <div class="d-flex gap-2 align-items-center">
            {{-- Filter by jenis --}}
            <form method="GET" action="{{ route('admin.pengalaman-proyek.index') }}" class="d-flex gap-2">
                <select name="jenis" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:200px;">
                    <option value="">— Semua Jenis —</option>
                    @foreach($jenisAll as $j)
                    <option value="{{ $j->id }}" {{ $jenisId == $j->id ? 'selected':'' }}>{{ $j->nama }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.pengalaman-proyek.create', $jenisId ? ['jenis' => $jenisId] : []) }}" class="add-btn">
                <i class="fas fa-plus me-1"></i>Tambah Item
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible m-3 mb-0" role="alert">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($items->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-list-ul fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>
            Belum ada item. <a href="{{ route('admin.pengalaman-proyek.create') }}">Tambah sekarang</a>
        </div>
        @else

        {{-- Group by jenis --}}
        @php
            $grouped = $items->groupBy('jenis_proyek_id');
            $colorMap = ['primary'=>'#1e5fa8','warning'=>'#d97706','success'=>'#15803d','danger'=>'#b91c1c','info'=>'#0891b2','secondary'=>'#475569','dark'=>'#1e293b'];
        @endphp

        @foreach($grouped as $jenisId2 => $groupItems)
        @php $jenis = $groupItems->first()->jenis; $bg = $colorMap[$jenis->warna ?? 'primary'] ?? '#1e5fa8'; @endphp
        <div class="border-bottom">
            <div class="px-3 py-2 d-flex align-items-center gap-2 justify-content-between"
                 style="background:{{ $bg }}15;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge d-flex align-items-center gap-1 px-3 py-2" style="background:{{ $bg }};font-size:12px;">
                        <i class="{{ $jenis->ikon ?? 'fas fa-folder' }} me-1"></i>{{ $jenis->nama }}
                    </span>
                    <span class="text-muted small">{{ $groupItems->count() }} item</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pengalaman-proyek.create', ['jenis' => $jenis->id]) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </a>
                    <a href="{{ route('admin.jenis-proyek.edit', $jenis) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">
                        <i class="fas fa-cog me-1"></i>Edit Jenis
                    </a>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama Item / Proyek yang Dikerjakan</th>
                        <th style="width:70px;">Urutan</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:110px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($groupItems as $item)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td style="font-size:14px;">
                        <i class="fas fa-chevron-right text-muted me-2" style="font-size:10px;"></i>{{ $item->nama }}
                    </td>
                    <td class="text-center"><span class="badge bg-light text-dark border">{{ $item->urutan }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm toggle-item {{ $item->is_active ? 'btn-success':'btn-outline-secondary' }}"
                                data-id="{{ $item->id }}" style="font-size:11px;padding:2px 10px;">
                            {{ $item->is_active ? 'Aktif':'Nonaktif' }}
                        </button>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.pengalaman-proyek.edit', $item) }}" class="btn btn-action btn-outline-primary me-1" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.pengalaman-proyek.destroy', $item) }}" method="POST"
                              class="d-inline" id="delItem{{ $item->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-action btn-outline-danger"
                                    onclick="confirmDelete(document.getElementById('delItem{{ $item->id }}'),'Hapus item ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    @if(!$items->isEmpty())
    <div class="card-footer bg-white text-end">
        <a href="{{ route('admin.jenis-proyek.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-layer-group me-1"></i>Kelola Jenis
        </a>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function previewPengImg(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var prev = document.getElementById('pengImgPrev');
        prev.outerHTML = '<img id="pengImgPrev" src="'+e.target.result+'" style="width:100%;max-height:180px;object-fit:contain;border-radius:10px;border:2px solid #0057A8;background:#f8faff;">';
    };
    reader.readAsDataURL(input.files[0]);
}
$(document).on('click', '.toggle-item', function(){
    const btn = $(this);
    const id  = btn.data('id');
    $.post('/admin/pengalaman-proyek/' + id + '/toggle', {_token:'{{ csrf_token() }}'}, function(res){
        if(res.is_active){
            btn.removeClass('btn-outline-secondary').addClass('btn-success').text('Aktif');
        } else {
            btn.removeClass('btn-success').addClass('btn-outline-secondary').text('Nonaktif');
        }
    });
});
</script>
@endpush
