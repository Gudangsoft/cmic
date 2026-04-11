@extends('layouts.admin')
@section('title','Jenis Klien') @section('page-title','Manajemen Jenis Klien')
@section('breadcrumb')<li class="breadcrumb-item active">Jenis Klien</li>@endsection
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-tags me-2"></i>Daftar Jenis Klien</span>
        <a href="{{ route('admin.client-types.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah Jenis Klien</a>
    </div>
    <div class="card-body p-0">
        @if($clientTypes->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-tags fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada data jenis klien.
        </div>
        @else
        <div class="table-responsive">
        <table class="table" id="clientTypeTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Nama Jenis Klien</th>
                    <th style="width:100px;">Jumlah Klien</th>
                    <th style="width:80px;">Urutan</th>
                    <th style="width:90px;">Status</th>
                    <th style="width:110px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($clientTypes as $ct)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td class="fw-medium">{{ $ct->name }}</td>
                <td>
                    <span class="badge bg-light text-dark border">
                        <i class="fas fa-handshake me-1 text-muted" style="font-size:11px;"></i>{{ $ct->clients_count }}
                    </span>
                </td>
                <td><span class="badge bg-light text-dark border">{{ $ct->order }}</span></td>
                <td><span class="badge {{ $ct->is_active ? 'bg-success':'bg-secondary' }}">{{ $ct->is_active ? 'Aktif':'Nonaktif' }}</span></td>
                <td class="text-center">
                    <a href="{{ route('admin.client-types.edit', $ct) }}" class="btn btn-action btn-outline-primary me-1" title="Edit"><i class="fas fa-pen"></i></a>
                    <form action="{{ route('admin.client-types.destroy', $ct) }}" method="POST" class="d-inline" id="delCt{{ $ct->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger"
                            onclick="confirmDelete(document.getElementById('delCt{{ $ct->id }}'),'Hapus jenis klien &quot;{{ addslashes($ct->name) }}&quot;?')">
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
<script>$(function(){ if($('#clientTypeTable tbody tr').length > 4) $('#clientTypeTable').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'_START_-_END_ dari _TOTAL_',paginate:{previous:'‹',next:'›'}},pageLength:10,order:[]}); });</script>
@endpush
