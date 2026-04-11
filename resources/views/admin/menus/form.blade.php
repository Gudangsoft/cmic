@extends('layouts.admin')
@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menu Navigasi</a></li>
        <li class="breadcrumb-item active">{{ isset($menu) ? 'Edit' : 'Tambah' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="fcard-header">
                <i class="fas fa-{{ isset($menu) ? 'edit' : 'plus-circle' }} me-2"></i>
                {{ isset($menu) ? 'Edit Menu: '.$menu->label : 'Tambah Menu Navigasi' }}
            </div>
            <div class="fcard-body">
                <form action="{{ isset($menu) ? route('admin.menus.update', $menu) : route('admin.menus.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($menu)) @method('PUT') @endif

                    @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="form-section-title">Informasi Menu</div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Label / Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                                   value="{{ old('label', $menu->label ?? '') }}" placeholder="misal: Beranda, Tentang Kami" required>
                            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                                   value="{{ old('order', $menu->order ?? 0) }}" min="0">
                            <small class="text-muted">Angka kecil = posisi pertama</small>
                            @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Route Name
                                <span class="text-muted fw-normal">(direkomendasikan)</span>
                            </label>
                            <input type="text" name="route_name" id="routeName"
                                   class="form-control @error('route_name') is-invalid @enderror"
                                   value="{{ old('route_name', $menu->route_name ?? '') }}"
                                   placeholder="home, tentang, layanan, sdm...">
                            <small class="text-muted">Nama route Laravel. Diisi otomatis dari daftar di bawah.</small>
                            @error('route_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                URL Manual
                                <span class="text-muted fw-normal">(untuk link eksternal)</span>
                            </label>
                            <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                                   value="{{ old('url', $menu->url ?? '') }}"
                                   placeholder="/halaman-custom atau https://example.com">
                            <small class="text-muted">Kosongkan jika menggunakan Route Name</small>
                            @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Quick route picker --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pilih Route Cepat</label>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $quickRoutes = [
                                        'home'        => 'Beranda',
                                        'tentang'     => 'Tentang Kami',
                                        'layanan'     => 'Lingkup Layanan',
                                        'sdm'         => 'SDM',
                                        'pengalaman'  => 'Pengalaman',
                                        'klien'       => 'Klien',
                                        'galeri'      => 'Galeri',
                                        'kontak'      => 'Kontak Kami',
                                    ];
                                @endphp
                                @foreach($quickRoutes as $routeKey => $routeLabel)
                                <button type="button" class="btn btn-sm btn-outline-secondary route-picker"
                                        data-route="{{ $routeKey }}" data-label="{{ $routeLabel }}">
                                    {{ $routeLabel }}
                                </button>
                                @endforeach
                            </div>
                            <small class="text-muted">Klik untuk mengisi Route Name & Label secara otomatis</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ikon FontAwesome</label>
                            <div class="input-group">
                                <span class="input-group-text" id="iconPreview">
                                    <i class="{{ old('icon', $menu->icon ?? 'fas fa-link') }}"></i>
                                </span>
                                <input type="text" name="icon" id="iconInput"
                                       class="form-control"
                                       value="{{ old('icon', $menu->icon ?? '') }}"
                                       placeholder="fas fa-home"
                                       oninput="document.getElementById('iconPreview').innerHTML='<i class=\''+this.value+'\'></i>'">
                            </div>
                            <small class="text-muted">Opsional. Contoh: <code>fas fa-home</code>, <code>fas fa-info-circle</code> —
                                <a href="https://fontawesome.com/icons" target="_blank">cari ikon</a>
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Parent Menu</label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">— Tidak ada (menu utama) —</option>
                                @foreach($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $menu->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->label }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih parent untuk menjadikan submenu dropdown</small>
                            @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Target</label>
                            <select name="target" class="form-select">
                                <option value="_self" {{ old('target', $menu->target ?? '_self') === '_self' ? 'selected' : '' }}>
                                    _self (buka di tab yang sama)
                                </option>
                                <option value="_blank" {{ old('target', $menu->target ?? '_self') === '_blank' ? 'selected' : '' }}>
                                    _blank (buka di tab baru)
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch fs-5 mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                    {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isActive">Menu Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>{{ isset($menu) ? 'Update Menu' : 'Simpan Menu' }}
                        </button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Quick route picker
document.querySelectorAll('.route-picker').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('routeName').value = this.dataset.route;
        const labelInput = document.querySelector('input[name="label"]');
        if (!labelInput.value) labelInput.value = this.dataset.label;
        document.querySelectorAll('.route-picker').forEach(b => b.classList.remove('btn-secondary', 'active'));
        this.classList.remove('btn-outline-secondary');
        this.classList.add('btn-secondary', 'active');
    });
});
</script>
@endpush
