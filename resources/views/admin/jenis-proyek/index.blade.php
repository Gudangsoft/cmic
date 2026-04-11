@extends('layouts.admin')
@section('title','Jenis Pengalaman Proyek')
@section('page-title','Jenis Pengalaman Proyek')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Pengalaman</a></li>
<li class="breadcrumb-item active">Jenis Proyek</li>
@endsection
@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #4f8ef7!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(79,142,247,.12);">
                    <i class="fas fa-layer-group" style="color:#4f8ef7;font-size:18px;"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1">{{ $stats['total'] }}</div>
                    <div class="text-muted small">Total Jenis</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #22c55e!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(34,197,94,.12);">
                    <i class="fas fa-check-circle" style="color:#22c55e;font-size:18px;"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1">{{ $stats['aktif'] }}</div>
                    <div class="text-muted small">Jenis Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #f59e0b!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(245,158,11,.12);">
                    <i class="fas fa-list-ul" style="color:#f59e0b;font-size:18px;"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1">{{ $stats['total_item'] }}</div>
                    <div class="text-muted small">Total Item</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #06b6d4!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;background:rgba(6,182,212,.12);">
                    <i class="fas fa-check" style="color:#06b6d4;font-size:18px;"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1">{{ $stats['total_aktif_item'] }}</div>
                    <div class="text-muted small">Item Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Card --}}
<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-layer-group me-2"></i>Daftar Jenis Pengalaman Proyek</span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pengalaman-proyek.index') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-list-ul me-1"></i>Semua Item
            </a>
            <a href="{{ route('admin.jenis-proyek.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah Jenis</a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($jenis->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-layer-group fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>
            Belum ada jenis proyek. <a href="{{ route('admin.jenis-proyek.create') }}">Tambah sekarang</a>
        </div>
        @else

        {{-- Visual Preview --}}
        <div class="p-3 bg-light border-bottom">
            <div class="small text-muted mb-2 fw-semibold"><i class="fas fa-eye me-1"></i>Preview Tampilan Frontend</div>
            <div class="row g-2">
                @foreach($jenis as $j)
                @php
                    $colorMap = ['primary'=>'#1e5fa8','warning'=>'#d97706','success'=>'#15803d','danger'=>'#b91c1c','info'=>'#0891b2','secondary'=>'#475569','dark'=>'#1e293b'];
                    $bg = $colorMap[$j->warna] ?? '#1e5fa8';
                @endphp
                <div class="col-6 col-md-3">
                    <div class="rounded px-3 py-2 d-flex align-items-center gap-2 {{ !$j->is_active ? 'opacity-50':'' }}"
                         style="background:{{ $bg }};color:#fff;font-size:13px;font-weight:600;">
                        <i class="{{ $j->ikon ?? 'fas fa-folder' }}"></i>
                        <span>{{ $j->nama }}</span>
                        <span class="ms-auto badge bg-white bg-opacity-25 text-white" style="font-size:10px;">{{ $j->pengalamans_count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="table-responsive">
        <table class="table" id="jenisTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Nama Jenis</th>
                    <th style="width:100px;">Warna</th>
                    <th style="width:120px;">Ikon</th>
                    <th style="width:70px;">Urutan</th>
                    <th style="width:80px;">Jml Item</th>
                    <th style="width:90px;">Status</th>
                    <th style="width:160px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($jenis as $j)
            @php
                $colorMap = ['primary'=>'#1e5fa8','warning'=>'#d97706','success'=>'#15803d','danger'=>'#b91c1c','info'=>'#0891b2','secondary'=>'#475569','dark'=>'#1e293b'];
                $bg = $colorMap[$j->warna] ?? '#1e5fa8';
            @endphp
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td class="fw-medium">
                    <span class="badge me-2" style="background:{{ $bg }};font-size:11px;padding:4px 8px;">
                        <i class="{{ $j->ikon ?? 'fas fa-folder' }} me-1"></i>{{ $j->nama }}
                    </span>
                </td>
                <td>
                    <span class="badge" style="background:{{ $bg }};font-size:11px;">{{ ucfirst($j->warna) }}</span>
                </td>
                <td style="font-size:13px;color:#64748b;"><i class="{{ $j->ikon ?? 'fas fa-folder' }} me-1"></i><span style="font-size:11px;">{{ $j->ikon }}</span></td>
                <td class="text-center"><span class="badge bg-light text-dark border">{{ $j->urutan }}</span></td>
                <td class="text-center">
                    <a href="{{ route('admin.pengalaman-proyek.index', ['jenis' => $j->id]) }}"
                       class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 text-decoration-none">
                        <i class="fas fa-list-ul me-1"></i>{{ $j->pengalamans_count }} item
                    </a>
                </td>
                <td>
                    <button type="button" class="btn btn-sm toggle-btn {{ $j->is_active ? 'btn-success':'btn-outline-secondary' }}"
                            data-id="{{ $j->id }}" style="font-size:11px;padding:2px 10px;">
                        {{ $j->is_active ? 'Aktif':'Nonaktif' }}
                    </button>
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.pengalaman-proyek.index', ['jenis' => $j->id]) }}"
                       class="btn btn-action btn-outline-info me-1" title="Kelola Item">
                        <i class="fas fa-list-ul"></i>
                    </a>
                    <a href="{{ route('admin.jenis-proyek.edit', $j) }}"
                       class="btn btn-action btn-outline-primary me-1" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.jenis-proyek.destroy', $j) }}" method="POST"
                          class="d-inline" id="delJenis{{ $j->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger"
                                onclick="confirmDelete(document.getElementById('delJenis{{ $j->id }}'),'Hapus jenis &quot;{{ addslashes($j->nama) }}&quot; beserta semua itemnya?')">
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
$(function(){
    // Toggle active
    $(document).on('click', '.toggle-btn', function(){
        const btn = $(this);
        const id = btn.data('id');
        $.post('{{ url("admin/jenis-proyek") }}/' + id + '/toggle', {_token:'{{ csrf_token() }}'}, function(res){
            if(res.is_active){
                btn.removeClass('btn-outline-secondary').addClass('btn-success').text('Aktif');
            } else {
                btn.removeClass('btn-success').addClass('btn-outline-secondary').text('Nonaktif');
            }
        });
    });
});
</script>
@endpush
