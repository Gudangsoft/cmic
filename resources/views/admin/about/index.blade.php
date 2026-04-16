@extends('layouts.admin')
@section('title', 'Tentang Kami')
@section('page-title', 'Tentang Kami')

@section('breadcrumb')
<li class="breadcrumb-item active">Tentang Kami</li>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-4">

    {{-- Kiri --}}
    <div class="col-lg-8">

        {{-- Deskripsi --}}
        <div class="form-card mb-4">
            <div class="fcard-header"><i class="fas fa-info-circle me-2"></i>Deskripsi Perusahaan</div>
            <div class="fcard-body">
                <label class="form-label fw-semibold">Paragraf Tentang Perusahaan</label>
                <textarea name="company_about" id="companyAboutEditor" class="form-control tinymce-editor" rows="6"
                    placeholder="Tulis deskripsi singkat tentang perusahaan...">{{ old('company_about', $settings['company_about'] ?? '') }}</textarea>
                <small class="text-muted d-block mt-2">Teks ini tampil di bagian utama halaman Tentang Kami. Supports: paragraf, list, bold, italic, alignment.</small>
            </div>
        </div>

        {{-- Visi Misi --}}
        <div class="form-card mb-4">
            <div class="fcard-header"><i class="fas fa-eye me-2"></i>Visi & Misi</div>
            <div class="fcard-body">
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-eye me-1 text-primary"></i>Visi
                    </label>
                    <textarea name="about_visi" rows="4" class="form-control"
                        placeholder="Tulis pernyataan visi perusahaan...">{{ old('about_visi', $settings['about_visi'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="form-label fw-semibold">
                        <i class="fas fa-bullseye me-1 text-warning"></i>Misi
                    </label>
                    <textarea name="about_misi" rows="6" class="form-control"
                        placeholder="Tulis poin-poin misi (tiap baris = satu poin)...">{{ old('about_misi', $settings['about_misi'] ?? '') }}</textarea>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Tiap baris baru akan menjadi satu poin misi dengan ikon centang.
                        Contoh:<br>
                        <code>Memberikan layanan berkualitas tinggi</code><br>
                        <code>Mengembangkan SDM yang kompeten</code>
                    </small>
                </div>
            </div>
        </div>

        {{-- Legalitas --}}
        <div class="form-card mb-4">
            <div class="fcard-header"><i class="fas fa-certificate me-2"></i>Legalitas Perusahaan</div>
            <div class="fcard-body">
                <div class="alert alert-info mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-lg"></i>
                    <div>
                        Data legalitas kini dikelola secara terpisah dan dapat ditambah/dikurangi secara bebas.
                        <a href="{{ route('admin.legal-items.index') }}" class="btn btn-sm btn-primary ms-3">
                            <i class="fas fa-external-link-alt me-1"></i>Kelola Legalitas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Highlight Boxes --}}
        <div class="form-card mb-4">
            <div class="fcard-header"><i class="fas fa-trophy me-2"></i>Kotak Keunggulan</div>
            <div class="fcard-body">
                <div class="alert alert-info mb-3 d-flex align-items-center gap-3">
                    <i class="fas fa-info-circle fa-lg flex-shrink-0"></i>
                    <div>
                        Kotak keunggulan kini dikelola secara dinamis — bisa ditambah, diedit, diurutkan, dan ditampilkan/sembunyikan secara bebas.
                        <a href="{{ route('admin.keunggulan.index') }}" class="btn btn-sm btn-primary ms-3">
                            <i class="fas fa-trophy me-1"></i>Kelola Keunggulan
                        </a>
                    </div>
                </div>
                @php $keunggulanPreview = \App\Models\Keunggulan::active()->take(4)->get(); @endphp
                @if($keunggulanPreview->isNotEmpty())
                <div class="row g-2">
                    @foreach($keunggulanPreview as $kp)
                    <div class="col-md-3 col-6">
                        <div class="p-2 border rounded text-center" style="border-color:#0057A8!important;">
                            <i class="{{ $kp->icon }} fa-lg mb-1" style="color:#0057A8;display:block;"></i>
                            <div style="font-size:11px;font-weight:600;">{{ $kp->label }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Kanan --}}
    <div class="col-lg-4">

        {{-- Gambar --}}
        <div class="form-card mb-4">
            <div class="fcard-header"><i class="fas fa-image me-2"></i>Foto / Ilustrasi</div>
            <div class="fcard-body">
                <div class="img-preview-box mb-3" id="aboutImgPreview">
                    @if(!empty($settings['about_image']))
                        <img src="{{ asset('storage/'.$settings['about_image']) }}" class="rounded w-100">
                    @else
                        <div class="text-muted py-4 text-center">
                            <i class="fas fa-building fa-3x d-block mb-2 opacity-25"></i>
                            <small>Belum ada gambar</small>
                        </div>
                    @endif
                </div>
                <input type="file" name="about_image" class="form-control" accept="image/*"
                       onchange="previewImage(this, 'aboutImgPreview')">
                <small class="text-muted d-block mt-1">Tampil di sebelah kiri deskripsi. Disarankan 600×450px. Maks. 2MB.</small>
            </div>
        </div>

        {{-- Preview --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-eye me-1"></i>Preview Singkat</h6>
                <div class="d-flex flex-wrap gap-2 mb-3" id="highlightPreviewArea">
                    <div class="text-center p-2 border rounded flex-fill" style="min-width:80px;" id="prev_h1">
                        <span id="prev_h1_icon_front" class="{{ $settings['about_h1_icon'] ?? 'fas fa-award' }}" style="font-size:18px;color:#0057A8;"></span>
                        <div class="small fw-semibold mt-1" id="prev_h1_label" style="font-size:11px;">{{ $settings['about_h1_label'] ?? 'Berpengalaman' }}</div>
                    </div>
                    <div class="text-center p-2 border rounded flex-fill" style="min-width:80px;" id="prev_h2">
                        <span id="prev_h2_icon_front" class="{{ $settings['about_h2_icon'] ?? 'fas fa-users' }}" style="font-size:18px;color:#0057A8;"></span>
                        <div class="small fw-semibold mt-1" id="prev_h2_label" style="font-size:11px;">{{ $settings['about_h2_label'] ?? 'Tim Profesional' }}</div>
                    </div>
                    <div class="text-center p-2 border rounded flex-fill" style="min-width:80px;" id="prev_h3">
                        <span id="prev_h3_icon_front" class="{{ $settings['about_h3_icon'] ?? 'fas fa-check-circle' }}" style="font-size:18px;color:#0057A8;"></span>
                        <div class="small fw-semibold mt-1" id="prev_h3_label" style="font-size:11px;">{{ $settings['about_h3_label'] ?? 'Terpercaya' }}</div>
                    </div>
                    <div class="text-center p-2 border rounded flex-fill" style="min-width:80px;" id="prev_h4">
                        <span id="prev_h4_icon_front" class="{{ $settings['about_h4_icon'] ?? 'fas fa-handshake' }}" style="font-size:18px;color:#0057A8;"></span>
                        <div class="small fw-semibold mt-1" id="prev_h4_label" style="font-size:11px;">{{ $settings['about_h4_label'] ?? 'Berkomitmen' }}</div>
                    </div>
                </div>
                <a href="{{ route('tentang') }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Halaman Tentang Kami
                </a>
            </div>
        </div>

        {{-- Save --}}
        <div class="form-card">
            <div class="fcard-body">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="fas fa-save me-2"></i>Simpan Semua Perubahan
                </button>
                <p class="text-muted text-center mt-2 mb-0" style="font-size:12px;">Perubahan langsung tampil di halaman website.</p>
            </div>
        </div>

    </div>

    {{-- FULL WIDTH: Struktur Organisasi --}}
    <div class="col-12 mt-2">
        @php
            $orgMode = $settings['about_org_mode'] ?? 'image';
            $orgTemplate = $settings['about_org_template'] ?? '1';
            $orgDataRaw = $settings['about_org_data'] ?? '[]';
            $orgItems = json_decode($orgDataRaw, true) ?: [];
        @endphp
        <input type="hidden" name="about_org_mode" id="orgModeInput" value="{{ $orgMode }}">
        <input type="hidden" name="about_org_template" id="orgTemplateInput" value="{{ $orgTemplate }}">

        <div class="form-card">
            {{-- Header --}}
            <div class="fcard-header d-flex align-items-center" style="background:linear-gradient(135deg,#0057A8 0%,#003d7a 100%);border-radius:14px 14px 0 0;">
                <i class="fas fa-sitemap fa-lg me-2"></i>
                <span class="fw-bold fs-6">Struktur Organisasi</span>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-sm org-tab-btn active" data-tab="image"
                        style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.4);border-radius:8px;padding:4px 14px;font-size:12px;">
                        <i class="fas fa-image me-1"></i>Upload Bagan
                    </button>
                    <button type="button" class="btn btn-sm org-tab-btn" data-tab="manual"
                        style="background:transparent;color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:4px 14px;font-size:12px;">
                        <i class="fas fa-project-diagram me-1"></i>Susun Manual
                    </button>
                </div>
            </div>

            <div class="fcard-body p-0">

                {{-- TAB: Upload Gambar --}}
                <div id="orgTabImage" class="org-tab-pane p-4" style="{{ $orgMode == 'manual' ? 'display:none' : '' }}">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-5">
                            <div id="orgDropZone" onclick="document.getElementById('orgFileInput').click()"
                                style="border:2px dashed #0057A8;border-radius:14px;padding:40px 20px;text-align:center;cursor:pointer;background:#f8faff;transition:all .2s;"
                                onmouseenter="this.style.background='#eef3ff'" onmouseleave="this.style.background='#f8faff'">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color:#0057A8;opacity:.6;"></i>
                                <div class="fw-semibold" style="color:#0057A8;">Klik atau Tarik Gambar ke Sini</div>
                                <div class="text-muted small mt-1">Format: JPG, PNG, GIF — Maks. 5MB</div>
                                <div class="text-muted small">Disarankan landscape (1200×600px)</div>
                            </div>
                            <input type="file" id="orgFileInput" name="about_org_image" accept="image/*" class="d-none"
                                   onchange="previewOrgImage(this)">
                            @if(!empty($settings['about_org_image']))
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <input type="checkbox" name="about_org_image_delete" value="1" class="form-check-input" id="orgImgDel">
                                <label for="orgImgDel" class="text-danger small"><i class="fas fa-trash me-1"></i>Hapus gambar yang ada</label>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-7">
                            <div id="orgImgPreviewArea">
                                @if(!empty($settings['about_org_image']))
                                    <img src="{{ asset('storage/'.$settings['about_org_image']) }}"
                                         id="orgCurrentImg"
                                         class="img-fluid rounded shadow" style="width:100%;max-height:360px;object-fit:contain;background:#f8faff;"
                                         alt="Struktur Organisasi">
                                @else
                                    <div id="orgImgPlaceholder"
                                         style="height:240px;background:linear-gradient(135deg,#f0f4ff,#e8eeff);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                        <div class="text-center">
                                            <i class="fas fa-sitemap fa-4x mb-3" style="color:#0057A8;opacity:.3;"></i>
                                            <div class="text-muted">Preview gambar akan muncul di sini</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: Susun Manual --}}
                <div id="orgTabManual" class="org-tab-pane" style="{{ $orgMode != 'manual' ? 'display:none' : '' }}">

                    {{-- Template Picker --}}
                    <div class="p-3 border-bottom" style="background:#f8faff;">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="fw-semibold small text-muted"><i class="fas fa-palette me-1"></i>Pilih Template Tampilan:</span>
                            @php
                            $templates = [
                                1 => ['Hierarki Kotak', 'fas fa-th-large'],
                                2 => ['Kartu Profil', 'fas fa-id-card'],
                                3 => ['Pohon Struktur', 'fas fa-sitemap'],
                                4 => ['Timeline', 'fas fa-stream'],
                                5 => ['Daftar Bergaya', 'fas fa-list-ul'],
                            ];
                            @endphp
                            @foreach($templates as $tNum => $tData)
                            <label class="org-tpl-label {{ $orgTemplate == $tNum ? 'active' : '' }}"
                                   style="display:flex;align-items:center;gap:6px;padding:6px 14px;border:2px solid {{ $orgTemplate == $tNum ? '#0057A8' : '#dde3ef' }};border-radius:8px;cursor:pointer;background:{{ $orgTemplate == $tNum ? '#eef4ff' : '#fff' }};font-size:12px;font-weight:600;color:{{ $orgTemplate == $tNum ? '#0057A8' : '#555' }};transition:all .15s;"
                                   onclick="selectOrgTemplate({{ $tNum }}, this)">
                                <i class="{{ $tData[1] }}"></i> {{ $tData[0] }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-0">

                        {{-- Kiri: Form Daftar --}}
                        <div class="col-lg-7 p-4" style="border-right:1px solid #e9ecef;">
                            <div class="d-flex align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i>Daftar Anggota Struktur</h6>
                                <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addOrgRow()">
                                    <i class="fas fa-plus me-1"></i>Tambah
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle" style="font-size:13px;">
                                    <thead style="background:#f8faff;">
                                        <tr>
                                            <th style="width:30px;">#</th>
                                            <th style="width:64px;">Foto</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th style="width:110px;">Level</th>
                                            <th style="width:40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="orgTableBody">
                                        @if(count($orgItems) > 0)
                                            @foreach($orgItems as $i => $item)
                                            <tr class="org-row">
                                                <td class="text-muted">{{ $i+1 }}</td>
                                                <td>
                                                    <div style="position:relative;width:48px;">
                                                        @if(!empty($item['foto']))
                                                        <img src="{{ asset('storage/'.$item['foto']) }}" class="org-thumb-preview" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #0057A8;cursor:pointer;" onclick="this.nextElementSibling.nextElementSibling.click()">
                                                        @else
                                                        <div class="org-thumb-preview" style="width:48px;height:48px;border-radius:50%;background:#e8eeff;border:2px dashed #aabbdd;display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="this.nextElementSibling.nextElementSibling.click()"><i class="fas fa-camera" style="color:#aabbdd;"></i></div>
                                                        @endif
                                                        <input type="hidden" name="org_foto_existing[]" value="{{ $item['foto'] ?? '' }}">
                                                        <input type="file" name="org_foto[]" accept="image/*" style="display:none;" onchange="previewOrgRowPhoto(this)">
                                                    </div>
                                                </td>
                                                <td><input type="text" name="org_nama[]" class="form-control form-control-sm org-nama" value="{{ $item['nama'] ?? '' }}" placeholder="Nama lengkap" oninput="schedulePreview()"></td>
                                                <td><input type="text" name="org_jabatan[]" class="form-control form-control-sm org-jabatan" value="{{ $item['jabatan'] ?? '' }}" placeholder="Jabatan / posisi" oninput="schedulePreview()"></td>
                                                <td>
                                                    <select name="org_level[]" class="form-select form-select-sm org-level-sel" onchange="schedulePreview()">
                                                        @foreach([1=>'Pimpinan',2=>'Direktur',3=>'Manager',4=>'Supervisor',5=>'Staff'] as $lv => $ln)
                                                        <option value="{{ $lv }}" {{ ($item['level'] ?? 1) == $lv ? 'selected' : '' }}>L{{ $lv }} - {{ $ln }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOrgRow(this)"><i class="fas fa-times"></i></button></td>
                                            </tr>
                                            @endforeach
                                        @else
                                            {{-- default empty row --}}
                                            <tr class="org-row">
                                                <td class="text-muted">1</td>
                                                <td>
                                                    <div style="position:relative;width:48px;">
                                                        <div class="org-thumb-preview" style="width:48px;height:48px;border-radius:50%;background:#e8eeff;border:2px dashed #aabbdd;display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="this.nextElementSibling.nextElementSibling.click()"><i class="fas fa-camera" style="color:#aabbdd;"></i></div>
                                                        <input type="hidden" name="org_foto_existing[]" value="">
                                                        <input type="file" name="org_foto[]" accept="image/*" style="display:none;" onchange="previewOrgRowPhoto(this)">
                                                    </div>
                                                </td>
                                                <td><input type="text" name="org_nama[]" class="form-control form-control-sm org-nama" placeholder="Nama lengkap" oninput="schedulePreview()"></td>
                                                <td><input type="text" name="org_jabatan[]" class="form-control form-control-sm org-jabatan" placeholder="Jabatan / posisi" oninput="schedulePreview()"></td>
                                                <td><select name="org_level[]" class="form-select form-select-sm org-level-sel" onchange="schedulePreview()"><option value="1">L1 - Pimpinan</option><option value="2">L2 - Direktur</option><option value="3">L3 - Manager</option><option value="4">L4 - Supervisor</option><option value="5">L5 - Staff</option></select></td>
                                                <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOrgRow(this)"><i class="fas fa-times"></i></button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-2 p-3 rounded" style="background:#f8faff;border:1px solid #e3eaff;">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach([1=>'Pimpinan',2=>'Direktur',3=>'Manager',4=>'Supervisor',5=>'Staff'] as $lv => $ln)
                                    <span class="badge" style="background:{{ ['','#0057A8','#0077CC','#00897B','#43A047','#78909C'][$lv] }};font-size:11px;">L{{ $lv }} {{ $ln }}</span>
                                    @endforeach
                                    <span class="text-muted small ms-1">— urutan level menentukan hierarki</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Live Preview --}}
                        <div class="col-lg-5 p-4" style="background:#f8faff;">
                            <h6 class="fw-bold mb-1"><i class="fas fa-eye me-2 text-primary"></i>Preview Struktur</h6>
                            <p class="text-muted small mb-3" style="font-size:11px;">Tampilan mini sesuai template yang dipilih</p>
                            <div id="orgChartPreview" style="min-height:200px;max-height:480px;overflow-y:auto;padding-right:4px;">
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-project-diagram fa-3x opacity-25 d-block mb-2"></i>
                                    <small>Isi daftar anggota untuk melihat preview</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
