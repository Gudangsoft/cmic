@extends('layouts.admin')
@section('title','Pesan Masuk') @section('page-title','Pesan Masuk')
@section('breadcrumb')<li class="breadcrumb-item active">Pesan Masuk</li>@endsection
@section('content')
<div class="card card-table">
    <div class="cht-header">
        <span>
            <i class="fas fa-envelope me-2"></i>Daftar Pesan
            @php $unreadCount = $contacts->where('is_read', false)->count() @endphp
            @if($unreadCount > 0)<span class="badge ms-2" style="background:rgba(245,197,24,.9);color:#333;">{{ $unreadCount }} belum dibaca</span>@endif
        </span>
        <a href="{{ route('admin.contacts.export') }}" class="btn btn-sm btn-outline-success" title="Export ke Excel/CSV">
            <i class="fas fa-file-excel me-1"></i>Export CSV
        </a>
    </div>
    <div class="card-body p-0">
        @if($contacts->isEmpty())
        <div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada pesan masuk.</div>
        @else
        <div class="table-responsive">
        <table class="table" id="contactTable">
            <thead><tr><th style="width:50px;">#</th><th>Pengirim</th><th>Email</th><th>Telepon</th><th>Subjek</th><th style="width:130px;">Tanggal</th><th style="width:110px;">Status</th><th style="width:100px;" class="text-center">Aksi</th></tr></thead>
            <tbody>
            @foreach($contacts as $c)
            <tr class="{{ !$c->is_read ? 'table-light' : '' }}">
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;border-radius:50%;background:{{ !$c->is_read ? '#0057A8':'#94a3b8' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-user text-white" style="font-size:12px;"></i>
                        </div>
                        <span class="fw-{{ !$c->is_read ? 'semibold' : 'normal' }}">{{ $c->name }}</span>
                    </div>
                </td>
                <td style="font-size:13px;">{{ $c->email }}</td>
                <td style="font-size:13px;color:#64748b;">{{ $c->phone ?? '—' }}</td>
                <td style="max-width:200px;font-size:13px;" class="fw-{{ !$c->is_read ? 'medium':'normal' }}">{{ Str::limit($c->subject, 45) }}</td>
                <td style="font-size:12px;color:#94a3b8;white-space:nowrap;">{{ $c->created_at->format('d M Y') }}<br>{{ $c->created_at->format('H:i') }}</td>
                <td>
                    @if(!$c->is_read)
                    <span class="badge" style="background:#fef9c3;color:#854d0e;">● Belum Dibaca</span>
                    @else
                    <span class="badge bg-light text-success border">✓ Dibaca</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.contacts.show', $c) }}" class="btn btn-action btn-outline-primary me-1" title="Lihat"><i class="fas fa-eye"></i></a>
                    <form action="{{ route('admin.contacts.destroy', $c) }}" method="POST" class="d-inline" id="delMsg{{ $c->id }}">@csrf @method('DELETE')
                        <button type="button" class="btn btn-action btn-outline-danger" onclick="confirmDelete(document.getElementById('delMsg{{ $c->id }}'),'Hapus pesan dari {{ addslashes($c->name) }}?')"><i class="fas fa-trash"></i></button>
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
<script>$(function(){ if($('#contactTable tbody tr').length > 4) $('#contactTable').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'_START_-_END_ dari _TOTAL_',paginate:{previous:'‹',next:'›'}},pageLength:15,order:[[5,'desc']]}); });</script>
@endpush
