@extends('layouts.admin')
@section('title', isset($item->id) ? 'Edit Jenis Proyek' : 'Tambah Jenis Proyek')
@section('page-title', isset($item->id) ? 'Edit Jenis Proyek' : 'Tambah Jenis Proyek')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.jenis-proyek.index') }}">Jenis Proyek</a></li>
<li class="breadcrumb-item active">{{ isset($item->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')

@php
$colorOptions = [
    'primary'   => ['label'=>'Biru Tua',    'bg'=>'#1e5fa8'],
    'warning'   => ['label'=>'Kuning',      'bg'=>'#d97706'],
    'success'   => ['label'=>'Hijau',       'bg'=>'#15803d'],
    'danger'    => ['label'=>'Merah',       'bg'=>'#b91c1c'],
    'info'      => ['label'=>'Biru Muda',   'bg'=>'#0891b2'],
    'secondary' => ['label'=>'Abu-abu',     'bg'=>'#475569'],
    'dark'      => ['label'=>'Gelap',       'bg'=>'#1e293b'],
];
$colorMap = ['primary'=>'#1e5fa8','warning'=>'#d97706','success'=>'#15803d','danger'=>'#b91c1c','info'=>'#0891b2','secondary'=>'#475569','dark'=>'#1e293b'];
@endphp

<form method="POST" action="{{ isset($item->id) ? route('admin.jenis-proyek.update', $item) : route('admin.jenis-proyek.store') }}">
    @csrf
    @if(isset($item->id)) @method('PUT') @endif

    <div class="row g-4">
        {{-- Left column: Form --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="fas fa-layer-group me-2 text-primary"></i>Data Jenis Proyek</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Jenis <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $item->nama ?? '') }}" id="inputNama"
                               placeholder="cth: Perencanaan Pembangunan Daerah" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Warna <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-2 mt-1" id="colorPicker">
                                @foreach($colorOptions as $key => $opt)
                                <label class="color-opt d-flex align-items-center gap-1 px-3 py-2 rounded cursor-pointer border"
                                       style="cursor:pointer;transition:all .15s;{{ old('warna', $item->warna ?? 'primary')===$key ? 'border-color:'.$opt['bg'].'!important;outline:2px solid '.$opt['bg'].'40;' : '' }}">
                                    <input type="radio" name="warna" value="{{ $key }}" class="d-none color-radio"
                                           {{ old('warna', $item->warna ?? 'primary')===$key ? 'checked' : '' }}>
                                    <span class="rounded-circle d-inline-block" style="width:16px;height:16px;background:{{ $opt['bg'] }};"></span>
                                    <span style="font-size:13px;font-weight:500;">{{ $opt['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                            @error('warna')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ikon FontAwesome</label>
                            <div class="input-group">
                                <span class="input-group-text" id="ikonPrev"><i id="ikonIcon" class="{{ old('ikon', $item->ikon ?? 'fas fa-folder') }}"></i></span>
                                <input type="text" name="ikon" id="inputIkon" class="form-control"
                                       value="{{ old('ikon', $item->ikon ?? 'fas fa-folder') }}"
                                       placeholder="fas fa-folder">
                            </div>
                            <div class="mt-1" style="font-size:12px;color:#64748b;">
                                Contoh: <code>fas fa-project-diagram</code>, <code>fas fa-map</code>, <code>fas fa-university</code>
                            </div>
                        </div>
                    </div>

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
                                       {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Manage items inline (edit mode only) --}}
            @if(isset($item->id))
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <span class="fw-semibold"><i class="fas fa-list-ul me-2 text-info"></i>Daftar Item Layanan / Proyek</span>
                    <a href="{{ route('admin.pengalaman-proyek.index', ['jenis' => $item->id]) }}"
                       class="btn btn-sm btn-outline-info">
                        <i class="fas fa-external-link-alt me-1"></i>Kelola Lengkap
                    </a>
                </div>
                <div class="card-body">
                    {{-- Quick Add --}}
                    <div class="input-group mb-3" id="quickAddForm">
                        <input type="text" id="quickItemName" class="form-control"
                               placeholder="Nama item baru, lalu tekan Enter atau klik Tambah...">
                        <button type="button" class="btn btn-primary" id="btnQuickAdd">
                            <i class="fas fa-plus me-1"></i>Tambah
                        </button>
                    </div>
                    <ul class="list-group" id="itemList">
                        @forelse($pengalamans ?? [] as $p)
                        <li class="list-group-item d-flex align-items-center gap-2 py-2 item-row" data-id="{{ $p->id }}">
                            <i class="fas fa-grip-vertical text-muted" style="cursor:move;"></i>
                            <span class="flex-grow-1" style="font-size:14px;">{{ $p->nama }}</span>
                            <span class="badge {{ $p->is_active ? 'bg-success':'bg-secondary' }}" style="font-size:10px;">
                                {{ $p->is_active ? 'Aktif':'Nonaktif' }}
                            </span>
                            <a href="{{ route('admin.pengalaman-proyek.edit', $p) }}" class="btn btn-sm btn-outline-primary btn-action ms-1" title="Edit"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action btn-quick-del"
                                    data-id="{{ $p->id }}" title="Hapus"><i class="fas fa-times"></i></button>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted" id="emptyMsg">Belum ada item. Tambahkan di atas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @endif
        </div>

        {{-- Right column: Preview --}}
        <div class="col-lg-4">
            <div class="card shadow-sm" style="position:sticky;top:80px;">
                <div class="card-header bg-white fw-semibold"><i class="fas fa-eye me-2 text-success"></i>Preview Box</div>
                <div class="card-body">
                    <div id="boxPreview" class="rounded px-3 py-3 d-flex align-items-center gap-2" style="background:#1e5fa8;color:#fff;transition:background .25s;">
                        <i id="prevIcon" class="{{ old('ikon', $item->ikon ?? 'fas fa-folder') }}" style="font-size:1.2em;"></i>
                        <span id="prevNama" class="fw-semibold" style="font-size:14px;">{{ old('nama', $item->nama ?? 'Nama Jenis Proyek') }}</span>
                    </div>
                    <div class="mt-3 text-muted small">Tampilan di halaman pengalaman</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
        <a href="{{ route('admin.jenis-proyek.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
@push('scripts')
<script>
const colorMap = @json($colorMap);

// Live preview
$('#inputNama').on('input', function(){
    $('#prevNama').text($(this).val() || 'Nama Jenis Proyek');
});
$('#inputIkon').on('input', function(){
    const cls = $(this).val() || 'fas fa-folder';
    $('#ikonIcon').attr('class', cls);
    $('#prevIcon').attr('class', cls);
});
$(document).on('change', '.color-radio', function(){
    const key = $(this).val();
    const bg = colorMap[key] || '#1e5fa8';
    $('#boxPreview').css('background', bg);
    // highlight selected
    $('.color-opt').css({borderColor:'', outline:''});
    $(this).closest('.color-opt').css({borderColor: bg, outline:'2px solid '+bg+'40'});
});

// Quick add (AJAX)
@if(isset($item->id))
const jenisId = {{ $item->id }};
function addItemRow(item){
    $('#emptyMsg').remove();
    const html = `<li class="list-group-item d-flex align-items-center gap-2 py-2 item-row" data-id="${item.id}">
        <i class="fas fa-grip-vertical text-muted" style="cursor:move;"></i>
        <span class="flex-grow-1" style="font-size:14px;">${item.nama}</span>
        <span class="badge bg-success" style="font-size:10px;">Aktif</span>
        <a href="/admin/pengalaman-proyek/${item.id}/edit" class="btn btn-sm btn-outline-primary btn-action ms-1"><i class="fas fa-pen"></i></a>
        <button type="button" class="btn btn-sm btn-outline-danger btn-action btn-quick-del" data-id="${item.id}"><i class="fas fa-times"></i></button>
    </li>`;
    $('#itemList').append(html);
}

$('#btnQuickAdd').on('click', doQuickAdd);
$('#quickItemName').on('keydown', function(e){ if(e.key==='Enter'){e.preventDefault();doQuickAdd();} });

function doQuickAdd(){
    const nama = $('#quickItemName').val().trim();
    if(!nama) return;
    $.ajax({
        url: '/admin/pengalaman-proyek/quick-store',
        method: 'POST',
        data: {_token:'{{ csrf_token() }}', jenis_proyek_id: jenisId, nama},
        success: function(res){
            if(res.success){ addItemRow(res.item); $('#quickItemName').val('').focus(); }
        }
    });
}

$(document).on('click', '.btn-quick-del', function(){
    const btn = $(this);
    const id = btn.data('id');
    if(!confirm('Hapus item ini?')) return;
    $.ajax({
        url: '/admin/pengalaman-proyek/' + id + '/quick-destroy',
        method: 'DELETE',
        data: {_token:'{{ csrf_token() }}'},
        success: function(res){
            if(res.success){
                btn.closest('.item-row').remove();
                if($('#itemList .item-row').length === 0){
                    $('#itemList').append('<li class="list-group-item text-center text-muted" id="emptyMsg">Belum ada item. Tambahkan di atas.</li>');
                }
            }
        }
    });
});
@endif
</script>
@endpush