/* ── Icon highlight boxes ── */
function updateIconPreview(key, iconClass) {
    var box = document.getElementById('prev_' + key + '_icon_box');
    if (box) box.innerHTML = '<i class="' + iconClass + '" style="color:#fff;font-size:13px;"></i>';
    var front = document.getElementById('prev_' + key + '_icon_front');
    if (front) { front.className = iconClass; front.style.fontSize = '18px'; front.style.color = '#0057A8'; }
}
document.querySelectorAll('input[name$="_label"]').forEach(function(input) {
    input.addEventListener('input', function() {
        var key = this.name.replace('about_', '').replace('_label', '');
        var lbl = document.getElementById('prev_' + key + '_label');
        if (lbl) lbl.textContent = this.value;
    });
});

/* ── Org tab switcher ── */
document.querySelectorAll('.org-tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var tab = this.dataset.tab;
        document.getElementById('orgModeInput').value = tab;
        document.querySelectorAll('.org-tab-pane').forEach(function(p){ p.style.display='none'; });
        document.getElementById('orgTab' + tab.charAt(0).toUpperCase() + tab.slice(1)).style.display = '';
        document.querySelectorAll('.org-tab-btn').forEach(function(b){
            b.style.background = 'transparent';
            b.style.color = 'rgba(255,255,255,.7)';
            b.style.border = '1px solid rgba(255,255,255,.3)';
            b.classList.remove('active');
        });
        this.style.background = 'rgba(255,255,255,.2)';
        this.style.color = '#fff';
        this.style.border = '1px solid rgba(255,255,255,.4)';
        this.classList.add('active');
    });
});
// init active tab highlight
(function(){
    var cur = document.getElementById('orgModeInput').value || 'image';
    document.querySelectorAll('.org-tab-btn').forEach(function(b){
        if (b.dataset.tab === cur) {
            b.style.background = 'rgba(255,255,255,.2)';
            b.style.color = '#fff';
            b.style.border = '1px solid rgba(255,255,255,.4)';
        }
    });
})();

