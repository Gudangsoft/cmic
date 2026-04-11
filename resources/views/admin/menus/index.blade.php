@extends('layouts.admin')
@section('title', 'Manajemen Menu')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Menu Navigasi</li>
    </ol>
</nav>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-table">
    <div class="cht-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-bars me-2"></i>Menu Navigasi Website
            <small class="ms-2 opacity-75">(drag untuk urutkan)</small>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="add-btn">
            <i class="fas fa-plus me-1"></i>Tambah Menu
        </a>
    </div>
    <div class="card-body p-0">
        {{-- Parent Menus --}}
        <div class="p-3">
            <div class="alert alert-info py-2 mb-3">
                <i class="fas fa-info-circle me-1"></i>
                Menu <strong>parent</strong> ditampilkan di navbar utama. Menu dengan <strong>parent</strong> akan menjadi submenu dropdown.
                Nomor urut menentukan posisi tampil (kecil = kiri/atas).
            </div>

            {{-- Group by parent --}}
            @php
                $parentMenus = $menus->whereNull('parent_id')->sortBy('order');
                $childMenus  = $menus->whereNotNull('parent_id');
            @endphp

            <table class="table table-hover" id="menuTable">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Label</th>
                        <th>URL / Route</th>
                        <th>Ikon</th>
                        <th>Urutan</th>
                        <th>Target</th>
                        <th width="80">Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parentMenus as $menu)
                    <tr class="table-primary">
                        <td><i class="fas fa-grip-vertical text-muted"></i></td>
                        <td>
                            <strong>
                                @if($menu->icon)<i class="{{ $menu->icon }} me-1 text-primary"></i>@endif
                                {{ $menu->label }}
                            </strong>
                            @if($childMenus->where('parent_id', $menu->id)->count())
                                <span class="badge bg-secondary ms-1">
                                    {{ $childMenus->where('parent_id', $menu->id)->count() }} submenu
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($menu->route_name)
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-route me-1"></i>{{ $menu->route_name }}
                                </span>
                            @elseif($menu->url)
                                <span class="text-muted small">{{ $menu->url }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($menu->icon)
                                <code class="small">{{ $menu->icon }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $menu->order }}</span></td>
                        <td><span class="badge {{ $menu->target === '_blank' ? 'bg-warning text-dark' : 'bg-secondary' }}">{{ $menu->target }}</span></td>
                        <td>
                            @if($menu->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="del-{{ $menu->id }}" action="{{ route('admin.menus.destroy', $menu) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" title="Hapus"
                                        onclick="confirmDelete(document.getElementById('del-{{ $menu->id }}'), '{{ $menu->label }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Children --}}
                    @foreach($childMenus->where('parent_id', $menu->id)->sortBy('order') as $child)
                    <tr>
                        <td><i class="fas fa-level-up-alt fa-rotate-90 text-muted ms-2"></i></td>
                        <td class="ps-4">
                            <small class="text-muted me-1">↳</small>
                            @if($child->icon)<i class="{{ $child->icon }} me-1 text-secondary"></i>@endif
                            {{ $child->label }}
                        </td>
                        <td>
                            @if($child->route_name)
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-route me-1"></i>{{ $child->route_name }}
                                </span>
                            @elseif($child->url)
                                <span class="text-muted small">{{ $child->url }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @if($child->icon)
                                <code class="small">{{ $child->icon }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $child->order }}</span></td>
                        <td><span class="badge {{ $child->target === '_blank' ? 'bg-warning text-dark' : 'bg-secondary' }}">{{ $child->target }}</span></td>
                        <td>
                            @if($child->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.menus.edit', $child) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="del-{{ $child->id }}" action="{{ route('admin.menus.destroy', $child) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" title="Hapus"
                                        onclick="confirmDelete(document.getElementById('del-{{ $child->id }}'), '{{ $child->label }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-bars fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada menu. <a href="{{ route('admin.menus.create') }}">Tambah menu pertama</a>
                        </td>
                    </tr>
                    @endforelse

                    {{-- Orphan children (whose parent was deleted) --}}
                    @foreach($childMenus->whereNotIn('parent_id', $parentMenus->pluck('id'))->sortBy('order') as $orphan)
                    <tr class="table-warning">
                        <td><i class="fas fa-exclamation-triangle text-warning"></i></td>
                        <td>
                            {{ $orphan->label }}
                            <span class="badge bg-warning text-dark ms-1">parent dihapus</span>
                        </td>
                        <td>{{ $orphan->route_name ?: $orphan->url ?: '—' }}</td>
                        <td>{{ $orphan->icon ?: '—' }}</td>
                        <td>{{ $orphan->order }}</td>
                        <td>{{ $orphan->target }}</td>
                        <td>
                            @if($orphan->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.menus.edit', $orphan) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="del-{{ $orphan->id }}" action="{{ route('admin.menus.destroy', $orphan) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" title="Hapus"
                                        onclick="confirmDelete(document.getElementById('del-{{ $orphan->id }}'), '{{ $orphan->label }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-lightbulb me-2"></i>Panduan Penggunaan Menu</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <strong><i class="fas fa-route me-1 text-info"></i>Route Name</strong>
                    <p class="small text-muted mb-0 mt-1">Gunakan nama route Laravel (misal: <code>home</code>, <code>tentang</code>, <code>layanan</code>).
                    URL akan di-generate otomatis.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <strong><i class="fas fa-link me-1 text-success"></i>URL Manual</strong>
                    <p class="small text-muted mb-0 mt-1">Kosongkan route name dan isi URL untuk link eksternal atau halaman kustom
                    (misal: <code>https://example.com</code>).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <strong><i class="fas fa-layer-group me-1 text-warning"></i>Dropdown Submenu</strong>
                    <p class="small text-muted mb-0 mt-1">Pilih <strong>Parent Menu</strong> untuk menjadikan item sebagai submenu dropdown
                    dari menu induknya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
