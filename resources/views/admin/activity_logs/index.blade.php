@extends('layouts.admin')
@section('title','Log Aktivitas') @section('page-title','Log Aktivitas Admin')
@section('breadcrumb')<li class="breadcrumb-item active">Log Aktivitas</li>@endsection
@section('content')
<div class="card card-table">
    <div class="cht-header">
        <span><i class="fas fa-history me-2"></i>Log Aktivitas</span>
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" id="clearForm">
            @csrf @method('DELETE')
            <button type="button" class="btn btn-sm btn-outline-danger"
                onclick="confirmDelete(document.getElementById('clearForm'), 'Hapus log lama? Log 100 terbaru akan dipertahankan.')">
                <i class="fas fa-trash me-1"></i>Bersihkan Log Lama
            </button>
        </form>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        @if($logs->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-history fa-2x mb-3 d-block" style="color:#e2e8f0;"></i>Belum ada log aktivitas.
        </div>
        @else
        <div class="table-responsive">
        <table class="table" id="logTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:140px;">Waktu</th>
                    <th>Pengguna</th>
                    <th style="width:100px;">Aksi</th>
                    <th>Keterangan</th>
                    <th style="width:130px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="text-muted">{{ ($logs->currentPage()-1)*$logs->perPage() + $loop->iteration }}</td>
                <td style="font-size:12px;color:#94a3b8;white-space:nowrap;">
                    {{ $log->created_at->format('d M Y') }}<br>
                    {{ $log->created_at->format('H:i:s') }}
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:30px;height:30px;border-radius:50%;background:#0057A8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="color:#fff;font-size:12px;font-weight:700;">
                                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                            </span>
                        </div>
                        <span style="font-size:13px;">{{ $log->user->name ?? '<em class="text-muted">Dihapus</em>' }}</span>
                    </div>
                </td>
                <td>
                    @php
                        $badge = match($log->action) {
                            'create' => ['bg-success',   'Tambah'],
                            'update' => ['bg-primary',   'Ubah'],
                            'delete' => ['bg-danger',    'Hapus'],
                            'login'  => ['bg-info',      'Login'],
                            'logout' => ['bg-secondary', 'Logout'],
                            default  => ['bg-light text-dark border', ucfirst($log->action)],
                        };
                    @endphp
                    <span class="badge {{ $badge[0] }}">{{ $badge[1] }}</span>
                </td>
                <td style="font-size:13px;">{{ $log->description }}</td>
                <td style="font-size:12px;color:#94a3b8;font-family:monospace;">{{ $log->ip_address ?? '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <div class="px-3 py-3">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
