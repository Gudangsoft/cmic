@extends('layouts.admin')
@section('title','Klien') @section('page-title','Manajemen Klien')
@section('breadcrumb')<li class="breadcrumb-item active">Klien</li>@endsection
@section('content')
<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-handshake me-2"></i>Daftar Klien</span>
        <a href="{{ route('admin.clients.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah Klien</a>
    </div>
    <div class="card-body p-0">
        @if($clients->isEmpty())
        <div class="text-center py-5 text-muted"><i class="fas fa-handshake fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada data klien.</div>
        @else
        <div class="alert alert-info border-0 rounded-0 mb-0 py-2 px-3" style="font-size:13px;background:#eff6ff;">
            <i class="fas fa-lightbulb me-1 text-primary"></i>
            <strong>Tips:</strong> Klik pada logo / area logo untuk mengganti gambar secara langsung tanpa buka halaman edit.
        </div>
        <div class="table-responsive">
        <table class="table" id="clientTable">
            <thead><tr><th style="width:50px;">#</th><th style="width:130px;">Logo <small class="text-muted fw-normal">(klik untuk ganti)</small></th><th>Nama Klien</th><th>Jenis Klien</th><th>Website</th><th style="width:80px;">Urutan</th><th style="width:90px;">Status</th><th style="width:110px;" class="text-center">Aksi</th></tr></thead>
            <tbody>
            @foreach($clients as $c)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>
                    {{-- Hidden file input per row --}}
                    <input type="file" id="logoInput{{ $c->id }}" accept="image/*" style="display:none;"
                           data-id="{{ $c->id }}"
                           data-url="{{ route('admin.clients.updateLogo', $c) }}">

                    <div class="logo-cell" onclick="document.getElementById('logoInput{{ $c->id }}').click()"
                         title="Klik untuk ganti logo"
                         style="cursor:pointer;position:relative;display:inline-block;">
                        @if($c->logo)
                        <img id="logoImg{{ $c->id }}"
                             src="{{ asset('storage/'.$c->logo) }}"
                             style="height:40px;max-width:110px;object-fit:contain;border:2px solid #e2e8f0;border-radius:6px;padding:4px;background:#fff;transition:border-color .2s;"
                             onmouseenter="this.style.borderColor='#3b82f6'"
                             onmouseleave="this.style.borderColor='#e2e8f0'">
                        @else
                        <div id="logoImg{{ $c->id }}"
                             style="width:100px;height:40px;background:#f1f5f9;border:2px dashed #cbd5e1;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#94a3b8;transition:border-color .2s;gap:4px;"
                             onmouseenter="this.style.borderColor='#3b82f6';this.style.color='#3b82f6'"
                             onmouseleave="this.style.borderColor='#cbd5e1';this.style.color='#94a3b8'">
                            <i class="fas fa-upload"></i> Upload
                        </div>
                        @endif
                        <div id="logoSpin{{ $c->id }}" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,.8);border-radius:6px;display:none;align-items:center;justify-content:center;">
                            <i class="fas fa-spinner fa-spin text-primary"></i>
                        </div>
                    </div>
                </td>
                <td class="fw-medium">{{ $c->name }}</td>
                <td>
                    @if($c->clientType)
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" style="font-size:11px;">{{ $c->clientType->name }}</span>
                    @else
                    <span class="text-muted small">—</span>
                    @endif
                </td>
                <td><a href="{{ $c->website }}" target="_blank" style="font-size:13px;">{{ $c->website ? Str::limit($c->website, 35) : '—' }}</a></td>
                <td><span class="badge bg-light text-dark border">{{ $c->order }}</span></td>
                <td><span class="badge {{ $c->is_active ? 'bg-success':'bg-secondary' }}">{{ $c->is_active ? 'Aktif':'Nonaktif' }}</span></td>
                <td class="text-center">
                    <a href="{{ route('admin.clients.edit', $c) }}" class="btn btn-action btn-outline-primary me-1" title="Edit"><i class="fas fa-pen"></i></a>
                    <form action="{{ route('admin.clients.destroy', $c) }}" method="POST" class="d-inline" id="delClient{{ $c->id }}">@csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger" onclick="confirmDelete(document.getElementById('delClient{{ $c->id }}'),'Hapus klien &quot;{{ addslashes($c->name) }}&quot;?')"><i class="fas fa-trash"></i></button>
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
// DataTable
$(function(){
    if($('#clientTable tbody tr').length > 4){
        $('#clientTable').DataTable({
            language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'_START_-_END_ dari _TOTAL_',paginate:{previous:'‹',next:'›'}},
            pageLength:25,order:[]
        });
    }
});

// Inline logo upload
document.querySelectorAll('input[type=file][data-url]').forEach(function(input) {
    input.addEventListener('change', function() {
        if (!this.files.length) return;

        var id      = this.dataset.id;
        var url     = this.dataset.url;
        var imgEl   = document.getElementById('logoImg' + id);
        var spinEl  = document.getElementById('logoSpin' + id);
        var file    = this.files[0];

        // Show spinner
        spinEl.style.display = 'flex';

        var fd = new FormData();
        fd.append('logo', file);
        fd.append('_token', '{{ csrf_token() }}');

        fetch(url, { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                spinEl.style.display = 'none';
                if (data.url) {
                    // Replace placeholder div with img or update existing img
                    if (imgEl.tagName === 'IMG') {
                        imgEl.src = data.url + '?t=' + Date.now();
                    } else {
                        var img = document.createElement('img');
                        img.id = 'logoImg' + id;
                        img.src = data.url + '?t=' + Date.now();
                        img.style.cssText = 'height:40px;max-width:110px;object-fit:contain;border:2px solid #22c55e;border-radius:6px;padding:4px;background:#fff;transition:border-color .2s;';
                        imgEl.parentNode.replaceChild(img, imgEl);
                        setTimeout(function(){ img.style.borderColor = '#e2e8f0'; }, 1500);
                    }
                    // Flash green border
                    imgEl.style.borderColor = '#22c55e';
                    setTimeout(function(){ if(imgEl) imgEl.style.borderColor = '#e2e8f0'; }, 1500);
                }
            })
            .catch(function() {
                spinEl.style.display = 'none';
                alert('Gagal upload logo. Silakan coba lagi.');
            });

        // Reset input so same file can be re-selected
        this.value = '';
    });
});
</script>
@endpush
