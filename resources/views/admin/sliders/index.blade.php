@extends('layouts.admin')
@section('title','Slider / Banner')
@section('page-title','Manajemen Slider / Banner')
@section('breadcrumb')
<li class="breadcrumb-item active">Slider</li>
@endsection
@section('content')
<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-images me-2"></i>Daftar Slider</span>
        <a href="{{ route('admin.sliders.create') }}" class="add-btn"><i class="fas fa-plus me-1"></i>Tambah Slider</a>
    </div>
    <div class="card-body p-0">
        @if($sliders->isEmpty())
        <div class="text-center py-5 text-muted"><i class="fas fa-images fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada data slider.</div>
        @else
        <div class="table-responsive">
        <table class="table" id="sliderTable">
            <thead><tr><th style="width:50px;">#</th><th>Preview</th><th>Tipe</th><th>Judul</th><th>Sub Judul</th><th style="width:80px;">Urutan</th><th style="width:90px;">Status</th><th style="width:110px;" class="text-center">Aksi</th></tr></thead>
            <tbody>
                @foreach($sliders as $s)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        @if($s->type === 'text')
                        <div style="width:95px;height:52px;border-radius:8px;background:linear-gradient(135deg,{{ $s->bg_color_start ?? '#003A78' }},{{ $s->bg_color_end ?? '#0057A8' }});display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-font text-white" style="font-size:20px;opacity:.8;"></i>
                        </div>
                        @else
                        <img src="{{ asset('storage/'.$s->image) }}" style="height:52px;width:95px;object-fit:cover;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.1);" alt="">
                        @endif
                    </td>
                    <td>
                        @if($s->type === 'text')
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size:11px;"><i class="fas fa-font me-1"></i>Teks</span>
                        @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border" style="font-size:11px;"><i class="fas fa-image me-1"></i>Gambar</span>
                        @endif
                    </td>
                    <td class="fw-medium">{{ $s->title ?? '<span class="text-muted fst-italic">—</span>' }}</td>
                    <td style="max-width:220px;"><span style="font-size:13px;color:#64748b;">{{ Str::limit($s->subtitle, 50) ?? '—' }}</span></td>
                    <td><span class="badge bg-light text-dark border">{{ $s->order }}</span></td>
                    <td><span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.sliders.edit', $s) }}" class="btn btn-action btn-outline-primary me-1" title="Edit"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('admin.sliders.destroy', $s) }}" method="POST" class="d-inline" id="delSlider{{ $s->id }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-action btn-outline-danger" title="Hapus" onclick="confirmDelete(document.getElementById('delSlider{{ $s->id }}'), 'Hapus slider &quot;{{ addslashes($s->title ?? 'ini') }}&quot;?')"><i class="fas fa-trash"></i></button>
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
<script>$(function(){ if($('#sliderTable tbody tr').length > 4) $('#sliderTable').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_-_END_ dari _TOTAL_ data',paginate:{previous:'‹',next:'›'},emptyTable:'Belum ada data'},pageLength:10,order:[]}); });</script>
@endpush
