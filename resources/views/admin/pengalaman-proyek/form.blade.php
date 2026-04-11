@extends('layouts.admin')
@section('title', isset($item->id) ? 'Edit Item Pengalaman' : 'Tambah Item Pengalaman')
@section('page-title', isset($item->id) ? 'Edit Item Pengalaman' : 'Tambah Item Pengalaman')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jenis-proyek.index') }}">Jenis Proyek</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pengalaman-proyek.index') }}">Item Pengalaman</a></li>
<li class="breadcrumb-item active">{{ isset($item->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')

<form method="POST" action="{{ isset($item->id) ? route('admin.pengalaman-proyek.update', $item) : route('admin.pengalaman-proyek.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($item->id)) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="fas fa-list-ul me-2 text-primary"></i>Data Item Pengalaman</div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label fw-medium">Jenis Proyek <span class="text-danger">*</span></label>
                        <select name="jenis_proyek_id" class="form-select @error('jenis_proyek_id') is-invalid @enderror" required>
                            <option value="">— Pilih Jenis —</option>
                            @foreach($jenisAll as $j)
                            <option value="{{ $j->id }}" {{ old('jenis_proyek_id', $jenisId ?? $item->jenis_proyek_id ?? '') == $j->id ? 'selected':'' }}>
                                {{ $j->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('jenis_proyek_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Link ke Proyek Portfolio <span class="text-muted fw-normal small">(opsional)</span></label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
                            <option value="">— Tidak ada (tampilkan halaman detail item) —</option>
                            @foreach($projectAll as $proj)
                            <option value="{{ $proj->id }}" {{ old('project_id', $item->project_id ?? '') == $proj->id ? 'selected':'' }}>
                                #{{ $proj->id }} — {{ $proj->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="text-muted small mt-1">Jika dipilih, klik item ini akan langsung menuju halaman detail proyek terkait (/pengalaman/proyek/ID).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Item / Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="inputNama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $item->nama ?? '') }}"
                               placeholder="cth: Penyusunan RPJPD 2025 – 2045 Kabupaten Pemalang" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="mt-1 text-muted small">Maksimal 400 karakter. Karakter saat ini: <span id="charCount">{{ strlen(old('nama', $item->nama ?? '')) }}</span></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi / Uraian Pekerjaan</label>
                        <textarea name="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Uraian singkat pekerjaan ini...">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Pemberi Tugas / Klien</label>
                            <input type="text" name="pemberi_tugas" class="form-control @error('pemberi_tugas') is-invalid @enderror"
                                   value="{{ old('pemberi_tugas', $item->pemberi_tugas ?? '') }}"
                                   placeholder="cth: Pemkab Pemalang">
                            @error('pemberi_tugas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                   value="{{ old('lokasi', $item->lokasi ?? '') }}"
                                   placeholder="cth: Kabupaten Pemalang">
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium">Tahun</label>
                            <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                                   value="{{ old('tahun', $item->tahun ?? '') }}"
                                   min="1990" max="2099" placeholder="{{ date('Y') }}">
                            @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Foto Utama --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Foto Utama</label>
                        @if(isset($item->id) && $item->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$item->gambar) }}" style="height:90px;border-radius:6px;object-fit:cover;">
                            <div class="text-muted small mt-1">Upload baru untuk mengganti</div>
                        </div>
                        @endif
                        <input type="file" name="gambar" accept="image/*" class="form-control @error('gambar') is-invalid @enderror">
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Galeri --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Galeri Foto (maks. 10)</label>
                        @if(isset($item->id) && $item->galeri)
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($item->galeri as $idx => $gPath)
                            <div class="position-relative">
                                <img src="{{ asset('storage/'.$gPath) }}" style="height:70px;width:100px;object-fit:cover;border-radius:6px;">
                                <input type="hidden" name="galeri_keep[]" value="{{ $gPath }}">
                            </div>
                            @endforeach
                        </div>
                        <div class="text-muted small mb-2">Upload baru akan ditambahkan ke galeri yang ada</div>
                        @endif
                        <input type="file" name="galeri_new[]" accept="image/*" multiple class="form-control @error('galeri_new') is-invalid @enderror">
                        @error('galeri_new')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Urutan</label>
                            <input type="number" name="urutan" class="form-control"
                                   value="{{ old('urutan', $item->urutan ?? 0) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium d-block">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                       {{ old('is_active', $item->is_active ?? true) ? 'checked':'' }}>
                                <label class="form-check-label" for="isActive">Aktif</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Tips --}}
        <div class="col-lg-4">
            <div class="card shadow-sm bg-light border-0">
                <div class="card-body">
                    <div class="fw-semibold mb-2"><i class="fas fa-info-circle me-2 text-info"></i>Tips Penulisan</div>
                    <ul class="mb-0 ps-3" style="font-size:13px;color:#64748b;">
                        <li>Tulis nama proyek secara lengkap dan jelas</li>
                        <li>Sertakan tahun dan nama daerah (jika ada)</li>
                        <li>Contoh: <em>Penyusunan RPJMD 2025–2030 Kota Jakarta</em></li>
                        <li>Gunakan urutan untuk mengontrol posisi tampilan</li>
                    </ul>
                </div>
            </div>

            @if(isset($item->id))
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white fw-semibold" style="font-size:13px;"><i class="fas fa-eye me-2 text-success"></i>Preview Item</div>
                <div class="card-body pt-2">
                    <ul class="mb-0 ps-3">
                        <li id="prevNamaItem" style="font-size:14px;">{{ $item->nama }}</li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
        <a href="{{ route('admin.pengalaman-proyek.index', ['jenis' => old('jenis_proyek_id', $jenisId ?? ($item->jenis_proyek_id ?? ''))]) }}"
           class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
@push('scripts')
<script>
$('#inputNama').on('input', function(){
    $('#charCount').text($(this).val().length);
    $('#prevNamaItem').text($(this).val() || '...');
});
</script>
@endpush
