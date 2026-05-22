@extends('layouts.admin')
@section('title','Manajemen Pengguna')
@section('page-title','Manajemen Pengguna')
@section('breadcrumb')
<li class="breadcrumb-item active">Pengguna</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0 fw-semibold" style="color:#1a2a4a;">Daftar Pengguna</h6>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="fas fa-plus me-1"></i>Tambah Pengguna
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8faff;">
                <tr>
                    <th class="ps-4" style="font-size:12px;color:#64748b;font-weight:600;">NAMA</th>
                    <th style="font-size:12px;color:#64748b;font-weight:600;">EMAIL</th>
                    <th style="font-size:12px;color:#64748b;font-weight:600;">ROLE</th>
                    <th style="font-size:12px;color:#64748b;font-weight:600;">DIBUAT</th>
                    <th class="text-end pe-4" style="font-size:12px;color:#64748b;font-weight:600;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td class="ps-4 align-middle">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:{{ $u->isAdmin() ? '#0057A8' : '#64748b' }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;font-size:14px;color:#1a2a4a;">{{ $u->name }}</span>
                            @if($u->id === auth()->id())
                            <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:10px;">Anda</span>
                            @endif
                        </div>
                    </td>
                    <td class="align-middle" style="font-size:14px;color:#475569;">{{ $u->email }}</td>
                    <td class="align-middle">
                        @if($u->isAdmin())
                        <span class="badge" style="background:#0057A8;font-size:11px;padding:5px 12px;">Administrator</span>
                        @else
                        <span class="badge" style="background:#64748b;font-size:11px;padding:5px 12px;">Viewer</span>
                        @endif
                    </td>
                    <td class="align-middle" style="font-size:13px;color:#94a3b8;">{{ $u->created_at->format('d M Y') }}</td>
                    <td class="align-middle text-end pe-4">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary px-3 me-1"><i class="fas fa-pen"></i></a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger px-3"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
