@extends('layouts.admin')
@section('title','Layanan') @section('page-title','Manajemen Lingkup Layanan')
@section('breadcrumb')<li class="breadcrumb-item active">Layanan</li>@endsection
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Deskripsi Pembuka --}}
<div class="form-card mb-4">
    <div class="fcard-header d-flex align-items-center">
        <i class="fas fa-align-left me-2"></i><span>Deskripsi Pembuka Halaman Layanan</span>
        <span class="ms-2 badge bg-primary" style="font-size:10px;font-weight:500;">Tampil di website</span>
    </div>
    <div class="fcard-body">
        <form action="{{ route('admin.services.updateIntro') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-4 align-items-start">

                {{-- KOLOM KIRI: Gambar --}}
                <div class="col-lg-3 col-md-4">
                    <label class="form-label fw-semibold small"><i class="fas fa-image me-1 text-primary"></i>Gambar Pembuka</label>
                    {{-- Preview --}}
                    <div class="mb-2 text-center">
                        @if(!empty($settings['services_section_image']))
                            <img src="{{ asset('storage/'.$settings['services_section_image']) }}"
                                 id="svcIntroImgPrev"
                                 style="width:100%;max-height:180px;object-fit:contain;border-radius:10px;border:2px solid #e2e8f0;background:#f8faff;">
                        @else
                            <div id="svcIntroImgPrev" style="width:100%;height:150px;background:#f1f5f9;border-radius:10px;border:2px dashed #cbd5e1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#94a3b8;">
                                <i class="fas fa-image mb-1" style="font-size:28px;"></i>
                                <span style="font-size:11px;">Preview gambar</span>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="services_section_image" class="form-control form-control-sm" accept="image/*"
                           onchange="previewSvcIntroImg(this)">
                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i>Ideal: <strong>300×300 px</strong>. JPG/PNG, maks. 2MB.</small>
                    @if(!empty($settings['services_section_image']))
                    <div class="mt-2">
                        <label class="form-check-label small text-danger d-flex align-items-center gap-1">
                            <input type="checkbox" name="services_section_image_delete" value="1" class="form-check-input">
                            Hapus gambar saat ini
                        </label>
                    </div>
                    @endif
                </div>

                {{-- KOLOM KANAN: Teks --}}
                <div class="col-lg-9 col-md-8">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold small">Paragraf Pembuka <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea name="services_intro" rows="5" class="form-control"
                                placeholder="Contoh: PT. CMIC menyediakan layanan konsultansi konstruksi yang komprehensif...">{{ old('services_intro', $settings['services_intro'] ?? '') }}</textarea>
                            <small class="text-muted">Teks ini tampil di atas daftar layanan pada halaman website.</small>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold small">Judul Section</label>
                            <input type="text" name="services_section_title" class="form-control mb-2"
                                   value="{{ old('services_section_title', $settings['services_section_title'] ?? '') }}"
                                   placeholder="Contoh: Apa yang Kami Tawarkan">
                            <label class="form-label fw-semibold small">Sub-judul / Tagline</label>
                            <input type="text" name="services_section_subtitle" class="form-control"
                                   value="{{ old('services_section_subtitle', $settings['services_section_subtitle'] ?? '') }}"
                                   placeholder="Contoh: Solusi terpadu untuk setiap kebutuhan">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Deskripsi
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
    @php
    $cards = [
        ['label'=>'Total Layanan',  'value'=>$stats['total'],        'icon'=>'fas fa-cogs',        'color'=>'#0057A8', 'bg'=>'#eef4ff'],
        ['label'=>'Aktif',          'value'=>$stats['active'],       'icon'=>'fas fa-check-circle','color'=>'#16a34a', 'bg'=>'#f0fdf4'],
        ['label'=>'Nonaktif',       'value'=>$stats['inactive'],     'icon'=>'fas fa-pause-circle','color'=>'#dc2626', 'bg'=>'#fff1f2'],
        ['label'=>'Ada Gambar',     'value'=>$stats['with_image'],   'icon'=>'fas fa-image',       'color'=>'#7c3aed', 'bg'=>'#faf5ff'],
        ['label'=>'Ada Galeri',     'value'=>$stats['with_gallery'], 'icon'=>'fas fa-images',      'color'=>'#0891b2', 'bg'=>'#f0f9ff'],
        ['label'=>'Ada Ikon FA',    'value'=>$stats['with_icon'],    'icon'=>'fas fa-star',        'color'=>'#d97706', 'bg'=>'#fffbeb'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="col-xl-2 col-lg-4 col-sm-6 col-6">
        <div style="background:#fff;border-radius:14px;padding:16px 14px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:4px solid {{ $card['color'] }};display:flex;align-items:center;gap:12px;height:100%;transition:transform .15s,box-shadow .15s;" onmouseenter="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'" onmouseleave="this.style.transform='';this.style.boxShadow='0 2px 10px rgba(0,0,0,.06)'">
            <div style="width:42px;height:42px;border-radius:10px;background:{{ $card['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="{{ $card['icon'] }}" style="color:{{ $card['color'] }};font-size:18px;"></i>
            </div>
            <div>
                <div style="font-size:22px;font-weight:800;color:#1a2a4a;line-height:1.1;">{{ $card['value'] }}</div>
                <div style="font-size:11px;color:#64748b;margin-top:1px;">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card form-card">
    <div class="fcard-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-cogs me-2"></i>Daftar Lingkup Layanan</span>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm px-3">
            <i class="fas fa-plus me-1"></i>Tambah Layanan
        </a>
    </div>
    <div class="fcard-body p-3">
        @if($services->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-cogs fa-3x mb-3 d-block" style="color:#e2e8f0;"></i>
            Belum ada data layanan.
        </div>
        @else
        <div class="d-flex flex-column gap-3">
        @foreach($services as $s)
        @php $galleryCount = count($s->gallery ?? []); @endphp
        <div style="background:#fff;border-radius:14px;border:1px solid #e8edf5;box-shadow:0 2px 10px rgba(0,0,0,.05);overflow:hidden;transition:box-shadow .2s;" onmouseenter="this.style.boxShadow='0 6px 24px rgba(0,87,168,.12)'" onmouseleave="this.style.boxShadow='0 2px 10px rgba(0,0,0,.05)'">
            <div class="d-flex">
                {{-- Gambar (kiri) --}}
                <div style="flex-shrink:0;width:180px;min-height:130px;position:relative;background:#eef4ff;">
                    @if($s->image)
                    <img src="{{ asset('storage/'.$s->image) }}"
                         style="width:180px;height:100%;min-height:130px;object-fit:cover;display:block;">
                    @else
                    <div style="width:180px;min-height:130px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;color:#94a3b8;">
                        @if($s->icon)
                        <i class="fas {{ $s->icon }}" style="font-size:32px;color:#0057A8;opacity:.5;"></i>
                        @else
                        <i class="fas fa-image" style="font-size:32px;"></i>
                        @endif
                        <span style="font-size:11px;">Tidak ada gambar</span>
                    </div>
                    @endif
                    {{-- Nomor urut badge --}}
                    <span style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.55);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">{{ $loop->iteration }}</span>
                </div>

                {{-- Teks (kanan) --}}
                <div class="d-flex flex-column justify-content-between p-3" style="flex:1;min-width:0;">
                    <div>
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                            <h6 class="fw-bold mb-0" style="color:#1a2a4a;font-size:15px;">
                                @if($s->icon)
                                <i class="fas {{ $s->icon }} me-1" style="color:#0057A8;font-size:13px;"></i>
                                @endif
                                {{ $s->title }}
                            </h6>
                            <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                <span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}" style="font-size:10px;">
                                    {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <span class="badge bg-light text-dark border" style="font-size:10px;" title="Urutan tampil">
                                    <i class="fas fa-sort-numeric-up me-1"></i>{{ $s->order }}
                                </span>
                            </div>
                        </div>
                        <p style="font-size:13px;color:#64748b;margin-bottom:8px;line-height:1.5;">{{ Str::limit(strip_tags($s->description), 150) }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($galleryCount > 0)
                        <button type="button" class="btn btn-sm btn-outline-info px-2 py-1"
                                data-bs-toggle="modal" data-bs-target="#galleryModal{{ $s->id }}"
                                style="font-size:11px;">
                            <i class="fas fa-images me-1"></i>{{ $galleryCount }} foto galeri
                        </button>
                        @endif
                        <div class="ms-auto d-flex gap-1">
                            <a href="{{ route('admin.services.edit', $s) }}"
                               class="btn btn-sm btn-outline-primary px-3" title="Edit">
                                <i class="fas fa-pen me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.services.destroy', $s) }}" method="POST"
                                  class="d-inline" id="delSvc{{ $s->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger px-3"
                                        onclick="confirmDelete(document.getElementById('delSvc{{ $s->id }}'),'Hapus layanan &quot;{{ addslashes($s->title) }}&quot;?')">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Gallery Modals --}}
@foreach($services as $s)
@php $gallery = $s->gallery ?? []; @endphp
@if(count($gallery) > 0)
<div class="modal fade" id="galleryModal{{ $s->id }}" tabindex="-1" aria-labelledby="galleryModalLabel{{ $s->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="galleryModalLabel{{ $s->id }}">
                    <i class="fas fa-images me-2 text-primary"></i>Galeri Foto — {{ $s->title }}
                    <span class="badge bg-primary ms-2">{{ count($gallery) }} foto</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    @foreach($gallery as $i => $img)
                    <div class="col-6 col-md-4">
                        <a href="{{ asset('storage/'.$img) }}" target="_blank">
                            <img src="{{ asset('storage/'.$img) }}"
                                 class="img-fluid rounded"
                                 style="width:100%;height:160px;object-fit:cover;border:2px solid #e2e8f0;transition:border-color .2s;"
                                 onmouseenter="this.style.borderColor='#0057A8'"
                                 onmouseleave="this.style.borderColor='#e2e8f0'"
                                 alt="Foto {{ $i + 1 }}">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('admin.services.edit', $s) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pen me-1"></i>Edit & Kelola Galeri
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

{{-- ═══════════════════ KEUNGGULAN SECTION ═══════════════════ --}}
<div class="card form-card mt-5">
    <div class="fcard-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-trophy me-2"></i>Keunggulan Kami <small class="ms-2 fw-normal opacity-75" style="font-size:11px;">Tampil di bawah daftar layanan website</small></span>
        <a href="{{ route('admin.keunggulan.create') }}" class="btn btn-warning btn-sm px-3" style="color:#1a2a4a;">
            <i class="fas fa-plus me-1"></i>Tambah Keunggulan
        </a>
    </div>
    <div class="fcard-body p-3">
        @if($keunggulan->isEmpty())
        <div class="text-center py-4 text-muted">
            <i class="fas fa-trophy fa-2x mb-2 d-block" style="color:#e2e8f0;"></i>
            Belum ada data keunggulan.
            <a href="{{ route('admin.keunggulan.create') }}" class="ms-1">Tambah sekarang</a>
        </div>
        @else
        <div class="row g-3">
            @foreach($keunggulan as $k)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div style="border-radius:14px;border:1px solid #e8edf5;background:#fff;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,.05);height:100%;transition:box-shadow .2s;" onmouseenter="this.style.boxShadow='0 6px 20px rgba(245,197,24,.18)'" onmouseleave="this.style.boxShadow='0 2px 10px rgba(0,0,0,.05)'">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div style="width:46px;height:46px;border-radius:50%;background:#fffbeb;border:2px solid #F5C518;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="{{ $k->icon }}" style="color:#d97706;font-size:18px;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-bold" style="font-size:14px;color:#1a2a4a;line-height:1.3;">{{ $k->label }}</div>
                            <span class="badge {{ $k->is_active ? 'bg-success' : 'bg-secondary' }}" style="font-size:10px;">{{ $k->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                    </div>
                    @if($k->description)
                    <p style="font-size:12px;color:#64748b;margin-bottom:10px;line-height:1.5;">{{ Str::limit(strip_tags($k->description), 80) }}</p>
                    @endif
                    <div class="d-flex gap-1 justify-content-end">
                        <a href="{{ route('admin.keunggulan.edit', $k) }}" class="btn btn-sm btn-outline-primary px-2" title="Edit"><i class="fas fa-pen me-1"></i>Edit</a>
                        <form action="{{ route('admin.keunggulan.destroy', $k) }}" method="POST" class="d-inline" id="delKu{{ $k->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                    onclick="confirmDelete(document.getElementById('delKu{{ $k->id }}'),'Hapus keunggulan &quot;{{ addslashes($k->label) }}&quot;?')">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
@push('scripts')
<script>
function previewSvcIntroImg(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var prev = document.getElementById('svcIntroImgPrev');
        prev.outerHTML = '<img id="svcIntroImgPrev" src="'+e.target.result+'" style="height:100px;max-width:100%;object-fit:contain;border-radius:8px;border:2px solid #0057A8;">';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