/* ── Org image upload preview ── */
function previewOrgImage(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var area = document.getElementById('orgImgPreviewArea');
        area.innerHTML = '<img src="'+e.target.result+'" class="img-fluid rounded shadow" style="width:100%;max-height:360px;object-fit:contain;background:#f8faff;">';
    };
    reader.readAsDataURL(file);
    // highlight drop zone
    document.getElementById('orgDropZone').style.borderColor = '#00897B';
    document.getElementById('orgDropZone').innerHTML = '<i class="fas fa-check-circle fa-2x mb-2" style="color:#00897B;"></i><div class="fw-semibold" style="color:#00897B;">'+file.name+'</div><div class="text-muted small">'+(file.size/1024).toFixed(0)+' KB</div>';
}
// Drag & drop
(function(){
    var dz = document.getElementById('orgDropZone');
    if (!dz) return;
    dz.addEventListener('dragover', function(e){ e.preventDefault(); this.style.background='#eef3ff'; });
    dz.addEventListener('dragleave', function(){ this.style.background='#f8faff'; });
    dz.addEventListener('drop', function(e){
        e.preventDefault();
        var fi = document.getElementById('orgFileInput');
        fi.files = e.dataTransfer.files;
        previewOrgImage(fi);
    });
})();

/* ── Org manual: add/remove rows ── */
var orgRowCount = document.querySelectorAll('.org-row').length;
function addOrgRow() {
    orgRowCount++;
    var lvOptions = '<option value="1">L1 - Pimpinan</option><option value="2">L2 - Direktur</option><option value="3">L3 - Manager</option><option value="4">L4 - Supervisor</option><option value="5">L5 - Staff</option>';
    var tr = document.createElement('tr');
    tr.className = 'org-row';
    tr.innerHTML = '<td class="text-muted">'+orgRowCount+'</td>'
        +'<td><div style="position:relative;width:48px;">'
        +'<div class="org-thumb-preview" style="width:48px;height:48px;border-radius:50%;background:#e8eeff;border:2px dashed #aabbdd;display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="this.nextElementSibling.nextElementSibling.click()"><i class="fas fa-camera" style="color:#aabbdd;"></i></div>'
        +'<input type="hidden" name="org_foto_existing[]" value="">'
        +'<input type="file" name="org_foto[]" accept="image/*" style="display:none;" onchange="previewOrgRowPhoto(this)">'
        +'</div></td>'
        +'<td><input type="text" name="org_nama[]" class="form-control form-control-sm org-nama" placeholder="Nama lengkap" oninput="schedulePreview()"></td>'
        +'<td><input type="text" name="org_jabatan[]" class="form-control form-control-sm org-jabatan" placeholder="Jabatan / posisi" oninput="schedulePreview()"></td>'
        +'<td><select name="org_level[]" class="form-select form-select-sm org-level-sel" onchange="schedulePreview()">'+lvOptions+'</select></td>'
        +'<td><button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOrgRow(this)"><i class="fas fa-times"></i></button></td>';
    document.getElementById('orgTableBody').appendChild(tr);
    schedulePreview();
    tr.querySelector('input[type=text]').focus();
}
function previewOrgRowPhoto(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var thumb = input.previousElementSibling.previousElementSibling;
        thumb.outerHTML = '<img src="'+e.target.result+'" class="org-thumb-preview" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #0057A8;cursor:pointer;" onclick="this.nextElementSibling.nextElementSibling.click()">';
    };
    reader.readAsDataURL(input.files[0]);
}
function removeOrgRow(btn) {
    btn.closest('tr').remove();
    renumberRows();
    schedulePreview();
}
function renumberRows() {
    document.querySelectorAll('#orgTableBody .org-row').forEach(function(r, i){
        r.cells[0].textContent = i + 1;
    });
    orgRowCount = document.querySelectorAll('#orgTableBody .org-row').length;
}

