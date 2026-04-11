@extends('layouts.admin')
@section('title','Legalitas Perusahaan')
@section('page-title','Manajemen Legalitas Perusahaan')
@section('breadcrumb')<li class="breadcrumb-item active">Legalitas</li>@endsection
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-certificate me-2"></i>Daftar Legalitas Perusahaan</span>
        <a href="{{ route('admin.legal-items.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah Legalitas</a>
    </div>
    <div class="card-body p-0">
        @if($items->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-certificate fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada data legalitas.
        </div>
        @else
        <div class="table-responsive">
        <table class="table" id="legalTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:50px;">Ikon</th>
                    <th>Label</th>
                    <th>Nilai / Nomor</th>
                    <th style="width:80px;">Urutan</th>
                    <th style="width:110px;">Tampil Web</th>
                    <th style="width:110px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
            <tr id="row-{{ $item->id }}">
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div style="width:36px;height:36px;background:#eef2fb;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="{{ $item->icon }} text-primary"></i>
                    </div>
                </td>
                <td class="fw-medium">{{ $item->label }}</td>
                <td style="font-size:13px;color:#64748b;">{{ $item->value }}</td>
                <td><span class="badge bg-light text-dark border">{{ $item->order }}</span></td>
                <td>
                    <button type="button"
                        class="btn btn-sm {{ $item->is_visible ? 'btn-success' : 'btn-outline-secondary' }}"
                        onclick="toggleVisibility({{ $item->id }}, this)"
                        style="font-size:12px;min-width:80px;"
                        data-visible="{{ $item->is_visible ? '1' : '0' }}"
                        title="{{ $item->is_visible ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan' }}">
                        <i class="fas {{ $item->is_visible ? 'fa-eye' : 'fa-eye-slash' }} me-1"></i>
                        {{ $item->is_visible ? 'Tampil' : 'Tersembunyi' }}
                    </button>
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.legal-items.edit', $item) }}" class="btn btn-action btn-outline-primary me-1" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.legal-items.destroy', $item) }}" method="POST" class="d-inline" id="delLegal{{ $item->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger"
                            onclick="confirmDelete(document.getElementById('delLegal{{ $item->id }}'),'Hapus legalitas &quot;{{ addslashes($item->label) }}&quot;?')">
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
@endsection
@push('scripts')
<script>
function toggleVisibility(id, btn) {
    fetch('{{ url('admin/legal-items') }}/' + id + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        var visible = data.is_visible;
        btn.dataset.visible = visible ? '1' : '0';
        btn.className = 'btn btn-sm ' + (visible ? 'btn-success' : 'btn-outline-secondary');
        btn.title = visible ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan';
        btn.innerHTML = '<i class="fas ' + (visible ? 'fa-eye' : 'fa-eye-slash') + ' me-1"></i>' + (visible ? 'Tampil' : 'Tersembunyi');
    });
}
$(function(){ if($('#legalTable tbody tr').length > 5) $('#legalTable').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'_START_-_END_ dari _TOTAL_',paginate:{previous:'‹',next:'›'}},pageLength:15,order:[]}); });
</script>
@endpush
