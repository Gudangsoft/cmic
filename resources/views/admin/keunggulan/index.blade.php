@extends('layouts.admin')
@section('title','Keunggulan')
@section('page-title','Manajemen Keunggulan')
@section('breadcrumb')<li class="breadcrumb-item active">Keunggulan</li>@endsection
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total',   'value'=>$stats['total'],    'icon'=>'fas fa-trophy',       'color'=>'#0057A8','bg'=>'#eef4ff'],
        ['label'=>'Aktif',   'value'=>$stats['active'],   'icon'=>'fas fa-check-circle', 'color'=>'#16a34a','bg'=>'#f0fdf4'],
        ['label'=>'Nonaktif','value'=>$stats['inactive'], 'icon'=>'fas fa-eye-slash',    'color'=>'#dc2626','bg'=>'#fff1f2'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-md-4">
        <div style="background:#fff;border-radius:14px;padding:16px 18px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:4px solid {{ $c['color'] }};display:flex;align-items:center;gap:14px;">
            <div style="width:46px;height:46px;border-radius:11px;background:{{ $c['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="{{ $c['icon'] }}" style="color:{{ $c['color'] }};font-size:20px;"></i>
            </div>
            <div>
                <div style="font-size:26px;font-weight:800;color:#1a2a4a;line-height:1;">{{ $c['value'] }}</div>
                <div style="font-size:12px;color:#64748b;margin-top:2px;">{{ $c['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-trophy me-2"></i>Daftar Keunggulan</span>
        <a href="{{ route('admin.keunggulan.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah</a>
    </div>
    <div class="card-body p-0">
        @if($items->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-trophy fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>
            Belum ada data keunggulan. <a href="{{ route('admin.keunggulan.create') }}">Tambah sekarang</a>
        </div>
        @else
        <div class="table-responsive">
        <table class="table" id="keunggulanTable">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th style="width:54px;">Ikon</th>
                    <th>Label</th>
                    <th>Deskripsi</th>
                    <th style="width:70px;">Urutan</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div style="width:40px;height:40px;background:#eef4ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="{{ $item->icon }}" style="color:#0057A8;font-size:17px;"></i>
                    </div>
                </td>
                <td class="fw-semibold">{{ $item->label }}</td>
                <td style="font-size:13px;color:#64748b;max-width:260px;">
                    {{ $item->description ? \Str::limit($item->description, 70) : '—' }}
                </td>
                <td><span class="badge bg-light text-dark border">{{ $item->order }}</span></td>
                <td>
                    <button type="button"
                        class="btn btn-sm {{ $item->is_active ? 'btn-success' : 'btn-secondary' }}"
                        style="font-size:11px;min-width:80px;"
                        data-id="{{ $item->id }}"
                        onclick="toggleActive(this, {{ $item->id }})">
                        <i class="fas {{ $item->is_active ? 'fa-eye' : 'fa-eye-slash' }} me-1"></i>
                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                    </button>
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.keunggulan.edit', $item) }}" class="btn btn-action btn-outline-primary me-1" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.keunggulan.destroy', $item) }}" method="POST" class="d-inline" id="delK{{ $item->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger"
                            onclick="confirmDelete(document.getElementById('delK{{ $item->id }}'),'Hapus keunggulan &quot;{{ addslashes($item->label) }}&quot;?')">
                            <i class="fas fa-trash"></i>
                        </button>
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

{{-- Preview section --}}
@if($items->where('is_active', true)->count() > 0)
<div class="form-card mt-4">
    <div class="fcard-header"><i class="fas fa-eye me-2"></i>Preview Tampilan di Website</div>
    <div class="fcard-body">
        <div class="row g-3">
            @foreach($items->where('is_active', true) as $item)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="p-3 border rounded text-center h-100" style="border-color:#0057A8!important;border-radius:12px!important;">
                    <i class="{{ $item->icon }} fa-2x mb-2" style="color:#0057A8;"></i>
                    <div class="fw-semibold" style="font-size:14px;">{{ $item->label }}</div>
                    @if($item->description)
                    <div class="text-muted mt-1" style="font-size:12px;">{{ $item->description }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function toggleActive(btn, id) {
    fetch('{{ url("admin/keunggulan") }}/' + id + '/toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.is_active) {
            btn.className = 'btn btn-sm btn-success';
            btn.innerHTML = '<i class="fas fa-eye me-1"></i>Aktif';
        } else {
            btn.className = 'btn btn-sm btn-secondary';
            btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i>Nonaktif';
        }
        btn.style.fontSize='11px'; btn.style.minWidth='80px';
    });
}
</script>
@endpush