/* ── Org chart live preview ── */
var previewTimer;
function schedulePreview() { clearTimeout(previewTimer); previewTimer = setTimeout(renderOrgPreview, 250); }
var levelColors = {1:'#0057A8',2:'#1976D2',3:'#00897B',4:'#43A047',5:'#78909C'};
var levelNames  = {1:'Pimpinan',2:'Direktur',3:'Manager',4:'Supervisor',5:'Staff'};

function collectOrgRows() {
    var byLevel = {}, allItems = [];
    document.querySelectorAll('#orgTableBody .org-row').forEach(function(row) {
        var nama    = row.querySelector('.org-nama').value.trim() || '...';
        var jabatan = row.querySelector('.org-jabatan').value.trim() || '...';
        var level   = parseInt(row.querySelector('.org-level-sel').value) || 1;
        var thumbEl = row.querySelector('.org-thumb-preview');
        var foto    = (thumbEl && thumbEl.tagName === 'IMG') ? thumbEl.src : null;
        var item    = {nama:nama, jabatan:jabatan, level:level, foto:foto};
        if (!byLevel[level]) byLevel[level] = [];
        byLevel[level].push(item);
        allItems.push(item);
    });
    return {byLevel:byLevel, allItems:allItems};
}

function renderOrgPreview() {
    var tpl    = parseInt(document.getElementById('orgTemplateInput').value) || 1;
    var data   = collectOrgRows();
    var byLevel = data.byLevel;
    var allItems = data.allItems;
    var levels = Object.keys(byLevel).map(Number).sort();
    var el     = document.getElementById('orgChartPreview');

    if (levels.length === 0) {
        el.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-project-diagram fa-3x opacity-25 d-block mb-2"></i><small>Isi daftar anggota untuk melihat preview</small></div>';
        return;
    }

    var html = '';

    /* === Template 1: Hierarki Kotak === */
    if (tpl === 1) {
        html += '<div style="display:flex;flex-direction:column;align-items:center;gap:0;">';
        levels.forEach(function(lv, idx) {
            var color = levelColors[lv] || '#999';
            html += '<div style="display:flex;justify-content:center;flex-wrap:wrap;gap:6px;">';
            byLevel[lv].forEach(function(p) {
                var avatar = p.foto
                    ? '<img src="'+p.foto+'" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid '+color+';margin:4px auto 4px;display:block;">'
                    : '<div style="width:36px;height:36px;border-radius:50%;background:'+color+'22;color:'+color+';display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;margin:4px auto;">'+p.nama.charAt(0).toUpperCase()+'</div>';
                html += '<div style="border:2px solid '+color+';border-radius:8px;overflow:hidden;min-width:80px;max-width:110px;text-align:center;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.08);">'
                      + '<div style="background:'+color+';color:#fff;font-size:8px;padding:3px 6px;font-weight:700;letter-spacing:.2px;">'+p.jabatan+'</div>'
                      + avatar
                      + '<div style="font-size:10px;padding:0 4px 6px;font-weight:600;color:#333;">'+p.nama+'</div>'
                      + '</div>';
            });
            html += '</div>';
            if (idx < levels.length - 1)
                html += '<div style="width:2px;height:14px;background:'+(levelColors[levels[idx+1]]||'#ccc')+';margin:0 auto;"></div>';
        });
        html += '</div>';

    /* === Template 2: Kartu Profil Grid === */
    } else if (tpl === 2) {
        levels.forEach(function(lv) {
            var color = levelColors[lv] || '#999';
            html += '<div style="margin-bottom:12px;">';
            html += '<div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;"><span style="background:'+color+';color:#fff;border-radius:20px;padding:2px 10px;font-size:9px;font-weight:700;">'+( levelNames[lv]||'L'+lv )+'</span><hr style="flex:1;margin:0;border-color:'+color+'44;"></div>';
            html += '<div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;">';
            byLevel[lv].forEach(function(p) {
                var avatar = p.foto
                    ? '<img src="'+p.foto+'" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:3px solid '+color+';display:block;margin:0 auto 6px;">'
                    : '<div style="width:48px;height:48px;border-radius:50%;background:'+color+'20;color:'+color+';display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;margin:0 auto 6px;">'+p.nama.charAt(0).toUpperCase()+'</div>';
                html += '<div style="background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);padding:10px 8px;text-align:center;min-width:80px;">'
                      + avatar
                      + '<div style="font-size:10px;font-weight:700;color:#1a2a4a;">'+p.nama+'</div>'
                      + '<div style="font-size:9px;color:#64748b;">'+p.jabatan+'</div>'
                      + '</div>';
            });
            html += '</div></div>';
        });

    /* === Template 3: Pohon Struktur === */
    } else if (tpl === 3) {
        html += '<div style="display:flex;flex-direction:column;align-items:center;">';
        levels.forEach(function(lv, idx) {
            var color = levelColors[lv] || '#999';
            if (idx > 0) html += '<div style="width:2px;height:14px;background:#cbd5e1;margin:0 auto;"></div>';
            html += '<div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;">';
            byLevel[lv].forEach(function(p) {
                var avatar = p.foto
                    ? '<img src="'+p.foto+'" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid '+color+'40;display:block;margin:0 auto 4px;">'
                    : '<div style="width:40px;height:40px;border-radius:50%;background:'+color+'15;color:'+color+';display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;margin:0 auto 4px;">'+p.nama.charAt(0).toUpperCase()+'</div>';
                html += '<div style="background:#fff;border-radius:10px;box-shadow:0 2px 10px rgba(0,87,168,.1);overflow:hidden;min-width:80px;max-width:110px;text-align:center;">'
                      + '<div style="height:4px;background:'+color+';"></div>'
                      + '<div style="padding:8px 6px;">'+ avatar
                      + '<div style="font-size:10px;font-weight:700;color:#1a2a4a;">'+p.nama+'</div>'
                      + '<div style="font-size:8px;color:#64748b;margin-top:2px;">'+p.jabatan+'</div>'
                      + '<span style="display:inline-block;background:'+color+'15;color:'+color+';border-radius:10px;padding:1px 6px;font-size:8px;font-weight:700;margin-top:3px;">'+(levelNames[lv]||'L'+lv)+'</span>'
                      + '</div></div>';
            });
            html += '</div>';
        });
        html += '</div>';

    /* === Template 4: Timeline Zigzag === */
    } else if (tpl === 4) {
        html += '<div style="position:relative;padding:0 12px;">';
        html += '<div style="position:absolute;left:50%;top:0;bottom:0;width:2px;background:linear-gradient(to bottom,#0057A8,#78909C);transform:translateX(-50%);border-radius:2px;"></div>';
        allItems.forEach(function(p, idx) {
            var color   = levelColors[p.level] || '#555';
            var isLeft  = idx % 2 === 0;
            var avatar = p.foto
                ? '<img src="'+p.foto+'" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid '+color+';flex-shrink:0;">'
                : '<div style="width:32px;height:32px;border-radius:50%;background:'+color+'18;color:'+color+';display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">'+p.nama.charAt(0).toUpperCase()+'</div>';
            var card = '<div style="background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);padding:8px 10px;display:flex;align-items:center;gap:8px;border-left:3px solid '+color+';max-width:42%;">'
                     + avatar
                     + '<div><div style="font-size:10px;font-weight:700;color:#1a2a4a;">'+p.nama+'</div><div style="font-size:9px;color:#64748b;">'+p.jabatan+'</div></div></div>';
            html += '<div style="display:flex;align-items:center;margin-bottom:10px;justify-content:'+(isLeft?'flex-start':'flex-end')+';position:relative;">';
            html += card;
            html += '<div style="width:10px;height:10px;border-radius:50%;background:'+color+';border:2px solid #fff;box-shadow:0 0 0 2px '+color+';position:absolute;left:calc(50% - 5px);"></div>';
            html += '</div>';
        });
        html += '</div>';

    /* === Template 5: Daftar Bergaya === */
    } else if (tpl === 5) {
        levels.forEach(function(lv) {
            var color = levelColors[lv] || '#999';
            html += '<div style="margin-bottom:10px;">';
            html += '<div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;"><span style="font-size:9px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:'+color+';">'+(levelNames[lv]||'L'+lv)+'</span><hr style="flex:1;border-width:2px;margin:0;border-color:'+color+'40;"></div>';
            byLevel[lv].forEach(function(p, k) {
                var avatar = p.foto
                    ? '<img src="'+p.foto+'" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid '+color+'20;flex-shrink:0;">'
                    : '<div style="width:36px;height:36px;border-radius:50%;background:'+color+'15;color:'+color+';display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0;">'+p.nama.charAt(0).toUpperCase()+'</div>';
                html += '<div style="display:flex;align-items:center;gap:8px;background:#fff;padding:7px 10px;border-radius:8px;margin-bottom:5px;box-shadow:0 1px 6px rgba(0,0,0,.06);border-left:4px solid '+color+';">'
                      + '<div style="width:20px;height:20px;border-radius:50%;background:'+color+';color:#fff;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;flex-shrink:0;">'+(k+1)+'</div>'
                      + avatar
                      + '<div style="flex:1;min-width:0;"><div style="font-size:10px;font-weight:700;color:#1a2a4a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+p.nama+'</div><div style="font-size:9px;color:#64748b;">'+p.jabatan+'</div></div>'
                      + '<span style="background:'+color+'15;color:'+color+';border-radius:10px;padding:2px 7px;font-size:8px;font-weight:700;flex-shrink:0;">'+(levelNames[lv]||'L'+lv)+'</span></div>';
            });
            html += '</div>';
        });
    }

    el.innerHTML = html;
}

// init preview on load if manual mode
if (document.getElementById('orgModeInput').value === 'manual') renderOrgPreview();

/* ── Template picker ── */
function selectOrgTemplate(num, el) {
    document.getElementById('orgTemplateInput').value = num;
    document.querySelectorAll('.org-tpl-label').forEach(function(l) {
        l.style.borderColor = '#dde3ef';
        l.style.background  = '#fff';
        l.style.color       = '#555';
    });
    el.style.borderColor = '#0057A8';
    el.style.background  = '#eef4ff';
    el.style.color       = '#0057A8';
    renderOrgPreview();
}
</script>
@endpush
